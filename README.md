# Dependent Form Helper

## JSON structure for the dependent form data
```javascript
[
   {
      "name":"human-readable type name",
      "type_id":"id of type",
      "params":[
         {
            "target_node":[
               "root object of type in nf_legacy_service_t format:(model.property)"
            ],
            "name":"input name attribute",
            "display_name":"input label",
            "type":"textarea|enum|bool|radio|dropdown|date|datetime|string|number|integer|float|password|time|file_upload|display_only|hidden|list|table",
            "validation":"CI-compatible validation",
            "source":"model_name.function_name | controller_name.function_name | url",
            "source_type":"model | controller | AJAX | AJAX_dynamic", (AJAX_dynamic must be placed under the destination field)
            "is_required":"true|false",
            "values":{
               "key":"value pairs for enum | radio | dropdown types"
            },
            "params":{ 
                "similar to the parent params node, for table types"
            }
	    "AJAX_url":"url to fetch the JSON dynamic options, must be placed under the source field",
	    "update_field_name":"the name of the dynamic destination field, must be placed under the source field",
	    "depends_on":"the name of the source field, must be placed under the dynamic destination field"
         }
      ],
      "additional_params":[
         {
            "name":"human-readable additional_params name",
	    "is_tabular":"true|false",
	    "actions":{
	    	"create":"callback function for create",
	    	"edit":"callback function for edit",
	    	"delete":"callback function for delete",
	    	"label":"other callback function reference"
	    },
            "params":{
               "similar to the main params node, for additional_params"
            },
	    "conditions" : {
	    	"all":"comma delimited list of additional_param field names to be displayed at all times",
		"main_param_name=value&main_param_name=value":"comma delimited list of additional_param field names if the key condition(s) is fulfilled",
	    }
         }
      ]
   }
]
```


## dependent_form_helper.php

### build_required_array 
##### — restructure the dependent form JSON so that it can be easily read by dependent_form_selector.js

#### Description
```php 
build_required_array(array $form_array)
```

#### Parameters
##### form_array: the parsed dependent form JSON file in array form 

#### Return Values
##### An array using the type_ids as the keys and an array containing the names of the required parameters as the value.


### set_dependent_form_validation_rules 
##### — loops through the parameters of the selected type_id (after submission of the form) and makes use of CI Form Validation to set the rules

#### Description
```php
set_dependent_form_validation_rules(array $params, array $conditions = array(), array $form_data = array(), array $values = array(), bool $is_table_cell = FALSE, string $table_name = "")
```

#### Parameters
##### params: an array representation of the "params" key of the dependent form JSON
##### conditions: an array containing the value of the conditions key under the selected additional parameter
##### form_data: an array containing column name - value pairs of the main form
##### values: the $_POST global
##### is_table_cell: set to true if the params array is under a table type parameter (only for recursive calls)
##### table_name: the name of the table type parameter (only for recursive calls)

#### Return Values
##### returns FALSE if choice retrieval fails, else returns TRUE


### get_default_rules
##### — called by set_dependent_form_validation_rules if the current parameter being checked does not have a "validation" key-value pair

#### Description
```php
get_default_rules(string $type, array $choices = array(), string $data_type = NULL)
```

#### Parameters
##### type: the value under the "type" key of the dependent form JSON
##### choices: an array containing the possible options for the enum/radio/dropdown type
##### list_data_type: if the value under the "type" key is list, refer to this instead for the type

#### Return Values
type | return value
------------ | -------------
DEPFORM_TYPE_ENUM/DEPFORM_TYPE_RADIO/DEPFORM_TYPE_DROPDOWN | in_list[choices]
DEPFORM_TYPE_BOOLEAN | in_list[0,1]
DEPFORM_TYPE_NUMBER | numeric, greater_than_equal_to[-PHP_FLOAT_MAX], less_than_equal_to[PHP_FLOAT_MAX]
DEPFORM_TYPE_FLOAT | decimal, greater_than_equal_to[-PHP_FLOAT_MAX], less_than_equal_to[PHP_FLOAT_MAX]
DEPFORM_TYPE_INT | integer, greater_than_equal_to[PHP_INT_MIN], less_than_equal_to[PHP_INT_MAX]
DEPFORM_TYPE_PASSWORD | password_strength_check
DEPFORM_TYPE_DATE | date_valid
DEPFORM_TYPE_TIME | time_valid
DEPFORM_TYPE_DATETIME | datetime_valid
default | empty string


### retrieve_error_messages
##### — loops through the parameters of the selected type_id (after submission of the form) and makes use of form_error() to retrieve the error messages and append them to an array

#### Description
```php
retrieve_error_messages(array $params, array $values = array(), bool $is_table_cell = FALSE, string $table_name = "")
```

#### Parameters
##### params: an array representation of the "params" key of the dependent form JSON
##### values: the $_POST global
##### is_table_cell: set to true if the params array is under a table type parameter (only for recursive calls)
##### table_name: the name of the table type parameter (only for recursive calls)

#### Return Values
##### The array containing the parameter names and their corresponmding error messages as key-value pairs.


### build_dependent_form
##### — echoes hidden divs for each of the forms in the dependent JSON file, then calls generate_HTML_from_params for their parameters

#### Description
```php
build_dependent_form(string $id, array $form_array, array $post_value_array = array(), array $validation_error_array = array())
```

#### Parameters
##### id: a user defined string to be used as the id attribute for the dependent form div wrapper
##### form_array: the parsed dependent form JSON file in array form
##### post_value_array: the $_POST global
##### validation_error_array: The array containing the parameter names and their corresponmding error messages as key-value pairs.

#### Return Values
##### No value is returned.


### generate_HTML_from_params
##### — loops through the params key-value pairs, and passes them to to_tag() to build the HTML of the dependent form

#### Description
```php
generate_HTML_from_params(array $params, array $post_value_array = array(), array $validation_error_array = array(), bool $is_table_cell = FALSE, string $table_name = "")
```

#### Parameters
##### params: an array representation of the values of the "params" key of the dependent form JSON
##### post_value_array: the $_POST global
##### validation_error_array: The array containing the parameter names and their corresponmding error messages as key-value pairs.
##### is_table_cell: set to true if the params array is under a table type parameter (only for recursive calls)
##### table_name: the name of the table type parameter (only for recursive calls)

#### Return Values
##### No value is returned.


### get_options_from_source
##### — uses the source_type (model/controller/AJAX/AJAX_dynamic) to determine how to retrieve the options of a paramter with type enum/radio/dropdown

#### Description
```php
get_options_from_source(string $source_type, array $param_value, mixed $depends_on_value = NULL, string $AJAX_url = "")
```

#### Parameters
##### source_type: the value under the "source_type" key of the dependent form JSON
##### param_value: an array representation of the values of the "params" key of the dependent form JSON
##### depends_on_value: if source_type is AJAX_dynamic, this is the value selected in the source field 
##### AJAX_url: the url to fetch the JSON dynamic options 

#### Return Values
```php
array(
	'success' : true|false,
      	'errmsg' : string,
      	'data' : array($data)
);
```
##### errmsg: contains the error message string if choice retrieval fails (success = false)
##### data: contains the option keys and their corresponmding display names as key-value pairs (success = true)


### to_tag
##### — passes the necessary parameters to a tag builder function based on its type 

#### Description
```php
to_tag(string $name, string $display_name, string $type, int $cardinality, bool $is_required, array $choices = array(), string $value = "", string $error = "", array $AJAX_params = array(), bool $build_label = TRUE)
```

#### Parameters
##### name: the value under the "name" key of the dependent form JSON
##### display_name: the value under the "display_name" key of the dependent form JSON
##### type: the value under the "type" key of the dependent form JSON
##### cardinality: the value under the "cardinality" key of the dependent form JSON
##### is_required: the value under the "is_required" key of the dependent form JSON
##### choices: the value under the "values" key of the dependent form JSON. If not found, the return value of get_options_from_source
##### value: the value retrieved from the $_POST global (previously submitted value)
##### error: the error string retrieved from form_error()
##### AJAX_params: an array containing the AJAX url and the dynamic destination field name
##### build_label: if false, do not show the label for the tags (used for table cells)

#### Function Mapping
type | builder call
------------ | -------------
DEPFORM_TYPE_TEXTAREA | textarea_field
DEPFORM_TYPE_ENUM | radio_field (if choices < DEPFORM_TYPE_DROPDOWN_MIN_OPTIONS), else dropdown_field
DEPFORM_TYPE_BOOLEAN | boolean_field
DEPFORM_TYPE_RADIO | radio_field
DEPFORM_TYPE_DROPDOWN | dropdown_field
DEPFORM_TYPE_DATE | date_field
DEPFORM_TYPE_DATETIME | datetime_field
DEPFORM_TYPE_STRING | text_field
DEPFORM_TYPE_NUMBER | number_field
DEPFORM_TYPE_INT | number_field
DEPFORM_TYPE_FLOAT | number_field
DEPFORM_TYPE_PASSWORD | password_field
DEPFORM_TYPE_TIME | time_field
DEPFORM_TYPE_FILE_UPLOAD | file_upload_field
DEPFORM_TYPE_DISPLAY_ONLY | display_only_field
DEPFORM_TYPE_HIDDEN | hidden_field
DEPFORM_TYPE_LIST | list_field

#### Return Values
##### No value is returned.


## dependent_form_selector.js

### updateDependentForm 
##### — hide all div children of the div with id "targetForm", then show the div whose id is equal to the value of the currently selected option in the dropdown with id "targetSelect"

#### Description
```js 
updateDependentForm()
```

#### Parameters
##### No parameters are passed. 

#### Return Values
##### No value is returned.


### updateRequiredElements 
##### — remove the required attribute of all tags in the formTags array (input, select, and textarea), traverse through the requiredArray and set all tags with the name attribute equivalent to the elements in the array as required

#### Description
```js 
updateRequiredElements(string currActive, array requiredArray)
```

#### Parameters
##### currActive: the value of the currently selected option in the dropdown with id "targetSelect"
##### requiredArray: an array using the type_ids as the keys and an array containing the names of the required parameters as the value

#### Return Values
##### No value is returned.


### getOptionsUsingAJAX 
##### — onchange of the value of the source field, fetch the JSON array from the AJAX url, filter the appropriate values, and update the options of the destination field

#### Description
```js 
getOptionsUsingAJAX(string srcField, string url, string dstField)
```

#### Parameters
##### srcField: the name of the source input (basis for the destination value)
##### url: url to fetch the JSON dynamic options
##### destField: the name of the dynamic destination input

#### Return Values
##### No value is returned.


### addRow 
##### — clone the appropriate row form the tableRowHTML array, replace the indices of the form tags with the value of tablecounter to ensure alignment, and append to the table with name tableName

#### Description
```js 
addRow(string tableName)
```

#### Parameters
##### tableName: the name of the table to be modified

#### Return Values
##### No value is returned.


### removeRow 
##### — remove the row where the clicked remove button resides

#### Description
```js 
removeRow(td tdElem)
```

#### Parameters
##### tdElem: the cell of the remove button

#### Return Values
##### No value is returned.
