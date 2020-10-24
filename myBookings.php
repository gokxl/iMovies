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

    //Fetch customer details and total bookings done by the customer
    $cust_id = $db->query("Select cust_id from $t_customer where cust_username = '$uid'")->fetch()['cust_id'];
    $cbookings = $db->query("Select count(*) as tcount from $t_reservation where reservation_cust_id=$cust_id")->fetch()['tcount'];

    if($cbookings>0){
        $i=1;
        foreach($db->query("select a.reservation_id, c.movie_title, d.theatre_name, d.theatre_location, 
                b.show_date, b.show_slot, a.reservation_seat_type, 
                a.reservation_seats_booked, a.reservation_amount 
                from $t_reservation a, $t_shows b, $t_movies c, $t_theatre d
                where a.reservation_cust_id = $cust_id and a.reservation_show_id = b.show_id and 
                b.show_movie_id=c.movie_id and b.show_theatre_id = d.theatre_id") as $rs1){
                    $myBookings[$i]['resid']    = $rs1['reservation_id'];
                    $myBookings[$i]['title']    = $rs1['movie_title'];
                    $myBookings[$i]['tname']    = $rs1['theatre_name'];
                    $myBookings[$i]['tcity']    = $rs1['theatre_location'];
                    $myBookings[$i]['date']     = $rs1['show_date'];
                    $myBookings[$i]['slot']     = $rs1['show_slot'];
                    $myBookings[$i]['seat']     = $rs1['reservation_seat_type'];
                    $myBookings[$i]['qty']      = $rs1['reservation_seats_booked'];
                    $myBookings[$i]['amt']      = $rs1['reservation_amount'];
                    $i++;
            }
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
            <!-- Add Show Form starts here -->

            <div class="container" style=" width:80% ">
                <label class="font-weight-bold">My Booking History:</label>
                <div class="row justify-content-center">
                    <div class="container">
                        <table id="myTable" class=" table order-list table-bordered table-sm">
                            <thead class="bg-primary">
                                <tr>
                                    <th>Res.ID</th>
                                    <th>Movie Title</th>
                                    <th>Theatre</th>
                                    <th>Show Date</th>
                                    <th>Show Slot</th>
                                    <th>Seat Type</th>
                                    <th># of Seats</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for($i=1; $i<=$cbookings; $i++){ 
                                        echo "<tr><td><a href=./myTicket.php?p_resid=" . $myBookings[$i]['resid'] . ">" . $myBookings[$i]['resid'] . "</td>";
                                        echo "<td>" . $myBookings[$i]['title'] . "</td>";
                                        echo "<td>" . $myBookings[$i]['tname'] . "(" . $myBookings[$i]['tcity'] . ")</td>";
                                        echo "<td>" . $myBookings[$i]['date'] . "</td>";
                                        echo "<td>" . $myBookings[$i]['slot'] . "</td>";
                                        echo "<td>" . $myBookings[$i]['seat']  . "</td>";
                                        echo "<td>" . $myBookings[$i]['qty'] . "</td>";
                                        echo "<td>" . $myBookings[$i]['amt'] . "</td></tr>";
                    
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