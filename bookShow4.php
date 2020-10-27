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

$customers = array(array());
for($i=1; $i<=$tcount; $i++){ 
        $customers[$i]['cName']=$_POST['cName'.$i];
        $customers[$i]['cGender']=$_POST['cGender'.$i];
        $customers[$i]['cAge']=$_POST['cAge'.$i];
}

include './database/config/config.php';
if ($connection == "local") {
    $t_theatre = "theatre";
    $t_seats = "seats";
    $t_movies = "movies";
    $t_shows = "shows";
    $t_show_inventory = "show_inventory";
} else {
    $t_theatre = "$database.theatre";
    $t_seats = "$database.seats";
    $t_movies = "$database.movies";
    $t_shows = "$database.shows";
    $t_show_inventory = "$database.show_inventory";
}

try {
    $db = new PDO("mysql:host=$host", $user, $password, $options);
    //echo "Database connected successfully <BR>";

    //Fetch cost of ticket based on theatre_id and seat_type
    $unitPrice = $db->query("SELECT seat_price from $t_seats where seat_theatre_id = $theatre_id and 
                                seat_type = '$ttype'")->fetch()['seat_price'];
    //Check available seats are more than the requested number of seats
    $availSeats = $db->query("SELECT inventory_seats_available as seats from $t_show_inventory 
                    where inventory_show_id = $tshow_id and inventory_seat_type='$ttype'")->fetch()['seats'];

    //Check if insufficient seats, popup alert message and return to prevoius screen         
    if ($tcount>$availSeats) {
        
        //echo '<script>alert("Insufficient seats. Try different seat type / date / slot.")</script>';
        session_start();
        $_SESSION['errorMessage'] = "Insufficient Seats Availability. Try different slot/date/seat type. ";
        header("Location: ./index2.php");
        exit();
    }
    

    //Fetch seat types and available seats from show inventory table 
    $i=1;
    foreach ($db->query("SELECT inventory_seat_type, inventory_seats_available from $t_show_inventory where inventory_show_id = $tshow_id") as $rs1) {
        $inv_seats[$i]['stype'] = $rs1['inventory_seat_type'];
        $inv_seats[$i]['savail'] = $rs1['inventory_seats_available'];
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
                <form action="bookFinal.php" method="post" enctype="multipart/form-data">
                    <div class="row justify-content-center">
                        <div class="col sm-4">
                            <label class="font-weight-bold">Selected Location:</label>
                            <input type="text" class="form-control" id="tCity" name="tCity"
                                value=<?php echo '"' .
                                ($db->query("Select theatre_location from $t_theatre where theatre_id=$theatre_id"))->fetch()['theatre_location'] . '"' ?> readonly />
                        </div>
                        <div class="col sm-4">
                            <label class="font-weight-bold">Selected Theatre:</label>
                            <input type="text" class="form-control" id="tname" name="tname"
                                value=<?php echo '"' .
                                ($db->query("Select theatre_name from $t_theatre where theatre_id=$theatre_id"))->fetch()['theatre_name'] . '"' ?> readonly />
                        </div>
                        <div class="col sm-4">
                            <label class="font-weight-bold">Selected Movie:</label>
                            <input type="text" class="form-control" id="movieName" name="movieName"
                                value=<?php echo '"' .
                                ($db->query("Select movie_title from $t_movies where movie_id=$movie_id"))->fetch()['movie_title'] . '"' ?> readonly />
                        </div>
                    </div><BR>
                    <div class="row justify-content-center">
                        <div class="col sm-4">
                            <label class="font-weight-bold">Selected Date:</label>
                            <input type="text" class="form-control" id="showDate" name="showDaate"
                                value=<?php echo $tdate; ?> readonly />
                        </div>
                        <div class="col sm-4">
                            <label class="font-weight-bold">Selected Slot:</label>
                            <input type="text" class="form-control" id="showSlot" name="showSlot"
                                value=<?php echo $tslot; ?> readonly />
                        </div>
                        <div class="col sm-4">
                            <label class="font-weight-bold" for="seatType">Selected Seat Type:</label>
                            <input type="text" class="form-control" id="seatType" name="seatType"
                                value=<?php echo $ttype; ?> readonly />
                        </div>
                    </div><BR>
                    <table class="table table-bordered table-sm small bg-primary text-white">
                        <tbody>
                            <tr>
                                <td><b>Seats Availability</b></td>
                                <?php
                                for($i=1; $i <= count($inv_seats); $i++){
                                    if($inv_seats[$i]['stype']==$ttype){
                                        echo "<td><b>Seat Type: </b>". $inv_seats[$i]['stype'] . "; ";
                                        echo " <b>No. of Seats: </b>" . $inv_seats[$i]['savail'] . "</td>";
                                    }
                                }
                            ?>
                            </tr>
                        </tbody>
                    </table>
                    <label class="font-weight-bold">Customer Details for booking:</label>
                    <div class="row justify-content-center">
                        <div class="container">
                            <table id="myTable" class=" table order-list table-bordered table-sm">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Ticket Cost </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for($i=1; $i<=$tcount; $i++){ 
                                        echo "<tr><td>" . $customers[$i]['cName'] . "</td>";
                                        echo "<td>" . $customers[$i]['cGender'] . "</td>";
                                        echo "<td>" . $customers[$i]['cAge'] . "</td>";
                                        echo "<td>" . $unitPrice . "</td></tr>"; 
                                    }   
                                    ?>
                                    <tr>
                                        <td><b>Total Cost:</b></td>
                                        <td></td>
                                        <td></td>
                                        <td><b> <?php echo $unitPrice*$tcount; ?> </b> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                    <div class="col sm-4">
                            <div class="form-group">
                                <label class="font-weight-bold" for="amount_paid">Amount to be paid:</label> <BR>
                                <input type="text" name="amount_paid" value=<?php echo $unitPrice*$tcount; ?> readonly/>
                            </div>
                        </div>
                        <div class="col sm-8">
                            <div class="form-group">
                                <label class="font-weight-bold" for="payment_mode">Select Payment Mode:</label> <BR>
                                <label class="radio-inline"><input type="radio" name="payment_mode"
                                        value="Credit card" required>Credit card</label>
                                <label class="radio-inline"><input type="radio" name="payment_mode"
                                        value="Debit card" required>Debit card</label>
                                <label class="radio-inline" required><input type="radio" name="payment_mode"
                                        value="Net banking">Net banking</label>
                                <label class="radio-inline" required><input type="radio" name="payment_mode"
                                        value="UPI">UPI</label>
                            </div>
                        </div>
                    </div>


                    <?php
                    for($i=1; $i<=$tcount; $i++){  ?>
                    <input type="hidden" id="cName" name="cName<?php echo $i; ?>"
                        value=<?php echo $customers[$i]['cName']; ?> />
                    <input type="hidden" id="cGender" name="cGender<?php echo $i; ?>"
                        value=<?php echo $customers[$i]['cGender']; ?> />
                    <input type="hidden" id="cAge" name="cAge<?php echo $i; ?>"
                        value=<?php echo $customers[$i]['cAge']; ?> />
                    <?php
                    } ?>

                    <input type="hidden" class="form-control" id="movieID" name="movieID"
                        value=<?php echo $movie_id; ?> />
                    <input type="hidden" class="form-control" id="tID" name="tID" value=<?php echo $theatre_id; ?> />
                    <input type="hidden" class="form-control" id="showDate" name="showDate"
                        value=<?php echo $tdate; ?> />
                    <input type="hidden" class="form-control" id="showID" name="showID"
                        value=<?php echo $tshow_id; ?> />
                    <input type="hidden" class="form-control" id="showSlot" name="showSlot"
                        value=<?php echo $tslot; ?> />
                    <input type="hidden" class="form-control" id="seatType" name="seatType"
                        value=<?php echo $ttype; ?> />
                    <input type="hidden" class="form-control" name="customerCount" id="customerCount"
                        value=<?php echo $tcount; ?> />


                    <div class="row justify-content-center">
                        <input class="form-group bg-primary text-white" type="submit" name="bookShow4"
                            value="Pay and Confirm Booking" />
                    </div>
                    <BR><BR>
                </form>
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