<?php

session_start();

$uri = $_SERVER['REQUEST_URI'];
$parts = parse_url($uri);
parse_str($parts['query'], $query);

if (!empty($query)) {
    $uid = $query["uid"];
    $_SESSION["uid"] = $query["uid"];
    $_SESSION["isadmin"]=TRUE;
} else {
    if (isset($_SESSION["uid"])) {
        $uid = $_SESSION["uid"];
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
            <a class="navbar-brand" href="#">iMovies</a>

            <!-- Rightside navbar Links: Set based on User signed-in or not-->
            <?php
            if (isset($_SESSION["uid"])) {

            ?>
                <!-- Set rightside navbar links if no user signed-in -->
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown text-info"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user-secret"></i> Welcome <?php echo $uid; ?></a>
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
                                    <a class="nav-link" >Movies</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="./addMovie.php">Add New Movie</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Manage Movies</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Screens</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Add New Screen</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Manage Screens</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Shows</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Add New Shows</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Manage Shows</a>
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
            <!-- Carousel begins here which occupies 10/12 of width -->

            <div class="col-sm-10">
                <div id="demo" class="carousel slide" data-ride="carousel">

                    <!-- Indicators -->
                    <ul class="carousel-indicators">
                        <li data-target="#demo" data-slide-to="0" class="active"></li>
                        <li data-target="#demo" data-slide-to="1"></li>
                    </ul>

                    <!-- The slideshow -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row">
                                <div class="col-sm-4">
                                    <img src="./img/ratsasan6.jpg" alt="Ratsasan">
                                </div>
                                <div class="col-sm-4">
                                    <img src="./img/ratsasan6.jpg" alt="Ratsasan">
                                </div>
                                <div class="col-sm-4">
                                    <img src="./img/ratsasan6.jpg" alt="Ratsasan">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <div class="col-sm-4">
                                    <img src="./img/ratsasan6.jpg" alt="Ratsasan">
                                </div>
                                <div class="col-sm-4">
                                    <img src="./img/ratsasan6.jpg" alt="Ratsasan">
                                </div>
                                <div class="col-sm-4">
                                    <img src="./img/ratsasan6.jpg" alt="Ratsasan">
                                </div>
                            </div>
                        </div>
                        <!-- Left and right controls -->
                        <a class="carousel-control-prev" href="#demo" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next" href="#demo" data-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Carousel ends here which occupies 10/12 of width -->


        </div>
    </div>
    <!-- footer section goes here-->

        <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
            <h3> Developed using technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap, Javascript.</h3>
        </div>

</body>

</html>