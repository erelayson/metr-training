<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

		<style type="text/css">
			.error {
				color: #FF0000;
			}
		</style>

		<title>Survey Questionnaire</title>
	</head>
	<body>
		<h1>Survey Questionnaire</h1>

		<!-- Call main form tags using form helpers -->
		<?php echo form_open('form/index'); ?>
			<div class="container">
				<div class="form-group">
					<?= textarea_field('experience', 'How was your experience?', set_value('experience'), form_error('experience', "<span class='error'>", "</span>")); ?>
				</div><br />
				<div class="form-group">
					<label for="targetSelect">Participant Details</label>
					<select class="form-control" id="targetSelect" name="targetSelect" required>
						<?= $form_controller->build_select_options($form_array, $type_id) ?>
					</select>
				</div><br />
				<?php build_dependent_form("targetForm", $form_array, $values, $error_array) ?>
				<br />
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</form>
		<?php echo form_close(); ?>

		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

		<script type="text/javascript">
			function getRequiredArray() {
				return <?= json_encode($required_array) ?>;
			}
		</script>
		<script src="<?php echo base_url('js/dependent_form_selector.js'); ?>"></script>
		
	</body>
</html>
