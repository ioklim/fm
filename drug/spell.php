<?php
header('Content-Type: text/html; charset=utf-8');
if(isset($_GET["keyword"])) $kw = strtoupper($_GET["keyword"]);
else exit();

$len = strlen($kw);
$thre = round($len/3);
if($thre<3) $thre=3;

$filename = "spell.txt";
$file = new SplFileObject($filename);
$aa = array();
while (!$file->eof()) {
	$fl = trim(strtoupper($file->fgets()));
	$lev = levenshtein($fl,$kw);
	if($lev==0){
		echo "找到關鍵字".$fl."<br>";
		exit();
	}
	if($lev<$thre){
		$sim = similar_text($fl,$kw);
		$yu = 24/($lev+1)+$sim;
		$aa[] = array("word"=>$fl,"score"=>$yu);
		//echo $fl.", sim:".$sim.", lev:".$lev.",====>".$yu."<br>";
	}
}
usort($aa, function($a, $b) {
    return $b['score'] - $a['score'];
});
$n = count($aa);
$str = "";
if($n>0){
	$str = "是不是要找 <a href='#' class='bg-info' onclick='key(this.innerHTML);'>".$aa[0]["word"]."</a>";
	if($n>1) $str .= ", 還是 <a href='#' class='bg-info' onclick='key(this.innerHTML);'>". $aa[1]["word"]."</a>";
	$str .= "？";
}
echo $str;
?>