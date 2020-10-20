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

$theatre_id = $_POST['tID'];
echo " the selected theatre is $theatre_id <br>";

$tdate = $_POST['citiesSelect'];
echo " the selected date is $tdate <br>";

$tslot = $_POST['theatresSelect'];
echo " the selected slot is $tslot <br>";

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

    //Fetch unique show_id from shows based on selected movie_id, thetre_id, show_date and show_slot
    $show_id = $db->query("SELECT show_id from $t_shows where show_movie_id = $movie_id and show_theatre_id = $theatre_id
                            and show_date='$tdate' and show_slot = '$tslot'")->fetch()['show_id'];
    echo "Show ID is : $show_id <BR>";    


    //Code to populate seat type from show inventory table 
    $i=1;
    foreach ($db->query("SELECT inventory_seat_type, inventory_seats_available from $t_show_inventory where inventory_show_id = $show_id") as $rs1) {
        $inv_seats[$i]['stype'] = $rs1['inventory_seat_type'];
        $inv_seats[$i]['savail'] = $rs1['inventory_seats_available'];
        echo "Seat Type         :" . $inv_seats[$i]['stype'];
        echo "Seats Available   :" . $inv_seats[$i]['savail'] .  "<BR>";
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
                <form action="bookShow4.php" method="post" enctype="multipart/form-data">
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
                            <input type="text" class="form-control" id="showDate" name="showDate"
                                value=<?php echo $tdate; ?> readonly />
                        </div>
                        <div class="col sm-4">
                            <label class="font-weight-bold">Selected Slot:</label>
                            <input type="text" class="form-control" id="showSlot" name="showSlot"
                                value=<?php echo $tslot; ?> readonly />
                        </div>
                        <div class="col sm-4">
                            <label class="font-weight-bold" for="seatType">Select Seat Type:</label>
                            <select class="form-control" id="seatType" name="seatType">
                                <?php  
                                    for($i=1; $i <= count($inv_seats); $i++){
                                        echo  "<option value=" . $inv_seats[$i]['stype'] . ">" . $inv_seats[$i]['stype'] . "</option>";
                                    }
                            
                                ?>
                            </select>
                        </div>
                    </div><BR>
                    <table class="table table-bordered table-sm small bg-primary text-white">
                        <tbody>
                            <tr><td><b>Seats Availability</b></td>
                            <?php
                                for($i=1; $i <= count($inv_seats); $i++){
                                    echo "<td><b>Seat Type: </b>". $inv_seats[$i]['stype'] . "; ";
                                    echo " <b>No. of Seats: </b>" . $inv_seats[$i]['savail'] . "</td>";
                                }
                            ?>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Table with dynamic number of customers.. starts here-->
                    <label class="font-weight-bold">Enter Customer Details:</label>
                    <div class="row justify-content-center">
                        <div class="container">
                            <table id="myTable" class=" table order-list table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="col-sm-4">
                                            <input type="text" name="cName1" id="cName1" class="form-control" />
                                        </td>
                                        <td class="col-sm-4">
                                            <select class="form-control" id="cGender1" name="cGender1">
                                                <option value="m">Male</option>
                                                <option value="f">Female</option>
                                                <option value="t">Transgender</option>
                                            </select>
                                        </td>
                                        <td class="col-sm-2">
                                            <input type="text" name="cAge1" id="cAge1" class="form-control" />
                                        </td>
                                        <td class="col-sm-2"><a class="deleteRow"></a>

                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" style="text-align: left;">
                                            <input type="button" class="btn bg-success btn-block " id="addrow"
                                                value="Add another customer" />
                                        </td>
                                    </tr>
                                    <tr>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Table with dynamic number of customers.. ends here.  -->
                    </div><BR>
                    <input type="hidden" class="form-control" id="movieID" name="movieID"
                        value=<?php echo $movie_id; ?> />
                    <input type="hidden" class="form-control" id="tID" name="tID" value=<?php echo $theatre_id; ?> />
                    <input type="hidden" class="form-control" name="showID" id="showID" value= <?php echo $show_id; ?> />
                    <input type="hidden" class="form-control" name="customerCount" id="customerCount" />
                    <div class="row justify-content-center ">
                        <input class="form-group bg-primary text-white" type="submit" name="bookShow3"
                            value="Click to proceed" /> 
                    </div>
                    <BR><BR>
                </form>

            </div>
        </div>

        <!-- script to add each seat type dynamically  in myTable-->
        <script>
        $(document).ready(function() {
            var counter = 1;
            $("input:hidden[name=customerCount]").val(counter); //set right after setting a row

            $("#addrow").on("click", function() {
                counter++; //trying to test deletion of row
                var newRow = $("<tr>");
                var cols = "";
                $("input:hidden[name=customerCount]").val(
                    counter); //set right after setting a row

                cols += '<td><input type="text" class="form-control" name="cName' + counter +
                    '"/></td>';

                cols += '<td class="col-sm-4"><select class="form-control" id="cGender' +
                    counter + '" name="cGender' + counter +
                    '"> <option value=m>Male</option><option value=f>Female</option><option value=t>Transgender</option></select></td>';

                cols += '<td><input type="text" class="form-control" name="cAge' + counter +
                    '"/></td>';

                cols +=
                    '<td><input type="button" class="ibtnDel btn btn-md btn-danger" value="Delete"></td></tr>';
                newRow.append(cols);
                $("table.order-list").append(newRow);
                //counter++;
            });

            $("table.order-list").on("click", ".ibtnDel", function(event) {

                $(this).closest("tr").remove(); // Removing the current row. 
                counter--; // Decreasing total number of rows by 1. s
                $("input:hidden[name=seatTypesCount]").val(
                    counter); //set right after deleting a row
            });
        });
        </script>
        <!-- End of script to add each seat type holder dynamically -->

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