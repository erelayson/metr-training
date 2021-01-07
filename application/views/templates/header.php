<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Promo Database</title>
  </head>
  <body>

  <script type="text/javascript">
    function searchStr() {
      if (document.getElementById("search").value.trim() != ""){
        window.location.href = "https://192.168.99.101/index.php/promo/search/" + document.getElementById("search").value;
      } else {
        alert("You must enter a search term");
      }
    }
  </script>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="<?= site_url('promo/'); ?>">Promo Database</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" id="createPromo" href="<?= site_url('promo/create'); ?>">Create a promo<span class="sr-only">(current)</span></a>
        </li>
      </ul>
      <div class="form-inline my-2 my-lg-0">
        <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="search">
        <button class="btn btn-outline-success my-2 my-sm-0" id="submit" onclick="searchStr()">Search</button>
      </div>
    </div>
  </nav>
  <div class="container">
    <h1><?= $title ?></h1>