<?php require_once('constant.inc.php');
/*
-- ------------------------------------------------
-- Include SQL Diver Here for database Connection
-- ------------------------------------------------
*/
require_once('sql/mysql.class.php');

// CHANGE THESE VALUES TO MATCH YOUR DATABASE!
$db = new MySQL(true, DB_DATABASE, DB_HOST, DB_USER, DB_PASS);
?>