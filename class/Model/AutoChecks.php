<?php

namespace App\Model;

use App\Core\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\BadResponseException;

class AutoChecks extends Model
{
    /** @return self */
    public static function i($data = null)
    {
        return parent::i($data);
    }

    private $domainsChecklistMap = [
        'httpStatus' => 'checkHttpStatus',
        'noindex' => 'checkNoIndex',
        'robots' => 'checkRobots',
        'disallow' => 'checkDisallow',
        'sitemap' => 'checkSitemap',
        'ga' => 'checkGA'
    ];

    private $urlsChecklistMap = [
        'httpStatus' => 'checkHttpStatus',
        'noindex' => 'checkNoIndex',
        'title' => 'checkTitle',
        'description' => 'checkDescription',
        'tags' => 'checkTags'
    ];

    private $urlResponse = '';
    private $robotsUrlResponse = '';

    public function getDomainsRows()
    {
        Auth::i()->checkAuth();

        $opts = $this->toArray($this->params);
        $where = $this->getWhere($opts, 'domain');

        $portion = intval($opts['portion'] ?? 1);

        $sortMap = [
            'domain' => 'domain',
            'lastCheck' => 'last_check'
        ];
        $order = $this->getOrder($opts, $sortMap, 'id');
        $limit = $this->getLimit($portion);

        $query = '
			SELECT
			    id,
			    domain,
			    noindex,
			    url,
				http_status AS httpStatus,
			    sitemap_url AS sitemap,
				robots_url AS robots,
				ga,
				renewal,
				last_check AS lastCheck,
				created_at AS createdAt
			FROM check_domains
			WHERE ('.implode(') AND (', $where).')
			ORDER BY '.$order.'
			LIMIT '.$limit.'
		';
        $res = mysqli_query($this->db, $query);
        $rows = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

        foreach ($rows as $key => $row) {
            $statusQuery = '
                SELECT check_name AS checkName, status, response
                FROM check_domains_statuses
                WHERE check_id = '.intval($row['id']).'
            ';
            $res = mysqli_query($this->db, $statusQuery);
            $data = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

            $rows[$key]['statuses'] = [];
            foreach ($data as $item) {
                $rows[$key]['statuses'][$item['checkName']] = $item;
            }

            $rows[$key]['lastCheck'] = $this->changeTimezone($row['lastCheck']);
            $rows[$key]['createdAt'] = $this->changeTimezone($row['createdAt']);
        }

        return ['rows' => $rows];
    }

    public function getDomainsStat()
    {
        Auth::i()->checkAuth();

        $opts = $this->toArray($this->params);
        $where = $this->getWhere($opts, 'domain');

        $query = '
			SELECT COUNT(*) AS cnt
			FROM check_domains
			WHERE ('.implode(') AND (', $where).')
		';
        $res = mysqli_query($this->db, $query);
        $data = $res ? mysqli_fetch_assoc($res) : [];

        return ['totalCnt' => intval($data['cnt'] ?? 0)];
    }

    public function getUrlsRows()
    {
        Auth::i()->checkAuth();

        $opts = $this->toArray($this->params);
        $where = $this->getWhere($opts, 'url');

        $sortMap = [
            'url' => 'url',
            'renewal' => 'renewal',
            'lastChecks' => 'last_check',
        ];
        $order = $this->getOrder($opts, $sortMap, 'id');

        $query = '
			SELECT
			    id,
			    domain,
			    url,
				http_status AS httpStatus,
				noindex,
				title,
				description,
				tag1,
				tag2,
				tag3,
				renewal,
				last_check AS lastCheck,
				created_at AS createdAt
			FROM check_urls
			WHERE ('.implode(') AND (', $where).')
			ORDER BY '.$order.'
		';
        $res = mysqli_query($this->db, $query);
        $rows = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

        foreach ($rows as $key => $row) {
            $statusQuery = '
                SELECT check_name AS checkName, status, response
                FROM check_urls_statuses
                WHERE check_id = '.intval($row['id']).'
            ';
            $res = mysqli_query($this->db, $statusQuery);
            $data = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

            $rows[$key]['statuses'] = [];
            foreach ($data as $item) {
                $rows[$key]['statuses'][$item['checkName']] = $item;
            }

            $rows[$key]['lastCheck'] = $this->changeTimezone($row['lastCheck']);
            $rows[$key]['createdAt'] = $this->changeTimezone($row['createdAt']);
        }

        return ['rows' => $rows];
    }

    public function getUrlsStat()
    {
        Auth::i()->checkAuth();

        $opts = $this->toArray($this->params);
        $where = $this->getWhere($opts, 'url');

        $query = '
			SELECT COUNT(*) AS cnt
			FROM check_urls
			WHERE ('.implode(') AND (', $where).')
		';
        $res = mysqli_query($this->db, $query);
        $data = $res ? mysqli_fetch_assoc($res) : [];

        return ['totalCnt' => intval($data['cnt'] ?? 0)];
    }

    private function getWhere($opts, $mode)
    {
        $where = [1];

        if (!empty($opts['domain'])) {
            $where[] = 'domain = '.$this->quote($opts['domain']);
        }
        if ($mode === 'url') return $where;

        if (!empty($opts['status'])) {
            $status = intval($opts['status']);
            $whereStatus = ['ds.status = '.$status];

            if ($status === 1) {
                $whereStatus[] = 'ds.check_id NOT IN (
					SELECT DISTINCT check_id FROM check_domains_statuses WHERE status != '.$status.'
				)';
            }

            $where[] = 'id IN (
				SELECT DISTINCT ds.check_id
				FROM check_domains_statuses AS ds
				WHERE ('.implode(') AND (', $whereStatus).')
			)';
        }

        return $where;
    }

    public function DomainsSearch()
    {
        Auth::i()->checkAuth();

        $q = trim($this->params['q'] ?? '');
        if (!$q) return ['items' => []];

        $query = '
			(
                SELECT domain
                FROM check_domains
                WHERE domain = '.$this->quote($q).'
                LIMIT 1
			) UNION (
                SELECT domain
                FROM check_urls
                WHERE domain = '.$this->quote($q).'
                LIMIT 1
            ) UNION (
			    SELECT domain
                FROM check_domains
                WHERE domain LIKE "'.$this->escapeLike($q).'%"
                ORDER BY domain
                LIMIT 10
			) UNION (
                SELECT domain
                FROM check_urls
                WHERE domain LIKE "'.$this->escapeLike($q).'%"
                ORDER BY domain
                LIMIT 10
            )
		';
        $res = mysqli_query($this->db, $query);
        $data = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

        $domains = [];
        foreach ($data as $row) {
            $domains[] = $row['domain'];
        }

        $domains = array_unique($domains);
        $cnt = count($domains);
        if ($cnt < 10) {
            $query = '
                (
                    SELECT domain
                    FROM check_domains
                    WHERE domain LIKE "%'.$this->escapeLike($q).'%"
                    ORDER BY domain
                    LIMIT 10
                ) UNION (
                    SELECT domain
                    FROM check_urls
                    WHERE domain LIKE "%'.$this->escapeLike($q).'%"
                    ORDER BY domain
                    LIMIT 10
                )
            ';
            $res = mysqli_query($this->db, $query);
            $data = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
            foreach ($data as $row) {
                $domains[] = $row['domain'];
            }
        }

        $domains = array_unique($domains);
        $domains = array_slice($domains, 0, 10);

        $items = [];
        foreach ($domains as $domain) {
            $items[] = ['key' => $domain, 'val' => $domain];
        }

        return ['items' => $items];
    }

    public function saveDomainCheck()
    {
        Auth::i()->checkAuth();

        $this->checkReqParams(['types', 'url']);
        $check = $this->toArray($this->params);
        $url = $this->ensureFullUrl($check['url']);

        $opts = [];
        $opts['params'] = [];
        $opts['params']['noindex'] = 2;
        $opts['params']['http_status'] = intval($check['httpStatus'] ?? 200);
        $opts['params']['domain'] = $this->quote($this->getDomainFromUrl($url));
        $opts['params']['url'] = $this->quote($url);
        $opts['params']['sitemap_url'] = $this->quote($check['sitemap'] ?? '');
        $opts['params']['robots_url'] = $this->quote($check['robots'] ?? '');
        $opts['params']['ga'] = $this->quote($check['ga'] ?? '');
        $opts['params']['renewal'] = intval($check['renewal'] ?? 1);

        $opts['types'] = [];
        foreach (explode(',', $check['types']) as $value) {
            $opts['types'][] = ['name' => $value];
        }

        $id = $check['id'] ?? null;
        $this->saveCheck($id, $opts, 'domains');
    }

    public function saveUrlCheck()
    {
        Auth::i()->checkAuth();

        $this->checkReqParams(['types', 'url']);
        $check = $this->toArray($this->params);
        $url = $this->ensureFullUrl($check['url']);

        $opts = [];
        $opts['params'] = [];
        $opts['params']['renewal'] = intval($check['renewal'] ?? 0);
        $opts['params']['url'] = $this->quote($url);
        $opts['params']['domain'] = $this->quote($this->getDomainFromUrl($url));
        $opts['params']['noindex'] = intval($check['noindex'] ?? 0);
        $opts['params']['http_status'] = intval($check['httpStatus'] ?? 0);
        $opts['params']['title'] = $this->quote($check['title'] ?? '');
        $opts['params']['description'] = $this->quote($check['description'] ?? '');
        $opts['params']['tag1'] = $this->quote($check['tag1'] ?? '');
        $opts['params']['tag2'] = $this->quote($check['tag2'] ?? '');
        $opts['params']['tag3'] = $this->quote($check['tag3'] ?? '');

        $opts['types'] = [];
        foreach (explode(',', $check['types']) as $value) {
            $opts['types'][] = ['name' => $value];
        }

        $id = $check['id'] ?? null;
        $this->saveCheck($id, $opts, 'urls');
    }

    private function saveCheck($id, $opts, $table)
    {
        if (empty($opts) || empty($opts['params'])) {
            $this->app->sendError(200, 'Empty params');
        }
        if ($table !== 'domains' && $table !== 'urls') {
            $this->app->sendError(200, 'Invalid table');
        }

        $isNewCheck = empty($id);

        $set = [];
        foreach ($opts['params'] as $key => $value) {
            $set[] = $key.' = '.$value;
        }

        if ($isNewCheck) {
            $query = '
                INSERT IGNORE INTO check_'.$table.'
                SET '.implode(', ', $set).'
            ';
            mysqli_query($this->db, $query);
            $id = mysqli_insert_id($this->db);

            if (!$id) $this->app->sendError(200, 'Empty id');
        } else {
            $query = '
                UPDATE check_'.$table.'
                SET '.implode(', ', $set).'
                WHERE id = '.intval($id).'
            ';
            mysqli_query($this->db, $query);

            $query = 'DELETE FROM check_'.$table.'_statuses WHERE check_id = '.intval($id);
            mysqli_query($this->db, $query);
        }

        $columns = ['check_id', 'check_name'];

        $cells = [];
        foreach ($opts['types'] as $check) {
            $cells[] = intval($id).', '.$this->quote($check['name']);
        }

        $query = '
            INSERT INTO check_'.$table.'_statuses ('.implode(', ', $columns).') 
            VALUES ('.implode('), (', $cells).')
        ';
        mysqli_query($this->db, $query);

        //if ($isNewCheck) $this->setAutoCheck($id, $table);
    }

    public function deleteCheck()
    {
        Auth::i()->checkAuth();

        $this->checkReqParams(['id', 'table']);
        $id = intval($this->params['id']);
        $table = $this->params['table'];

        if (empty($id)) $this->app->sendError(200, 'Empty id');
        if ($table !== 'domains' && $table !== 'urls') {
            $this->app->sendError(200, 'Invalid table');
        }

        $query = 'DELETE FROM check_'.$table.' WHERE id = '.$id;
        mysqli_query($this->db, $query);

        $query = 'DELETE FROM check_'.$table.'_statuses WHERE check_id = '.$id;
        mysqli_query($this->db, $query);

        return true;
    }

    public function robotChecker($id = null, $table = null)
    {
        Auth::i()->checkAuth();

        $id = $this->params['id'] ?? $id;
        $table = $this->params['table'] ?? $table;

        if (!$table || $table === 'domains') $this->setAutoCheck($id, 'domains');
        if (!$table || $table === 'urls') $this->setAutoCheck($id, 'urls');
    }

    private function setAutoCheck($id = null, $table)
    {
        if ($table !== 'domains' && $table !== 'urls') return;

        $select = [
            'id',
            'url',
            'domain',
            'http_status AS httpStatus',
            'noindex',
            'DATEDIFF(CURDATE(), created_at) % renewal AS renew'
        ];

        if ($table === 'domains') {
            $select[] = 'sitemap_url AS sitemapUrl';
            $select[] = 'robots_url AS robotsUrl';
            $select[] = 'ga';
        } else {
            $select[] = 'title';
            $select[] = 'description';
            $select[] = 'tag1';
            $select[] = 'tag2';
            $select[] = 'tag3';
        }

        $where = [1];

        if ($id) $where[] = 'id = '.intval($id);

        $query = '
            SELECT '.implode(',', $select).'
            FROM check_'.$table.'
            WHERE ('.implode(') AND (', $where).')
        ';
        $res = mysqli_query($this->db, $query);
        $data = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

        $rows = [];
        foreach ($data as $row) {
            if (!$id && $row['renew'] !== '0') continue;

            $row['checklist'] = [];

            $queryStatuses = '
                SELECT check_name AS checkName
                FROM check_' . $table . '_statuses
                WHERE check_id = '.intval($row['id']).'
            ';
            $res = mysqli_query($this->db, $queryStatuses);
            $statuses = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

            foreach ($statuses as $item) {
                $row['checklist'][] = $item['checkName'];
            }

            if (empty($row['checklist'])) continue;

            $rows[] = $row;
        }

        $httpClient = new Client([
            'defaults' => [
                'connect_timeout' => 20,
                'verify' => false,
                'allow_redirects' => $table === 'domains',
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36',
                ]
            ]
        ]);
        foreach (array_chunk($rows, 50) as $chunk) {
            $promises = [];
            foreach ($chunk as $key => $row) {
                $promises[$key] = $httpClient->getAsync($row['url']);
            }

            $responses = Promise\settle($promises)->wait();
            foreach ($responses as $key => $response) {
                $row = $chunk[$key];
                if (empty($row) || empty($row['checklist'])) continue;

                $this->urlResponse = $response['value'] ?? '';
                if (!$this->urlResponse && !empty($response['reason'])) {
                    $this->urlResponse = $response['reason']->getResponse();
                }

                $urlCodeSt = $this->urlResponse ? intval($this->urlResponse->getStatusCode()) : 0;
                $this->robotsUrlResponse = '';

                if (!empty($row['robotsUrl']) && $urlCodeSt === 200) {
                    try {
                        $this->robotsUrlResponse = $httpClient->get($row['robotsUrl']);
                    } catch (BadResponseException $exception) {
                        $this->robotsUrlResponse = $exception->getResponse() ?? '';
                    }
                }

                $object = $table.'ChecklistMap';
                foreach ($this->$object as $checkName => $function) {
                    if (!in_array($checkName, $row['checklist'])) continue;
                    if ($urlCodeSt !== 200 && $table === 'domains') {
                        $error = 'Code '.$urlCodeSt;
                    } else {
                        $error = $this->$function($row);
                    }

                    $row['status'] = $error ? 2 : 1;
                    $row['response'] = $error ?? '';
                    $this->setCheckStatus($row, $checkName, $table);
                }
            }
        }
    }

    private function checkHttpStatus($data)
    {
        if (empty($this->urlResponse)) return 'Empty url';
        if (empty($data['httpStatus'])) return 'Empty http status';

        $code = $this->urlResponse->getStatusCode();

        if (intval($data['httpStatus']) !== intval($code)) return 'Code '.intval($code);
    }

    private function checkRobots($data)
    {
        if (empty($this->robotsUrlResponse)) return 'Empty robots url';

        $code = $this->robotsUrlResponse->getStatusCode();

        if (intval($code) !== 200) return 'Robots.txt not found';
    }

    private function checkDisallow($data)
    {
        $error = $this->checkRobots($data);
        if ($error) return $error;

        $txt = (string) $this->robotsUrlResponse->getBody();
        $txt = preg_replace('/#(.*)/', '', $txt);

        $agents = explode('USER-AGENT:', strtoupper($txt));
        foreach ($agents as $text) {
            $text = 'User-agent:'.$text;

            preg_match('/disallow: \/[$\n\r\$]/i', $text, $disallow);
            if (empty($disallow)) continue;

            preg_match('/User-agent: Googlebot|User-agent: \*/i', $text, $match);
            if (!empty($match)) return 'Found '.$disallow[0];
        }
    }

    private function checkSitemap($data)
    {
        if (empty($data['sitemapUrl'])) return 'Empty sitemap url';

        $http = new Client([
            'defaults' => [
                'connect_timeout' => 20,
                'verify' => false,
                'allow_redirects' => true,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36',
                ]
            ]
        ]);

        $urls = [];
        $sitemapUrls = array_map('trim', explode("\n", $data['sitemapUrl']));
        foreach ($sitemapUrls as $url) {
            if (!$url) continue;
            $urls[] = $url;
        }
        if (empty($urls)) return 'Empty sitemap urls';

        $urls = array_unique($urls);
        foreach ($urls as $key => $url) {
            $promises[$key] = $http->getAsync($url);
        }

        $responses = Promise\settle($promises)->wait();
        foreach ($responses as $res) {
            $result = $res['value'] ?? '';
            if (!$result) return 'Sitemap not found';
            if (intval($result->getStatusCode()) !== 200) {
                return 'Sitemap not found';
            }
        }
    }

    private function checkGA($data)
    {
        if (empty($this->urlResponse)) return 'Empty url';
        if (intval($this->urlResponse->getStatusCode()) !== 200) return 'Site is not responding';
        if (empty($data['ga'])) return 'Empty ga';

        $html = (string) $this->urlResponse->getBody();
        $html = $this->removeHtmlComments($html);

        preg_match_all('/<script[^>]*?>(.+?)<\/script>/si', $html, $matches);
        if (empty($matches[1])) return 'GA not found';

        foreach ($matches[1] as $script) {
            $ga = preg_quote($data['ga'], '/');
            preg_match('/'.$ga.'/si', $script, $match);
            if (!empty($match)) return;
        }

        return 'GA not found';
    }

    private function checkNoIndex($data)
    {
        if (empty($this->urlResponse)) return 'Empty url';
        if (!isset($data['noindex'])) return 'Empty noindex';
        if (intval($this->urlResponse->getStatusCode()) !== 200) return 'Site is not responding';

        $html = (string) $this->urlResponse->getBody();
        $html = $this->removeHtmlComments($html);
        $noindexAvailable = intval($data['noindex']) === 1;

        preg_match_all('/<meta (.*?)\/?>/si', $html, $matches);
        if (empty($matches[1]) && $noindexAvailable) return 'Noindex not found';

        foreach ($matches[1] as $item) {
            preg_match('/name\s*?=\s*?(["\'])robots\1|name\s*?=\s*?(["\'])googlebot\2/si', $item, $match);
            if (empty($match)) continue;

            preg_match('/content\s*?=\s*?(["\'])(.*?)\1/si', $item, $match);
            if (empty($match[2])) continue;

            preg_match('/noindex/si', $match[2], $match);
            if (!empty($match) && $noindexAvailable) return;
            if (!empty($match) && !$noindexAvailable) return 'Noindex found';
        }

        if ($noindexAvailable) return 'Noindex not found';
    }

    private function checkTitle($data)
    {
        if (empty($this->urlResponse)) return 'Empty url';
        if (intval($this->urlResponse->getStatusCode()) !== 200) return 'Site is not responding';
        if (empty($data['title'])) return 'Empty title';

        $html = (string) $this->urlResponse->getBody();
        $html = $this->removeHtmlComments($html);

        preg_match('/<title[^>]*?>(.+?)<\/title>/si', $html, $match);
        if (empty($match[1])) return 'Title not found';
        if ($match[1] !== $data['title']) return 'Incorrect title';
    }

    private function checkDescription($data)
    {
        if (empty($this->urlResponse)) return 'Empty url';
        if (intval($this->urlResponse->getStatusCode()) !== 200) return 'Site is not responding';
        if (empty($data['description'])) return 'Empty description';

        $html = (string) $this->urlResponse->getBody();
        $html = $this->removeHtmlComments($html);

        preg_match_all('/<meta (.*?)\/?>/si', $html, $match);
        if (empty($match) || empty($match[1])) return 'Description not found';

        foreach ($match[1] as $item) {
            preg_match('/name\s*?=\s*?(["\'])description\1/si', $item, $match);
            if (empty($match)) continue;

            preg_match('/content\s*?=\s*?(["\'])(.*?)\1/si', $item, $match);
            if (empty($match[2])) continue;
            if ($data['description'] === $match[2]) return;

            return 'Incorrect description';
        }

        return 'Description not found';
    }

    private function checkTags($data)
    {
        if (empty($this->urlResponse)) return 'Empty url';
        if (intval($this->urlResponse->getStatusCode()) !== 200) return 'Site is not responding';
        if (empty($data['tag1']) && empty($data['tag2']) && empty($data['tag3'])) return 'Empty tags';

        $html = (string) $this->urlResponse->getBody();
        $tags = [$data['tag1'], $data['tag2'], $data['tag3']];

        $html = $this->removeHtmlComments($html);

        foreach ($tags as $key => $tag) {
            if (empty($tag)) continue;

            $tag = preg_quote($tag, '/');
            preg_match('/'.$tag.'/su', $html, $match);
            if (empty($match)) return 'Tag '.($key + 1).' not found';
        }
    }

    private function removeHtmlComments($html) {
        return preg_replace('/<!--(.*?)-->/s', '', $html);
    }

    private function setCheckStatus($data, $checkName, $table)
    {
        $query = '
            UPDATE check_'.$table.'_statuses
            SET status = '.intval($data['status']).', response = '.$this->quote($data['response']).'
            WHERE check_id = '.intval($data['id']).' AND check_name = '.$this->quote($checkName).'
        ';
        mysqli_query($this->db, $query);

        $query = '
            UPDATE check_'.$table.'
            SET last_check = NOW()
            WHERE id = '.intval($data['id']).'
        ';
        mysqli_query($this->db, $query);
    }
}