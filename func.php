<?php
//data array
function string_data_array($data){

if(is_string($data)){
$a=explode(",",$data);
foreach ($a as $d => $k) { 
$t=explode("=",$k);
$b[$t[0]]=$t[1];
}
return $b;
}

}



// --- מעלה מספר באחד ---
function update_row_rise($table,$field,$condition) {
global $system;

if (is_numeric($condition)) {
$condition="id=".$condition;
}

$sql="select ".$field." from ".$table." where ".$condition;

$query=mysql_query($sql,$system['mysql_connect']);
$num=mysql_result($query, 0);
$num++;

mysql_query("UPDATE ".$table." SET ".$field."=".$num." WHERE ".$condition,$system['mysql_connect']);
mysql_free_result($query);

return $num;

}



// --- שולף שורה אחת מטבלה ---
function count_rows($sql,$cols="*") {
global $system;

if (!preg_match("/^select /i", $sql)) {
$sql="select count(".$cols.") from ".$sql;
}

$query=mysql_query($sql,$system['mysql_connect']);
return mysql_result($query, 0);
mysql_free_result($query);

}


// --- שולף שורה אחת מטבלה ---
function fetch_row($sql,$cols="*") {
global $system;

if (!preg_match("/^select /i", $sql)) {
$sql="select ".$cols." from ".$sql;
}

$query=mysql_query($sql,$system['mysql_connect']);
return mysql_result($query, 0);
mysql_free_result($query);

}

// --- שולף שורות מטבלה ---
function fetch_rows($sql,$cols="*") {
global $system;

if (!preg_match("/^select /i", $sql)) {
$sql="select ".$cols." from ".$sql;
}
$return=array();
$query=mysql_query($sql,$system['mysql_connect']);

   while($row=mysql_fetch_array($query)) {
       $return[] = $row;
   }
   
   if(!isset($return)){
   return false;
   }else{
   return $return;
   }
}


// --- מכניס נתונים לטבלה ---
function insert_row($table,$data) {
global $system;
if(is_string($data)){
$data=string_data_array($data);
}

  $cols = '(';
      $values = '(';
      
      foreach ($data as $key=>$value) { 
          
         $cols .= $key.",";  
         
$query = mysql_query("SELECT ".$key." FROM ".$table);
$col_type = mysql_field_type($query, 0);
mysql_free_result($query);
 
         if (!$col_type) return false;
         if (is_null($value)) {
            $values .= "NULL,";   
         } 
         elseif (substr_count('int real ', "$col_type ")) {
            $values .= intval($value).",";
         }
         elseif (substr_count('string blob ', "$col_type ")) {
            $values .= "'$value',";
         }
      }
      $cols = rtrim($cols, ',').')';
      $values = rtrim($values, ',').')';     

      $sql = "INSERT INTO ".$table." ".$cols." VALUES ".$values;
  $query=mysql_query($sql,$system['mysql_connect']);
  $insert_id=mysql_insert_id();
  
      if($insert_id==0){return 0;}else{return $insert_id;}
      

}


// --- עדכון נתונים מטבלה ---
function update_row($table, $data, $condition) {
global $system;

if(is_string($data)){
$data=string_data_array($data);
}

if (is_numeric($condition)) {
$condition="id=".$condition;
}
      
      if (empty($data)) {
      echo "You must pass an array to the update_row() function.";
      return false;
      }
      
      $sql = "UPDATE ".$table." SET";
      foreach ($data as $key=>$value) {
          
         $sql .= " $key=";  
         
        $query = mysql_query("SELECT ".$key." FROM ".$table);
$col_type = mysql_field_type($query, 0);
mysql_free_result($query);

         if (!$col_type) return false;
         
         if (is_null($value)) {
            $sql .= "NULL,";   
         } 
         elseif (substr_count('int real ', "$col_type ")) {
            $sql .= intval($value).",";
         }
         elseif (substr_count('string blob ', "$col_type ")) {
            $sql .= "'".$value."',";  
         }

      }
      $sql = rtrim($sql, ',');
      if (!empty($condition)) $sql .= " WHERE ".$condition;
      
  $query=mysql_query($sql,$system['mysql_connect']);
  if(!$query){
  echo mysql_error();
  }else{
      return mysql_affected_rows();
  }
  
   }
   
   
   
   
   
   
   
   
   
// --- בודק אם יש ---
function isset_post($data){
$data=explode(",",$data);
foreach ($data as $key) { 
if(!isset($_POST[$key])){
return false;
}
}
return true;

}

// --- בודק ממולא ---
function full_post($data){
$data=explode(",",$data);
foreach ($data as $key) { 
if($_POST[$key]==""){
return false;
}
}
return true;

}






function clean_sc($text)
{
$text=str_replace('\"', '&quot;', $text);
$text=str_replace("\'", '&#039;', $text);
$text=str_replace('"', '&quot;', $text);
$text=str_replace("'", '&#039;', $text);

return $text;
}
function unclean_sc($text)
{
$text=str_replace('&quot;', '"', $text);
$text=str_replace("&#039;", "'", $text);

return $text;
}





function hebrew_get($get){
$heb_get=preg_replace("/([\xE0-\xFA])/e","chr(215).chr(ord(\${1})-80)", $_GET[$get]);
$heb_get=str_replace("_", " ", $heb_get);

return $heb_get;
}




function replace_for_url_sp($text){

$text=str_replace("'","",$text);
$text=str_replace("\"","",$text);
$text=str_replace(".","",$text);
$text=str_replace(",","",$text);
$text=str_replace("?","",$text);
$text=str_replace("!","",$text);
$text=str_replace(":","",$text);
$text=str_replace(")","",$text);
$text=str_replace("(","",$text);
$text=str_replace("-"," ",$text);

$text=str_replace("&quot;","",$text);
$text=str_replace("&#039;","",$text);

$text=str_replace(" ","-",$text);
$text=str_replace("--","-",$text);
$text=str_replace("---","-",$text);
$text=str_replace("--","-",$text);
return $text;
}






// -- העלאת קובץ --
function upload_file($name_post,$folder_name,$allow_type=""){

if($_FILES[$name_post]['error']!==4) {

$c=explode("/",$_FILES[$name_post]['type']);

if($c[0]!==$allow_type && $allow_type!==""){
return false;
}else{

$type=preg_replace("/^.*\.(.*).*/s","\\1",$_FILES[$name_post]['name']);
$file=substr(md5(rand(0,9).mt_rand(0,9).rand(0,9).mt_rand(0,9).$_FILES[$name_post]['tmp_name']),0,5).".".$type;
move_uploaded_file($_FILES[$name_post]['tmp_name'], "files/".$folder_name."/".$file);

return $file;
}
}else{
return false;
}

}


// -- שליחת אימייל עם כותר וקידוד טובים --
function send_email($subject,$content,$recipient){
global $admin_email;

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= "From: Nex <" . $admin_email . ">\r\n";

if(mail($recipient, $subject, $content, $headers)){
return true;
}else{
return false;
}
}
?>