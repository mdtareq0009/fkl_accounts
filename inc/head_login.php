<?php require_once('ini.php'); ?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="metro4:init" content="true">
    

    <!-- Metro 4 -->
    <link rel="stylesheet" href="vendor/metro4/css/metro-all.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/custom-style.css">
    <link rel="stylesheet" href="css/print.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/buttons.dataTables.min.css">

    <script src="vendor/jquery/jquery-3.4.1.min.js"></script>
    <!-- <script src="js/printjs.min.js"></script> -->
    <script>
    // JavaScript function to disable Enter key submit
    function disableEnterSubmit(event) {
      if (event.key === 'Enter') {
        event.preventDefault();
      }
    }
  </script>

    <title><?=$pageOpt->appTitle('-');?>FKL Automation System</title>
    
</head>