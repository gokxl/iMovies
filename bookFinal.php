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
$movieName = $_POST['movieName'];
$theatre_id = $_POST['tID'];
$tdate = $_POST['showDate'];
$tslot = $_POST['showSlot'];
$ttype = $_POST['seatType'];
$tshow_id = $_POST['showID'];
$tcount = $_POST['customerCount'];
$tamount = $_POST['amount_paid'];
$tpaymode = $_POST['payment_mode'];
//echo "Payment Mode: $tpaymode <BR>";

$customers = array(array());
for($i=1; $i<=$tcount; $i++){ 
        $customers[$i]['cName']=$_POST['cName'.$i];
        $customers[$i]['cGender']=$_POST['cGender'.$i];
        $customers[$i]['cAge']=$_POST['cAge'.$i];
        $customers[$i]['ctype']=$ttype;
        $customers[$i]['cCost']=$tamount/$tcount;
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

    $cust_id = $db->query("Select cust_id from $t_customer where cust_username = '$uid'")->fetch()['cust_id'];
    $seats_available = $db->query("SELECT inventory_seats_available from $t_show_inventory where inventory_show_id = $tshow_id
                                        and inventory_seat_type='$ttype'")->fetch()['inventory_seats_available'];

    $sql_insert1 = "INSERT INTO $t_reservation(reservation_show_id, reservation_cust_id, reservation_seat_type, 
        reservation_seats_booked, reservation_amount, reservation_payment_status, reservation_payment_mode) 
        VALUES ($tshow_id, $cust_id, '$ttype', $tcount, $tamount, 'Payment success', '$tpaymode')";
   
    //echo "SQL Statement $sql_insert1";
    $stmt1 = $db->prepare($sql_insert1);
    $rows1 = $stmt1->execute(array());
    //echo "Rows  $rows1 <BR>";

    If ($rows1>0){   
        //echo "Reservation table row added successfully.. <BR>";
        $sql_reservation_id =$db->query("SELECT max(reservation_id) as max_id from $t_reservation")->fetch()['max_id'];

        //insert and create tickets for each customer 
        for($i=1; $i<=$tcount; $i++){ 
            $cName =  $customers[$i]['cName'];
            $cGender =  $customers[$i]['cGender'];
            $cAge =  $customers[$i]['cAge'];
            $sql_insert2 = "INSERT INTO $t_ticket(ticket_reservation_id, ticket_name, ticket_age, ticket_gender) 
                VALUES ($sql_reservation_id, '$cName',$cAge,'$cGender')";
            //echo "SQL Statement $sql_insert2 <BR>";
            $stmt2 = $db->prepare($sql_insert2);
            $rows2 = $stmt2->execute(array());
        } 

        //Fetch ticket IDs for the reservation based on reservation id
        $j=1;
        foreach($db->query("Select ticket_id from $t_ticket where ticket_reservation_id = $sql_reservation_id") as $rs1){
            $customers[$j]['ticket_id']=$rs1['ticket_id'];
            $j++;
        }

        //Update show inventory, deduct number of seats booked now from existing inventory
        $inventory_updated = $db->query("Update $t_show_inventory set inventory_seats_available = $seats_available-$tcount
                                    where inventory_show_id = $tshow_id and inventory_seat_type='$ttype'")->execute();
        if ($inventory_updated>0){
            //echo " Show Inventory updated successfully";
            $seats_available = $db->query("SELECT inventory_seats_available from $t_show_inventory where inventory_show_id = $tshow_id
            and inventory_seat_type='$ttype'")->fetch()['inventory_seats_available'];
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
                <h4 class="text-success"> Congratulations!... your booking completed successfully. </h4>
                    <div class="row justify-content-center">
                        <div class="col sm-3">
                            <label class="font-weight-bold">Booking Reference:</label>
                            <input type="text" class="form-control" name="tresID" value=<?php echo $sql_reservation_id; ?> readonly />
                        </div>  
                        <div class="col sm-3">
                            <label class="font-weight-bold">City:</label>
                            <input type="text" class="form-control" id="tCity" name="tCity"
                                value=<?php echo '"' .
                                ($db->query("Select theatre_location from $t_theatre where theatre_id=$theatre_id"))->fetch()['theatre_location'] . '"' ?> readonly />
                        </div>
                        <div class="col sm-3">
                            <label class="font-weight-bold">Theatre:</label>
                            <input type="text" class="form-control" id="tname" name="tname"
                                value=<?php echo '"' .
                                ($db->query("Select theatre_name from $t_theatre where theatre_id=$theatre_id"))->fetch()['theatre_name'] . '"' ?> readonly />
                        </div>
                        <div class="col sm-3">
                            <label class="font-weight-bold">Movie:</label>
                            <input type="text" class="form-control" id="movieName" name="movieName"
                                value=<?php echo '"' .
                                ($db->query("Select movie_title from $t_movies where movie_id=$movie_id"))->fetch()['movie_title'] . '"' ?> readonly />
                        </div>
                    </div><BR>
                    <div class="row justify-content-center">
                        <div class="col sm-3">
                            <label class="font-weight-bold">Date:</label>
                            <input type="text" class="form-control" id="showDate" name="showDaate"
                                value=<?php echo $tdate; ?> readonly />
                        </div>
                        <div class="col sm-3">
                            <label class="font-weight-bold">Slot:</label>
                            <input type="text" class="form-control" id="showSlot" name="showSlot"
                                value=<?php echo $tslot; ?> readonly />
                        </div>
                        <div class="col sm-3">
                            <label class="font-weight-bold" for="payMode">Payment Mode:</label>
                            <input type="text" class="form-control" id="payMode" name="payMode"
                                value=<?php echo "$tpaymode"; ?> readonly />
                        </div>
                        <div class="col sm-3">
                            <label class="font-weight-bold" for="amountPaid">Amount Paid:</label>
                            <input type="text" class="form-control" id="amountPaid" name="amountPaid"
                                value=<?php echo $tamount; ?> readonly />
                        </div>
                    </div><BR>
                    <table class="table table-bordered table-sm small bg-primary text-white">
                        <tbody>
                            <tr><td><b>Current seats availability</b></td>
                            <?php
                                    echo "<td><b>Seat Type: </b>". $ttype . "; ";
                                    echo " <b>No. of Seats: </b>" . $seats_available . "</td>";
                            ?>
                            </tr>
                        </tbody>
                    </table>
                    <label class="font-weight-bold">Movie Tickets:</label>
                    <div class="row justify-content-center">
                        <div class="container">
                            <table id="myTable" class=" table order-list table-bordered table-sm">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>Customer Name</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Seat Type</th>
                                        <th>Ticket Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for($i=1; $i<=$tcount; $i++){ 
                                        echo "<tr><td>" . $customers[$i]['ticket_id'] . "</td>";
                                        echo "<td>" . $customers[$i]['cName'] . "</td>";
                                        echo "<td>" . $customers[$i]['cGender'] . "</td>";
                                        echo "<td>" . $customers[$i]['cAge'] . "</td>";
                                        echo "<td>" . $customers[$i]['ctype'] . "</td>";
                                        echo "<td>" . $customers[$i]['cCost'] . "</td></tr>";
                    
                                    }   
                                    ?>
                                    <tr><td><b>Total Amount Paid:</b></td><td></td><td></td><td></td><td></td><td><b> <?php echo $tamount; ?> </b> </td></tr>
                                </tbody>
                            </table>
                        </div>
                
                    </div>
                    <h4 class="text-success"> Check your email/SMS. Tickets will be delivered through registered email & mobile number. </h4>
                    <BR><BR>
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