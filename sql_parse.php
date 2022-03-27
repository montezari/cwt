<?php
ini_set('memory_limit', '5120M');
set_time_limit (0);
error_reporting(E_ALL);
ini_set('display_errors', '1');

function split_sql_file($sql, $delimiter){
   $tokens = explode($delimiter, $sql);
   $sql = "";
   $output = array();
   $matches = array();
   $token_count = count($tokens);
   for ($i = 0; $i < $token_count; $i++){
      if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))){
         $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
         $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
         $unescaped_quotes = $total_quotes - $escaped_quotes;
         if (($unescaped_quotes % 2) == 0){
            $output[] = $tokens[$i];
            $tokens[$i] = "";
         }else{
            $temp = $tokens[$i] . $delimiter;
            $tokens[$i] = "";
            $complete_stmt = false;
            for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++){
               $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
               $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
               $unescaped_quotes = $total_quotes - $escaped_quotes;
               if (($unescaped_quotes % 2) == 1){
                  $output[] = $temp . $tokens[$j];
                  $tokens[$j] = "";
                  $temp = "";
                  $complete_stmt = true;
                  $i = $j;
               }else{
                  $temp .= $tokens[$j] . $delimiter;
                  $tokens[$j] = "";
               }
            } 
         }
      }
   }

   return $output;
}


mysql_connect('localhost','k6802855_cwt_usr','k6802855_cwt_12345') or die('Gagal koneksi ke database');
mysql_select_db('k6802855_cwt_db') or die('Database tidak di ketemukan.');

if(trim($_REQUEST["file"])!=""){
  $dbms_schema = 'cache/'.trim($_REQUEST["file"]).'.sql';
  $fp = fopen($dbms_schema, 'r');
  //chmod($dbms_schema, 0777);
  $sql_query = fread($fp, filesize($dbms_schema)) or die('Gagal buka file.');
  fclose($fp);
  $sql_query = split_sql_file($sql_query,';');
  $i=0;
  foreach($sql_query as $sql){
	mysql_query($sql) or die('Query gagal di eksekusi. Error : '. mysql_error());
	$i++;
  }
  if($i>0){
    echo "Proses eksekusi SQL telah selesai.";
  }else{
    echo "Tidak ada SQL yang di eksekusi.";
  }
}	
?>