<?php
// --- starter ---
mb_internal_encoding("UTF-8");
ob_start();
session_start();
session_regenerate_id(true);
date_default_timezone_set('Asia/Jerusalem');
header('Content-type: text/html; charset=utf-8');

//בודק אם יש אישור להדפיס דף
function page(){
global $system;
if(@!$system['c_page']==1){if($system['c_page']==0){$system['c_page']=1;}}else{
return true;
}
}

// -- resources --
$system['page']="index";

$dh = opendir("resource/");
while (($file = readdir($dh)) !== false) {
$file=explode(".",$file);
if(isset($file[1])){
if($file[1]=="php"){
if(isset($_GET[$file[0]])){
$system['page']=$file[0];
}
}
}
}
closedir($dh);

// -- security inputs --
if(isset($_GET[$system['page']])){
if(@preg_match("/<|>|,/i",$_GET[$system['page']])){
$system['page']="index";
}
}

// --- founctions files ---
include("func.php");

//מנקה קצת מכל ה ' וה- " למיניהם...
foreach($_POST as $k=>$v){
$_POST[$k]=clean_sc($v);
}
foreach($_GET as $k=>$v){
$_GET[$k]=clean_sc($v);
}

// --- defualt vars ---
$system['c_page']=0;
$system['admin_login']=0;
$system['user_login']=0;

// --- settings file ---
include("config.php");

// -- token --
$_SESSION['token'] = (isset($_SESSION['token']) && strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' ? $_SESSION['token'] : sha1(microtime(true)));
if(isset($_POST['token']) && $_POST['token']==$_SESSION['token']){
$_SESSION['token'] = sha1(microtime(true));
$system['token']=1;
}else{
$system['token']=0;
}

// --- connect mysql ---
if($system['db_host']!=="" && $system['db_user']!==""){
$system['mysql_connect']=mysql_connect($system['db_host'],$system['db_user'],$system['db_pass']);
if (mysql_select_db($system['db_name'])==true && $system['mysql_connect']==true){
mysql_query("SET character_set_client = utf8");
mysql_query("SET character_set_connection = utf8");
mysql_query("SET character_set_results = utf8");
}else{
die("Error find in function 'mysql_select_db'.");
}
}



// --- check login admin ---
if(isset($_SESSION['admin_sha1password']) && isset($_SESSION['admin_username'])){
if($system['admin_username']==$_SESSION['admin_username'] && $_SESSION['admin_sha1password']==sha1($system['admin_password'])){
$system['admin_login']=1;
}
}

// --- check url htaccess ---
if(isset($_GET['str1'])){
if(isset($system['pages_dir'])){
if(is_array($system['pages_dir'])){

foreach($system['pages_dir'] as $name=>$page){
if(replace_for_url_sp($name)==$_GET['str1']){
$system['page']=$page;
break;
}
}

}
}
}

// --- include page ---
if(is_file("resource/".$system['page'].".php")){
include("resource/".$system['page'].".php");
}

// --- templates file ---
if(is_file($system['template'].".php")){
include($system['template'].".php");
}

// --- close mysql connect --
if(isset($system['mysql_connect'])){
mysql_close($system['mysql_connect']);
}
?>