<?php
require_once('dbconnection.php');
session_start();
$count = 0;
$qry = "SELECT IsoTemplate,`UID` FROM `admin` where IsAct=1";
$row = mysqli_query($con, $qry);
while ($num = mysqli_fetch_array($row)) {
    $count += 1;
    $result[$count] = $num['IsoTemplate'];
    $result[++$count] = $num['UID'];
    $_SESSION['biolgnid'] = $num['UID'];
}
if ($count > 0) {
    $response = array(
        'status' => true,
        'message' => 'Success',
        'data' => $result,
        'count' => $count
    );
} else {
    $response = array(
        'status' => false,
        'message' => 'No record',
    );
}
echo json_encode($response);
