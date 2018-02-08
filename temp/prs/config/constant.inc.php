<?php session_start();
define('SERVER_URL','http://localhost/pradeep/web/');
define('TOKEN','APKHINDAEHBFH2312J97709032NBJK');
define('LOGIN_URL','http://localhost/pradeep/web/API/getvendordetails.json');


$conn=mysql_connect('192.168.80.5','exhibition','exhibition'); 
mysql_select_db('exhibition') or die('Mysql not Connect');
?>

