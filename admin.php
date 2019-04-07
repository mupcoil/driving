<?php
//כותרת הדף
@$system['site_title'].=" - ניהול";

if(@page()){
if($system['admin_login']==0){
// <<<<<<<<< from here output <<<<<<<<<
?>
<b>התחברות לניהול</b>
<br />
<?php
$error=1;
$form_input='username,password';
if(isset_post($form_input) && full_post($form_input)){
if($system['admin_username']==$_POST['username'] && $system['admin_password']==$_POST['password']){

$error=0;
$_SESSION['admin_username']=$system['admin_username'];
$_SESSION['admin_sha1password']=sha1($system['admin_password']);
echo "<br />התחברת בהצלחה.<br /><br /><a href=\"".$system['site_url']."?".$system['page']."\">המשך</a>";

}else{
echo "התחברות נכשלה";
}
echo "<br />";
}
if($error==1){
?>
<form action="?<?=$system['page']?>" method="post">
<table width="0" border="0">
	
	<tr>
    <td width="86">שם משתמש:</td>
    <td width="97"><input name="username" type="text" value="" /> <?php if(isset_post($form_input) and !full_post('username')){echo "שדה זה חובה";} ?></td>
	</tr>
	
	<tr>
    <td width="86">סיסמה:</td>
    <td width="97"><input name="password" type="password" value="" /> <?php if(isset_post($form_input) and !full_post('password')){echo "שדה זה חובה";} ?></td>
	</tr>
	
</table>
<input name="" type="submit" value=" אישור " />
</form>
<?php } 








}else{
if(!isset($_GET[$system['page']]) || $_GET[$system['page']]==""){
include("resource/admin/index.php");
}else{
include("resource/admin/".$_GET[$system['page']].".php");

if($_GET[$system['page']]=="logout"){
unset ($_SESSION['admin_username']);
unset ($_SESSION['admin_sha1password']);
header("location: ".$system['site_url']);
}

}

}
// >>>>>>>>> end of output >>>>>>>>>
}
?>