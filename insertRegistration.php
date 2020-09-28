<?php

//Test Sessions variable with hardcoding
// $_SESSION["uid"]="Karups";
// $_SESSION["pwd"]="1234";


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




    include './database/config/config.php';

    try {
        $db = new PDO("mysql:host=$host", $user, $password, $options);
        //echo "Database connected successfully <BR>";

        

        $sql_insert = "INSERT INTO $t_movies (movie_name, movie_cast, movie_director, movie_img_fn, movie_language, 
        movie_rel_date, movie_short_desc)  VALUES ('$iname', '$icast' , '$idirect','$ifn', '$ilang', 
        date('$ireldate'),'$idesc' )";
    //echo "SQL Statement $sql_insert";
    $stmt = $db->prepare($sql_insert);
    $rows = $stmt->execute(array());
    //echo "Rows  $rows <BR>";

    If ($rows>0){   

            //echo   $rows['username'];
            //echo '<script>alert("Login Successful")</script>';
            $_SESSION['valid'] = TRUE;
            $_SESSION['user'] = $_POST["user"];
            $_SESSION["pwd"] = $_POST["pwd"];
        } else {
            echo '<script>alert("Invalid Username or Password. Try again")</script>';
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
?>