<?php
header("Content-Type:text/html; charset=utf-8");
mb_internal_encoding("UTF-8");
if(isset($_REQUEST["file"])){
	$filename = $_REQUEST["file"].".ind";
	$file = new SplFileObject($filename);
	$file->seek(PHP_INT_MAX);
	echo $file->key();
}
?>