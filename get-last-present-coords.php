<?php
if (isset($_GET["geocode"])) {
    $geocode = $_GET['geocode'];

    $conn = mysqli_connect("103.56.148.108", "rifan", "YazaPratama@23B", "riza_db");

    $sth = mysqli_query($conn, 
    "SELECT PEOPLE_CODE, PPL_PRESENT, PRESENT_CODE,PPL_LONGITUDE,PPL_LATTITUDE 
    FROM people_present 
    WHERE ENTRY='".$geocode."' ORDER BY PPL_PRESENT DESC LIMIT 15");
    $rows = array();
    while($r = mysqli_fetch_assoc($sth)) {
        $rows[] = $r;
    }

    echo json_encode($rows);
} else {
    echo "false";
}
