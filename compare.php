<?php
require_once('dbconnection.php');
$count = 0;
$qry = "SELECT IsoTemplate,ID,`Name`,email,CaseId,CreateDate FROM biomatricdata where IsAct=1";
$row = mysqli_query($con, $qry);
while ($num = mysqli_fetch_array($row)) {
    $count += 1;
    $result[$count] = $num['IsoTemplate'];
    $result[++$count] = $num['ID'];
    $result[++$count] = $num['Name'];
    $result[++$count] = $num['email'];
    $result[++$count] = $num['CaseId'];
    $result[++$count] = $num['CreateDate'];
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
