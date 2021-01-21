$(document).ready(function(){

	updateDependentForm();

	$('#targetSelect').change(function() {
		updateDependentForm();
	});

});

function updateDependentForm() {

	$('#targetForm > div').each(function () { 
		$(this).hide();
	});

	var currActive = $('#targetSelect').val();
	if (currActive) {
		$('div[id="dependentForm'+currActive+'"]').show();
	}

	// Comment out this portion to test backend validation
	// updateRequiredElements(currActive, getRequiredArray());

}

function updateRequiredElements(currActive, requiredArray) {

	var formTags = ['input', 'select', 'textarea'];

	formTags.forEach( function (item) {
		$(item, $('#targetForm')).each(function () {
		    $(this).removeAttr('required');
		});
	});

	if (currActive) {
		requiredArray[currActive].forEach(function (arrayItem) {
			$('[name='+arrayItem+"]").attr('required', true);
		});
	}
}