<?php

session_start();

if (isset($_SESSION["uid"])) {
    $uid = $_SESSION["uid"];
}

if (
    isset($_POST["login"]) && !empty($_POST["uid"])
    && !empty($_POST["pwd"])
) {
    $uid = $_POST['uid'];
    $pwd = $_POST['pwd'];

    include './database/config/config.php';
     //set table name based on local or remote connection
    if ($connection == "local") {
        $t_admin = "admin";
    } else {
        $t_admin = "$database.admin";
    }

    try {
        $db = new PDO("mysql:host=$host", $user, $password, $options);
        //echo "Database connected successfully <BR>";

        $sql_select = "Select * from $t_admin where admin_username = '$uid' and admin_pwd = '$pwd'";

        $stmt = $db->prepare($sql_select);
        $stmt->execute();

        if ($rows = $stmt->fetch()) {
            $_SESSION['valid'] = TRUE;
            $_SESSION['uid'] = $uid;
            $_SESSION["pwd"] = $pwd;
            $_SESSION["isadmin"]=TRUE;
        } else {
            echo '<script>alert("Invalid Username or Password. Try again")</script>';
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

//Fetch latest 6 movies and related images for corousel display
include './database/config/config.php';
//set table name based on local or remote connection
if ($connection == "local") {
    $t_Movies = "movies";
} else {
    $t_Movies = "$database.movies";
}
try {
    $i = 1;
    $db = new PDO("mysql:host=$host", $user, $password, $options);

    foreach ($db->query("select movie_id,movie_title,movie_image_fn,movie_cast
                from $t_Movies order by movie_id desc limit 6") as $rs1) {

        $movieTable[$i]['movie_image_fn'] = $rs1['movie_image_fn'];
        $movieTable[$i]['movie_title'] = $rs1['movie_title'];
        $movieTable[$i]['movie_cast'] = $rs1['movie_cast'];

        $i++;
    }
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
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
            <a class="navbar-brand" href="#">iMovies</a>

            <!-- Rightside navbar Links: Set based on User signed-in or not-->
            <?php
            if (isset($_SESSION["uid"])) {

            ?>
            <!-- Set rightside navbar links if no user signed-in -->
            <ul class="navbar-nav navbar-right">
                <li class="dropdown text-info"><a class="dropdown-toggle" data-toggle="dropdown"><i
                            class="fa fa-user-secret"></i> Welcome <?php echo $uid; ?></a>
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
                                    <a class="nav-link">Basic</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="./addMovie.php">Add Movie</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="./addTheatre.php">Add Theatre / Seats</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link">Main</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="./addShow.php">Add Show / Inventory</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="./manageShow.php">Manage Shows</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link">Report</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="./ticketsShow.php">Tickets by Show</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="./collectionTheatre.php">Collection by
                                                    Theatre</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="./collectionMovie.php">Collection by Movie</a>
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

            <div class="container" style=" width:80% ">
                <h4 class="text-primary"> Trending movies </h4>

                <div id="myCarousel" class="carousel slide" data-ride="carousel">

                    <!-- Indicators -->
                    <ul class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                    </ul>

                    <!-- The slideshow -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row">
                                <?php for ($j = 1; $j <= 3; $j++) { ?>
                                <div class="col-sm-4">
                                    <img src="./database/images/<?php echo $movieTable[$j]['movie_image_fn']; ?>"
                                        alt="<?php echo $movieTable[$i]['movie_title']; ?>" style="width:100%;">
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <?php for ($j = 4; $j <= 6; $j++) { ?>
                                <div class="col-sm-4">
                                    <img src="./database/images/<?php echo $movieTable[$j]['movie_image_fn']; ?>"
                                        alt="<?php echo $movieTable[$i]['movie_title']; ?>" style="width:100%;">
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                        <!-- Left and right controls -->
                        <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next" href="#myCarousel" data-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>
                </div>

                <BR>
                <h4 class="text-primary"> iMovies - Online Reservation System Key features: </h4>
                <ol>Admin Users:
                    <li>
                        Admin Users can add new movies, add new theatres, define type of seats in those theatres,
                        schedule shows.
                    </li>
                    <li>
                        Use Add Movies to add any new movie with key informations like movie title, director, language
                        and etc.
                    </li>
                    <li>
                        Use Add Theatre to add new theatre and type of seats, number of seats in that. 
                        Currenlty theatres can be added only in 3 cities such as Vellore, Bangalore and Chennai
                    </li>
                    <li>
                        Use Add Show/Inventory option to schedule movie shows in the selected theatre, for specific 
                        dates and slots. If shows are already shcheduled, conflict details will be displayed
                    </li>
                    <li>
                        Use Manage Shows option to close scheduled shows in the selected theatre, for a specific date and slots
                    </li>
                    <li>
                        Use varioius report options for administrative and management purpose. eg., use Tickets by Show to 
                        view list of all customers for a particular show and total collection for that show.
                    </li>
                    
                    
                </ol>


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