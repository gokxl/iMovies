<?php
   session_start();
   unset($_SESSION["uid"]);
   unset($_SESSION["pwd"]);
   unset($_SESSION["valid"]);
   
   //echo 'You have cleaned session';
   //header('Refresh: 2; URL = login.php');
   //header("Refresh: 2; Location: ./index2.php"); 
   header("Refresh: 1; URL = index.php"); 
   exit();
?>