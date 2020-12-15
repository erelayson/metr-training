$(document).ready(function(){

	$("#insertBtn").click(function() {
		$("#insertForm").show();
		$("#updateForm").hide();
	})

	$("#updateBtn").click(function() {
		$("#insertForm").hide();
		$("#updateForm").show();
	})

	optionList = "";
	$(".id").each(function() {
		optionList += "<option>" + $(this).text() + "</option>";
	})

	$("#idSelect").html(optionList);
});