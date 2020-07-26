<?php
    
    $connection = "local";
    //$connection = "remote";
    
    if($connection == "remote"){

        /* db4free.net remote db details

        $database = "familydb";        # Get these database details from
        $host =  "db4free.net:3306/familydb";  # the web console
        $user     = "karups_thangaraj";   #
        $password = "mysql4free";   #
        $port     = 3306;           #
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); */

        /* infinityfree.net remote db details at sql306.epizy.com mysql */

        //sql306.byetcluster.com

        $database = "epiz_26361741_imoviesdb";        # Get these database details from
        $host =  "sql310.epizy.com:3306";  # the web console
        $user     = "epiz_26361741";   #
        $password = "myfreehost";   #
        $port     = 3306;           #
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 

    }else{
        
        $database = "familydb";        # Get these database details from
        $host =  "localhost;dbname=familydb";  # the web console
        $user     = "imovies";   #
        $password = "Vit2020@project";   #
        $port     = 3306;           #
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');         
    }

    //  echo '<script>alert("'.$connection.' Connection: '.$host.'")</script>'; 

?>