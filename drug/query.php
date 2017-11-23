<?php
session_start();
header("Content-Type:text/html; charset=utf-8");
//header("Access-Control-Allow-Origin: http://drug.healthinfo.tw");
if(isset($_SERVER['HTTP_ORIGIN'])){
	$http_origin = $_SERVER['HTTP_ORIGIN'];
	if ($http_origin == "http://drug.healthinfo.tw" || $http_origin == "https://drug.healthinfo.tw")
	{  
		header("Access-Control-Allow-Origin: $http_origin");
	}
}
mb_internal_encoding("UTF-8");
setlocale(LC_ALL,'zh_TW.UTF-8');
set_time_limit(45);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
$col = array(array('New_mark',1,2), 
	array('口服錠註記',4,13), 
	array('單/複方註記',15,16), 
	array('藥品代碼',18,27), 
	array('藥價參考金額',29,37), 
	array('藥價參考日期',39,45), 
	array('藥價參考截止日期',47,53), 
	array('藥品英文名稱',55,174), 
	array('藥品規格量',176,182), 
	array('藥品規格單位',184,235), 
	array('成份名稱',237,292), 
	array('成份含量',294,305), 
	array('成份含量單位',307,357), 
	array('藥品劑型',359,376), 
	array('空白',378,383), 
	array('空白',385,444), 
	array('空白',446,603), 
	array('藥商名稱',605,624), 
	array('空白',626,766), 
	array('藥品分類',768,768), 
	array('品質分類碼',770,770), 
	array('藥品中文名稱',772,899), 
	array('分類分組名稱',901,1200), 
	array('（複方一）成份名稱',1201,1256), 
	array('（複方一）藥品成份含量',1259,1269), 
	array('（複方一）藥品成份含量單位',1271,1321), 
	array('（複方二）成份名稱',1323,1378), 
	array('（複方二）藥品成份含量',1380,1390), 
	array('（複方二）藥品成份含量單位',1392,1442), 
	array('（複方三）成份名稱',1444,1499), 
	array('（複方三）藥品成份含量',1501,1511), 
	array('（複方三）藥品成份含量單位',1513,1563), 
	array('（複方四）成份名稱',1565,1620), 
	array('（複方四）藥品成份含量',1622,1632), 
	array('（複方四）藥品成份含量單位',1634,1684), 
	array('（複方五）成份名稱',1686,1741), 
	array('（複方五）藥品成份含量',1743,1753), 
	array('（複方五）藥品成份含量單位',1755,1805) );
	
$y = intval(date("Y"))-1911;
$vToday = intval(strval($y).date("md"));

function fda2link($str){ //許可證號轉連結
	$aafda = array("衛署藥製"=>"01", "衛署藥輸"=>"02", "衛署成製"=>"03", "衛署中藥輸"=>"04", "衛署醫器製"=>"05", "衛署醫器輸"=>"06", "衛署粧製"=>"07", "衛署粧輸"=>"08", "衛署菌疫製"=>"09", "衛署菌疫輸"=>"10", "衛署色輸"=>"11", "內衛藥製"=>"12", "內衛藥輸"=>"13", "內衛成製"=>"14", "內衛菌疫製"=>"15", "內衛菌疫輸"=>"16", "內藥登"=>"17", "署藥兼食製"=>"18", "衛署成輸"=>"19", "衛署罕藥輸"=>"20", "衛署罕藥製"=>"21", "罕菌疫輸"=>"22", "罕菌疫製"=>"23", "罕醫器輸"=>"24", "罕醫器製"=>"25", "衛署色製"=>"31", "衛署粧陸輸"=>"40", "衛署藥陸輸"=>"41", "衛署醫器陸輸"=>"42", "衛署醫器製壹"=>"43", "衛署醫器輸壹"=>"44", "衛署醫器陸輸壹"=>"46", "衛部藥製"=>"51", "衛部藥輸"=>"52", "衛部成製"=>"53", "衛部醫器製"=>"55", "衛部醫器輸"=>"56", "衛部粧製"=>"57", "衛部粧輸"=>"58", "衛部菌疫製"=>"59", "衛部菌疫輸"=>"60", "衛部色輸"=>"61", "部藥兼食製"=>"68", "衛部成輸"=>"69", "衛部罕藥輸"=>"70", "衛部罕藥製"=>"71", "衛部罕菌疫輸"=>"72", "衛部罕菌疫製"=>"73", "衛部罕醫器輸"=>"74", "衛部色製"=>"81", "衛部粧陸輸"=>"90", "衛部藥陸輸"=>"91", "衛部醫器陸輸"=>"92", "衛部醫器製壹"=>"93", "衛部醫器輸壹"=>"94", "衛部醫器陸輸壹"=>"96", "衛署菌製"=>"99");
	if(mb_strpos($str,"字第",0,"UTF-8")!==false){
		$p = mb_strpos($str,"字第",0,"UTF-8");
		$prefix = mb_substr($str,0,$p,"UTF-8");
		$code = mb_substr($str,$p+2,6,"UTF-8");
		if (array_key_exists($prefix, $aafda)) {
			return "https://www.fda.gov.tw/MLMS/H0001D.aspx?Type=Lic&LicId=".$aafda[$prefix].$code;
		} else {
			return "";
		}
	}else{
		return "";
	}
}
function fda2linkIns($str){ //許可證號轉仿單連結
	$aafda = array("衛署藥製"=>"01", "衛署藥輸"=>"02", "衛署成製"=>"03", "衛署中藥輸"=>"04", "衛署醫器製"=>"05", "衛署醫器輸"=>"06", "衛署粧製"=>"07", "衛署粧輸"=>"08", "衛署菌疫製"=>"09", "衛署菌疫輸"=>"10", "衛署色輸"=>"11", "內衛藥製"=>"12", "內衛藥輸"=>"13", "內衛成製"=>"14", "內衛菌疫製"=>"15", "內衛菌疫輸"=>"16", "內藥登"=>"17", "署藥兼食製"=>"18", "衛署成輸"=>"19", "衛署罕藥輸"=>"20", "衛署罕藥製"=>"21", "罕菌疫輸"=>"22", "罕菌疫製"=>"23", "罕醫器輸"=>"24", "罕醫器製"=>"25", "衛署色製"=>"31", "衛署粧陸輸"=>"40", "衛署藥陸輸"=>"41", "衛署醫器陸輸"=>"42", "衛署醫器製壹"=>"43", "衛署醫器輸壹"=>"44", "衛署醫器陸輸壹"=>"46", "衛部藥製"=>"51", "衛部藥輸"=>"52", "衛部成製"=>"53", "衛部醫器製"=>"55", "衛部醫器輸"=>"56", "衛部粧製"=>"57", "衛部粧輸"=>"58", "衛部菌疫製"=>"59", "衛部菌疫輸"=>"60", "衛部色輸"=>"61", "部藥兼食製"=>"68", "衛部成輸"=>"69", "衛部罕藥輸"=>"70", "衛部罕藥製"=>"71", "衛部罕菌疫輸"=>"72", "衛部罕菌疫製"=>"73", "衛部罕醫器輸"=>"74", "衛部色製"=>"81", "衛部粧陸輸"=>"90", "衛部藥陸輸"=>"91", "衛部醫器陸輸"=>"92", "衛部醫器製壹"=>"93", "衛部醫器輸壹"=>"94", "衛部醫器陸輸壹"=>"96", "衛署菌製"=>"99");
	if(mb_strpos($str,"字第",0,"UTF-8")!==false){
		$p = mb_strpos($str,"字第",0,"UTF-8");
		$prefix = mb_substr($str,0,$p,"UTF-8");
		$code = mb_substr($str,$p+2,6,"UTF-8");
		if (array_key_exists($prefix, $aafda)) {
			return "https://www.fda.gov.tw/MLMS/H0001D3.aspx?LicId=".$aafda[$prefix].$code;
		} else {
			return "";
		}
	}else{
		return "";
	}
}
function jb2link($str){ //健保代碼轉連結
	if(strlen($str)!=10) return "";
	$jbaa = array( "A"=>"01",
		"N"=>"12",
		"V"=>"20",
		"B"=>"02",
		"P"=>"13",
		"W"=>"21",
		"J"=>"09",
		"R"=>"15",
		"Y"=>"22",
		"K"=>"10",
		"S"=>"16",
		"Z"=>"23");
	$code = substr($str,2,5);
	$prefix = substr($str,0,1);
	if (array_key_exists($prefix, $jbaa)) {
		return "https://www.fda.gov.tw/MLMS/H0001D.aspx?Type=Lic&LicId=".$jbaa[$prefix]."0".$code;
	}else{
		return "";
	}
}
function jb2linkIns($str){ //健保代碼轉仿單連結
	if(strlen($str)!=10) return "";
	$jbaa = array( "A"=>"01",
		"N"=>"12",
		"V"=>"20",
		"B"=>"02",
		"P"=>"13",
		"W"=>"21",
		"J"=>"09",
		"R"=>"15",
		"Y"=>"22",
		"K"=>"10",
		"S"=>"16",
		"Z"=>"23");
	$code = substr($str,2,5);
	$prefix = substr($str,0,1);
	if (array_key_exists($prefix, $jbaa)) {
		return "https://www.fda.gov.tw/MLMS/H0001D3.aspx?LicId=".$jbaa[$prefix]."0".$code;
	}else{
		return "";
	}
}
function getth(){
	$output = "<tr>";
	global $col;
	for($i=0;$i<count($col);$i++){
		$output .= "<th>".$col[$i][0]."</th>";
	}
	$output .= "</tr>";
	return $output;
}
function permutations(array $elements) //傳回所有組合
{
    if (count($elements) <= 1) {
        yield $elements;
    } else {
        foreach (permutations(array_slice($elements, 1)) as $permutation) {
            foreach (range(0, count($elements) - 1) as $i) {
                yield array_merge(
                    array_slice($permutation, 0, $i),
                    [$elements[0]],
                    array_slice($permutation, $i)
                );
            }
        }
    }
}
function transneedle($str){ //關鍵字處理
	setlocale(LC_CTYPE, "en_US.UTF-8");
	$str = escapeshellcmd($str); //避免command injection
	$str = trim($str);
	//echo "關鍵字:".$str."<br>";
	if(mb_strpos($str," ")==false) return $str;
	$aa  = explode(" ",$str);
	//print_r($aa);
	$result = "";
	foreach (permutations($aa) as $permutation) {
		if($result!="") $result .= "\|";
		$result.= implode('.*', $permutation);
	}
	return $result;
}
function line2tr($str){
	global $vToday;
	$vDrug = intval(substr($str,47-1,53-47+1));
	if($vDrug>$vToday) $output = "<tr>";
	else $output = "<tr style='background-color:#e0e0e0;color:gray;'>";
	global $col;
	for($i=0;$i<count($col);$i++){
		$tdstr = substr($str,$col[$i][1]-1,$col[$i][2]-$col[$i][1]+1);
		$tdstrU = iconv( "big5","UTF-8",  $tdstr);
		if($i==3){
			$link = jb2link($tdstrU);
			if($link!=""){
				$tdstrU = "<a target='_blank' href='".$link."'>".$tdstrU."</a>";
			}
		}
		$output .= "<td>".$tdstrU."</td>";
	}
	$output .= "</tr>";
	return $output;
}
function line2trfda($str){
	$output = "<tr>";
	$aa = explode("\t",$str);
	for($i=0;$i<count($aa)-1;$i++){
		$strtd = $aa[$i];
		if($i==0){
			$link = fda2link($aa[$i]);
			if($link!=""){
				$strtd = "<a target='_blank' href='".$link."'>".$aa[$i]."</a>";
			}
		}
		$output .= "<td>".$strtd."</td>";
	}
	$output .= "</tr>";
	return $output;
}
function line2trind($str,$ind,$db,$optExp){
	$aa = explode("\t",$str);
	if($optExp=="wo"){
		$dateNow = strtotime("now");
		switch ($db) {
		case "fda":
			$itemExp = $aa[4];  //到期日
			$dateDrug = strtotime($itemExp);
			break;
		case "nhi":
			$itemExp = $aa[6];  //到期日
			$dateDrug = strtotime((intval(substr($itemExp,0,3))+1911)."-".substr($itemExp,3,2)."-".substr($itemExp,5,2));
			break;
		}
		if($dateDrug < $dateNow) return "";
	}

	$output = "<tr>";
	
	for($i=0;$i<count($aa);$i++){
		//if(!isset($aa[$i])) continue;
		$strtd = $aa[$i];
		if($i==$ind){
			$len = mb_strlen($strtd);
			if($len==10) $link = jb2link($aa[$i]); //健保碼 長度10
			else $link = fda2link($aa[$i]); //許可證
			if($link!=""){
				$strtd = "<a target='_blank' href='".$link."'>".$aa[$i]."</a>";
			}
		}
		$output .= "<td>".$strtd."</td>";
	}
	$output .= "</tr>";
	return $output;
}
function line2div($str,$db,$optExp){
	$aa = explode("\t",$str);
	$classExpired="";
	$dateNow = strtotime("now");

	switch ($db) {
    case "fda":
		$itemChi = $aa[9];  //中文名
		$itemEng = $aa[10]; //英文名
		$itemIng = str_replace(";;",",",$aa[16]); //成分
		$itemInd = $aa[11]; //適應症
		$itemID  = $aa[0];  //許可證號
		$itemExp = $aa[4];  //到期日
		$link = fda2link($itemID); //藥品資料連結
		$linkIns = fda2linkIns($itemID); //藥品資料連結
		$dateDrug = strtotime($aa[4]);
        break;
    case "nhi":
		$itemChi = $aa[21];  //中文名
		$itemEng = $aa[7]; //英文名
		$itemIng = $aa[22]; //成分
		$itemInd = "健保藥價".$aa[4]; //適應症
		$itemID  = $aa[3];  //許可證號
		$itemExp = $aa[6];  //到期日
		$link = jb2link($itemID); //藥品資料連結
		$linkIns = jb2linkIns($itemID); //藥品仿單連結
		$dateDrug = strtotime((intval(substr($itemExp,0,3))+1911)."-".substr($itemExp,3,2)."-".substr($itemExp,5,2));
        break;
    default:
       return "";
	}

	
	if($dateDrug > $dateNow) {
		$classExpired = "";
		if(substr($itemExp,0,3)=="999" && $aa[4]=="0.00") $classExpired = " maxdate";
	} else{
		$classExpired = " expired";
		if($optExp=="wo") return "";
	}
	$output = '<div class="drugDiv'.$classExpired.'" id="drugeg"><table class="table" style="margin:auto;"><thead><tr><th><big>'.$itemChi.'</big></th></tr></thead><tfoot><tr><td><small><a href="'.$link.'" data-toggle="tooltip" title="食藥署連結" target="_blank"><span class="glyphicon linkicon glyphicon-link" aria-hidden="true"></span>'.$itemID.'</a>,&nbsp;'.$itemExp.'到期&nbsp;<a href="'.$linkIns.'" data-toggle="tooltip" title="仿單文件" target="_blank"><span class="glyphicon linkicon glyphicon-file" aria-hidden="true"></span></a></small></td></tr></tfoot><tbody><tr><td><big>'.$itemEng.'</big></td></tr><tr><td class="small" style="padding:0px;"><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$itemIng.'<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$itemInd.'</small></td></tr></tbody></table></div>';
	return $output;
}
function line2trcsv($str){
	$output = "<tr>";
	$aa = explode("\t",$str);
	for($i=0;$i<count($aa)-1;$i++){
		$output .= "<td>".$aa[$i]."</td>";
	}
	$output .= "</tr>";
	return $output;
}
$result = "";
$linecount = 0;

if(isset($_POST["query"])){ //搜尋健保資料
	//$result = "<table class='tftable'>";
	//$result .= getth();
	$filename = "data/drug".$_POST["file"].".txt";
	$needle = $_POST["query"];
	$needle = iconv( "UTF-8","big5",  $needle);
	foreach(file($filename) as $fli=>$fl)
	{
		//$strutf = iconv( "big5","UTF-8",  $fl);
		if(stripos($fl, $needle)!==false)
		{
			//$strutf = iconv( "big5","UTF-8",  $fl);
			//echo $filename . ' on line ' . ($fli+1) . ': <br>' . $strutf ."<br>";
			$result .= line2tr($fl);
		}
	}
	//$result .= "</table>";
}elseif(isset($_POST["queryfda"])){ //搜尋食藥署資料
	$filename = "data/fdadrug".$_POST["file"].".csv";
	$needle = $_POST["queryfda"];
	foreach(file($filename) as $fli=>$fl)
	{
		if(mb_stripos($fl, $needle,0,"UTF-8")!==false)
		{
			$result .= line2trfda($fl);
		}
	}
}elseif(isset($_POST["queryapp"])){ //搜尋藥品外觀資料
	//$result .= "<tr><td>".var_export($_POST,true)."</td></tr>";
	$filename = "data/drugapp".$_POST["file"].".csv";
	$needle = $_POST["queryapp"];
	foreach(file($filename) as $fli=>$fl)
	{
		if(mb_stripos($fl, $needle,0,"UTF-8")!==false)
		{
			if($linecount<102){
				$linecount++;
				$result .= line2trfda($fl);
			}
		}
	}
}elseif(isset($_POST["querycsv"])){ //搜尋其他資料庫 無索引檔
	$filename = "data/".$_POST["file"].".csv";
	$needle = $_POST["querycsv"];
	$license = intval($_POST["license"]);
	if(isset($_POST["output"])) $optOutput = $_POST["output"];
	else $optOutput = "t";
	if(isset($_POST["database"])) $db = $_POST["database"];
	else $db = "fda";
	if(isset($_POST["expire"])) $optExp = $_POST["expire"];
	else $optExp = "wi";
	$start = intval($_POST["start"]);
	$run = intval($_POST["run"]);
	$max = intval($_POST["max"]);
	//log 紀錄
	if(!isset($_SESSION['querycsv'])) $_SESSION['querycsv'] = "";
	if($_SESSION['querycsv'] != $needle){ //同一人搜尋同一關鍵字不記錄第二次
		$logfile = "log/".date('Ym').".log";
		$log = fopen($logfile,'a');
		$strlog = date('Y-m-d H:i:s')."--".$_POST["querycsv"]."--".$_POST["database"]."--".$_POST["output"]."--".$_POST["expire"]."--".$_SERVER["REMOTE_ADDR"]."--".$_SERVER["HTTP_USER_AGENT"]."\n";
		fwrite($log,$strlog);
		fclose($log);
	}
	$_SESSION['querycsv'] = $needle;
	//==
	$file = new SplFileObject($filename);
	$file->seek($start);
	$count=0;
	while (!$file->eof() && $count<$run) {
		$fl = $file->fgets();
		if(mb_stripos($fl, $needle,0,"UTF-8")!==false)
		{
			if($linecount<102){
				$linecount++;
				//$result .= line2trcsv($fl);
				if($optOutput=="c") $result .= line2div($fl,$db,$optExp);
				else $result .=line2trind($fl,$license,$db,$optExp);
			}
		}
		$count++;
	}
}elseif(isset($_POST["queryind"])){ //搜尋其他資料庫 有索引檔
	//$result .= "<tr><td>".var_export($_POST,true)."</td></tr>";
	$filenameIndex = "data/".$_POST["file"].".ind";
	$filenameData = "data/".$_POST["file"].".csv";
	$needle = $_POST["queryind"];
	//$needle = mb_convert_encoding( $_POST["queryind"], "BIG5", "UTF-8");
	$start = intval($_POST["start"]);
	$run = intval($_POST["run"]);
	$max = intval($_POST["max"]);
	$license = intval($_POST["license"]);
	
	$fileIndex = new SplFileObject($filenameIndex);
	$fileData = new SplFileObject($filenameData);
	$fileIndex->seek($start);
	$count=0;
	while (!$fileIndex->eof() && $count<$run) {
		$fl = $fileIndex->fgets();
		if(mb_stripos($fl, $needle,0,"UTF-8")!==false)
		//if(stripos($fl, $needle)!==false)
		{
			$fileData->seek($fileIndex->key());
			$fll = $fileData->current();
			if($linecount<102){
				$linecount++;
				$result .= line2trind($fll,$license);
			}
		}
		$count++;
	}
}elseif(isset($_POST["querygrep"])){ //linux指令搜尋
	$filename = "data/".$_POST["file"].".csv";
	$needle = transneedle($_POST["querygrep"]); //關鍵字處理
	$license = intval($_POST["license"]);
	if(isset($_POST["output"])) $optOutput = $_POST["output"];
	else $optOutput = "t";
	if(isset($_POST["database"])) $db = $_POST["database"];
	else $db = "fda";
	if(isset($_POST["expire"])) $optExp = $_POST["expire"];
	else $optExp = "wi";
	//log 紀錄
	$logfile = "log/".date('Ym').".log";
	$log = fopen($logfile,'a');
	$strlog = date('Y-m-d H:i:s')."--".$_POST["querygrep"]."--".$_POST["database"]."--".$_POST["output"]."--".$_POST["expire"]."--".$_SERVER["HTTP_X_CLIENT_IP"]."--".$_SERVER["HTTP_USER_AGENT"]."\n";
	fwrite($log,$strlog);
	fclose($log);
	//==
	$strexec = 'grep -i "'.$needle.'" '.$filename;
	$strResult = shell_exec($strexec);
	$aResult = explode("\n",$strResult);
	foreach($aResult as $key => $value){
		if($value=="") continue;
		if($linecount<102){
			$linecount++;
			if($optOutput=="c") $result .= line2div($value,$db,$optExp);
			else $result .=line2trind($value,$license,$db,$optExp);
		}
	}
}
echo $result;
?>