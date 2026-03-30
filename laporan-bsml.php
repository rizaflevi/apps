<?php
require_once 'sysfunction.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->

    <!-- Optional theme -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> -->

    <!-- <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/> -->
    <!-- <title>Document</title> -->
</head>
<body>
    <!-- <div class="container"> -->


<?php

// Database connection details
$host = 'localhost';
$dbname = 'riza_db';
$username = 'rifan';
$password = 'YazaPratama@23B';

// echo $date1;
try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $d1 = $_GET['d1'];
    $d2 = $_GET['d2'];
    $date1 = new DateTime($d1);
    $date2 = new DateTime($d2);
    $date2->modify('+1 day');
    $date1 = $date1->format('Y-m-d');
    $date2 = $date2->format('Y-m-d');

    $sql = 
"WITH time_bounds AS (
    SELECT 
        a.PEOPLE_CODE,
        DATE(PPL_PRESENT) AS tgl,
        MIN(CASE WHEN PRESENT_CODE = 0 THEN PPL_PRESENT END) AS earliest_in_time,
        MAX(CASE WHEN PRESENT_CODE = 1 THEN PPL_PRESENT END) AS latest_out_time
    FROM people_present a
    LEFT JOIN people b ON a.PEOPLE_CODE = b.PEOPLE_CODE
    LEFT JOIN prs_person_occupation c ON a.PEOPLE_CODE = c.PRSON_CODE
    LEFT JOIN tb_customer d ON c.CUST_CODE = d.CUST_CODE

    WHERE 
        d.CUST_CODE = '24BR3'
        AND (a.PPL_PRESENT BETWEEN '$date1' AND '$date2')
    GROUP BY PEOPLE_CODE, DATE(PPL_PRESENT)
)
SELECT 
    a.PEOPLE_CODE,
    b.PEOPLE_NAME,
    d.CUST_NAME,
    e.LOKS_NAME,
    f.JOB_CODE,
    f.JOB_NAME,
    tb.tgl,
    tb.earliest_in_time,
    MAX(CASE WHEN a.PRESENT_CODE = 1 
             AND a.PPL_PRESENT > tb.earliest_in_time
             AND a.PPL_PRESENT < tb.latest_out_time
             AND f.JOB_CODE NOT IN ('D3','G3')
        THEN a.PPL_PRESENT ELSE NULL END) AS lunch_out_time,
    MIN(CASE WHEN a.PRESENT_CODE = 0 
             AND a.PPL_PRESENT > tb.earliest_in_time
             AND a.PPL_PRESENT < tb.latest_out_time
             AND f.JOB_CODE NOT IN ('D3','G3')
        THEN a.PPL_PRESENT ELSE NULL END) AS lunch_in_time,
    tb.latest_out_time
FROM 
    people_present a
LEFT JOIN people b ON a.PEOPLE_CODE = b.PEOPLE_CODE
LEFT JOIN prs_person_occupation c ON a.PEOPLE_CODE = c.PRSON_CODE
LEFT JOIN tb_customer d ON c.CUST_CODE = d.CUST_CODE
LEFT JOIN prs_locs e ON c.KODE_LOKS = e.LOKS_CODE
LEFT JOIN prs_tb_occupation f ON c.JOB_CODE = f.JOB_CODE
JOIN time_bounds tb ON a.PEOPLE_CODE = tb.PEOPLE_CODE AND DATE(a.PPL_PRESENT) = tb.tgl
WHERE 
    d.CUST_CODE = '24BR3'
    AND (a.PPL_PRESENT BETWEEN '$date1' AND '$date2')
GROUP BY 
    a.PEOPLE_CODE, b.PEOPLE_NAME, tb.tgl, f.JOB_NAME, tb.earliest_in_time, tb.latest_out_time
ORDER BY 
    f.JOB_NAME, b.PEOPLE_NAME, a.PPL_PRESENT ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Fetch all rows
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create an associative array to store results by person and date
    $organizedResults = [];
    foreach ($results as $row) {
        $personCode = $row['PEOPLE_CODE'];
        $date = $row['tgl'];
        $organizedResults[$personCode][$date] = $row;
    }

    $startDate = new DateTime($d1);
    $endDate = new DateTime($d2);
    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

    $indonesianDays = [
        0 => 'Min',
        1 => 'Sen',
        2 => 'Sel',
        3 => 'Rab',
        4 => 'Kam',
        5 => 'Jum',
        6 => 'Sab'
    ];

     // Output HTML
     echo "
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
  }
    td {
  page-break-inside: avoid;
}

</style>
<h1>Laporan Kehadiran</h1>
<h2>BSML Regional III</h2>
<h2>Periode ".$d1." s.d ".$d2."</h2>
<table class='table' id='myTable'><thead>";
    
     // First row: Dates
    //  echo "<tr class=''>
    //  <th rowspan='2'>People Code</th>
    //  <th rowspan='2'>People Name</th>
    //  <th rowspan='2'>Cust Name</th>
    //  <th rowspan='2'>Lokasi</th>
    //  <th rowspan='2'>Jabatan</th>
    //  <th rowspan='2'>Time Type</th>";
     echo "<tr class=''>
     <th rowspan='2' width='4%'>NIP; NAMA; JABATAN</th>
     <th rowspan='2' width='4%'>Time Type</th>";
     foreach ($dateRange as $date) {
         echo "<th>" . $date->format('d') . "</th>";
     }
     echo "</tr>";
 
     // Second row: Days of the week
     echo "<tr>";
     foreach ($dateRange as $date) {
         $dayOfWeek = $indonesianDays[$date->format('w')];
         echo "<th>" . $dayOfWeek . "</th>";
     }
     echo "</tr></thead><tbody>";
 
     // Data rows
     foreach ($organizedResults as $personCode => $personDates) {
         $personName = '';
         
         // Find the first non-empty date to get the person's name
         foreach ($personDates as $date => $data) {
             if (!empty($data['PEOPLE_NAME'])) {
                 $personName = $data['PEOPLE_NAME'];
                 break;
             }
         }
         
         // Four rows for each person (In, Lunch Out, Lunch In, Out)
         $timeTypes = ['Masuk', 'Mul.Ist.', 'Sel.Ist', 'Keluar'];
         $timeFields = ['earliest_in_time', 'lunch_out_time', 'lunch_in_time', 'latest_out_time'];
         
         for ($i = 0; $i < 4; $i++) {
             echo "<tr>";
             if ($i == 0) {
                 echo "<td class='text-nowrap' rowspan='4' width='4%'>" 
                 . htmlspecialchars($personCode) .';<br/>'
                  . htmlspecialchars($personName) .';<br/>'
                  . htmlspecialchars($data['JOB_NAME']) . "</td>";
                //  echo "<td class='text-nowrap' rowspan='4'>" . htmlspecialchars($personCode) . "</td>";
                //  echo "<td class='text-nowrap' rowspan='4'>" . htmlspecialchars($personName) . "</td>";
                //  echo "<td class='text-nowrap' rowspan='4'>" . htmlspecialchars($data['CUST_NAME']) . "</td>";
                //  echo "<td class='text-nowrap' rowspan='4'>" . htmlspecialchars($data['LOKS_NAME']) . "</td>";
                //  echo "<td class='text-nowrap' rowspan='4'>" . htmlspecialchars($data['JOB_NAME']) . "</td>";
             }
             echo "<td class='text-nowrap' width='4%'>" . $timeTypes[$i] . "</td>";
             
             foreach ($dateRange as $date) {
                 $currentDate = $date->format('Y-m-d');
                //  if (isset($personDates[$currentDate])) {
                     $row = $personDates[$currentDate];
                     echo "<td>" . formatTimeJakarta($row[$timeFields[$i]]) . "</td>";
                //  } else {
                    //  if ($i == 0) {
                        //  echo "<td rowspan='4'></td>";//off atau hari libur
                    //  }
                //  }
             }
             echo "</tr>";
         }
     }
 
     echo "</tbody></table>";
 
 } catch(PDOException $e) {
     echo "Error: " . $e->getMessage();
 }
// Close the connection
$pdo = null;

// Function to format time to HH:MM
// Function to format time to HH:MM in Asia/Jakarta timezone
function formatTimeJakarta($time) {
    if ($time === null) {
        return '';
    }
    $dateTime = new DateTime($time, new DateTimeZone('Asia/Jakarta'));
    // $dateTime->setTimezone(new DateTimeZone('Asia/Jakarta'));
    $dateTime->modify('+1 hour'); // Shift time 1 hour later
    return htmlspecialchars($dateTime->format('H:i'));
}
?>
    <!-- </div> -->
</body>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script> 
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.0/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.js"></script>
<script>
    // $(document).ready( function () {
    //     $('#myTable').DataTable({
    //         /* No ordering applied by DataTables during initialisation */
    //         'order': [],
    //         'paging': false
    //     });
    // } );
</script>
</html>