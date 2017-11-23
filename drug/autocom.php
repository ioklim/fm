<?php
header('Content-Type: text/html; charset=utf-8');
if(isset($_GET["q"])){
	$needle=trim($_GET["q"]);
} else{
	exit();
}
$filenameIndex = "autocom.txt";

$fileIndex = new SplFileObject($filenameIndex);
$fileIndex->seek(0);
$count=0;
while (!$fileIndex->eof() && $count<7) {
	$fl = $fileIndex->fgets();
	if(mb_stripos($fl, $needle,0,"UTF-8")===0){
		echo $fl."\n";
		$count++;
	}
}
//echo $fl."<br>\n";
?>