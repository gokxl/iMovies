<?php

session_start();

if (isset($_SESSION["uid"])) {
    //echo "UID is set <BR>";
    $uid = $_SESSION["uid"];
} else {
    header('Refresh:1   ; url=./login.php');
    echo 'Please Log In First';
    exit();
}

$movie_id = $_POST['movieID'];
echo " the selected movie id is $movie_id<br>";
$movieName = $_POST['movieName'];
echo "Movie Name is $movieName <BR>";

$theatre = $_POST['theatresSelect'];
echo " the selected theatre is $theatre <br>";

$city = $_POST['citiesSelect'];
echo " the selected city is $city <br>";

include './database/config/config.php';
if ($connection == "local") {
    $t_theatre = "theatre";
    $t_seats = "seats";
    $t_movies = "movies";
    $t_shows = "shows";
} else {
    $t_theatre = "$database.theatre";
    $t_seats = "$database.seats";
    $t_movies = "$database.movies";
    $t_shows = "$database.shows";
}

try {
    $db = new PDO("mysql:host=$host", $user, $password, $options);
    //echo "Database connected successfully <BR>";

    //Code to populate selection of City, followed by selection of theatre, using JSON/Javascript
    foreach ($db->query("SELECT DISTINCT show_date from $t_shows where show_movie_id = $movie_id and show_theatre_id = $theatre") as $rs1) {
        $cities[] = array("tdate" => $rs1['show_date']);
    }
    foreach ($db->query("SELECT DISTINCT show_date,show_slot from $t_shows where show_movie_id = $movie_id and show_theatre_id = $theatre") as $rs2) {
        $theatres[$rs2['show_date']][] = array("tslot" => $rs2['show_slot']);
    }
    $jsonCities = json_encode($cities);
    $jsonTheatres = json_encode($theatres);

    //echo $jsonCities . "<BR>";
    //echo $jsonTheatres . "<BR>";

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br />";
    die();
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

    <!-- script to populate theatre names based on selected City dynamically -->
    <script type='text/javascript'>
        <?php
        // make json variables available
        echo "var cities = $jsonCities; \n";
        echo "var theatres = $jsonTheatres; \n";
        ?>

        function loadCities() {
            var select = document.getElementById("citiesSelect");
            select.onchange = updateTheatres;
            for (var i = 0; i < cities.length; i++) {
                select.options[i] = new Option(cities[i].tdate);
            }
        }

        function updateTheatres() {
            var citySelect = this;
            var tcity = this.value;
            var theatresSelect = document.getElementById("theatresSelect");
            theatresSelect.options.length = 0; //delete all options if any present
            for (var i = 0; i < theatres[tcity].length; i++) {
                theatresSelect.options[i] = new Option(theatres[tcity][i].tslot);
            }
        }
    </script>
    <!-- End of script to populate theatre names based on selected City dynamically  -->
</head>

<body onload='loadCities()'>

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
                    <li class="dropdown text-info"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user-secret"></i> Welcome <?php echo $uid; ?></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"> <i class="fa fa-user-plus"></i> My Profile</a></li>
                            <li><a href="#"> <i class="fa fa-briefcase"></i> My Bookings</a></li>
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
                                    <a class="nav-link" href="bookNow.php">Book Now</a>
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
            <!-- Add Show Form starts here -->

            <div class="container" style=" width:80% ">
                <form action="bookShow3.php" method="post" enctype="multipart/form-data">
                    <div class="row justify-content-center">
                        <div class="col sm-6">
                            <label class="font-weight-bold">Selected Location:</label>
                            <input type="text" class="form-control" id="tCity" name="tCity" value=<?php echo '"' .
                                ($db->query("Select theatre_location from $t_theatre where theatre_id=$theatre"))->fetch()['theatre_location'] . '"' ?> readonly />
                        </div>
                        <div class="col sm-6">
                            <label class="font-weight-bold">Selected Theatre:</label>
                            <input type="text" class="form-control" id="tname" name="tname" value=<?php echo '"' .
                                ($db->query("Select theatre_name from $t_theatre where theatre_id=$theatre"))->fetch()['theatre_name'] . '"' ?> readonly />
                        </div>
                    </div><BR>
                    <div class="row justify-content-center">
                        <div class="col sm-6">
                            <label class="font-weight-bold">Selected Movie:</label>
                            <input type="text" class="form-control" id="movieName" name="movieName" value=<?php echo '"' .
                                ($db->query("Select movie_title from $t_movies where movie_id=$movie_id"))->fetch()['movie_title'] . '"' ?> readonly />

                        </div>
                    </div><BR>

                    <div class="row justify-content-center">
                        <div class="col sm-6">
                            <label class="font-weight-bold" for="citiesSelect">Select Date:</label>
                            <select class="form-control" id="citiesSelect" name="citiesSelect">
                            </select>
                        </div>
                        <div class="col sm-6">
                            <label class="font-weight-bold" for="theatresSelect">Select Slot:</label>
                            <select class="form-control" id="theatresSelect" name="theatresSelect">
                            </select>
                        </div>
                    </div><BR>
                    <input type="hidden" class="form-control" id="movieID" name="movieID" value=<?php echo $movie_id; ?> />
                    <input type="hidden" class="form-control" id="tID" name="tID" value=<?php echo $theatre; ?> />
                    <div class="row justify-content-center ">
                        <input class="form-group bg-primary text-white" type="submit" name="bookShow2.php" value="Click to proceed" />
                    </div>
                </form>



            </div>
        </div>
        <!-- footer section goes here-->

        <div class="navbar fixed-bottom">
            <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
                <h3> Developed using following technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap, Javascript.
                </h3>
            </div>
        </div>
    </div>
</body>

</html>