<?php
header("Content-Type:application/json; charset=utf-8");
mb_internal_encoding("UTF-8");
if(isset($_REQUEST["file"])){
	$filename = $_REQUEST["file"].".csv";
	$file = new SplFileObject($filename);
	$file->seek(PHP_INT_MAX);
	$maxline = $file->key();
	$strTr = file_get_contents($_REQUEST["file"].".tr");
	$result = array("max" => $maxline, "tr" => $strTr);
	echo json_encode($result);
}
?>