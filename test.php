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

include './database/config/config.php';
if ($connection == "local"){
    $t_theatre = "theatre";
    $t_seats = "seats";
    $t_movies = "movies";
}else {
    $t_theatre = "$database.theatre";
    $t_seats = "$database.seats";
    $t_movies = "$database.movies";
}
$tCity = "Bangalore"; //To show Bangalore theatres as default option

try { 
    $db = new PDO("mysql:host=$host",$user,$password,$options);
    echo "Database connected successfully <BR>";

    foreach($db->query("SELECT distinct theatre_location from $t_theatre") as $rs1){
        $cities[] = array( "tcity" => $rs1['theatre_location']);
    }
    foreach($db->query("SELECT theatre_location, theatre_id, theatre_name from $t_theatre") as $rs2){
        $theatres[$rs2['theatre_location']][] = array("tid" => $rs2['theatre_id'], "tname" => $rs2['theatre_name']);

    }

    echo "before <BR>";
    echo ($db->query("Select movie_title from $t_movies where movie_id=7"))->fetch()['movie_title'] . "<BR>";
    echo "after <BR>";
    $jsonCities = json_encode($cities);
    $jsonTheatres = json_encode($theatres);

    echo $jsonCities . "<BR>";
    echo $jsonTheatres  . "<BR><BR>";

    
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br />";
die();
}

?>

<!docytpe html>
    <html>

    <head>
    
        <script type='text/javascript'>
        <?php
            // make json variables available
            echo "var cities = $jsonCities; \n";
            echo "var theatres = $jsonTheatres; \n";
        ?>

        function loadCities() {
            var select = document.getElementById("citiesSelect");
            select.onchange = updateTheatres;
            for (var i = 0; i < cities.length; i++) {
                select.options[i] = new Option(cities[i].tcity);
            }
        }

        function updateTheatres() {
            var citySelect = this;
            var tcity = this.value;
            var theatresSelect = document.getElementById("theatresSelect");
            theatresSelect.options.length = 0; //delete all options if any present
            for (var i = 0; i < theatres[tcity].length; i++) {
                theatresSelect.options[i] = new Option(theatres[tcity][i].tname, theatres[tcity][i].tid);
            }
        }
        </script>

    </head>

    <body onload='loadCities()'>
        <select id='citiesSelect'>
        </select>

        <select id='theatresSelect'>
        </select>
    </body>

    </html>