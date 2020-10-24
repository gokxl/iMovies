<?php

session_start();

if (isset($_SESSION["uid"])) {
    $uid = $_SESSION["uid"];
}

$theatre_id = $_POST['theatresSelect'];
$tcity = $_POST['citiesSelect'];
echo "Theatre ID is $theatre_id<br>";

include './database/config/config.php';
//set table name based on local or remote connection
if ($connection == "local") {
    $t_customer = "customer";
    $t_Movies = "movies";
    $t_shows = "shows";
    $t_theatre = "theatre";
} else {
    $t_customer = "$database.customer";
    $t_Movies = "$database.movies";
    $t_shows = "$database.shows";
    $t_theatre = "$database.theatre";
}


try {
    $db = new PDO("mysql:host=$host", $user, $password, $options);

    $i = 1;
    foreach ($db->query("select movie_id, movie_title,movie_language,movie_cast,movie_description,movie_image_fn 
            from $t_Movies where movie_id in(select distinct show_movie_id from $t_shows where show_theatre_id=$theatre_id and 
            show_status in('upcoming','running'))") as $rs1) {
        //echo 'movie title is: ' . $rs1['movie_title'] . "<BR>";

        $movieTable[$i]['movie_id'] = $rs1['movie_id']; 
        $movieTable[$i]['movie_title'] = $rs1['movie_title'];
        $movieTable[$i]['movie_language'] = $rs1['movie_language'];
        $movieTable[$i]['movie_cast'] = $rs1['movie_cast'];
        $movieTable[$i]['movie_description'] = $rs1['movie_description'];
        $movieTable[$i]['movie_image_fn'] = $rs1['movie_image_fn'];

        //echo "the select movie names are" .  $movieTable[$i]['movie_title'] . " - " .      $movieTable[$i]['movie_cast'] . " <br>";
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
            <!-- content add -->
            <div class="container" style=" width:80% ">
                <div class="row justify-content-center">
                    <div class="col sm-6">
                        <label class="font-weight-bold">Selected Location:</label>
                        <input type="text" class="form-control" id="tCity" name="tCity" value=<?php echo $tcity; ?>
                            readonly />
                    </div>
                    <div class="col sm-6">
                        <label class="font-weight-bold">Selected Theatre:</label>
                        <input type="text" class="form-control" id="tname" name="tname"
                            value=<?php echo '"' . ($db->query("Select theatre_name from $t_theatre where theatre_id=$theatre_id"))->fetch()['theatre_name'] . '"' ?>
                            readonly />
                    </div>
                </div><BR>


                <?php
                $number_of_cards = 0;         //Track to display 4 cards per row
                $row_count = $db->query("SELECT count(*) from $t_Movies")->fetchColumn();
                $number_of_rows = 0;
                $number_of_movies = 0;

                for ($i = 1; $i <= count($movieTable); $i++) {

                    if ($number_of_cards == 0) {     //3 cards per row  
                ?>
                <div class="card-deck">
                    <?php }
                    if (($number_of_rows % 2) == 0) {
                        if (($number_of_cards % 2) == 0) {
                            $bg = "bg-primary";
                            $btn = "btn-warning";
                        } else {
                            $bg = "bg-warning";
                            $btn = "btn-primary";
                        }
                    } else {
                        if (($number_of_cards % 2) == 1) {
                            $bg = "bg-primary";
                            $btn = "btn-warning";
                        } else {
                            $bg = "bg-warning";
                            $btn = "btn-primary";
                        }
                    } ?>

                    <div class="card <?php echo $bg; ?>" style="max-width:18rem">
                        <img class="card-img-top rounded-circle"
                            src="./database/images/<?php echo $movieTable[$i]['movie_image_fn']; ?>" alt="Card image"
                            style="width:100%">
                        <div class="card-body">
                            <h4 class="card-title">
                                <?php echo $movieTable[$i]['movie_title'], "(", $movieTable[$i]['movie_language'], ")"; ?>
                            </h4>
                            <p class="card-text"> <?php echo $movieTable[$i]['movie_cast']; ?> </p>
                            <p class="card-text small"> <?php echo $movieTable[$i]['movie_description']; ?> </p>

                        </div>
                        <div class="card-footer">
                            <a href="./bookShowA.php?p_movie_id=<?php echo $movieTable[$i]['movie_id']."&p_theatre_id=".$theatre_id; ?>"
                                class="btn <?php echo $btn; ?>">Book Show</a>
                        </div>
                    </div>

                    <?php
                        $number_of_cards++;
                        $number_of_movies++;
                        if (($number_of_cards == 4) or ($number_of_movies == $row_count)) {  ?>
                </div> <BR>
                <?php
                            $number_of_cards = 0;
                            $number_of_rows++;
                        }
                    }
                ?>

            </div>
        </div>
    </div>
    <BR><BR>
    <!-- footer section goes here-->

        <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
            <h3> Developed using technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap, Javascript.</h3>
        </div>

</body>

</html>