<?php

include('serverconnect.php');
session_start();



if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $loginuser = $_SESSION['id'];
    $equipmentID = $_POST['equipment'];
    $equser = $_POST['userid'];
    $date = date('Y-m-d H:i:s');
    $checkoutRequestsID = $_POST['checkoutRequestsID'];

    echo $equipmentID;
    echo "loginuser", $loginuser;
    echo "Eq", $equser;
    echo $checkoutRequestsID;

    if ($loginuser == $equser) {

        $checkQty = "Select * from EqManage.equipment where id = '$equipmentID'";
        $checkQtyResult = mysqli_query($db, $checkQty);
        $checkQtyArray = mysqli_fetch_assoc($checkQtyResult);

        $leftQty = $checkQtyArray['leftQuantity'];
        echo $leftQty;
        $newQty = $leftQty + 1;


        $statusupdate = "UPDATE EqManage.equipment SET leftQuantity='$newQty' where id='$equipmentID'";
        $requestsinsert = "INSERT INTO EqManage.requests (users_id,equipment_id,requestDate,active,action) VALUES ('$equser','$equipmentID','$date','1','return')";

        if (mysqli_query($db, $requestsinsert)) {
            $last_id = mysqli_insert_id($db);
            echo "Request inserted. Last inserted ID is: " . $last_id;
        } else {
            echo "Error: " . $requestsinsert . "<br>" . mysqli_error($db);
        }
        $loginsert = "update EqManage.log set returnRequests_id = '$last_id', returnDate = '$date' where checkoutRequests_id='$checkoutRequestsID'";

//        $loginsert = "INSERT INTO log (Name, Equipment, CheckIn) values ('$equser', '$equipment', '$date')";
//          Old way of getting the last log
//        $lastlog = mysqli_query($db, "select * from log where Name ='$equser' AND Equipment='$equipment' order by id desc limit 1 ");
//
//        $row = mysqli_fetch_array($lastlog);
//
//        echo $row['Equipment'];
//
//        $lastlogid = $row['id'];
//        echo $lastlogid;
//
//        $loginsert = "update log SET CheckIn='$date' where id='$lastlogid'";


        mysqli_query($db, $statusupdate);
        mysqli_query($db, $requestsinsert);
        mysqli_query($db, $loginsert);

        header('Location: new_index.php?return=1');


    } else header('Location: new_index.php?return=0');

}





?>