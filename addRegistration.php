<?php

//Test Sessions variable with hardcoding
// $_SESSION["uid"]="Karups";
// $_SESSION["pwd"]="1234";


if (isset($_SESSION["uid"])) {
    $uid = $_SESSION["uid"];
}

if (
    isset($_POST["login"]) && !empty($_POST["uid"])
    && !empty($_POST["pwd"])
) {


    if (empty($_POST["isadmin"])) {
        $isadmin = FALSE;
        $_SESSION["isadmin"] = FALSE;
    } else {
        $isadmin = TRUE;
        $_SESSION["isadmin"] = TRUE;
    }

    $uid = $_POST['uid'];
    $pwd = $_POST['pwd'];

    include './database/config/config.php';
    // if Login as Admin is checked, use admin table. Or use user table.
    if ($isadmin) {
        if ($connection == "local") {
            $t_admin = "admin";
        } else {
            $t_admin = "$database.admin";
        }
    } else {
        if ($connection == "local") {
            $t_customer = "customer";
        } else {
            $t_customer = "$database.customer";
        }
    }
    //echo "table name is $t_user";

    try {
        $db = new PDO("mysql:host=$host", $user, $password, $options);
        //echo "Database connected successfully <BR>";

        if ($isadmin) {
            $sql_select = "Select * from $t_admin where admin_username = '$uid' and admin_pwd = '$pwd'";
            //echo "SQL Statement is : $sql_select <BR>";
        } else {
            $sql_select = "Select * from $t_customer where cust_username =  '$uid' and cust_pwd = '$pwd'";
            //echo "SQL Statement is : $sql_select <BR>";
        }

        $stmt = $db->prepare($sql_select);
        $stmt->execute();

        if ($rows = $stmt->fetch()) {
            //echo   $rows['username'];
            //echo '<script>alert("Login Successful")</script>';
            $_SESSION['valid'] = TRUE;
            $_SESSION['uid'] = $_POST["uid"];
            $_SESSION["pwd"] = $_POST["pwd"];
        } else {
            echo '<script>alert("Invalid Username or Password. Try again")</script>';
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>iMovies - Online Movies Reservation System </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


</head>

<body>

    <!-- Header section goes here -->
    <div class="container-fluid text-center bg-primary text-white pt-3">
        <h1>iMovies - Online Movie Reservation System</h1>
        <h2>DBMS Academic Project - Fall Semester 2020</h2>
        <h4>Done by - K Gokul Raj, Rahul Sanjeev, Mohit Sinha</h4>
        <br>
    </div>

    <!--menu section goes here-->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark pt-3">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand" href="index2.php">iMovies</a>

            <!-- Rightside navbar Links: Set based on User signed-in or not-->
            <?php
            if (isset($_SESSION["uid"])) {

            ?>
                <!-- Set rightside navbar links if no user signed-in -->
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown text-info"><a class="dropdown-toggle" data-toggle="dropdown">
                            <?php if ($isadmin) { ?> <i class="fa fa-user-secret"></i> <?php } ?> Welcome
                            <?php echo $uid; ?></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"> <i class="fa fa-user-plus"></i> My Profile</a></li>
                          
                            <li><a href="./logout.php"> <i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>

            <?php } else { ?>
                <!-- Set rightside navbar links if user has signed-in -->
                <ul class="navbar-nav navbar-right">
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php"><i class="fa fa-sign-in"></i> Login</a>
                    </li>
                </ul>
            <?php } ?>

        </div>
    </nav>

    <!-- LOGO -->
    <div class="container-fluid" style="margin-top:10px">
        <div class="row">
            <div class="col-sm-2">
                <div class="container ">
                    <div class="row">

                        <!-- left side nvertical navigation bar starts here -->

                        <nav class="navbar bg-light">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Movies</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">By Language</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">By Screen</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Screens</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">By Language</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">By Movies</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">About us</a>
                                </li>
                            </ul>
                        </nav>

                        <!-- left side nvertical navigation bar ends here -->
                    </div>

                </div>
            </div>

            <!--registration form starts here -->
            <div class="container-sm" style="Width:80%">

                <div class="login-form">
                    <form action="./insertRegistration.php" method="post">
                        <h2 class="text-center">Registration Form</h2>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <span class="fa fa-user"></span>
                                                </span>
                                            </div>
                                            <input type="text" name="cname" id="cname" class="form-control" placeholder="Customer Name" required="required">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-list-ol "></i>
                                                </span>
                                            </div>
                                            <input type="number" name="cage" id="cage" class="form-control" placeholder="Customer Age" required="required">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <select class="form-control" id="cgender" name="cgender" placeholder="Select Gender">
                                            <option>m</option>
                                            <option>f</option>
                                            <option>t</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <span class="fa fa-envelope"></span>
                                                </span>
                                            </div>
                                            <input type="email" name="cemail" id="cemail" class="form-control" placeholder="Customer Email" required="required">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <span class="fa fa-mobile "></span>
                                                </span>
                                            </div>
                                            <input type="tel" name="cphone" id="cphone" class="form-control" placeholder="Customer Mobile Number" required="required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container-sm" style="Width:40%">

                            <div class="login-form">
                                <form action="./index2.php" method="post">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <span class="fa fa-user"></span>
                                                </span>
                                            </div>
                                            <input type="text" name="cuser" id="cuser" class="form-control" placeholder="Customer Username" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-lock"></i>
                                                </span>
                                            </div>
                                            <input type="password" name="cpwd" id="cpwd" class="form-control" placeholder="Password" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-lock"></i>
                                                </span>
                                            </div>
                                            <input type="password" name="cpwd1" id="cpwd1" class="form-control" placeholder="Confirm Password" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>
            </div>

            <!--registration form ends here -->

        </div>
    </div>
    <!-- footer section goes here-->

    <div class="navbar fixed-bottom">
        <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
            <h3> Developed using following technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap, Javascript.</h3>
        </div>
    </div>

</body>

</html>