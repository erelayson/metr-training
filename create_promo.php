<!DOCTYPE html>
<html>
<head>
	<title>Create a Promo!</title>
  <?php require "head.php" ?>
</head>
<body class="bg-secondary text-light">
  <?php 
    require "testdb_connector.php";
    if (isset($_REQUEST['submit'])) {
      // Add to table
      $query = "INSERT INTO PROMO (KEYWORD, NAME, DESCRIPTION, EXPIRY, RENEWAL) VALUES (?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($dbc, $query);
      mysqli_stmt_bind_param($stmt, "ssssi", $_REQUEST['keyword'],  $_REQUEST['name'], $_REQUEST['desc'], $_REQUEST['expiry'], $_REQUEST['renewal']);
      mysqli_stmt_execute($stmt);
      $affected_rows = mysqli_stmt_affected_rows($stmt);

      if($affected_rows == 1) {
        if ($success) {
          echo "<div class='alert alert-success text-center' role='alert'>
                  Entry added <br />
                </div>";
        }
        mysqli_stmt_close($stmt);

      } else {
        echo "<div class='alert alert-danger text-center' role='alert'>
                Error Occurred <br/><span class='font-weight-bold'>" . mysqli_error($dbc) . 
             "</span></div>";
      }
    } else if (isset($_REQUEST['delete'])) {
      $query = "DELETE FROM PROMO WHERE KEYWORD = ?";
      $stmt = mysqli_prepare($dbc, $query);
      mysqli_stmt_bind_param($stmt, "s", $_REQUEST['keyword']);
      mysqli_stmt_execute($stmt);
      $affected_rows = mysqli_stmt_affected_rows($stmt);

      if($affected_rows == 1) {
        if ($success) {
          echo "<div class='alert alert-success text-center' role='alert'>
                  Entry deleted <br />
                </div>";
        }
        mysqli_stmt_close($stmt);

      } else {
        echo "<div class='alert alert-danger text-center' role='alert'>
                Error Occurred <br/><span class='font-weight-bold'>" . mysqli_error($dbc) . 
             "</span></div>";
      }
    } else if (isset($_REQUEST['toggle'])) {
      $query = "UPDATE PROMO SET STATUS = ? WHERE KEYWORD = ?";
      $stmt = mysqli_prepare($dbc, $query);
      if ($_REQUEST['toggle'] == 0) {
        $status = 1;
      } else {
        $status = 0;
      }
      mysqli_stmt_bind_param($stmt, "is", $status, $_REQUEST['keyword']);
      mysqli_stmt_execute($stmt);
      $affected_rows = mysqli_stmt_affected_rows($stmt);
      mysqli_stmt_close($stmt);

      // If toggling to Active state, set all related Promo SKUs and Service SKUs as Active
      if ($status == 1) {
        $query = "UPDATE PROMO SET ACTIVATED = 1 WHERE KEYWORD = ?";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, "s", $_REQUEST['keyword']);
        mysqli_stmt_execute($stmt);
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        $query = "UPDATE PROMO_SKU SET STATUS = 1 WHERE PROMO = ?";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, "s", $_REQUEST['keyword']);
        mysqli_stmt_execute($stmt);
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        $query = "UPDATE PROMO_SERVICE_SKU SET STATUS = 1 WHERE PROMO_SKU IN (SELECT KEYWORD FROM PROMO_SKU where PROMO=?)";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, "s", $_REQUEST['keyword']);
        mysqli_stmt_execute($stmt);
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
      }
    }
  ?>
  <div class="col text-center">
    <button class="btn btn-light" disabled>Create a Promo</button>
    <a href="create_sku.php" class="btn btn-light">Create a Promo SKU</a>
    <a href="link_promo_service_sku.php" class="btn btn-light">Add a Service SKU</a> <br/><br/>
    <form id="createPromo" action="create_promo.php" method="POST">
      <table>
        <tr>
          <td>Keyword:</td>
          <td><input type="text" name="keyword" required oninput="this.value = this.value.toUpperCase()"/></td>
        </tr>
        <tr>
          <td>Name:</td>
          <td><input type="text" name="name" required /></td>
        </tr>
        <tr>
          <td>Description:</td>
          <td><textarea name="desc" required></textarea></td>
        </tr>
        <tr>
          <td>Expiry:</td>
          <td><input type="datetime-local" name="expiry" required /></td>
        </tr>
        <tr>
          <td>Renewal:</td>
          <td><input type="text" name="renewal" required min="0" /></td>
        </tr>
      </table><br/>
      <input type="submit" name="submit" value="Create" class="btn btn-light" />
    </form>
  </div>

  <br/>

  <table class="table table-bordered table-hover table-secondary">
    <caption class="text-white">Current Records</caption>
    <thead class="thead-dark">
      <tr>
        <th>Keyword</th>
        <th>Name</th>
        <th>Description</th>
        <th>Expiry</th>
        <th>Renewal</th>
        <th>Status</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>

        <?php

        $query = "SELECT KEYWORD, NAME, DESCRIPTION, EXPIRY, RENEWAL, STATUS, ACTIVATED FROM PROMO";
        $response = @mysqli_query($dbc, $query);

        if ($response) {
          while($row = mysqli_fetch_array($response)) {
            $keyword = $row['KEYWORD'];
            if ($row['STATUS']) {
              $status = "Active";
            } else {
              $status = "Inactive";
            }
            $delete = "";
            if ($row['ACTIVATED'] == 0) {
              $delete = "<button type='submit' name='delete' value='delete'>Delete</button>";
            }
            echo "<form action='create_promo.php' method='POST'>
                  <tr>
                    <td><input type='hidden' name='keyword' value='$keyword'>$keyword</td>
                    <td>" . $row['NAME'] . "</td>
                    <td>" . $row['DESCRIPTION'] . "</td>
                    <td>" . $row['EXPIRY'] . "</td>
                    <td>" . $row['RENEWAL'] . "</td>
                    <td>" . $status . "</td>
                    <td><button type='submit' name='toggle' value=". $row['STATUS'] .">Toggle Status</button></td>
                    <td>$delete</td>
                  </tr>
                  </form>";
          }
        } else {
          echo "<div class='alert alert-danger text-center' role='alert'>
                  Couldn't issue database query: <span class='font-weight-bold'>" . mysqli_error($dbc) . 
               "</span></div>";
        }
        mysqli_close($dbc);

        ?>

    </tbody>
  </table>
</body>
</html>