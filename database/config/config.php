<?php
    
    $connection = "local";
    //$connection = "remote";
    
    if($connection == "remote"){

        /* infinityfree.net remote db details at sql306.epizy.com mysql */

        //sql306.byetcluster.com

        $database = " 	epiz_26943116_iMovies";        # Get these database details from
        $host =  "sql308.epizy.com:3306";  # the web console
        $user     = "epiz_26943116";   #
        $password = "Vit2020project";   #
        $port     = 3306;           #
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 

    }else{
        
        $database = "iMovies";        # Get these database details from
        $host =  "localhost;dbname=iMovies";  # the web console
        $user     = "imovies";   #
        $password = "Vit2020@project";   #
        $port     = 3306;           #
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');         
    }

    //  echo '<script>alert("'.$connection.' Connection: '.$host.'")</script>'; 

?>