<?php
$conn = mysqli_connect("db", "root", "1234", "patient_db");
$id = $_POST['id'];
$full_name = $_POST['full_name'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$phone_number = $_POST['phone_number'];
$address = $_POST['address'];
$diagnosis = $_POST['diagnosis'];
$admission_date = $_POST['admission_date'];
$discharge_date = $_POST['discharge_date'];
$doctor_id = $_POST['doctor_id'];

$sql = "UPDATE patients SET 
        full_name='$full_name',
        age='$age',
        gender='$gender',
        phone_number='$phone_number',
        address='$address',
        diagnosis='$diagnosis',
        admission_date='$admission_date',
        discharge_date='$discharge_date',
        doctor_id='$doctor_id'
        WHERE id='$id'";
$conn->query($sql);
$conn->close();
