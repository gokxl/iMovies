<?php

session_start();

if (isset($_SESSION["uid"])) {
    $uid = $_SESSION["uid"];
}


include './database/config/config.php';
//set table name based on local or remote connection
if ($connection == "local") {
    $t_customer = "customer";
    
} else {
    $t_customer = "$database.customer";
    $t_Movies = "$database.Movies";  
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>iMovies - Online Movies Reservation System</title>
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
            <a class="navbar-brand" href="./index2.php">iMovies</a>

            <!-- Rightside navbar Links: Set based on User signed-in or not-->
            <?php
            if (isset($_SESSION["uid"])) {

            ?>
            <!-- Set rightside navbar links if no user signed-in -->
            <ul class="navbar-nav navbar-right">
                <li class="dropdown text-info"><a class="dropdown-toggle" data-toggle="dropdown">Welcome
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
            <!-----content add -->
            <div class="container" style=" width:80% ">
                <div class="row">

                    <form action="viewLang2.php" method="post" class="was-validated" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="font-weight-bold" for="mov_lang">Movie Language:</label> <BR>
                            <label class="radio-inline"><input type="radio" name="mov_lang"
                                    value="English">English</label>
                            <label class="radio-inline"><input type="radio" name="mov_lang" value="Hindi">Hindi</label>
                            <label class="radio-inline"><input type="radio" name="mov_lang"
                                    value="Kannada">Kannada</label>
                            <label class="radio-inline"><input type="radio" name="mov_lang"
                                    value="Malayalam">Malayalam</label>
                            <label class="radio-inline"><input type="radio" name="mov_lang" value="Tamil"
                                    checked>Tamil</label>
                            <label class="radio-inline"><input type="radio" name="mov_lang"
                                    value="Telugu">Telegu</label>
                            <label class="radio-inline"><input type="radio" name="mov_lang" value="Other">Other</label>
                        </div>

                        <input class="form-group bg-primary text-white" type="submit" name="submit" value="View Movies">
                    </form>

                </div>
                <br>
                <div class="row">
                    <div class="col-sm-2"></div>

                    <img src="./img/viewpageImage2.jpg" alt="searchGuy">
                </div>
            </div> <BR>

            <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
                <h3> Developed using technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap, Javascript.
                </h3>
            </div>


</body>

</html>