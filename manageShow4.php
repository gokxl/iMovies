<?php

session_start();

if (isset($_SESSION["uid"])) {
    $uid = $_SESSION["uid"];
}
if (isset($_SESSION["isadmin"])) {
    $isadmin = TRUE;
}

//Read HTML FORM submitted values using POST Method
//echo "Entering managesshow final step <BR>";

$theatreID = $_POST['theatreID'];
$movieID = $_POST['movieID'];
$showTable = $_POST['closeShow'];

//echo "Theatre ID $theatreID <BR>";
//echo "movie ID $movieID <BR>";

$n = count($showTable);
for($i=0;$i<$n;$i++){
   // echo "Show ID: $showTable[$i] <BR>";
}

//echo $showCity . " - " . $showTheatre . " - " . $showStatus . " - " . $showStartDate . " - " . $showEndDate . "<BR>";

include './database/config/config.php';
if ($connection == "local"){
    $t_theatre = "theatre";
    $t_seats = "seats";
    $t_movies = "movies";
    $t_shows = "shows";
    $t_show_inventory = "show_inventory";
}else {
    $t_theatre = "$database.theatre";
    $t_seats = "$database.seats";
    $t_movies = "$database.movies";
    $t_shows = "$database.shows";
    $t_show_inventory = "$database.show_inventory";
}


try { 
    $db = new PDO("mysql:host=$host",$user,$password,$options);

    for($i=0;$i<$n;$i++){
        $updated=$db->query("update $t_shows set show_status='closed' where show_id=$showTable[$i]")->execute();
        if ($updated>0){
            // echo "Show ID: $showTable[$i]  closed successfully<BR>";
        } else {
           echo "Error: Show ID: $showTable[$i] not closed <BR>";
        }
    }
    
    for($i=0;$i<$n;$i++){
        $rs1 = $db->query("select show_id, show_date, show_slot, show_status from $t_shows 
            where show_id=$showTable[$i]")->fetch();
        //echo "Hello Fetched rs1 for" . $showTable[$i] .  " - " . $rs1['show_id'] . " - " . $i . "<BR>";

        $updatedTable[$i]['show_id']=$rs1['show_id'];
        $updatedTable[$i]['show_date']=$rs1['show_date'];
        $updatedTable[$i]['show_slot']=$rs1['show_slot'];
        $updatedTable[$i]['show_status']=$rs1['show_status'];
    }
   
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <title>Insert New Movie</title>
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

            <div class="container" style=" width:80% ">
            <h4>Following shows closed successfully...</h4>
                <form action="manageShow4.php" method="post" enctype="multipart/form-data">
                    <div class="row justify-content-center">
                        <div class="col sm-6">
                            <label class="font-weight-bold">Selected Theatre:</label>
                            <input type="text" class="form-control" id="theatreID" name="theatreID"
                                value=<?php echo
                            ($db->query("Select theatre_name from $t_theatre where theatre_id=$theatreID"))->fetch()['theatre_name'] ?> readonly></input>
                        </div>
                        <div class="col sm-6">
                            <label class="font-weight-bold">Selected Movie:</label>
                            <input type="text" class="form-control" id="movieID" name="movieID"
                                value=<?php echo
                            ($db->query("Select movie_title from $t_movies where movie_id=$movieID"))->fetch()['movie_title'] ?> readonly></input>
                        </div>
                    </div><BR>


                    <div class="container-fluid">
                        <table class="table table-striped table-bordered">
                            <thead style='text-align:center'>
                                <tr>
                                    <th class="col-sm-2">Show_ID</th>
                                    <th class="col-sm-2">Show_Date</th>
                                    <th class="col-sm-2">Show_Slot </th>
                                    <th class="col-sm-3">Show_Status </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php     
                                for($i=0; $i <= count($updatedTable); $i++){
                                    echo "<tr><td style='text-align:center'>". $updatedTable[$i]['show_id'] . "</td>";
                                    echo "<td>". $updatedTable[$i]['show_date'] . "</td>";
                                    echo "<td style='text-align:center'>" . $updatedTable[$i]['show_slot'] . "</td>";
                                    echo "<td style='text-align:center'>" . $updatedTable[$i]['show_status'] . "</td></tr>";
                                  
                                } ?>
                            </tbody>
                        </table>
                    </div><BR>
                    <div class="row justify-content-center ">
                        <input class="form-group bg-primary text-white" type="submit" name="manageStep4"
                            value="Update Shows" />
                    </div><BR><BR><BR>
                </form>
            </div>

            <!-- footer section goes here-->

            <div class="navbar fixed-bottom">
                <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
                    <h3> Developed using following technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap,
                        Javascript.</h3>
                </div>
            </div>

</body>

</html>