<?php 

session_start();
if (isset($_POST['register'])) {

    //echo '<script>alert("Entering processRegistration.php"); </script>';
    $cname = $_POST["cname"];
    $cage = $_POST["cage"];
    $cgender = $_POST["cgender"];
    $cemail = $_POST["cemail"];
    $cphone = $_POST["cphone"];
    $cuser = $_POST["cuser"];
    $cpwd = $_POST["cpwd"];
    $cpwd1 = $_POST["cpwd1"];

    include './database/config/config.php'; 
    if ($connection == "local") {
        $t_customer = "customer";
    } else {
        $t_customer = "$database.customer";
    }

    try {
        $db = new PDO("mysql:host=$host", $user, $password, $options);
        //echo '<script>alert("Db connected successfully")</script>';
        //Check username exists, if so, return to registration form
        $userExist = $db->query("Select count(*) as ucount from $t_customer where cust_username = '$cuser'")->fetch()['ucount'];
        $emailExist = $db->query("Select count(*) as ucount from $t_customer where cust_email_id = '$cemail'")->fetch()['ucount'];
        if($userExist>0){
            $form_error = "Sorry... username already taken"; 	
            $err=TRUE;
            echo "<script> alert('Username already taken. Try again'); </script>";
            //header("Refresh: 1; URL = addRegistration.php");
            //exit();
        } else if ($emailExist>0){
            $form_error = "Sorry... email already taken"; 
            $err=TRUE;
            echo "<script> alert('Email already taken. Try again'); </script>";
            //header("Refresh: 1; URL = addRegistration.php");
            //exit();
        } else {
            $sql_insert = "INSERT INTO $t_customer (cust_name,cust_pwd,cust_email_id,cust_ph_no,cust_age,cust_gender,cust_username)  
            VALUES ('$cname', '$cpwd' , '$cemail','$cphone', $cage , '$cgender' ,'$cuser' )";
            
            $stmt = $db->prepare($sql_insert);
            $rows = $stmt->execute();

            if ($rows > 0) {

                $_SESSION["valid"] = TRUE;
                $_SESSION["uid"] = $_POST["cuser"];
                $_SESSION["pwd"] = $_POST["cpwd"];
                
                if (isset($_SESSION["uid"])) {
                    $uid = $_SESSION["uid"];
                    //echo "<script> alert('Session variable UID is set') </script>";
                    //echo "session uid is $uid<br>";
                }

                echo "<script> alert('Registration Successful'); </script>";
                header("Refresh: 1; URL = index2.php");
                exit();
            }
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

?>
<<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
    <form id="processReg" name="processReg" action= "addRegistration.php" >
        <input type="hidden" name="cname"   id="cname"  value=<?php echo "'$cname'"; ?> />
        <input type="hidden" name="cage"    id="cage"   value=<?php echo "$cage"; ?> />
        <input type="hidden" name="cgender" id="cgender" value=<?php echo "'$cgender'"; ?> />
        <input type="hidden" name="cphone"  id="cphone" value=<?php echo "$cphone"; ?> />
        <input type="hidden" name="ferror"  id="ferror" value=<?php echo "'$form_error'"; ?> />

    </form>
    <script type="text/javascript">
        document.getElementById('processReg').submit();
    </script>
    </body>
</html>