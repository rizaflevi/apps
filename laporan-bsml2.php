<?php
require_once 'sysfunction.php';
// Database connection setup (replace with your actual connection details)
$host = 'localhost';
$db   = 'riza_db';
$user = 'rifan';
$pass = 'YazaPratama@23B';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$cc = isset($_GET['cc']) ? filter_input(INPUT_GET, 'cc', FILTER_SANITIZE_STRING) : null;
// $cc = $pdo->quote($cc);
$d1 = isset($_GET['d1']) ? filter_input(INPUT_GET, 'd1', FILTER_SANITIZE_STRING) : null;
// $d1 = $pdo->quote($d1);
$d2 = isset($_GET['d2']) ? filter_input(INPUT_GET, 'd2', FILTER_SANITIZE_STRING) : null;
// $d2 = $pdo->quote($d2);
$date1 = new DateTime($d1);
$date2 = new DateTime($d2);
$date2->modify('+1 day');
$date1 = $date1->format('Y-m-d');
$date2 = $date2->format('Y-m-d');
// var_dump($date1, $date2);
// Your SQL query
$sql = "SELECT 
    a.PRSON_CODE,
    b.PEOPLE_NAME,
    d.CUST_NAME,
    e.LOKS_NAME,
    f.JOB_CODE,
    f.JOB_NAME,
    a.REP_TIME,
    a.REP_CONTENT
FROM 
    prs_report a
LEFT JOIN people b
    ON a.PRSON_CODE = b.PEOPLE_CODE
LEFT JOIN prs_person_occupation c 
    ON a.PRSON_CODE = c.PRSON_CODE
LEFT JOIN tb_customer d
    ON c.CUST_CODE = d.CUST_CODE
LEFT JOIN prs_locs e
    ON c.KODE_LOKS = e.LOKS_CODE
LEFT JOIN prs_tb_occupation f
    ON c.JOB_CODE = f.JOB_CODE
WHERE 
    d.CUST_CODE = :cust_code 
    AND (a.REP_TIME BETWEEN :start_date AND :end_date)
ORDER BY JOB_NAME, PEOPLE_NAME, REP_TIME ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':cust_code' => $cc,
    ':start_date' => $date1,
    ':end_date' => $date2
]);
$row = $stmt->fetch();
echo "
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
  }
</style>

<h1>Laporan Dari Karyawan</h1>
<h2>".htmlspecialchars($row["CUST_NAME"])."</h2>
<h2>Periode ".$date1." - ".$d2."</h2>
<table>

<tr>
    <th>NIP</th>
    <th>Nama</th>
    <th>Customer</th>
    <th>Lokasi</th>
    <th>Jabatan</th>
    <th>Tanggal</th>
    <th>Keterangan</th>
</tr>";

while ($row = $stmt->fetch()) {
    echo "<tr>
        <td>".htmlspecialchars($row["PRSON_CODE"])."</td>
        <td>".htmlspecialchars($row["PEOPLE_NAME"])."</td>
        <td>".htmlspecialchars($row["CUST_NAME"])."</td>
        <td>".htmlspecialchars($row["LOKS_NAME"])."</td>
        <td>".htmlspecialchars($row["JOB_NAME"])."</td>
        <td>".htmlspecialchars($row["REP_TIME"])."</td>
        <td>".htmlspecialchars($row["REP_CONTENT"])."</td>
    </tr>";
}
echo "</table>";