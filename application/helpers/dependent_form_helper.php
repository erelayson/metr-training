<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	define("DEPFORM_SRCTYPE_MODEL", "model");
	define("DEPFORM_SRCTYPE_CONTROLLER", "controller");
	define("DEPFORM_SRCTYPE_AJAX", "AJAX");
	define("DEPFORM_SRCTYPE_AJAX_DYNAMIC", "AJAX_dynamic");

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
	define("DEPFORM_TYPE_TABLE", "table");

	define("DEPFORM_TYPE_DEFAULT_CARDINALITY", 3);
	define("DEPFORM_TYPE_DROPDOWN_MIN_OPTIONS", 10);

	function build_required_array($form_array) {
		$required_array = array();
		foreach ($form_array as $form_key => $form_value) {
			// Traverse params and generate the fields HTML
			foreach ($form_value['params'] as $param_key => $param_value) {
				$name = $param_value['name'] ?? NULL;
				$is_required = $param_value['is_required'] ?? NULL;
				if ($is_required) {
					$required_array[$form_value['type_id']][] = $name;
				}
			}
		}
		return $required_array;
	}

	function set_dependent_form_validation_rules($params, $conditions = array(), $form_data = array(), $values = array(), $is_table_cell = FALSE, $table_name = "") {
		$function_success = TRUE;
		$ci = &get_instance();
		// Retrieve necessary fields for validation if conditions array is passed
		if(!empty($conditions)) {
			$included_fields = $ci->fetch_included_fields($conditions, $form_data);
			// echo "<pre>";
			// print_r ($included_fields);
			// echo "</pre>";
		}
		// Traverse params and set the rules
		foreach ($params as $param_key => $param_value) {

			$name = $param_value['name'] ?? NULL;
			// Skip validation if the additional parameter is not in the includied_fields
			if(!empty($conditions) and !in_array($name, $included_fields)) {
				continue;
			}

			// For table types, do a recursive call and skip the rest of the loop
			if($param_value['type'] == DEPFORM_TYPE_TABLE) {
				set_dependent_form_validation_rules($param_value['params'], array(), array(), $values, TRUE, $param_value['name']);
				continue;
			}

			$display_name = $param_value['display_name'];

			if(array_key_exists('validation', $param_value)) {
				$rules = $param_value['validation'];
			} else {
				$source_type = $param_value['source_type'] ?? NULL;
				$list_data_type = $param_value['list_data_type'] ?? NULL;

				$choices = array();
				if (in_array($param_value['type'], array(DEPFORM_TYPE_ENUM, DEPFORM_TYPE_BOOLEAN, DEPFORM_TYPE_RADIO, DEPFORM_TYPE_DROPDOWN))){
					// For dynamic AJAX src, get the depends_on name to fetch the value from the POST data, then traverse the dependent form JSON again to fetch the AJAX url
					$depends_on = $param_value['depends_on'] ?? NULL;
					if (isset($depends_on)) {
						$depends_on_value = $ci->input->post($depends_on);
						foreach ($params as $AJAX_src_value) {
							$AJAX_src_value_name = $AJAX_src_value['name'] ?? NULL;
							if ($AJAX_src_value_name == $depends_on) {
								$AJAX_url = $AJAX_src_value['AJAX_url'];
								break;
							}
						}
					} else {
						$depends_on_value = NULL;
						$AJAX_url = NULL;
					}
					$choices = get_options_from_source($source_type, $param_value, $depends_on_value, $AJAX_url);

					// Skip setting the rules if choice retrieval fails
					if($choices['success'] == FALSE) {
						echo $choices['errmsg'] . "<br/>";
						$function_success = FALSE;
						continue;
					}
				}

				$choices_data = $choices['data'] ?? NULL;
				$rules = get_default_rules($param_value['type'], $choices_data, $list_data_type);
			}

			if ($param_value['is_required']) {
				if(!empty($rules)) {
					$rules .= "|";
				}
				$rules .= 'required';
			}

			if(!empty($rules)){
				// For table validation, set the rules by traversing the available keys to access each row
				if ($is_table_cell == TRUE) {
					foreach ($values[$table_name] as $row_num => $row_values) {
						// Set the rules for each given input if list type
						if($param_value['type'] == DEPFORM_TYPE_LIST) {
							for($i = 0; $i < $param_value['cardinality']; $i++) {
								$ci->form_validation->set_rules($table_name."[$row_num][$name][$i]", $display_name, $rules);
								echo $rules;
							}
						} else {
							$ci->form_validation->set_rules($table_name."[$row_num][$name]", $display_name, $rules);
						}
					}
				} else {
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
		return $function_success;
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
				$min = -PHP_FLOAT_MAX;
				$max = PHP_FLOAT_MAX;
				return "numeric|greater_than_equal_to[$min]|less_than_equal_to[$max]";

			case DEPFORM_TYPE_FLOAT:
				$min = -PHP_FLOAT_MAX;
				$max = PHP_FLOAT_MAX;
				return "decimal|greater_than_equal_to[$min]|less_than_equal_to[$max]";

			case DEPFORM_TYPE_INT:
				$min = PHP_INT_MIN;
				$max = PHP_INT_MAX;
				return "integer|greater_than_equal_to[$min]|less_than_equal_to[$max]";

			case DEPFORM_TYPE_PASSWORD:
				return "password_strength_check";

			case DEPFORM_TYPE_DATE:
				return "date_valid";

			case DEPFORM_TYPE_TIME:
				return "time_valid";

			case DEPFORM_TYPE_DATETIME:
				return "datetime_valid";

			default:
				return "";
		}
	}


	function retrieve_error_messages($params, $values = array(), $is_table_cell = FALSE, $table_name = "") {
		$ci = &get_instance();
		$errors = array();
		// Traverse params and fetch the error messages
		if ($is_table_cell == TRUE) {
			foreach ($params as $param_key => $param_value) {
				$name = $param_value['name'] ?? NULL;
				foreach ($values[$table_name] as $row_num => $row_values) { 
					// If type is list, assign the array of errors
					if ($param_value['type'] == DEPFORM_TYPE_LIST) {
						for($i = 0; $i < $param_value['cardinality']; $i++) {
								$errors[$row_num][$name][$i] = form_error($table_name."[$row_num][$name][$i]");
							}
					} else {
						$errors[$row_num][$name] = form_error($table_name."[$row_num][$name]");
					}
				}
			}
		} else {
			foreach ($params as $param_key => $param_value) {
				$name = $param_value['name'] ?? NULL;
				// If type is list, assign the array of errors
				if ($param_value['type'] == DEPFORM_TYPE_LIST) {
					for($i = 0; $i < $param_value['cardinality']; $i++) {
							$errors[$name][$i] = form_error($name."[$i]");
						}
				} elseif ($param_value['type'] == DEPFORM_TYPE_TABLE) {
					$errors[$name] = retrieve_error_messages($params[$param_key]['params'], $values, TRUE, $name);
				} else {
					$errors[$name] = form_error($name);
				}
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

			generate_HTML_from_params($form_value['params'], $post_value_array, $validation_error_array);

			div_close();
		}

		div_close();
	}

	function generate_HTML_from_params($params, $post_value_array = array(), $validation_error_array = array(), $is_table_cell = FALSE, $table_name = "") {
		// echo "<pre>";
		// print_r ($post_value_array);
		// echo "</pre>";
		foreach ($params as $param_key => $param_value) {
			$type = $param_value['type'];
			$name = $param_value['name'];
			$display_name = $param_value['display_name'];

			if ($is_table_cell == TRUE) {
				$name = $table_name."[0][$name]";
			}

			if ($type == DEPFORM_TYPE_TABLE) {
				build_label($name,$display_name);
				echo "<table class='table' name='$name'>
								<thead>
									<tr>";
				foreach ($param_value['params'] as $table_row) {
					echo "		<th>".$table_row['display_name']."</th>";
				}
				echo "			<th>Actions</th>
									</tr>
								</thead>
								<tbody>";
				echo generate_HTML_from_params($param_value['params'], $post_value_array[$name], $validation_error_array, TRUE, $name);
				echo "	</tbody>
									<tfoot>
									<tr>
										<td><button type='button' class='btn btn-primary' onclick=addRow('$name')>Add Row</button></td>
									</tr>
									</tfoot>
								</table>";

				if (!empty($validation_error_array[$name])) {
					$row_num = 1;
					echo "Errors:<br/>";
					foreach ($validation_error_array[$name] as $error_list) {
						if (!empty(implode('', $error_list))) {
							echo "For row #$row_num: " . implode('', $error_list);
						}
						$row_num += 1;
					}
				}
				continue;
			}

			$is_required = $param_value['is_required'];

			$source_type = $param_value['source_type'] ?? NULL;

			$is_dependent = FALSE;
			if ($source_type == DEPFORM_SRCTYPE_AJAX_DYNAMIC) {
				$is_dependent = TRUE;
			}

			$AJAX_url = $param_value['AJAX_url'] ?? NULL;
			$update_field_name = $param_value['update_field_name'] ?? NULL;

			$AJAX_params = NULL;
			if (isset($AJAX_url) and isset($update_field_name)) {
				$AJAX_params = array($AJAX_url, $update_field_name);
			}

			$cardinality = $param_value['cardinality'] ?? DEPFORM_TYPE_DEFAULT_CARDINALITY;

			$post_value = $post_value_array[$name] ?? NULL;
			$validation_error = $validation_error_array[$name] ?? NULL;

			$build_label = !$is_table_cell;

			if($is_table_cell) {
				echo "<td>";
			}

			$options = array();
			if ($type == DEPFORM_TYPE_ENUM || $type == DEPFORM_TYPE_DROPDOWN || $type == DEPFORM_TYPE_RADIO) {
				$options = get_options_from_source($source_type, $param_value);
				if($options['success'] == TRUE) {
					to_tag($name, $display_name, $type, $is_dependent, $cardinality, $is_required, $options['data'], $post_value, $validation_error, $AJAX_params, $build_label);
				} else {
					display_only_field($display_name, $options['errmsg']);
					echo "<br/>";
				}
			} else {
				to_tag($name, $display_name, $type, $is_dependent, $cardinality, $is_required, $options, $post_value, $validation_error, $AJAX_params, $build_label);
			}

			if($is_table_cell) {
				echo "</td>";
			}

		}
		if($is_table_cell) {
			echo "<td><button type='button' class='btn btn-danger' onclick=removeRow(this)>X</button></td";
		}
	}


	// return_array syntax: 
	// array(
	//      'success' : true|false,
	//       'errmsg' : string,
	//       'data' : array($data)
	// );

	function get_options_from_source($source_type, $param_value, $depends_on_value = NULL, $AJAX_url = "") {
		$return_array = array();
		switch ($source_type) {
			case DEPFORM_SRCTYPE_MODEL:
				$model_method = explode('.', $param_value['source']);
				$model_string = $model_method[0];
				$method_string = $model_method[1];
				$ci = &get_instance();
				// Load the model and execute the method
				$ci->load->model($model_string, 'source_model');
				
				if (method_exists($ci->source_model,$method_string)) {
					$return_array['data'] = $ci->source_model->$method_string();
					$return_array['success'] = TRUE;
				} else {
					$return_array['errmsg'] = "The method $method_string from model $model_string does not exist.";
					$return_array['success'] = FALSE;
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
					$return_array['data'] = $controller_string::$method_string();
					$return_array['success'] = TRUE;
				} else {
					$return_array['errmsg'] = "The method $method_string from controller $controller_string does not exist.";
					$return_array['success'] = FALSE;
				}
				break;

			case DEPFORM_SRCTYPE_AJAX:
				$return_array = curl_get($param_value['source']);
				break;

			// For dynamic, use the depends_on_value as key to fetch the available options
			case DEPFORM_SRCTYPE_AJAX_DYNAMIC:
				if(isset($depends_on_value)) {
					$return_array = curl_get($AJAX_url);
					if ($return_array['success'] == TRUE) {
						$return_array['data'] = $return_array['data'][$depends_on_value];
					} else {
						$return_array['errmsg'] = "Error fetching options: " . $return_array['errmsg'];
					}
				} else {
					$return_array['data'] = array();
					$return_array['success'] = TRUE;
				}
				break;

			default:
				$return_array['data'] = $param_value['values'] ?? NULL;
				if (empty($return_array['data'])) {
					$return_array['errmsg'] = "No given values provided";
					$return_array['success'] = FALSE;
				} else {
					$return_array['success'] = TRUE;
				}
				break;
		}
		return $return_array;
	}

	function curl_get($url) {
		$return_array = array();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		// Returns the value instead of printing
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$return_array['data'] = json_decode(curl_exec($ch), TRUE);
		$return_array['success'] = TRUE;
		if (curl_errno($ch)) {
			$return_array['errmsg'] = curl_error($ch);
			$return_array['success'] = FALSE;
		}
		curl_close($ch);  
		return $return_array;
	}

	// Build the HTML tags per field
	function to_tag($name, $display_name, $type, $is_dependent, $cardinality, $is_required, $choices = array(), $value = "", $error = "", $AJAX_params = array(), $build_label = TRUE) {
		div_open('', 'form-group');

		$options = array("label" => $build_label);

		switch ($type) {
			case DEPFORM_TYPE_TEXTAREA:
				textarea_field($name, $display_name, $value, $error, $options);
				break;

			case DEPFORM_TYPE_ENUM:
				if($is_dependent == TRUE) {
					$choices = array(""=>"");
					dropdown_field($name, $display_name, $choices, $value, $error, $options);
				} else {
					if (isset($choices)){
						if (count($choices) < DEPFORM_TYPE_DROPDOWN_MIN_OPTIONS) {
							radio_field($name, $display_name, $value, $choices, $error, $options);
						} else {
							dropdown_field($name, $display_name, $choices, $value, $error, $options);
						}
					} else {
						echo "No choices provided for $name.";
					}
				}
				break;

			case DEPFORM_TYPE_BOOLEAN:
				boolean_field($name, $display_name, $value, $error, $options);
				break;

			case DEPFORM_TYPE_RADIO:
				if (isset($choices)){
					radio_field($name, $display_name, $value, $choices, $error, $options);
				} else {
					echo "No choices provided for $name.";
				}
				break;

			case DEPFORM_TYPE_DROPDOWN:
				if(isset($AJAX_params)) {
					$options["attributes"] = array("onchange"=>"getOptionsUsingAJAX(this,'$AJAX_params[0]','$AJAX_params[1]')");
				}

				if (isset($choices)){
					dropdown_field($name, $display_name, $choices, $value, $error, $options);
				} else {
					echo "No choices provided for $name.";
				}
				break;

			case DEPFORM_TYPE_DATE:
				date_field($name, $display_name, $value, $error, $options);
				break;

			case DEPFORM_TYPE_DATETIME:
				datetime_field($name, $display_name, $value, $error, $options);
				break;

			case DEPFORM_TYPE_STRING:
				text_field($name, $display_name, $value, $error, $options);
				break;

			case DEPFORM_TYPE_NUMBER:
			case DEPFORM_TYPE_FLOAT:
				$options["attributes"] = array('step'=>PHP_FLOAT_MIN);
				number_field($name, $display_name, $value, $error, $options);
				break;

			case DEPFORM_TYPE_INT:
				number_field($name, $display_name, $value, $error, $options);
				break;

			case DEPFORM_TYPE_PASSWORD:
				password_field($name, $display_name, $value, $error, $options);
				break;

			case DEPFORM_TYPE_TIME:
				time_field($name, $display_name, $value, $error, $options);
				break;

			case DEPFORM_TYPE_FILE_UPLOAD:
				file_upload_field($name, $display_name, $error, $options);
				break;

			case DEPFORM_TYPE_DISPLAY_ONLY:
				display_only_field($display_name, $value, $options);
				break;

			case DEPFORM_TYPE_HIDDEN:
				hidden_field($name, $value);
				break;

			case DEPFORM_TYPE_LIST:
				list_field($name, $display_name, $value, $error, $cardinality, $options);
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

	function text_field($name, $label, $value, $error, $options = array("label" => TRUE)) {
		if ($options['label']) {
			echo build_label($name, $label);
		}
		echo "<input class='form-control' value='$value' name='$name' type='text'/>" . $error;
	}

	function number_field($name, $label, $value, $error, $options = array("label" => TRUE)) {
		if ($options['label']) {
			echo build_label($name, $label);
		}
		echo "<input class='form-control' value='$value' name='$name' type='number' ";
		$attributes = $options['attributes'] ?? NULL;
		if(!empty($attributes)) {
			foreach ($attributes as $key => $value) {
				echo "$key='$value'";
			}
		}
		echo "/>" . $error;
	}

	function textarea_field($name, $label, $value, $error, $options = array("label" => TRUE)) {
		if ($options['label']) {
			echo build_label($name, $label);
		}
		echo "<textarea class='form-control' name='$name'>$value</textarea>" . $error;
	}

	function password_field($name, $label, $value, $error, $options = array("label" => TRUE)) {
		if ($options['label']) {
			echo build_label($name, $label);
		}
		echo "<input class='form-control' value='$value' name='$name' type='password'/>" . $error;
	}

	function date_field($name, $label, $value, $error, $options = array("label" => TRUE)) {
		if ($options['label']) {
			echo build_label($name, $label);
		}
		echo "<input class='form-control' value='$value' name='$name' type='date'/>" . $error;
	}

	function datetime_field($name, $label, $value, $error, $options = array("label" => TRUE)) {
		if ($options['label']) {
			echo build_label($name, $label);
		}
		echo "<input class='form-control' value='$value' name='$name' type='datetime-local'/>" . $error;
	}

	function time_field($name, $label, $value, $error, $options = array("label" => TRUE)) {
		if ($options['label']) {
			echo build_label($name, $label);
		}
		echo "<input class='form-control' value='$value' name='$name' type='time'/>" . $error;
	}

	function boolean_field($name, $label, $value, $error, $options = array("label" => TRUE)) {
		$choices = array(True => 'Yes', FALSE => 'No');
		echo radio_field($name, $label, $value, $choices, $error, $options);
	}

	function radio_field($name, $label, $value, $choices, $error, $options = array("label" => TRUE)) {
		$radioTags = "";
		if ($options['label']) {
			$radioTags = build_label($name, $label);
		}
		foreach ($choices as $choice_key => $choice_value) {
			$checked = mark_option($choice_key, $value, "checked");
			$radioTags .= "<div class='form-check'><input class='form-check-input' type='radio' name='$name' value='$choice_key' $checked><label class='form-check-label' for='$name'>$choice_value</label></div>";
		}
		echo $radioTags . $error;
	}

	function dropdown_field($name, $label, $choices, $value, $error, $options = array("label" => TRUE)) {
		$field_HTML = "";
		if ($options['label']) {
			$field_HTML = build_label($name, $label);
		}
		$field_HTML .= "<select class='form-control' value='$value' name='$name'";
		$attributes = $options['attributes'] ?? NULL;
		if (!empty($attributes)){
			foreach ($attributes as $attribute => $option_value) {
				$field_HTML .= "$attribute=$option_value";
			}
		}
		$field_HTML .= ">";
		foreach ($choices as $choice_key => $choice_value) {
			$selected = mark_option($choice_key, $value, "selected");
			$field_HTML .= generate_option($choice_key, $choice_value, $selected);
		}
		echo $field_HTML .= "</select>" . $error;
	}

	function generate_option($value, $label, $selected = '', $disabled = '') {
		return "<option value='$value' $selected $disabled>$label</option>";
	}

	function file_upload_field($name, $label, $error, $options = array("label" => TRUE), $help_msg = null, $max_filesize = 100000) {
		if ($options['label']) {
			echo build_label($name, $label);
		}
		echo "<input class='form-control' name='$name' type='file'/>" . $error;
	}

	function display_only_field($label, $text, $options = array("label" => TRUE)) {
		if ($options['label']) {
			echo build_label("", $label);
		}
		echo ": $text";
	}

	function hidden_field($name, $value) {
		echo "<input class='form-control' value='$value' name='$name' type='hidden'/>";
	}

	function list_field($name, $label, $value, $error, $cardinality = DEPFORM_TYPE_DEFAULT_CARDINALITY, $options = array("label" => TRUE)) {
		if ($options['label']) {
			echo build_label($name, $label);
		}
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
