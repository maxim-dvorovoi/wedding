<?php

function dumpException(\Throwable $err) {
	echo '<pre>';
	echo $err->getMessage()."\n\n";
	echo $err->getFile().":".$err->getLine()."\n";
	echo $err->getTraceAsString();
	echo '</pre>';
	exit();
}