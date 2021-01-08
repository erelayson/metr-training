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

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Promo Database</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="promoMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Promo
          </a>
          <div class="dropdown-menu" aria-labelledby="promoMenu">
            <a class="dropdown-item" href="<?= site_url('promo/'); ?>">Index</a>
            <a class="dropdown-item" href="<?= site_url('promo/create'); ?>">Create a promo</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="promoSKUMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Promo SKU
          </a>
          <div class="dropdown-menu" aria-labelledby="promoSKUMenu">
            <a class="dropdown-item" href="<?= site_url('promosku/'); ?>">Index</a>
            <a class="dropdown-item" href="<?= site_url('promosku/create'); ?>">Create a promo SKU</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="serviceSKUMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Service SKU
          </a>
          <div class="dropdown-menu" aria-labelledby="serviceSKUMenu">
            <a class="dropdown-item" href="<?= site_url('servicesku/'); ?>">Index</a>
            <a class="dropdown-item" href="<?= site_url('servicesku/create'); ?>">Create a service SKU</a>
          </div>
        </li>
      </ul>
      <!-- <form class="form-inline my-2 my-lg-0" action="<?= base_url().'index.php/promo/search' ?>" method="post">
        <input class="form-control" type="text" name="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form> -->
    </div>
  </nav>
  <div class="container">
    <h1><?= $title ?></h1>