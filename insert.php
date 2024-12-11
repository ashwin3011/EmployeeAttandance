<?php
require_once('connection.php');

if (!empty($_POST['Name']) && !empty($_POST['Email']) && !empty($_POST['Password']) && !empty($_POST['Bitmap']) && !empty($_POST['IsoTemplate'])) {
    echo "inside loop";
    $qry = "SELECT MAX(ID) FROM biomatricdata";
    $row = mysqli_query($con, $qry);
    $num = mysqli_fetch_array($row);
    if ($num > 0) {
        $caseid = $num[0];
    }
    $qry = "insert into biomatricdata (`UID`, `Name`, `Email`,`Password`, `Bitmap`, `Quality`, `Nfic`, `InWidth`, `InHeight`, `InArea`, `Resolution`, `GrayScale`, `Bpp`, `WsqCompressRatio`, `WsqInfo`, `IsoTemplate`, `AnsiTemplate`, `IsoImage`, `RawData`, `WsqImage`, `CaseId`, `CreateDate`, `IsAct`) values(1,'" . $_POST['Name'] . "'," . $_POST['Contact'] . ",'" . $_POST['Address'] . "','" . $_POST['Bitmap'] . "','" . $_POST['Quality'] . "','" . $_POST['Nfic'] . "','" . $_POST['InWidth'] . "','" . $_POST['InHeight'] . "','" . $_POST['InArea'] . "','" . $_POST['Resolution'] . "','" . $_POST['GrayScale'] . "','" . $_POST['Bpp'] . "','" . $_POST['WsqCompressRatio'] . "','" . $_POST['WsqInfo'] . "','" . $_POST['IsoTemplate'] . "','" . $_POST['AnsiTemplate'] . "','" . $_POST['IsoImage'] . "','" . $_POST['RawData'] . "','" . $_POST['WsqImage'] . "','" . $caseid . "','" . date('Y-m-d') . "',1)";
    $row = mysqli_query($con, $qry);
    if ($row > 0) {
        $response = array(
            'status' => true,
            'message' => 'Success',
        );
    } else {
        $response = array(
            'status' => false,
            'message' => 'fail',
        );
    }
    echo json_encode($response);
}
