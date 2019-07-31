<?php 
    if(!isset($_SESSION["username"])) {  
        header("location:login.php");  
    }  
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>POS | Lucky Bunny App</title>
    <meta name="description" content="">
    <!-- favicon
        ============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/LuckyBunnyLogo.ico">
    <link rel="stylesheet" type="text/css" href="css/nocss/bootstrap-4.3.1.min.css">
    <link rel="stylesheet" type="text/css" href="css/nocss/data-table.css">
    <link rel="stylesheet" type="text/css" href="css/nocss/calculator.css">
    <link rel="stylesheet" type="text/css" href="css/nocss/sidebar.css">
    <link rel="stylesheet" type="text/css" href="css/nocss/jquery-ui-1.12.1.css">
  </head>
  <body>