<!DOCTYPE html>
<html>
<head>
  <title>Add a Service SKU!</title>
  <?php require "head.php" ?>
</head>
<body class="bg-secondary text-light">
  <?php 
    require "testdb_connector.php";
    if (isset($_REQUEST['submit'])) {
      // Add to table
      $query = "INSERT INTO PROMO_SERVICE_SKU (PROMO_SKU, SERVICE_SKU) VALUES (?, ?)";
      $stmt = mysqli_prepare($dbc, $query);
      mysqli_stmt_bind_param($stmt, "ss", $_REQUEST['promo_sku'], $_REQUEST['service_sku']);
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
      // Add to table
      $query = "DELETE FROM PROMO_SERVICE_SKU WHERE PROMO_SKU = ? AND SERVICE_SKU = ?";
      $stmt = mysqli_prepare($dbc, $query);
      mysqli_stmt_bind_param($stmt, "ss", $_REQUEST['promo_sku'], $_REQUEST['service_sku']);
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
    }
  ?>
  <div class="col text-center">
    <a href="create_promo.php" class="btn btn-light">Create a Promo</a>
    <a href="create_sku.php" class="btn btn-light" disabled>Create a Promo SKU</a>
    <button class="btn btn-light" disabled>Add a Service SKU</button> <br/><br/>
    <form id="addSKU" action="link_promo_service_sku.php" method="POST">
      <table>
        <tr>
          <td>Promo SKU:</td>
          <td><select id="promoSelect" name="promo_sku" required>
            <?php

            $query = "SELECT KEYWORD FROM PROMO_SKU";
            $response = @mysqli_query($dbc, $query);

            if ($response) {
              while($row = mysqli_fetch_array($response)) {
                echo "<option>".$row['KEYWORD']."</option>";
              }
            } else {
              echo "<div class='alert alert-danger text-center' role='alert'>
                      Couldn't issue database query: <span class='font-weight-bold'>" . mysqli_error($dbc) . 
                   "</span></div>";
            }

            ?>
          </select></td>
        </tr>
        <tr>
          <td>Service SKU:</td>
          <td><select id="serviceSelect" name="service_sku" required>
            <?php

            $query = "SELECT KEYWORD FROM SERVICE_SKU";
            $response = @mysqli_query($dbc, $query);

            if ($response) {
              while($row = mysqli_fetch_array($response)) {
                echo "<option>".$row['KEYWORD']."</option>";
              }
            } else {
              echo "<div class='alert alert-danger text-center' role='alert'>
                      Couldn't issue database query: <span class='font-weight-bold'>" . mysqli_error($dbc) . 
                   "</span></div>";
            }

            ?>
          </select></td>
        </tr>
      </table><br/>
      <input type="submit" name="submit" value="Add" class="btn btn-light" />
    </form>
  </div>

  <br/>

  <table class="table table-bordered table-hover table-secondary">
    <caption class="text-white">Current Records</caption>
    <thead class="thead-dark">
      <tr>
        <th>Promo SKU</th>
        <th>Service SKU</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php

      $query = "SELECT PROMO_SKU, SERVICE_SKU, STATUS FROM PROMO_SERVICE_SKU";
      $response = @mysqli_query($dbc, $query);

      if ($response) {
        while($row = mysqli_fetch_array($response)) {
          $promo_sku = $row['PROMO_SKU'];
          $service_sku = $row['SERVICE_SKU'];
          $delete = "";
          if ($row['STATUS'] == 0) {
            $delete = "<button type='submit' name='delete' value='delete'>Delete</button>";
          }
          echo "<form action='link_promo_service_sku.php' method='POST'>
                  <tr>
                    <td><input type='hidden' name='promo_sku' value='$promo_sku'>$promo_sku</td>
                    <td><input type='hidden' name='service_sku' value='$service_sku'>$service_sku</td>
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