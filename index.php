<!-- patients.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Patients Management</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <style>
    body {
      background-color: #f8f9fa;
      padding: 40px;
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
    }
    .dataTables_filter {
      display: none !important;
    }
    .alert {
      display: none;
    }
  </style>
</head>
<body>

<div class="container">
  <h1>Patient Records</h1>

  <div id="message" class="alert alert-success"></div>

  <div class="d-flex align-items-center mb-3" style="gap: 10px;">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Patient</button>

    <select id="filterColumn" class="form-select" style="width: auto;">
      <option value="1">Search by Full Name</option>
      <option value="4">Search by Phone Number</option>
      <option value="6">Search by Diagnosis</option>
    </select>

    <input type="text" id="searchBox" class="form-control" placeholder="Type to search..." style="width: 250px;" />
  </div>

  <table id="patientsTable" class="display table table-striped table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Phone Number</th>
        <th>Address</th>
        <th>Diagnosis</th>
        <th>Admission Date</th>
        <th>Discharge Date</th>
        <th>Doctor ID</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="patientsBody">
      <!-- Data loaded via AJAX -->
    </tbody>
  </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="addForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
      <?php
    $fields = [
      'Full Name' => 'full_name',
      'Age' => 'age',
      'Gender' => 'gender',
      'Phone Number' => 'phone_number',
      'Address' => 'address',
      'Diagnosis' => 'diagnosis',
      'Admission Date' => 'admission_date',
      'Discharge Date' => 'discharge_date',
      'Doctor ID' => 'doctor_id',
    ];
    foreach ($fields as $label => $name) {
      echo "<div class='col-md-6'><label class='form-label'>$label</label>";

      if ($name === 'gender') {
        echo "<select name='$name' class='form-select' required>
                <option value=''>Select Gender</option>
                <option value='Male'>Male</option>
                <option value='Female'>Female</option>
              </select>";
      } elseif (in_array($name, ['admission_date', 'discharge_date'])) {
        echo "<input type='date' name='$name' class='form-control' required>";
      } else {
        echo "<input type='text' name='$name' class='form-control' required>";
      }

      echo "</div>";
    }
  ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <input type="hidden" name="id" />
        <?php
    foreach ($fields as $label => $name) {
      echo "<div class='col-md-6'><label class='form-label'>$label</label>";

      if ($name === 'gender') {
        echo "<select name='$name' class='form-select' required>
                <option disabled value=''>Select Gender</option>
                <option value='Male'>Male</option>
                <option value='Female'>Female</option>
              </select>";
      } elseif (in_array($name, ['admission_date', 'discharge_date'])) {
        echo "<input type='date' name='$name' class='form-control' required>";
      } else {
        echo "<input type='text' name='$name' class='form-control' required>";
      }

      echo "</div>";
    }
  ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning">Update</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  let table;

  function showMessage(msg) {
    $('#message').text(msg).fadeIn();
    setTimeout(() => $('#message').fadeOut(), 3000);
  }

  function loadPatients() {
    if ($.fn.DataTable.isDataTable('#patientsTable')) {
      $('#patientsTable').DataTable().destroy();
    }
    $('#patientsBody').html('<tr><td colspan="11" class="text-center">Loading...</td></tr>');
    $.get("fetch_patients.php", function(data) {
      $('#patientsBody').html(data);

      table = $('#patientsTable').DataTable({
        pageLength: 10
      });
    });
  }

  $(document).ready(function() {
    loadPatients();

    $('#searchBox, #filterColumn').on('input change', function() {
      let value = $('#searchBox').val().toLowerCase();
      let colIndex = parseInt($('#filterColumn').val());

      table.rows().every(function() {
        let cellData = this.data()[colIndex];
        if (cellData.toString().toLowerCase().includes(value)) {
          $(this.node()).show();
        } else {
          $(this.node()).hide();
        }
      });
    });

    $('#addForm').on('submit', function(e) {
      e.preventDefault();
      $.post("add_patient.php", $(this).serialize(), function(res) {
        $('#addModal').modal('hide');
        $('#addForm')[0].reset();
        loadPatients();
        showMessage("Patient added successfully.");
      });
    });

    $(document).on('click', '.editBtn', function() {
      let row = $(this).closest('tr').children();
      $('#editForm input[name=id]').val(row.eq(0).text());
      <?php
$index = 1;
foreach ($fields as $label => $name):
?>
  <?php if ($name === 'gender'): ?>
    $('#editForm select[name=<?= $name ?>]').val(row.eq(<?= $index ?>).text());
  <?php elseif (in_array($name, ['admission_date', 'discharge_date'])): ?>
    $('#editForm input[name=<?= $name ?>]').val(row.eq(<?= $index ?>).text());
  <?php else: ?>
    $('#editForm input[name=<?= $name ?>]').val(row.eq(<?= $index ?>).text());
  <?php endif; ?>
<?php
$index++;
endforeach;
?>

      $('#editModal').modal('show');
    });

    $('#editForm').on('submit', function(e) {
      e.preventDefault();
      $.post("update_patient.php", $(this).serialize(), function(res) {
        $('#editModal').modal('hide');
        loadPatients();
        showMessage("Patient updated successfully.");
      });
    });

    // Delete button
    $(document).on('click', '.deleteBtn', function() {
      if (confirm("Are you sure you want to delete this patient?")) {
        let id = $(this).data('id');
        $.post("delete_patient.php", { id: id }, function() {
          loadPatients();
          showMessage("Patient deleted successfully.");
        });
      }
    });

    $(document).on('click', '.printBtn', function() {
      let row = $(this).closest('tr').children();
      let printWindow = window.open('', '', 'width=600,height=700');
      let htmlContent = `
        <html>
        <head>
          <title>Print Patient Details</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
          <style>
            body { padding: 20px; font-family: Arial, sans-serif; }
            h2 { margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { padding: 8px; text-align: left; border: 1px solid #dee2e6; }
            button { margin-top: 20px; }
          </style>
        </head>
        <body>
          <h2>Patient Details</h2>
          <table class="table table-bordered">
            <tr><th>ID</th><td>${row.eq(0).text()}</td></tr>
            <tr><th>Full Name</th><td>${row.eq(1).text()}</td></tr>
            <tr><th>Age</th><td>${row.eq(2).text()}</td></tr>
            <tr><th>Gender</th><td>${row.eq(3).text()}</td></tr>
            <tr><th>Phone Number</th><td>${row.eq(4).text()}</td></tr>
            <tr><th>Address</th><td>${row.eq(5).text()}</td></tr>
            <tr><th>Diagnosis</th><td>${row.eq(6).text()}</td></tr>
            <tr><th>Admission Date</th><td>${row.eq(7).text()}</td></tr>
            <tr><th>Discharge Date</th><td>${row.eq(8).text()}</td></tr>
            <tr><th>Doctor ID</th><td>${row.eq(9).text()}</td></tr>
          </table>
          <button onclick="window.print()" class="btn btn-primary">Print</button>
        </body>
        </html>
      `;
      printWindow.document.write(htmlContent);
      printWindow.document.close();
    });
  });
</script>

</body>
</html>
