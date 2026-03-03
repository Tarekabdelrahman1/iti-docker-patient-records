<?php
$conn = mysqli_connect("localhost", "root", "", "people");

$full_name = $_POST['full_name'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$phone_number = $_POST['phone_number'];
$address = $_POST['address'];
$diagnosis = $_POST['diagnosis'];
$admission_date = $_POST['admission_date'];
$discharge_date = $_POST['discharge_date'];
$doctor_id = $_POST['doctor_id'];

$sql = "INSERT INTO patients (full_name, age, gender, phone_number, address, diagnosis, admission_date, discharge_date, doctor_id)
        VALUES ('$full_name', '$age', '$gender', '$phone_number', '$address', '$diagnosis', '$admission_date', '$discharge_date', '$doctor_id')";
$conn->query($sql);
$conn->close();
