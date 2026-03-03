<?php
$conn = mysqli_connect("db", "root", "1234", "patient_db");

$sql = "SELECT * FROM patients ORDER BY id DESC";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
  echo "<tr>";
  echo "<td>{$row['id']}</td>";
  echo "<td>{$row['full_name']}</td>";
  echo "<td>{$row['age']}</td>";
  echo "<td>{$row['gender']}</td>";
  echo "<td>{$row['phone_number']}</td>";
  echo "<td>{$row['address']}</td>";
  echo "<td>{$row['diagnosis']}</td>";
  echo "<td>{$row['admission_date']}</td>";
  echo "<td>{$row['discharge_date']}</td>";
  echo "<td>{$row['doctor_id']}</td>";
  echo "<td class='text-center'>
          <button class='btn btn-sm btn-outline-primary printBtn' title='Print'><i class='bi bi-printer'></i></button>
          <button class='btn btn-sm btn-outline-warning editBtn' title='Edit'><i class='bi bi-pencil-square'></i></button>
          <button class='btn btn-sm btn-outline-danger deleteBtn' data-id='{$row['id']}' title='Delete'><i class='bi bi-trash'></i></button>
        </td>";
  echo "</tr>";
}

$conn->close();
