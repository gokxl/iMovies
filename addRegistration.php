<?php
if (isset($_POST['err'])) {
    echo "Re-entering addRegistration.php";
    $cname = $_POST["cname"];
    $cage = $_POST["cage"];
    $cgender = $_POST["cgender"];
    $cphone = $_POST["cphone"];
    $ferror = $_POST["ferror"];
    $err = TRUE;
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
                                    <a class="nav-link">Movies</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="./viewLang.php">By Language</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="./viewTheatre.php">By Theatre</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="./viewAll.php">All</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./myBookings.php">My Bookings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="aboutUs.php">About us</a>
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
                    <form action="./processRegistration.php" method="post">
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
                                            <input type="text" name="cname" id="cname" class="form-control"
                                                placeholder="Customer Name" required <?php if($err){ echo "value=$cname"; } ?> >
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
                                            <input type="number" name="cage" id="cage" class="form-control"
                                                placeholder="Customer Age" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <select class="form-control" id="cgender" name="cgender"
                                            placeholder="Select Gender" >
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
                                            <input type="email" name="cemail" id="cemail" class="form-control"
                                                placeholder="Customer Email" required>
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
                                            <input type="tel" name="cphone" id="cphone" class="form-control"
                                                placeholder="Customer Mobile Number" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container-sm" style="Width:40%">

                            <div class="login-form">
                                <form action="./processRegistration.php" method="post">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <span class="fa fa-user"></span>
                                                </span>
                                            </div>
                                            <input type="text" name="cuser" id="cuser" class="form-control"
                                                placeholder="Enter Username" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-lock"></i>
                                                </span>
                                            </div>
                                            <input type="password" name="cpwd" id="cpwd" class="form-control"
                                                placeholder="Enter Password" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-lock"></i>
                                                </span>
                                            </div>
                                            <input type="password" name="cpwd1" id="cpwd1" class="form-control"
                                                placeholder="Confirm Password" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="register"
                                            class="btn btn-primary btn-block">Register</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>
            </div>

            <!--registration form ends here -->

        </div>
    </div>

    <!-- script to check user exist dynamically-->
    <?php 
    if (isset($_SESSION["regerror"])) { echo " Entering addRegistration again <BR>"; ?>
        <script>
        $(document).ready(function() {
            alert("User Name already taken. Try another name");
        });
        </script>
        <!-- End of script to check user exist dynamically -->
    <?php } ?>
    <!-- footer section goes here-->

        <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
            <h3> Developed using technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap, Javascript.</h3>
        </div>

</body>

</html>
