<?php
$conn = mysqli_connect("localhost", "root", "", "people");

$id = $_POST['id'];

$sql = "DELETE FROM patients WHERE id='$id'";
$conn->query($sql);
$conn->close();
