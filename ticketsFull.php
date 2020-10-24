<?php

session_start();

if (isset($_SESSION["uid"])) {
    $uid = $_SESSION["uid"];
}
if (isset($_SESSION["isadmin"])) {
    $isadmin = TRUE;
}


$showid = $_GET['show_id'];

include './database/config/config.php';
if ($connection == "local") {
    $t_customer = "customer";
    $t_theatre = "theatre";
    $t_seats = "seats";
    $t_movies = "movies";
    $t_shows = "shows";
    $t_show_inventory = "show_inventory";
    $t_reservation = "reservation";
    $t_ticket = "ticket";
} else {
    $t_customer = "$database.customer";
    $t_theatre = "$database.theatre";
    $t_seats = "$database.seats";
    $t_movies = "$database.movies";
    $t_shows = "$database.shows";
    $t_show_inventory = "$database.show_inventory";
    $t_reservation = "$database.reservation";
    $t_ticket = "$database.ticket";
}

try {
    $db = new PDO("mysql:host=$host", $user, $password, $options);
    //echo "Database connected successfully <BR>";

    //$cust_id = $db->query("Select cust_id from $t_customer where cust_username = '$uid'")->fetch()['cust_id'];
    $rs1 = $db->query("select distinct b.show_id, c.movie_title, d.theatre_name, d.theatre_location, 
            b.show_date, b.show_slot
            from $t_reservation a, $t_shows b, $t_movies c, $t_theatre d
            where b.show_id = $showid and a.reservation_show_id = b.show_id and 
            b.show_movie_id=c.movie_id and b.show_theatre_id = d.theatre_id")->fetch();

    $myBookings['showid']    = $rs1['show_id'];
    //echo "show id after fetch" .  $myBookings['showid']  . "<BR>";
    $myBookings['title']    = $rs1['movie_title'];
    $myBookings['tname']    = $rs1['theatre_name'];
    $myBookings['tcity']    = $rs1['theatre_location'];
    $myBookings['date']     = $rs1['show_date'];
    $myBookings['slot']     = $rs1['show_slot'];
    
    //$tcount = $db->query("Select count(*) as tcount from $t_ticket where ticket_reservation_id=$resid")->fetch()['tcount'];

    $i=1;
    $showAmount=0;
    foreach($db->query("Select ticket_id, ticket_name, ticket_age, ticket_gender , b.reservation_seat_type, 
                b.reservation_amount / b.reservation_seats_booked as ticket_cost
                from $t_ticket a, $t_reservation b, $t_shows c where c.show_id=$showid and
                b.reservation_show_id=c.show_id and a.ticket_reservation_id = b.reservation_id") as $rs2){

        $myTicket[$i]['tid']    = $rs2['ticket_id'];
        $myTicket[$i]['tname']    = $rs2['ticket_name'];
        $myTicket[$i]['ttype']    = $rs2['reservation_seat_type'];
        $myTicket[$i]['tcost']    = round($rs2['ticket_cost'],0);
        $myTicket[$i]['tage']    = $rs2['ticket_age'];
        $myTicket[$i]['tgender']    = $rs2['ticket_gender'];

        //echo  $myTicket[$i]['tname']  . "<BR>";
        $showAmount = $showAmount + $myTicket[$i]['tcost'] ;
        $i++;

    }
    
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
            <!-- Add Show Form starts here -->

            <div class="container" style=" width:80% ">
                <label class="font-weight-bold">My Reservation Details:</label>
                <div class="row justify-content-center">
                    <div class="container">
                        <table id="myTable" class=" table order-list table-bordered table-sm">
                            <thead class="bg-primary">
                                <tr>
                                    <th>Show ID</th>
                                    <th>Movie Title</th>
                                    <th>Theatre</th>
                                    <th>Show Date</th>
                                    <th>Show Slot</th>
                                    <th># of Tickets</th>
                                    <th>Show Amount</th>
                                
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                  
                                        echo "<tr><td>". $myBookings['showid'] . "</td>";
                                        echo "<td>" . $myBookings['title'] . "</td>";
                                        echo "<td>" . $myBookings['tname'] . "(" . $myBookings['tcity'] . ")</td>";
                                        echo "<td>" . $myBookings['date'] . "</td>";
                                        echo "<td>" . $myBookings['slot'] . "</td>";
                                        echo "<td>" . count($myTicket)  . "</td>";
                                        echo "<td>" . $showAmount . "</td></tr>";
                                 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <label class="font-weight-bold">My Ticket Details for this reservation:</label>
                <div class="row justify-content-center">
                    <div class="container">
                        <table id="myTable" class=" table order-list table-bordered table-sm">
                            <thead class="bg-primary">
                                <tr>
                                    <th>TicketID</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Seat Type</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for($i=1; $i<=count($myTicket); $i++){ 
                                        echo "<tr><td>" . $myTicket[$i]['tid'] . "</td>";
                                        echo "<td>" . $myTicket[$i]['tname'] . "</td>";
                                        echo "<td>" . $myTicket[$i]['tage'] . "</td>";
                                        echo "<td>" . $myTicket[$i]['tgender']  . "</td>";
                                        echo "<td>" . $myTicket[$i]['ttype']  . "</td>";
                                        echo "<td>" . $myTicket[$i]['tcost'] . "</td></tr>";
                    
                                    }   
                                ?>
                            </tbody>
                            
                        </table>
                    </div>
                </div>
                <BR>
            </div>
        </div>


        <!-- footer section goes here-->

        <div class="navbar">
            <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
                <h3> Developed using technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap, Javascript.
                </h3>
            </div>
        </div>
    </div>
</body>

</html>