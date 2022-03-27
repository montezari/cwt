<?php
date_default_timezone_set('UTC');
$db = mysql_connect('localhost', 'root', 'root');
if (!$db) {
    die('Gagal koneksi : ' . mysql_error());
}
mysql_select_db('cwt_db', $db);

$sql  = "SELECT * FROM ( ";
$sql .= "SELECT rid, rvalue, rtime,ltime, rtype FROM 1_etemp0 WHERE rtype=1 ";
$sql .= "UNION "; 
$sql .= "SELECT rid, rvalue, rtime,ltime, rtype FROM 1_etemp0 WHERE rtype=0  ";
$sql .= ") AS tmp ";
//$sql .= "WHERE DATE_FORMAT(tmp.ltime,'%Y-%m-%d') = '2013-12-19' ";
$sql .= "ORDER BY ltime ";

$rs = mysql_query($sql);

$str  = "{ ";
$str .= "    \"label\": \"Suhu\", ";
$str .= "    \"data\": [";
$idx=0;
$byk = mysql_num_rows($rs);
while ($r = mysql_fetch_assoc($rs)) {
  $date = strtotime($r["ltime"]);
  $y=date("Y",$date);  $d=date("d",$date);  $m=date("m",$date);
  $s=date("s",$date);  $i=date("i",$date);  $h=date("H",$date);
  $val=mktime($h+1,$i,$s,$m,$d,$y)*1000;
  $str .= "[".$val.",".$r["rvalue"]."]";
  $str .= $idx<$byk-1 ? "," : ""; 	
  $idx++;
}
$str .= "]} ";

echo $str;

?>