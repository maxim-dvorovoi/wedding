<?php

namespace App\Core;

use App\E\HttpException;
use App\Model\Auth;

class Model extends Singleton
{
    /** @var App */
	protected $app;

	protected $db;
	protected $params;

	/** @return self */
    public static function i($data = null)
    {
        return parent::i($data);
    }

	public function __construct()
	{
	    if (!$this->checkCli()) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->params = $_GET;
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $content_type = strtolower($_SERVER['CONTENT_TYPE']);
                if (strpos($content_type,'application/json') !== false) {
                    $this->params = json_decode(file_get_contents('php://input'), true);
                } else {
                    $this->params = $_POST;
                }
            } else {
                throw new HttpException(400, 'Method not available');
            }
        }

		$this->app = App::getInstance();
		$this->db = $this->app->db;
        $this->config = $this->app->getConfig();
	}

    protected function checkReqParams($requiredParams)
    {
        $params = $this->toArray($this->params);

        foreach ($requiredParams as $param) {
            if (empty($params[$param])) {
                $this->app->sendError(200, 'Missing "'.$param.'" parameter');
            }
        }
    }

    protected function toArray($data = null)
    {
        if (!$data || is_array($data)) return $data;
        return (array)$data;
    }

    protected function checkCli()
    {
        return (PHP_SAPI === 'cli');
    }

    protected function changeTimezone($dateTime, $fromZone = 'UTC', $toZone = 'Europe/Kiev')
    {
        $dt = date_create($dateTime, timezone_open($fromZone));
        date_timezone_set($dt, timezone_open($toZone));
        $res = date_format($dt, 'Y-m-d H:i:s');

        return $res;
    }

    protected function escape($str)
    {
        return mysqli_real_escape_string($this->db, $str);
    }

    protected function quote($str)
    {
        return '"'.$this->escape($str).'"';
    }

    protected function escapeLike($str)
    {
        return str_replace(['_', '%'], ['\_', '\%'], $this->escape($str));
    }

    protected function getLimit($portion, $limit = 50)
    {
        $offset = ($portion - 1) * $limit;
        return intval($offset).', '.intval($limit);
    }

    protected function getOrder($opts, $sortMap, $defaultField, $dir = 'ASC')
    {
        $sort = $opts['sort'] ?? null;

        if (isset($opts['sortDesc'])) {
            if (!empty($opts['sortDesc'])) {
                $dir = 'DESC';
            }
            else{
                $dir = 'ASC';
            }
        } elseif ($sort) {
            if($sort[0] === '-'){
                $sort = substr($sort, 1);
                $dir = 'DESC';
            }
            else{
                $dir = 'ASC';
            }
        }

        $sortField = $sortMap[$sort] ?? null;

        $sorts = [];
        if ($sortField) {
            $sorts[] = $sortField.' '.$dir;
        }
        $sorts[] = $defaultField.' '.$dir;

        return implode(', ', $sorts);
    }

    protected function getDomainFromUrl($url, $opts = [])
    {
        $domain = $url;
        $domain = trim($domain);
        if (empty($opts['keepCase'])) {
            $domain = mb_strtolower($domain, 'UTF-8');
        }
        $domain = preg_replace('~^https?://~', '', $domain);
        if (empty($opts['keepWww'])) {
            $domain = preg_replace('~^www\.~', '', $domain);
        }
        $domain = preg_replace('~/.*~', '', $domain);
        if (!empty($opts['ascii'])) {
            $domain = idn_to_ascii($domain, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
        }
        return $domain;
    }

    protected function ensureFullUrl($url)
    {
        $url = trim($url);
        if (!preg_match('~^https?://~i', $url)) {
            if (preg_match('~^//~i', $url)) {
                $url = 'http:' . $url;
            } else {
                $url = 'http://' . $url;
            }
        }

        return $url;
    }
}