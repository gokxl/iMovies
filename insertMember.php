<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Family Member added </title>
    <link rel="stylesheet" href="css/indexstyles.css">
</head>
<body>
    

<?php

include './database/config/config.php';

$table = "$database.members";

$iname = $_POST['fullname'];
$igender = $_POST['gender'];
$idob = $_POST['DoB'];

try {
    $db = new PDO("mysql:host=$host",$user,$password,$options);
   
    $sql_insert = "INSERT INTO $table (mem_name, mem_gender, mem_dob) VALUES ('$iname', '$igender' , date('$idob') )";
    $stmt = $db->prepare($sql_insert);
    $rows = $stmt->execute();
    If ($rows>0){   ?>

        <div class="main"> 
            <h2> New family member added successfully </h2>

            <table>
                <tr> <th> Name      : </th> <td> <?php echo $iname ?> </tr>
                <tr> <th> Gender    : </th> <td> <?php echo $igender ?> </tr>
                <tr> <th> DoB       : </th> <td> <?php echo $idob ?> </tr>
            </table>

            <p class="alignleft"> Add Family Member <a href="addfamilymember.php" target="_self">Add Family</a> </p>
            <p class="alignright"> Return to Home Page <a href="index2.php" target="_self">Home Page</a> </p>
            <div style="clear: both;"></div>
        </div>
                
    <?php
    }else{
        echo "Error Inserting new member <BR>";
    }
} catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }


?>

</body>
</html>