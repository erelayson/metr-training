# Dependent Form Helper

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
set_dependent_form_validation_rules(array $params)
```

#### Parameters
##### params: an array representation of the "params" key of the dependent form JSON

#### Return Values
##### No value is returned.


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
retrieve_error_messages(array $params)
```

#### Parameters
##### params: an array representation of the "params" key of the dependent form JSON

#### Return Values
##### The array containing the parameter names and their corresponmding error messages as key-value pairs.


### build_dependent_form
##### — loops through the  dependent JSON file, retrieves the key-value pairs, and passes them to to_tag() to build the HTML of the dependent form

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


### get_options_from_source
##### — uses the source_type (model/controllect/AJAX/etc) to determine how to retrieve the options of a paramtere with type enum/radio/dropdown

#### Description
```php
get_options_from_source(string $source_type, array $param_value)
```

#### Parameters
##### source_type: the value under the "source_type" key of the dependent form JSON
##### param_value: an array representation of the values of the "params" key of the dependent form JSON

#### Return Values
##### The array containing the option keys and their corresponmding display names as key-value pairs.


### to_tag
##### — passes the necessary parameters to a tag builder function based on its type 

#### Description
```php
to_tag(string $name, string $display_name, string $type, int $cardinality, bool $is_required, array $choices = array(), string $value = "", string $error = "")
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
