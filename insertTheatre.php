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

    <?php

    session_start();

    if (isset($_SESSION["uid"])) {
        $uid = $_SESSION["uid"];
    }
    if (isset($_SESSION["isadmin"])) {
        $isadmin = TRUE;
    }
    //This will insert rows into both Theatre and Seats table.
    
    //Read HTML FORM submitted values using POST Method

    $tName = $_POST['tName'];
    $tCity = $_POST['tCity'];
    $seatTypesCount = $_POST['seatTypesCount'];

    //echo "Number of seat types $seatTypesCount <BR>";
    //echo " Theatre Name $tName <BR>";
    //echo "City $tCity <BR>";
   
    
    $seatTypes = array(array());
    for($i=1; $i<=$seatTypesCount; $i++){ 
            $seatTypes[$i]['sType']=$_POST['sType'.$i];
            $seatTypes[$i]['sNumbers']=$_POST['sNumbers'.$i];
            $seatTypes[$i]['sPrice']=$_POST['sPrice'.$i];
            //echo $i. " Seat Type : ".$seatTypes[$i]['sType']."<BR>";
            //echo $i. " Number of Seats : ".$seatTypes[$i]['sNumbers']."<BR>";
            //echo $i. " Price : ".$seatTypes[$i]['sPrice']."<BR>";
    }
    

    include './database/config/config.php';
    if ($connection == "local"){
        $t_theatre = "theatre";
        $t_seats = "seats";
    }else {
        $t_theatre = "$database.theatre";
        $t_seats = "$database.seats";
    }

  
    try { 
        $db = new PDO("mysql:host=$host",$user,$password,$options);
        //echo "Database connected successfully <BR>";

        //insert new row into theatre table
        $sql_insert = "INSERT INTO $t_theatre (theatre_name, theatre_location) VALUES ('$tName','$tCity')";
        $stmt = $db->prepare($sql_insert);
        $rows = $stmt->execute(array());
       
        If ($rows>0){   
            //echo "Theatre added successfully.. <BR>";
            $sql_theatre_id ="SELECT max(theatre_id) as max_id from $t_theatre";
            //echo "Select statement is $sql_theatre_id <BR>";
            $stmt1 = $db->query($sql_theatre_id);
            $rows1 = $stmt1->fetch();
            $t_theatre_id = $rows1['max_id'];

            //echo "Theatre ID is $t_theatre_id <BR>";
            //insert all types of seats into Seats table for the given theatre
            for($i=1; $i<=$seatTypesCount; $i++){ 
                $sType = $seatTypes[$i]['sType'];
                $sNumbers = $seatTypes[$i]['sNumbers'];
                $sPrice = $seatTypes[$i]['sPrice'];
                $sql_insert2 = "INSERT INTO $t_seats (seat_theatre_id, seat_type,seats_no_of_seats,seat_price) 
                    VALUES ($t_theatre_id,'$sType',$sNumbers,$sPrice)";
                //echo "SQL Statement $sql_insert2 <BR>";
                $stmt2 = $db->prepare($sql_insert2);
                $rows2 = $stmt2->execute(array());
            } 
        }

    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    ?>

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
                                    <a class="nav-link">Main</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="./addMovie.php">Add Movie</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="./addTheatre.php">Add Theatre</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Screens</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Add New Screen</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Manage Screens</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Shows</a>
                                    <nav class="navbar bg-light">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Add New Shows</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Manage Shows</a>
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
                <h4>Folloing new theatre added successfully..... </h4>
                <div class="card-group">
                    <div class="card bg-primary">

                        <div class="card-body">
                            <h3 class="card-title"><?php echo $tName,"(",$tCity,")"; ?></h3>

                            <div class="container">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="col-sm-4">Seat Type</th>
                                            <th class="col-sm-4">Number of Seats</th>
                                            <th class="col-sm-4">Rate / Price </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            for($i=1; $i<=$seatTypesCount; $i++){ 
                                                $sType = $seatTypes[$i]['sType'];
                                                $sNumbers = $seatTypes[$i]['sNumbers'];
                                                $sPrice = $seatTypes[$i]['sPrice'];
                                                
                                                echo "<tr><td>$sType</td>";
                                                echo "<td>$sNumbers</td>";
                                                echo "<td>$$sPrice</td></tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

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