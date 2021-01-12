$(document).ready(function(){

  var formJSON = fetchJSON();

  // Construct the select dropdown options
  var optionHTML = "<option disabled selected value> -- select an option -- </option>";
  for (var i = 0; i < formJSON.length; i++) {
    // console.log(formJSON[i]);
    optionHTML += "<option value='" + formJSON[i].type_id + "'>" + formJSON[i].name + "</option>";
  }
  $('#targetSelect').html(optionHTML);

  // Change the dependent form if the select dropdown option has changed
  $('#targetSelect').change(function() {
    $('#targetForm').empty();
    var value = $('#targetSelect').val();
    var index = formJSON.findIndex(obj => obj.type_id==value);
    var dependentFormHTML = "";

    for (var i = 0; i < formJSON[index].params.length; i++) {
      // console.log(formJSON[index].params[i]);
      var name = formJSON[index].params[i].name;
      var display_name = formJSON[index].params[i].display_name;
      var type = formJSON[index].params[i].type;
      var values = formJSON[index].params[i].values;
      var required = "";
      if (formJSON[index].params[i].is_required) {
        required = "required";
      }
      dependentFormHTML += "<div class='form-group'><label for='" + name + "'>" + display_name + "</label>";

      if (type == "textarea") {
        dependentFormHTML += "<textarea class='form-control' name='" + name + "' "+ required +"></textarea>";

      } else if (type == "enum") {
        if (values) {
          keyVal = Object.keys(values);
          if (keyVal.length < 10) {
            keyVal.forEach(function(key) {
              dependentFormHTML += radioWrapper(name, key, values[key], required);
            })

          } else {
            dependentFormHTML += "<select class='form-control' name='" + name + "' " + required + ">";
            keyVal.forEach(function(key) {
              dependentFormHTML += "<option value='" + key + "'>" + values[key] + "</option>";
            })
            dependentFormHTML += "</select>";
          }

        } else {
          dependentFormHTML += radioWrapper(name, false, "Yes", required);
          dependentFormHTML += radioWrapper(name, true, "No", required);
        }

      } else {
        dependentFormHTML += "<input class='form-control' name='" + name + "' type='" + type + "' "+ required +"/>"
      }

      dependentFormHTML += "</div><br />";
    }

    $('#targetForm').html(dependentFormHTML);
  });
});

function radioWrapper(name, value, string, required) {
  return "<div class='form-check'><input class='form-check-input' type='radio' name='" + name + "' value='" + value + "' " + required + "><label class='form-check-label' for='" + name + "'>" + string + "</label></div>";
}