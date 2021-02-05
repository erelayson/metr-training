var tableRowHTML = {};
var formTags = ['input', 'select', 'textarea'];
var tablecounter = 0;

$(document).ready(function(){

	updateDependentForm();

	$('#targetSelect').change(function() {
		updateDependentForm();
	});

  // Save the first row data on page load to and array with tableName:trHTML value pairs
  $("table").each(function(){
    // console.log($(this).attr('name'));
    tableRowHTML[$(this).attr('name')] = $(this).find('tbody>tr:first');
  });

  // console.log(tableRowHTML);

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

	formTags.forEach( function (item) {
		$(item, $('#targetForm')).each(function () {
				$(this).removeAttr('required');
		});
	});

	if (currActive) {
		requiredArray[currActive].forEach(function (arrayItem) {
			$('[name='+arrayItem+']').attr('required', true);
		});
	}
}

function getOptionsUsingAJAX(srcField, url, dstField){
	sourceValue = $(srcField).val();

	$.getJSON({
      url: url,
      success: function(data){
        // console.log(data);
          $.each( data, function( key, val ) {
          	if (key == sourceValue){
          		var optionHTML = "";
          		$.each( val, function( value, displayName ) { 
          			optionHTML += "<option value='" + value + "'>" + displayName + "</option>";
          		});
          		$('[name ='+dstField+']').html(optionHTML);
          	}
				    
				  });
      },
      error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Cannot connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        $('[name='+dstField+']').replaceWith('<br/>'+msg);
      }
  });

}

function addRow(tableName){
  $row = tableRowHTML[tableName].clone();

  tablecounter++;

  formTags.forEach( function (item) {
    $(item, $row).each(function () {
        rowName = $(this).attr('name').replace('0',tablecounter);
        $(this).attr('name', rowName);
    });
  });

  $('table[name='+tableName+']>tbody').append($row);
}

function removeRow(tdElem) {
  $(tdElem).closest('tr').remove();
}