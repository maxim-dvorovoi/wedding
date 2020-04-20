<?php

namespace App\Core;

use App\E\HttpException;
class Model extends Singleton
{
	protected $app;
	protected $db;

	use MixinTrait;

    public static function i($data = null)
    {
        return parent::i($data);
    }

	public function __construct()
	{
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->params = $_GET;
        } elseif($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->params = $_POST;
        } else {
            throw new HttpException(400, 'METHOD_NOT_AVAILABLE');
        }

        $this->checkReqParams();

		$this->app = App::i();
		$this->db = $this->app->db;
        $this->config = $this->app->getConfig();
	}

	public function quote($str)
    {
        return '"'.mysqli_real_escape_string($this->db, $str).'"';
    }
}