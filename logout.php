<?php session_start();
require('API/api.class.php');
    $apiService = new apiService();
    $apiService->logout();
?>

