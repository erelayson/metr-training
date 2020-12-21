$(document).ready(function(){

	$("#insertBtn").click(function() {
		$("#insertForm").show();
		$("#updateForm").hide();
		$(this).attr("disabled", true);
		$("#updateBtn").removeAttr("disabled");
	})

	$("#updateBtn").click(function() {
		$("#insertForm").hide();
		$("#updateForm").show();
		$(this).attr("disabled", true);
		$("#insertBtn").removeAttr("disabled");
	})

	optionList = "";
	$(".id").each(function() {
		optionList += "<option>" + $(this).text() + "</option>";
	})

	$("#idSelect").html(optionList);
});