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

    //Check username exists, if so, return to registration form
    $userExist = $db->query("Select count(*) as ucount from $t_customer where cust_username = '$cuser'")->fetch()['ucount'];
    if($userExist>0){
        echo "User Name already taken. Please try another";
        $_SESSION["regerror"]=TRUE;
    } else {

        $sql_insert = "INSERT INTO $t_customer (cust_name,cust_pwd,cust_email_id,cust_ph_no,cust_age,cust_gender,cust_username)  
            VALUES ('$cname', '$cpwd' , '$cemail','$cphone', $cage , '$cgender' ,'$cuser' )";
        echo "$sql_insert <br>";
        echo "query insertion begins here<br>";
        
        $stmt = $db->prepare($sql_insert);
        $rows = $stmt->execute();

        echo "query has been executed<br>";
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
            //header("Refresh: 1; URL = index2.php");
            //exit();
        }
    }
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

echo " About to enter HTML Body <BR>";

?>
<html>

<body>
    <?php 
    if($userExist>0){  
        echo "Trying to return <BR>"; ?>
    <form id="regform" action= <?php if($userExist){ echo "addRegistration.php"; }else{echo "index2.php";} ?> >
        <input type="hidden" name="cname"   value=<?php echo $cname; ?> />
        <input type="hidden" name="cage"    value=<?php echo $cage; ?> />
        <input type="hidden" name="cgender" value=<?php echo $cgender; ?> />
        <input type="hidden" name="cemail"  value=<?php echo $cemail; ?> />
        <input type="hidden" name="cphone"  value=<?php echo $cphone; ?> />
    </form>
    <script type="text/javascript">
        document.getElementById('regform').submit();
    </script>
    <?php

    }   
    ?>

</body>

</html>