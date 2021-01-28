<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	define("DEPFORM_SRCTYPE_MODEL", "model");
	define("DEPFORM_SRCTYPE_CONTROLLER", "controller");
	define("DEPFORM_SRCTYPE_AJAX", "AJAX");

	define("DEPFORM_TYPE_TEXTAREA", "textarea");
	define("DEPFORM_TYPE_ENUM", "enum");
	define("DEPFORM_TYPE_BOOLEAN", "bool");
	define("DEPFORM_TYPE_RADIO", "radio");
	define("DEPFORM_TYPE_DROPDOWN", "dropdown");
	define("DEPFORM_TYPE_DATE", "date");
	define("DEPFORM_TYPE_DATETIME", "datetime");
	define("DEPFORM_TYPE_STRING", "string");
	define("DEPFORM_TYPE_NUMBER", "number");
	define("DEPFORM_TYPE_INT", "integer");
	define("DEPFORM_TYPE_FLOAT", "float");
	define("DEPFORM_TYPE_PASSWORD", "password");
	define("DEPFORM_TYPE_TIME", "time");
	define("DEPFORM_TYPE_FILE_UPLOAD", "file_upload");
	define("DEPFORM_TYPE_DISPLAY_ONLY", "display_only");
	define("DEPFORM_TYPE_HIDDEN", "hidden");
	define("DEPFORM_TYPE_LIST", "list");

	define("DEPFORM_TYPE_DEFAULT_CARDINALITY", 3);
	define("DEPFORM_TYPE_DROPDOWN_MIN_OPTIONS", 10);

	function build_required_array($form_array) {
		$required_array = array();
		foreach ($form_array as $form_key => $form_value) {
			// Traverse params and generate the fields HTML
			foreach ($form_value['params'] as $param_key => $param_value) {
				$name = $param_value['name'];
				$is_required = $param_value['is_required'];
				if ($is_required) {
					$required_array[$form_value['type_id']][] = $name;
				}
			}
		}
		return $required_array;
	}

	function set_dependent_form_validation_rules($params) {
		$ci = &get_instance();
		// Traverse params and set the rules
		foreach ($params as $param_key => $param_value) {
			$name = $param_value['name'];
			$display_name = $param_value['display_name'];

			if(array_key_exists('validation', $param_value)) {
				$rules = $param_value['validation'];
			} else {
				$source_type = $param_value['source_type'] ?? NULL;
				$list_data_type = $param_value['list_data_type'] ?? NULL;

				$choices = array();
				if (in_array($param_value['type'], array(DEPFORM_TYPE_ENUM, DEPFORM_TYPE_BOOLEAN, DEPFORM_TYPE_RADIO, DEPFORM_TYPE_DROPDOWN))){
					$choices = get_options_from_source($source_type, $param_value);
				}

				$rules = get_default_rules($param_value['type'], $choices, $list_data_type);
			}

			if ($param_value['is_required']) {
				if(!empty($rules)) {
					$rules .= "|";
				}
				$rules .= 'required';
			}

			if(!empty($rules)){
				// Set the rules for each given input if list type
				if($param_value['type'] == DEPFORM_TYPE_LIST) {
					for($i = 0; $i < $param_value['cardinality']; $i++) {
						$ci->form_validation->set_rules($name."[$i]", $display_name, $rules);
						echo $rules;
					}
				} else {
					$ci->form_validation->set_rules($name, $display_name, $rules);
				}
			}
		}
	}

	function get_default_rules($type, $choices = array(), $list_data_type = NULL) {
		if ($type == DEPFORM_TYPE_LIST) {
			$type = $list_data_type;
		}
		
		switch ($type) {

			case DEPFORM_TYPE_DROPDOWN:
			case DEPFORM_TYPE_RADIO:
			case DEPFORM_TYPE_ENUM:
				return "in_list[".implode(array_keys($choices),',')."]";

			case DEPFORM_TYPE_BOOLEAN:
				return "in_list[0,1]";

			case DEPFORM_TYPE_NUMBER:
				return "numeric|greater_than_equal_to[-PHP_FLOAT_MAX]|less_than_equal_to[PHP_FLOAT_MAX]";

			case DEPFORM_TYPE_FLOAT:
				return "decimal|greater_than_equal_to[-PHP_FLOAT_MAX]|less_than_equal_to[PHP_FLOAT_MAX]";

			case DEPFORM_TYPE_INT:
				return "integer|greater_than_equal_to[PHP_INT_MIN]|less_than_equal_to[PHP_INT_MAX]";

			case DEPFORM_TYPE_PASSWORD:
				return "callback_password_strength_check";

			case DEPFORM_TYPE_DATE:
				return "callback_date_valid";

			case DEPFORM_TYPE_TIME:
				return "callback_time_valid";

			case DEPFORM_TYPE_DATETIME:
				return "callback_datetime_valid";

			default:
				return "";
		}
	}

	// Placeholder function for the password validator
	function password_strength_check($str){
		if(strlen($str) < 8) {
			return FALSE;
		}
		return TRUE;
	}

	function date_valid($date) {
		$day = (int) substr($date, 0, 2);
    $month = (int) substr($date, 3, 2);
    $year = (int) substr($date, 6, 4);
    return checkdate($month, $day, $year);
	}

	function time_valid($time) {
		$dateObj = DateTime::createFromFormat('H:i', $time);
		if ($dateObj == FALSE) { 
			return FALSE;
		}
		return TRUE;
	}

	function datetime_valid($datetime) {
		$dateObj = DateTime::createFromFormat('Y-m-d\TH:i', $datetime);
		if ($dateObj == FALSE) { 
			return FALSE;
		}
		return TRUE;
	}

	function retrieve_error_messages($params) {
		$ci = &get_instance();
		$errors = array();
		// Traverse params and fetch the error messages
		foreach ($params as $param_key => $param_value) {
			$name = $param_value['name'];
			// If type is list, assign the array of errors
			if ($param_value['type'] == DEPFORM_TYPE_LIST) {
				for($i = 0; $i < $param_value['cardinality']; $i++) {
						$errors[$name][$key] = form_error($name."[$i]");
					}
			} else {
				$errors[$name] = form_error($name);
			}
		}
		return $errors;
	}

	// Output error as validation error
	function build_dependent_form($id, $form_array, $post_value_array = array(), $validation_error_array = array()) {
		div_open($id);

		// Traverse forms and generate the options HTML for the select tag
		foreach ($form_array as $form_key => $form_value) {
			div_open('dependentForm'.$form_value['type_id'], '', 'display: none;');

			// Traverse params and generate the fields HTML
			foreach ($form_value['params'] as $param_key => $param_value) {
				$name = $param_value['name'];
				$display_name = $param_value['display_name'];
				$type = $param_value['type'];
				$is_required = $param_value['is_required'];

				$source_type = $param_value['source_type'] ?? NULL;
				$cardinality = $param_value['cardinality'] ?? DEPFORM_TYPE_DEFAULT_CARDINALITY;

				$options = array();
				if ($type == DEPFORM_TYPE_ENUM || $type == DEPFORM_TYPE_DROPDOWN || $type == DEPFORM_TYPE_RADIO) {
					$options = get_options_from_source($source_type, $param_value);
				}

				$post_value = $post_value_array[$name] ?? NULL;
				$validation_error = $validation_error_array[$name] ?? NULL;

				to_tag($name, $display_name, $type, $cardinality, $is_required, $options, $post_value, $validation_error);
			}

			div_close();
		}

		div_close();
	}

	function get_options_from_source($source_type, $param_value) {
		switch ($source_type) {
			case DEPFORM_SRCTYPE_MODEL:
				$model_method = explode('.', $param_value['source']);
				$model_string = $model_method[0];
				$method_string = $model_method[1];
				$ci = &get_instance();
				// Load the model and execute the method
				$ci->load->model($model_string, 'source_model');
				
				if (method_exists($ci->source_model,$method_string)) {
					$options = $ci->source_model->$method_string();
				} else {
					$options = NULL;
					echo "The method $method_string from model $model_string does not exist.";
				}
				break;
			
			// Change to a method of the current controller (later)
			case DEPFORM_SRCTYPE_CONTROLLER:
				$controller_method = explode('.', $param_value['source']);
				$controller_string = $controller_method[0];
				$method_string = $controller_method[1];
				// Load the controller file and execute the method
				require_once(APPPATH.'controllers/'.$controller_string.".php");
				
				if (method_exists($controller_string,$method_string)) {
					$options = $controller_string::$method_string();
				} else {
					$options = NULL;
					echo "The method $method_string from controller $controller_string does not exist.";
				}
				break;

			case DEPFORM_SRCTYPE_AJAX:
				$options = curl_get($param_value['source']);
				break;

			default:
				$options = $param_value['values'] ?? NULL;
				break;
		}
		return $options;
	}

	function curl_get($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		// Returns the value instead of printing
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$options = json_decode(curl_exec($ch), TRUE);
		if (curl_errno($ch)) {
			echo curl_error($ch);
		}
		curl_close($ch);  
		return $options;
	}

	// Build the HTML tags per field
	function to_tag($name, $display_name, $type, $cardinality, $is_required, $choices = array(), $value = "", $error = "") {
		div_open('', 'form-group');
		switch ($type) {
			case DEPFORM_TYPE_TEXTAREA:
				textarea_field($name, $display_name, $value, $error);
				break;

			case DEPFORM_TYPE_ENUM:
				if (isset($choices)){
					if (count($choices) < DEPFORM_TYPE_DROPDOWN_MIN_OPTIONS) {
						radio_field($name, $display_name, $value, $choices, $error);
					} else {
						dropdown_field($name, $display_name, $choices, $value, $error);
					}
				} else {
					echo "No choices provided for $name.";
				}
				break;

			case DEPFORM_TYPE_BOOLEAN:
				boolEANan_field($name, $display_name, $value, $error, $options = array());
				break;

			case DEPFORM_TYPE_RADIO:
				if (isset($choices)){
					radio_field($name, $display_name, $value, $choices, $error);
				} else {
					echo "No choices provided for $name.";
				}
				break;

			case DEPFORM_TYPE_DROPDOWN:
				if (isset($choices)){
					dropdown_field($name, $display_name, $choices, $value, $error);
				} else {
					echo "No choices provided for $name.";
				}
				break;

			case DEPFORM_TYPE_DATE:
				date_field($name, $display_name, $value, $error);
				break;

			case DEPFORM_TYPE_DATETIME:
				datetime_field($name, $display_name, $value, $error);
				break;

			case DEPFORM_TYPE_STRING:
				text_field($name, $display_name, $value, $error);
				break;

			case DEPFORM_TYPE_NUMBER:
				number_field($name, $display_name, $value, $error, array('step'=>PHP_FLOAT_MIN));
				break;

			case DEPFORM_TYPE_INT:
				number_field($name, $display_name, $value, $error);
				break;

			case DEPFORM_TYPE_FLOAT:
				number_field($name, $display_name, $value, $error, array('step'=>PHP_FLOAT_MIN));
				break;

			case DEPFORM_TYPE_PASSWORD:
				password_field($name, $display_name, $value, $error);
				break;

			case DEPFORM_TYPE_TIME:
				time_field($name, $display_name, $value, $error);
				break;

			case DEPFORM_TYPE_FILE_UPLOAD:
				file_upload_field($name, $display_name, $error);
				break;

			case DEPFORM_TYPE_DISPLAY_ONLY:
				display_only_field($display_name, $value);
				break;

			case DEPFORM_TYPE_HIDDEN:
				hidden_field($name, $value);
				break;

			case DEPFORM_TYPE_LIST:
				list_field($name, $display_name, $value, $error, $cardinality);
				break;

			default:
				echo "Error generating $name tag. Unknown field type $type in the JSON file.";
		}
		div_close();
	}

	function div_open($id = '', $class = '', $style = '') {
		echo "<div id='$id' class='$class' style='$style'>";
	}

	function div_close() {
		echo "</div>";
	}

	function build_label($name, $label) {
		// $asterisk = "";
		// if($is_required) {
		// 	$asterisk = "*";
		// }
		echo "<label for='$name'>$label</label>";
	}

	function text_field($name, $label, $value, $error, $options = array()) {
		echo build_label($name, $label) . "<input class='form-control' value='$value' name='$name' type='text'/>" . $error;
	}

	function number_field($name, $label, $value, $error, $options = array()) {
		echo build_label($name, $label) . "<input class='form-control' value='$value' name='$name' type='number' ";
		foreach ($options as $key => $value) {
			echo "$key='$value'";
		}
		echo "/>" . $error;
	}

	function textarea_field($name, $label, $value, $error, $options = array()) {
		echo build_label($name, $label) . "<textarea class='form-control' name='$name'>$value</textarea>" . $error;
	}

	function password_field($name, $label, $value, $error, $options = array()) {
		echo build_label($name, $label) . "<input class='form-control' value='$value' name='$name' type='password'/>" . $error;
	}

	function date_field($name, $label, $value, $error, $options = array()) {
		echo build_label($name, $label) . "<input class='form-control' value='$value' name='$name' type='date'/>" . $error;
	}

	function datetime_field($name, $label, $value, $error, $options = array()) {
		echo build_label($name, $label) . "<input class='form-control' value='$value' name='$name' type='datetime-local'/>" . $error;
	}

	function time_field($name, $label, $value, $error, $options = array()) {
		echo build_label($name, $label) . "<input class='form-control' value='$value' name='$name' type='time'/>" . $error;
	}

	function boolEANan_field($name, $label, $value, $error, $options = array()) {
		$choices = array(True => 'Yes', FALSE => 'No');
		echo radio_field($name, $label, $value, $choices, $error, $options);
	}

	function radio_field($name, $label, $value, $choices, $error, $options = array()) {
		$radioTags = build_label($name, $label);
		foreach ($choices as $choice_key => $choice_value) {
			$checked = mark_option($choice_key, $value, "checked");
			$radioTags .= "<div class='form-check'><input class='form-check-input' type='radio' name='$name' value='$choice_key' $checked><label class='form-check-label' for='$name'>$choice_value</label></div>";
		}
		echo $radioTags . $error;
	}

	function dropdown_field($name, $label, $choices, $value, $error, $options = array()) {
		$field_HTML = build_label($name, $label) . "<select class='form-control' value='$value' name='$name'>";
		foreach ($choices as $choice_key => $choice_value) {
			$selected = mark_option($choice_key, $value, "selected");
			$field_HTML .= generate_option($choice_key, $choice_value, $selected);
		}
		echo $field_HTML .= "</select>" . $error;
	}

	function generate_option($value, $label, $selected = '', $disabled = '') {
		return "<option value='$value' $selected $disabled>$label</option>";
	}

	function file_upload_field($name, $label, $error, $options = array(), $help_msg = null, $max_filesize = 100000) {
		echo build_label($name, $label) . "<input class='form-control' name='$name' type='file'/>" . $error;
	}

	function display_only_field($label, $text, $options = array()) {
		echo build_label("", $label) . $text;
	}

	function hidden_field($name, $value) {
		echo "<input class='form-control' value='$value' name='$name' type='hidden'/>";
	}

	function list_field($name, $label, $value, $error, $cardinality = DEPFORM_TYPE_DEFAULT_CARDINALITY, $options = array()) {
		echo build_label($name, $label);
		for ($i=0; $i < $cardinality; $i++) { 
			echo "<input class='form-control' value='$value[$i]' name='$name"."[]'/>$error[$i]";
		}
	}

	function href_button($label, $url, $type, $href_class = '', $href_id = '') {
		switch ($type) {
			case 'add':
				$class = "primary";
				break;
			
			case 'update':
				$class = "success";
				break;

			case 'delete':
				$class = "danger";
				break;

			case 'details':
				$class = "info";
				break;

			case 'back':
				$class = "light";
				break;

			case 'download':
				$class = "secondary";
				break;

			case 'cancel':
				$class = "warning";
				break;

			default:
				echo "Error generating $label href_button. Unknown type \"" . $type . "\".";
		}
		echo "<a class='btn btn-$class' href='$url' role='button'>$label</a>";
	}

	function mark_option($key, $value, $string) {
		if ($key == $value) {
			return $string;
		} else {
			return "";
		}
	}
