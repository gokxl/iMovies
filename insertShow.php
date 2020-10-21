<?php

session_start();

if (isset($_SESSION["uid"])) {
    $uid = $_SESSION["uid"];
}
if (isset($_SESSION["isadmin"])) {
    $isadmin = TRUE;
}

//Read HTML FORM submitted values using POST Method
//echo "Entering insertShow <BR>";

$showCity = $_POST['citiesSelect'];
$showTheatre = $_POST['theatresSelect'];
$showMovie = $_POST['tMovie'];
$showStatus = $_POST['tStatus'];
$showStartDate = $_POST['tStartdate'];
$showEndDate = $_POST['tEnddate'];
$showSlots = $_POST['tSlots'];  //Array of values

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

// Prepare start and end date of show for scheduling
$begin = new DateTime($showStartDate);
$end = new DateTime($showEndDate);
$end = $end->modify( '+1 day' ); //1 day added for daterange to cover end date
$interval = new DateInterval('P1D');
$daterange = new DatePeriod($begin, $interval, $end);

//test date range
/* foreach($daterange as $date) {
    echo $date->format('Y-m-d')."<br />";
} */

$show_conflict=FALSE;

try { 
    $db = new PDO("mysql:host=$host",$user,$password,$options);
    //echo "Database connected successfully <BR>";

    //Check for free slots. Combination of theatre, date and slot can't be duplicate
    $n = count($showSlots);
    //echo "Conflicting show schedules <BR>";
    $cr = 1; //Increment to store conflict rows
    foreach($daterange as $date) {
        $checkDate = $date->format('Y-m-d');
        //echo "checking for date: " . $checkDate . "<BR>";

        for($i=0; $i < $n; $i++){
            //echo("Checking for slot: " . $showSlots[$i] . " <BR>");     

            foreach($db->query("SELECT b.theatre_name, b.theatre_location, a.show_date, c.movie_title, a.show_slot 
                from $t_shows as a, $t_theatre as b, $t_movies as c where a.show_theatre_id = b.theatre_id 
                and a.show_movie_id = c.movie_id and a.show_theatre_id = $showTheatre and a.show_date = '$checkDate' and
                a.show_slot = '$showSlots[$i]' and a.show_status <> 'closed' ") as $rs1){
                
                $show_conflict = TRUE;
                $conflictShow[$cr]['theatre_name'] = $rs1['theatre_name'];
                $conflictShow[$cr]['theatre_location'] = $rs1['theatre_location'];
                $conflictShow[$cr]['show_date'] = $rs1['show_date'];
                $conflictShow[$cr]['movie_title'] = $rs1['movie_title'];
                $conflictShow[$cr]['show_slot'] = $rs1['show_slot'];
                $cr++;
                //echo $rs1['theatre_name'] . $rs1['theatre_location'] . $rs1['show_date'] . $rs1['movie_title'] . $rs1['show_slot'] . "<BR>";
            }
        }
    }

    $conflictRows = count($conflictShow);   //Number of conflicting shows 

    

    //Insert show schedules if there are no conflicts
    if(!$show_conflict){
        //echo "Insert into show and show inventory <BR>";
        $j=1;
        //select seat types and # of seats from theatre_seats
        foreach($db->query("SELECT seat_type, seats_no_of_seats from $t_seats
            where seat_theatre_id=$showTheatre") as $rs2) {
                $seatTypes[$j] = $rs2['seat_type'];
                $no_of_seats[$j] = $rs2['seats_no_of_seats'];
                //echo $seatTypes[$j] . " - " . $no_of_seats[$j] . "<BR>";
                $j++;
            } 
        $seatTypesCount = count($seatTypes);   
        //echo "number of seat types in theatre $t_theatre_id is $seatTypesCount <BR>";

        foreach($daterange as $date) {
            for($i=0; $i<$n; $i++){
                $sdate = $date->format('Y-m-d');
                //echo "Hi " . $sdate . $showSlots[$i] . "<BR>";
                $sql_insert1 = "INSERT INTO $t_shows (show_theatre_id, show_date, show_movie_id,show_slot,show_status) 
                VALUES ($showTheatre,'$sdate',$showMovie,'$showSlots[$i]','$showStatus')";
                //echo "SQL Insert1: $sql_insert1 <BR>";

                $stmt1 = $db->prepare($sql_insert1);
                $rows1 = $stmt1->execute(array());
               
                If ($rows1>0){   
                    //echo "Shows added successfully.. <BR>";
                    $sql_show_id ="SELECT max(show_id) as max_id from $t_shows";
                    //echo "Select statement is $sql_show_id <BR>";
                    $stmt = $db->query($sql_show_id);
                    $rows = $stmt->fetch();
                    $t_show_id = $rows['max_id'];
        
                    //echo "show ID is $t_show_id <BR>";
                    //insert all types of seats into Seats table for the given theatre
                    for($j=1; $j<=$seatTypesCount; $j++){ 
                        $sType = $seatTypes[$j];
                        $sNumbers = $no_of_seats[$j];
                       
                        $sql_insert2 = "INSERT INTO $t_show_inventory (inventory_show_id, inventory_seat_type, inventory_seats_available) 
                            VALUES ($t_show_id,'$sType',$sNumbers)";
                        //echo "SQL Statement $sql_insert2 <BR>";
                        $stmt2 = $db->prepare($sql_insert2);
                        $rows2 = $stmt2->execute(array());
                        if (rows2 > 0 ){
                            echo "Inventory updated successfully for $t_show_id with seat type $sType <BR>";
                        }
                    } 
                }

            }
        }
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
                                                <a class="nav-link" href="./collectionTheatre.php">Collection by Theatre</a>
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

            <div class="container">
                <?php if(!$show_conflict) { ?>
                <h4>Success: Following movie shows scheduled successfully.... </h4>
                <?php } else { ?>
                <h4>Conflicts: Following conflicts should be avoided..... </h4>
                <?php } ?>

                <div class="container-fluid">
                    <?php  if($show_conflict){  ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="col-sm-3">Theatre</th>
                                <th class="col-sm-2">City</th>
                                <th class="col-sm-3">Movie </th>
                                <th class="col-sm-1">Date </th>
                                <th class="col-sm-2">Slot </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                
                                for($i=1; $i <= $conflictRows; $i++){
                                    echo "<tr><td>". $conflictShow[$i]['theatre_name'] . "</td>";
                                    echo "<td>" . $conflictShow[$i]['theatre_location'] . "</td>";
                                    echo "<td>" . $conflictShow[$i]['movie_title'] . "</td>";
                                    echo "<td>" . $conflictShow[$i]['show_date'] . "</td>";
                                    echo "<td>" . $conflictShow[$i]['show_slot'] . "</td></tr>";
                                }
                            }  else { 
                                    echo "<table class='table table-striped table-bordered'>";
                                    echo "<tr><th>Movie Title</th>";
                                    echo "<td>" . ($db->query("Select movie_title from $t_movies where movie_id=$showMovie"))->fetch()['movie_title'] . "</td></tr>";
                                    echo "<tr><th>Theatre</th>";
                                    echo "<td>" . ($db->query("Select theatre_name from $t_theatre where theatre_id=$showTheatre"))->fetch()['theatre_name'] . "</td></tr>";
                                    echo "<tr><th>City</th>";
                                    echo "<td>" . $showCity . "</td></tr>";
                                    echo "<tr><th>Start Date</th>";
                                    echo "<td>" . $showStartDate . "</td></tr>";
                                    echo "<tr><th>End Date</th>";
                                    echo "<td>" . $showEndDate . "</td></tr>";
                                    echo "<tr><th>Show Slots</th>";
                                    echo "<td>";
                                    for($i=0; $i < $n; $i++){
                                        echo $showSlots[$i] . " ";
                                    }
                                    echo "</td></tr>";
                                    
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div> <BR>

            <!-- footer section goes here-->

            <div class="navbar fixed-bottom">
                <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
                    <h3> Developed using following technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap,
                        Javascript.</h3>
                </div>
            </div>

</body>

</html>