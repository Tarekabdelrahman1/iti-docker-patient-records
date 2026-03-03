<?php
$conn = mysqli_connect("db", "root", "1234", "patient_db");

$id = $_POST['id'];

$sql = "DELETE FROM patients WHERE id='$id'";
$conn->query($sql);
$conn->close();
