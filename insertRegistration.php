<?php

//Test Sessions variable with hardcoding
// $_SESSION["uid"]="Karups";
// $_SESSION["pwd"]="1234";

include './database/config/config.php';

if (isset($_SESSION["uid"])) {
    $uid = $_SESSION["uid"];
}


$cname = $_POST["cname"];
$cage = $_POST["cage"];
$cgender = $_POST["cgender"];

$cemail = $_POST["cemail"];
$cphone = $_POST["cphone"];
$cuser = $_POST["cuser"];
$cpwd = $_POST["cpwd"];
$cpwd1 = $_POST["cpwd1"];



if ($connection == "local") {
    $t_customer = "customer";
} else {
    $t_customer = "$database.customer";
}

try {
    $db = new PDO("mysql:host=$host", $user, $password, $options);
    //echo "Database connected successfully <BR>";

    $sql_insert = "INSERT INTO $t_customer (cust_name,cust_pwd,cust_email_id,cust_ph_no,cust_age,cust_gender,cust_username)  
        VALUES ('$cname', '$cpwd' , '$cemail','$cphone', $cage , '$cgender' ,'$cuser' )";
    echo "$sql_insert <br>";
    echo "query insertion begins here<br>";
    //echo "SQL Statement $sql_insert";
    $stmt = $db->prepare($sql_insert);
    $rows = $stmt->execute();

    echo "query has been executed<br>";


    //echo "Rows  $rows <BR>";

    if ($rows > 0) {

        echo "query has been inserted<br>";
        //echo   $rows['username'];
        //echo '<script>alert("Login Successful")</script>';
        $_SESSION["valid"] = TRUE;
        $_SESSION["uid"] = $_POST["cuser"];
        $_SESSION["pwd"] = $_POST["cpwd"];

        if (isset($_SESSION["uid"])) {
            $uid = $_SESSION["uid"];
            echo "session uid is $uid<br>";
        }
        header("Refresh: 1; URL = index.php");
        exit();
    } else {
        echo '<script>alert("Insert Appropriate values")</script>';
    }
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>
<html>

<body>

</body>

</html>