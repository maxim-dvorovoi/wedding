<?php

namespace App\Core;

use App\E\HttpException;
use AltoRouter;

class App extends Singleton
{
	private $config;
	private $data;
	public $db;

	/** @return self */
	public static function i($data = null)
	{
		return parent::i($data);
	}

	public function __construct()
	{
		$this->config = $this->getConfig();

		$cred = $this->config->db;
		$this->db = mysqli_connect($cred->host, $cred->user, $cred->pass, $cred->db);

        if (!$this->db) {
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
	}

	public function getConfig()
	{
		if (!$this->config) {
			$conf = require ROOT . '/include/config.php';
			$this->config = json_decode(json_encode($conf));
		}

		return $this->config;
	}

	public function run()
	{
        $r = new AltoRouter();
        $routes = require ROOT.'/routes/index.r.php';

        foreach ($routes as $route) {
            call_user_func_array([$r, 'map'], $route);
        }

        $match = $r->match();
        if (!$match) {
            throw new HttpException(404, 'Not found');
        }

        list($className, $methodName) = explode('.', $match['target'], 2);
        $fullClassName = '\\App\\Model\\'.$className;

        if (!class_exists($fullClassName)) {
            throw new \Error('Error: class dont exist;', 503);
        }

        $instance = new $fullClassName();

        if (!method_exists($instance, $methodName)) {
            throw new \Error('Error: method dont exist', 503);
        }

        $this->data = $instance->{$methodName}();

        $this->sendData();
	}

    private function sendData()
    {
        $res = [
            'err' => null,
            'res' => $this->data
        ];
        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode($res);
    }

    public function sendError($code = 500, $msg = 'Server error')
    {
        $res = [
            'err' => $msg,
            'res' => null
        ];

        http_response_code($code);
        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode($res);
    }
}
