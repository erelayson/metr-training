<!DOCTYPE html>
<html>
<head>
  <title>Create a Promo SKU!</title>
  <?php require "head.php" ?>
</head>
<body class="bg-secondary text-light">
  <?php 
    require "testdb_connector.php";
    if (isset($_REQUEST['submit'])) {
      // Add to table
      $query = "INSERT INTO PROMO_SKU (PROMO, KEYWORD, NAME, DESCRIPTION, PRICE, STATUS) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($dbc, $query);
      mysqli_stmt_bind_param($stmt, "ssssdi", $_REQUEST['promo'], $_REQUEST['keyword'],  $_REQUEST['name'], $_REQUEST['desc'], $_REQUEST['price'], $activated);
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
      $query = "DELETE FROM PROMO_SKU WHERE KEYWORD = ?";
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
    }
  ?>

  <div class="col text-center">
    <a href="create_promo.php" class="btn btn-light">Create a Promo</a>
    <button href="create_sku.php" class="btn btn-light" disabled>Create a Promo SKU</button>
    <a href="link_promo_service_sku.php" class="btn btn-light">Add a Service SKU</a> <br/><br/>
    <form id="createSKU" action="create_sku.php" method="POST">
      <table>
        <tr>
          <td>Promo:</td>
          <td><select id="promoSelect" name="promo" required>
            <?php

            $query = "SELECT KEYWORD FROM PROMO";
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
          <td>Price:</td>
          <td><input type="number" name="price" required /></td>
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
        <th>Promo</th>
        <th>Keyword</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th></th>
      </tr>
    </thead>
    <tbody>

      <?php

      $query = "SELECT PROMO, KEYWORD, NAME, DESCRIPTION, PRICE, STATUS FROM PROMO_SKU";
      $response = @mysqli_query($dbc, $query);

      if ($response) {
        while($row = mysqli_fetch_array($response)) {
          $keyword = $row['KEYWORD'];
          $delete = "";
          if ($row['STATUS'] == 0) {
            $delete = "<button type='submit' name='delete' value='delete'>Delete</button>";
          }
          echo "<form action='create_sku.php' method='POST'>
                  <tr>
                    <td>" . $row['PROMO'] . "</td>
                    <td><input type='hidden' name='keyword' value='$keyword'>$keyword</td>
                    <td>" . $row['NAME'] . "</td>
                    <td>" . $row['DESCRIPTION'] . "</td>
                    <td>" . $row['PRICE'] . "</td>
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