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

$theatreID = $_POST['theatreID'];
$tCity = $_POST['tCity'];
$tMovieID = $_POST['tMovie'];

//echo "Theatre ID $theatreID <BR>";
//echo "movie ID $tMovieID <BR>";

//echo $showCity . " - " . $showTheatre . " - " . $showStatus . " - " . $showStartDate . " - " . $showEndDate . "<BR>";

include './database/config/config.php';
if ($connection == "local"){
    $t_theatre = "theatre";
    $t_seats = "seats";
    $t_movies = "movies";
    $t_shows = "shows";
    $t_show_inventory = "show_inventory";
    $t_reservation = "reservation";
}else {
    $t_theatre = "$database.theatre";
    $t_seats = "$database.seats";
    $t_movies = "$database.movies";
    $t_shows = "$database.shows";
    $t_show_inventory = "$database.show_inventory";
    $t_reservation = "$database.reservation";
}

try { 
    $db = new PDO("mysql:host=$host",$user,$password,$options);

    $i=1;

    foreach($db->query("select show_id, show_date, show_slot, show_status from $t_shows 
        where show_theatre_id = $theatreID and show_movie_id=$tMovieID and show_status <> 'closed'") as $rs1){
        
        $showTable[$i]['show_id']=$rs1['show_id'];
        $tshowid = $rs1['show_id'];
        $showTable[$i]['show_date']=$rs1['show_date'];
        $tshowdate = $rs1['show_date'];
        $showTable[$i]['show_slot']=$rs1['show_slot'];
        $tshowslot = $rs1['show_slot'];
        $showTable[$i]['booked']=$db->query("select sum(reservation_seats_booked) as booked from $t_reservation a,
                                $t_shows b where a.reservation_show_id=$tshowid and a.reservation_show_id=b.show_id
                                and b.show_date='$tshowdate' and b.show_slot = '$tshowslot' 
                                group by reservation_show_id")->fetch()['booked'];
        //$showTable[$i]['amount']=$db->query("select sum(reservation_amount) as amt from $t_reservation 
          //                      where reservation_show_id=$tshowid and reservation_show_date=$tshowdate
            //                    and reservation_show_slot=$tshowslot group by reservation_show_id")->fetch()['amt'];
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

    <title>iMovies - Online Movie Reservation System</title>
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

            <div class="container" style=" width:80% ">
                <form action="manageShow4.php" method="post" enctype="multipart/form-data">
                <input type="hidden" class="form-control" id="theatreID" name="theatreID" value=<?php echo $theatreID; ?> />
                <input type="hidden" class="form-control" id="movieID" name="movieID" value=<?php echo $tMovieID; ?> />
                    <div class="row justify-content-center">
                        <div class="col sm-6">
                            <label class="font-weight-bold">Selected Theatre:</label>
                            <input type="text" readonly class="form-control" id="tName" name="tName"
                                value=<?php echo "'" . 
                            ($db->query("Select theatre_name from $t_theatre where theatre_id=$theatreID"))->fetch()['theatre_name'] . "'" ?> />
                        </div>
                        <div class="col sm-6">
                            <label class="font-weight-bold">Selected Movie:</label>
                            <input type="text" readonly class="form-control" id="movieName" name="movieName"
                                value=<?php echo "'" .
                            ($db->query("Select movie_title from $t_movies where movie_id=$tMovieID"))->fetch()['movie_title'] . "'" ?> />
                        </div>
                    </div><BR>


                    <div class="container-fluid">
                        <table class="table table-striped table-bordered">
                            <thead style='text-align:center'>
                                <tr>
                                    <th class="col-sm-2">Show_ID</th>
                                    <th class="col-sm-2">Show_Date</th>
                                    <th class="col-sm-2">Show_Slot </th>
                                    <th class="col-sm-3">Tickets Booked </th>
                                    <th class="col-sm-3">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php     
                                for($i=1; $i <= count($showTable); $i++){
                                    echo "<tr><td style='text-align:center'><a href=ticketsFull.php?show_id=". 
                                        $showTable[$i]['show_id'] . ">" .  $showTable[$i]['show_id'] . "</td>";
                                    echo "<td>". $showTable[$i]['show_date'] . "</td>";
                                    echo "<td style='text-align:center'>" . $showTable[$i]['show_slot'] . "</td>";
                                    echo "<td style='text-align:center'>" . $showTable[$i]['booked'] . "</td>";
                                    echo "<td style='text-align:center'>" . $showTable[$i]['amount'] . "</tr></td>";
                                }
                            ?>
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

            <div class="navbar">
                <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
                    <h3> Developed using technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap,
                        Javascript.</h3>
                </div>
            </div>

</body>

</html>