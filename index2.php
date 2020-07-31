<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <title>Welcome to my new Home Page - Karups</title>
</head>



<body>

    <div class="container-fluid  p-3 my-3 bg-primary text-white text-center">
        <h1>Welcome to my Home Page</h1>
        <p>Resize this responsive page to see the effect!</p>
    </div>

    <div class="container">

        <div class="card-deck">
            <div class="card bg-primary" style="width:200px">
                <img class="card-img-top" src="./img/profile.png" alt="Card image" style="width:100%">
                <div class="card-body">
                    <h4 class="card-title">Profile</h4>
                    <p class="card-text">Use this option to create/update your profile</p>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn btn-warning">Click here</a>
                </div>
            </div>

            <div class="card bg-warning" style="width:200px">
                <img class="card-img-top" src="./img/movies.png" alt="Card image" style="width:100%">
                <div class="card-body">
                    <h4 class="card-title">Movies</h4>
                    <p class="card-text">Use this option to view movies showing currently</p>
                </div>
                <div class="card-footer">
                    <a href="./viewMovies.php" class="btn btn-primary">Click here</a>
                </div>
            </div>

            <div class="card bg-primary" style="width:200px">
                <img class="card-img-top" src="./img/family.png" alt="Card image" style="width:100%">
                <div class="card-body">
                    <h4 class="card-title">Family</h4>
                    <p class="card-text">Use this option to view current family members</p>
                </div>
                <div class="card-footer">
                    <a href="./viewfamily.php" class="btn btn-warning">Click here</a>
                </div>

            </div>

            <div class="card bg-warning" style="width:200px">
                <img class="card-img-top" src="./img/booking.jpg" alt="Card image" style="width:100%">
                <div class="card-body">
                    <h4 class="card-title">Bookings</h4>
                    <p class="card-text">Use this option to view your history of bookings</p>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn btn-primary">Click here</a>
                </div>
            </div>

        </div> <BR>


        <div class="container-fluid  p-3 my-3 bg-primary text-white text-center">
        <h3>Developed using following technology stack:</h3>
        <p>PHP, MySQL, Apache, HTML5, CSS, Bootstrap. </p>
        <p small> VS Code as IDE, GitHub as Source Code Library and free hosting at Infinityfree! :) </p>
    </div>


</body>

</html>