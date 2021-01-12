<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>Survey Questionnaire</title>
  </head>
  <body>
    <h1>Survey Questionnaire</h1>

    <?php echo form_open('form/index'); ?>
      <div class="container">
        <?= validation_errors("<div class='alert alert-danger'>","</div>"); ?>
        <div class="form-group">
          <label for="experience">How was your experience?</label>
          <textarea class="form-control" name="experience" aria-describedby="emailHelp"></textarea>
        </div><br />
        <div class="form-group">
          <label for="targetSelect">Participant Details</label>
          <select class="form-control" id="targetSelect" name="targetSelect" required></select>
        </div><br />
        <div id="targetForm">
          <!-- <?php echo $formJSON; ?> -->
        </div>
        <br />
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

    <script type="text/javascript">
    function fetchJSON() {
      return <?php echo $formJSON; ?>;
    }
    </script>

    <script src="<?php echo base_url(); ?>js/dependent_form_generator.js"></script>
  </body>
</html>
