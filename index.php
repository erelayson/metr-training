<html>
  <head>
    <title>Name Age CRUD</title>
    <link rel="stylesheet" href="css/style.css"/>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
  <body class="bg-secondary text-light">

  <?php
  require "testdb_connector.php";

  $affected_rows = 0;
  $name = filter_var($_GET['name'], FILTER_SANITIZE_STRING);
  $age = filter_var($_GET['age'], FILTER_SANITIZE_NUMBER_INT);

  if ($_GET['submit'] == "Insert") {
    // Add to table
    $query = "INSERT INTO USER (NAME, AGE) VALUES (?, ?)";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "si", $name, $age);
    $success = "Entry added <br />";

  } elseif ($_GET['submit'] == "Update") {
    // Update table entry
    $query = "UPDATE USER SET NAME = ?, AGE = ? WHERE ID = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "sii", $name, $age, $_GET['id']);
    $success = "Entry updated <br />";

  } elseif (isset($_GET['delete'])) {
    // Delete from table
    $query = "DELETE FROM USER WHERE ID = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "i", $_GET['delete']);
    $success = "Entry deleted <br />";
  }

  if (isset($_GET['submit']) or isset($_GET['delete'])) {
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);
  }

  if($affected_rows == 1 or $success == NULL) {
    if ($success) {
      echo "<div class='alert alert-success text-center' role='alert'>
              $success
            </div>";
    }
    mysqli_stmt_close($stmt);

  } else {
    echo "<div class='alert alert-danger text-center' role='alert'>
            Error Occurred <br/><span class='font-weight-bold'>" . mysqli_error($dbc) . 
         "</span></div>";
  }

  ?>

  <div class="col text-center">
    <button id="insertBtn" class="btn btn-light" disabled>Insert a Record</button>
    <button id="updateBtn" class="btn btn-light">Update a Record</button> <br/><br/>

    <form id="insertForm" action="index.php" method="GET">
      <table>
        <tr>
          <td>Name:</td>
          <td><input type="text" name="name" required /></td>
        </tr>
        <tr>
          <td>Age:</td>
          <td><input type="text" name="age" required min="1" /></td>
        </tr>
      </table><br/>
      <input type="submit" name="submit" value="Insert" class="btn btn-light" />
    </form>

    <form id="updateForm" action="index.php" method="GET">
      <table id>
        <tr>
          <td>ID:</td>
          <td><select id="idSelect" name="id"></select></td>
        </tr>
        <tr>
          <td>Name:</td>
          <td><input type="text" name="name" required/></td>
        </tr>
        <tr>
          <td>Age:</td>
          <td><input type="text" name="age" required min="1" /></td>
        </tr>
      </table><br/>
      <input type="submit" name="submit" value="Update" class="btn btn-light"/>
    </form>
  </div>

  <table class="table table-bordered table-hover table-secondary">
    <caption class="text-white">Current Records</caption>
    <thead class="thead-dark">
      <tr>
        <th class="col-2">ID</th>
        <th class="col-6">Name</th>
        <th class="col-2">Age</th>
        <th class="col-2"></th>
      </tr>
    </thead>
    <tbody>
      <form action="index.php" method="GET">
        
        <?php

        $query = "SELECT ID, NAME, AGE FROM USER";
        $response = @mysqli_query($dbc, $query);

        if ($response) {
          while($row = mysqli_fetch_array($response)) {
            $id = $row['ID'];
            echo "<tr>
                    <td>
                      <span class='id'>" . $id . "</span>
                    </td>
                    <td>" . $row['NAME'] . "</td>
                    <td>" . $row['AGE'] . "</td>
                    <td>" . "<button type='submit' name='delete' value='$id'>Delete</button>" . "</td>
                  </tr>";
          }
        } else {
          echo "<div class='alert alert-danger text-center' role='alert'>
                  Couldn't issue database query: <span class='font-weight-bold'>" . mysqli_error($dbc) . 
               "</span></div>";
        }
        mysqli_close($dbc);

        ?>

      </form>
    </tbody>
  </table>

  <script src="js/script.js"></script>
  </body>
</html>