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

    //Read HTML FORM submitted values using POST Method
    $iname = str_replace("'", "\'", $_POST['mov_name']);
    $icast = str_replace("'", "\'", $_POST['mov_cast']);
    $idirect = str_replace("'", "\'", $_POST['mov_direct']);
    $ilang = $_POST['mov_lang'];
    $ireldate = $_POST['mov_rel_date'];
    $iimg = basename($_FILES["fileToUpload"]["name"]);
    $idesc = str_replace("'", "\'", $_POST['mov_short_desc']);

    uploadImage(); // Upload the image to temporary folder

    $ifn = moveImageToDatabase($iname,$ilang); // Move from temp to target folder

    include './database/config/config.php';
    if ($connection == "local"){
        $t_movies = "movies";
    }else {
        $t_movies = "$database.movies";
    }

    try { 
        $db = new PDO("mysql:host=$host",$user,$password,$options);
        echo "Database connected successfully <BR>";

        $sql_insert = "INSERT INTO $t_movies (movie_title, movie_cast, movie_director, movie_image_fn, movie_language, 
            movie_release_date, movie_description)  VALUES ('$iname', '$icast' , '$idirect','$ifn', '$ilang', 
            date('$ireldate'),'$idesc' )";
        //echo "SQL Statement $sql_insert";
        $stmt = $db->prepare($sql_insert);
        $rows = $stmt->execute(array());
        //echo "Rows  $rows <BR>";

        If ($rows>0){   ?>


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
                                                <a class="nav-link" href="./addMovie.php">Add New Movie</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Manage Movies</a>
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
                <h4>Folloing new movie added successfully..... </h4>
                <div class="card-group">
                    <div class="card bg-primary" style="width:200px">

                        <div class="card-body">
                            <h3 class="card-title"><?php echo $iname,"(",$ilang,")"; ?></h3>
                            <h4 class="card-text">Cast: <?php echo $icast; ?></h4>
                            <h5 class="card-text">Directed by: <?php echo $idirect; ?></h5>
                            <h5 class="card-text">Release Date: <?php echo $ireldate; ?></h5>
                            <p class="card-text">Summary: <?php echo $idesc; ?></p>
                        </div>
                    </div>

                    <div class="card bg-warning" style="width:200px">
                        <img class="card-img-top" src="./database/images/<?php echo $ifn; ?>" alt="Card image"
                            style="width:100%">
                        <div class="card-footer">
                            <h4 class="card-text">Language: <?php echo $ilang; ?></h4>
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

            <?php
        }else{
            echo "Error Inserting new member <BR>";
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }


  function uploadImage(){
    $target_dir = "./tmp/";    
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    }
}

function moveImageToDatabase($pname, $plang){

    $source_path = "./tmp/".$_FILES["fileToUpload"]["name"];
    $target_file = substr(str_replace(" ","",$pname),0,4) . substr($plang,0,4) . $_FILES["fileToUpload"]["name"];
    $target_path = "./database/images/". $target_file;

    $msg = 'No file: ' .$source_path;
    if (file_exists($source_path)) {
        $ok = rename($source_path, $target_path);
        $msg = 'Failed to rename???';
       if($ok)
       {
          return $target_file;
       }
    }
    echo $msg;
    return 0;
}

?>

</body>

</html>