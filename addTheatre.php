<?php

session_start();

if (isset($_SESSION["uid"])) {
    echo "UID is set <BR>";
    $uid = $_SESSION["uid"];
}else {
    echo "UID not set <BR>";
}
if (isset($_SESSION["isadmin"])) {
    echo "isadmin is true <BR>";
    $isadmin = TRUE;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>iMovies -  Online Movies Reservation System</title>
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

            <!-- Add Movie Form starts here -->

            <div class="container" style=" width:80% ">
                <form action="insertTheatre.php" method="post" enctype="multipart/form-data">
                    <div class="row justify-content-center">
                        <div class="col sm-6">
                            <div class="form-group">
                                <label class="font-weight-bold" for="tCity">Enter Theatre Name:</label>
                                <input type="text" name="tName" id="tName" class="form-control" />
                            </div>
                        </div>
                        <div class="col sm-6">
                            <label class="font-weight-bold" for="tCity">Select City:</label>
                            <select class="form-control" id="tCity" name="tCity">
                                <option value="Bangalore">Bangalore</option>
                                <option value="Chennai">Chennai</option>
                                <option value="Vellore">Vellore</option>
                            </select>
                        </div>
                    </div><BR>
                    <div class="container">
                        <table id="myTable" class=" table order-list table-bordered">
                            <thead>
                                <tr>
                                    <td>Seat Type</td>
                                    <td>Number of Seats</td>
                                    <td>Rate / Price </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-sm-4">
                                        <select class="form-control" id="sType1" name="sType1">
                                            <option value="normal">NORMAL</option>
                                            <option value="deluxe">DELUXE</option>
                                            <option value="gold">GOLD</option>
                                            <option value="platinum">PLATINUM</option>
                                        </select>
                                    </td>
                                    <td class="col-sm-3">
                                        <input type="text" name="sNumbers1" id="sNumbers1" class="form-control" />
                                    </td>
                                    <td class="col-sm-3">
                                        <input type="text" name="sPrice1" id="sPrice1" class="form-control" />
                                    </td>
                                    <td class="col-sm-2"><a class="deleteRow"></a>

                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" style="text-align: left;">
                                        <input type="button" class="btn bg-success btn-block " id="addrow"
                                            value="Add Entry" />
                                    </td>
                                </tr>
                                <tr>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row justify-content-center ">
                        <input class="form-group bg-primary text-white" type="submit" name="submit"
                            value="Add Theatre" />

                    </div>
                    <input type="hidden" class="form-control" name="seatTypesCount" id="seatTypesCount">
                </form>

                <!-- script to add each seat type dynamically -->
                <script>
                $(document).ready(function() {
                    var counter = 1;
                    $("input:hidden[name=seatTypesCount]").val(counter); //set right after setting a row

                    $("#addrow").on("click", function() {
                        counter++; //trying to test deletion of row
                        var newRow = $("<tr>");
                        var cols = "";
                        $("input:hidden[name=seatTypesCount]").val(
                            counter); //set right after setting a row

                        cols += '<td class="col-sm-4"><select class="form-control" id="sType' +
                            counter + '" name="sType' + counter +
                            '"> <option value=normal>NORMAL</option><option value=deluxe>DELUXE</option><option value=gold>GOLD</option><option value=platinum>PLATINUM</option></select></td>';
                        cols += '<td><input type="text" class="form-control" name="sNumbers' + counter +
                            '"/></td>';

                        cols += '<td><input type="text" class="form-control" name="sPrice' + counter +
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

            </div>
        </div>
        <!-- footer section goes here-->

        <div class="navbar fixed-bottom">
            <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
                <h3> Developed using following technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap, Javascript.
                </h3>
            </div>
        </div>

</body>

</html>