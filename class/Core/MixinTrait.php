<?php

namespace App\Core;

trait MixinTrait
{
	protected function toJson($data)
	{
		return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	}

    protected function getRandomString($len = 10) {
        $abc = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $idx = rand(0,strlen($abc)-1);
            $str .= substr($abc, $idx, 1);
        }
        return $str;
    }

	protected function dump($data)
	{
		echo '<pre>';
		if (is_null($data) || is_bool($data) || $data === '') {
			var_dump($data);
		} else {
			print_r($data);
		}
		echo '</pre>';
		exit();
	}

	protected function log($msg = '', $logFile = 'general.log')
	{
		$logFile = str_replace('/', '', $logFile);
		$file = ROOT.'/storage/logs/'.$logFile;
		if (is_object($msg) || is_array($msg)) {
			$msg = json_encode($msg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		}
		$data = date('Y-m-d H:i:s').' '.' '.$msg."\n";
		file_put_contents($file, $data, FILE_APPEND);
	}
}
