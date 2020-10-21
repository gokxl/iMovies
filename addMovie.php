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
                <form action="insertMovie.php" method="post" class="was-validated" enctype="multipart/form-data">
                    <div class="row justify-content-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="font-weight-bold" for="mov_name">Movie Title:</label>
                                <input type="text" class="form-control" id="mov_name" placeholder="Enter Movie Title"
                                    name="mov_name" required>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold" for="mov_cast">Movie Cast:</label>
                                <input type="text" class="form-control" id="mov_cast" placeholder="Enter Cast Details"
                                    name="mov_cast" required>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold" for="mov_direct">Movie Director:</label>
                                <input type="text" class="form-control" id="mov_direct"
                                    placeholder="Enter Director Name" name="mov_direct" required>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold" for="mov_lang">Movie Language:</label> <BR>
                                <label class="radio-inline"><input type="radio" name="mov_lang"
                                        value="English">English</label>
                                <label class="radio-inline"><input type="radio" name="mov_lang"
                                        value="Hindi">Hindi</label>
                                <label class="radio-inline"><input type="radio" name="mov_lang"
                                        value="Kannada">Kannada</label>
                                <label class="radio-inline"><input type="radio" name="mov_lang"
                                        value="Malayalam">Malayalam</label>
                                <label class="radio-inline"><input type="radio" name="mov_lang" value="Tamil"
                                        checked>Tamil</label>
                                <label class="radio-inline"><input type="radio" name="mov_lang"
                                        value="Telugu">Telegu</label>
                                <label class="radio-inline"><input type="radio" name="mov_lang"
                                        value="Other">Other</label>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold" for="mov_rel_date">Release Date:</label>
                                <input type="date" class="form-control" placeholder="Select Release Date"
                                    id="mov_rel_date" name="mov_rel_date" required>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="font-weight-bold" for="mov_short_desc">Movie Short Description:</label>
                                <textarea rows="3" class="form-control-file" id="mov_short_desc" name="mov_short_desc"
                                    required></textarea>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold" for="fileToUpload">Movie Image:</label>
                                <input type="file" class="form-control-file" id="fileToUpload"
                                    accept="image/jpg, image/jpeg, image/png, image/gif" name="fileToUpload" required />
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                                <div class="ml-2">
                                    <img src="./tmp/80x80.png" id="preview" class="img-thumbnail">
                                </div>
                            </div>

                            <input class="form-group bg-primary text-white" type="submit" name="submit"
                                value="Add Movie">
                        </div>
                    </div>
                </form>
            </div>
        </div><BR>

        <!--  java script to upload image -->
        <script>
        // Preview selected image
        $(document).on("click", ".browse", function() {
            var file = $(this).parents().find(".file");
            file.trigger("click");
        });
        $('input[type="file"]').change(function(e) {
            var fileName = e.target.files[0].name;
            $("#file").val(fileName);

            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                document.getElementById("preview").src = e.target.result;
            };
            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);

        });
        </script>
        <!-- Add Movie Form ends here-->

    </div>
    </div>
    <!-- footer section goes here-->

    <div class="navbar fixed-bottom">
        <div class="container-fluid text-center bg-primary text-white fill-height pt-3">
            <h3> Developed using following technology stack: PHP, MySQL, Apache, HTML5, CSS, Bootstrap, Javascript.</h3>
        </div>
    </div>

</body>

</html>