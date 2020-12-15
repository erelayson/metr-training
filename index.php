<html>
  <head>
    <title>Name Age Adder</title>
    <link rel="stylesheet" href="css/style.css"/>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
  <body>

  <?php

  // Connect to the database
  DEFINE ('DB_USER', 'username');
  DEFINE ('DB_PASSWORD', 'password');
  DEFINE ('DB_HOST', 'localhost');
  DEFINE ('DB_NAME', 'testdb');

  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die('Could not connect to MySQL ' . mysqli_connect_error());

  $affected_rows = 0;

  if ($_GET['submit'] == "Insert") {
    // Add to table
    $query = "INSERT INTO USER (NAME, AGE) VALUES (?, ?)";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "si", $_GET['name'], $_GET['age']);
    $success = "Entry added <br />";

  } elseif ($_GET['submit'] == "Update") {
    // Update table entry
    $query = "UPDATE USER SET NAME = ?, AGE = ? WHERE ID = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "sii", $_GET['name'], $_GET['age'], $_GET['id']);
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
    echo $success;
    mysqli_stmt_close($stmt);
  } else {
    echo "Error Occurred: <br />";
    echo mysqli_error($dbc);
  }

  ?>

  <button id="insertBtn">Insert a Record</button>
  <button id="updateBtn">Update a Record</button> <br/><br/>

  <form id="insertForm" action="index.php" method="GET">
    <table>
      <tr>
        <td>Name:</td>
        <td><input type="text" name="name"/></td>
      </tr>
      <tr>
        <td>Age:</td>
        <td><input type="text" name="age"/></td>
      </tr>
    </table>
    <input type="submit" name="submit" value="Insert"/>
  </form>

  <form id="updateForm" action="index.php" method="GET">
    <table id>
      <tr>
        <td>ID:</td>
        <td><select id="idSelect" name="id"></td>
      </tr>
      <tr>
        <td>Name:</td>
        <td><input type="text" name="name"/></td>
      </tr>
      <tr>
        <td>Age:</td>
        <td><input type="text" name="age"/></td>
      </tr>
    </table>
    <input type="submit" name="submit" value="Update"/>
  </form>

  Current Records:

  <table class="table table-bordered table-hover">
    <thead class="thead">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Age</th>
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
          echo "Couldn't issue database query";
          echo mysqli_error($dbc);
        }
        mysqli_close($dbc);

        ?>
      </form>
    </tbody>
  </table>

  <script src="js/script.js"></script>
  </body>
</html>