<?php

class Form extends CI_Controller {

	public function index() {
		$this->load->helper(array('form','url','dependent_form_helper'));
		$this->load->library('form_validation');

		$file_name = $this->config->item('dependent_form_json');
		$form_JSON = file_get_contents("application/config/" . $file_name);
		$form_array = $this->parse_JSON($form_JSON);
		if(empty($form_array)) {
			exit;
		}

		$data['form_controller'] = $this;
		$data['required_array'] = build_required_array($form_array);
		$data['form_array'] = $form_array;
		$data['type_id'] = "";
		$data['values'] = NULL;
		$data['error_array'] = NULL;

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$type_id = trim($this->input->post('targetSelect'));
			$selected_form_array = $this->get_selected_form_array($form_array, $type_id);
			
			$error_array = $this->validate_form($selected_form_array, TRUE);

			if ($error_array['success'] == FALSE) {
				exit;
			}

			if (!empty($error_array['errors'])){
				$data['type_id'] = $type_id;
				$data['values'] = $_POST;
				$data['error_array'] = $error_array['errors'];
				$this->load->view('form/survey', $data);
				return;
			}

			$method = $selected_form_array['actions']['create'];
			$return_array = $this->$method($_POST);

			if($return_array['success'] == TRUE) {
				// Execute success here
			} else {
				// Print the error message and reload the form values
				echo $return_array['errmsg'];
				$data['type_id'] = $type_id;
				$data['values'] = $_POST;
			}
		}
		
		$this->load->view('form/survey', $data);
	}

	private function create_duo($values) {
		// Execute saving here
		$return_array = array(
			"success" => FALSE,
			"errmsg" => "Saving failed"
		);
		return $return_array;
	}

	public function edit() {
		$this->load->helper(array('form','url','dependent_form_helper'));
		$this->load->library('form_validation');
		$this->load->model('survey_model');

		$file_name = $this->config->item('dependent_form_json');
		$form_JSON = file_get_contents("application/config/" . $file_name);
		$form_array = $this->parse_JSON($form_JSON);
		if(empty($form_array)) {
			exit;
		}

		$form_data = $this->survey_model->get_survey();
		$selected_form_array = $this->get_selected_form_array($form_array, $form_data['targetSelect']);

		$data['params'] = $selected_form_array['params'];
		$data['selector_name'] = 'targetSelect';
		$data['selector_display_name'] = 'Type';
		$data['selector_value'] = $selected_form_array['name'];
		$data['values'] = $form_data;
		$data['error_array'] = NULL;

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			
			$error_array = $this->validate_form($selected_form_array, TRUE);

			if ($error_array['success'] == FALSE) {
				exit;
			}

			if (!empty($error_array['errors'])){
				$data['values'] = $_POST;
				$data['error_array'] = $error_array['errors'];
				$this->load->view('form/edit', $data);
				return;
			}

			$method = $selected_form_array['actions']['edit'];
			$return_array = $this->$method($_POST);

			if($return_array['success'] == TRUE) {
				// Execute success here
			} else {
				// Print the error message and reload the form values
				echo $return_array['errmsg'];
				$data['values'] = $_POST;
			}
		}

		$this->load->view('form/edit', $data);
	}

	private function edit_duo($values) {
		// Execute updating here
		$return_array = array(
			"success" => FALSE,
			"errmsg" => "Updating failed"
		);
		return $return_array;
	}

	public function view() {
		$this->load->helper(array('form','url','dependent_form_helper'));
		$this->load->library('form_validation');
		$this->load->model('survey_model');
		$form_data = $this->survey_model->get_survey();
		$sku_data = $this->survey_model->get_skus();
		$duo_message_data = $this->survey_model->get_duo_messages();

		$file_name = $this->config->item('dependent_form_json');
		$form_JSON = file_get_contents("application/config/" . $file_name);
		$dependent_form_array = $this->parse_JSON($form_JSON);
		if(empty($dependent_form_array)) {
			exit;
		}

		// echo "<pre>";
		// print_r ($form_data);
		// echo "</pre>";

		$selector_name = 'targetSelect';
		$selector_display_name = 'Type';

		$data['form_controller'] = $this;

		// Send the additional parameters to the view
		foreach ($dependent_form_array as $key => $value) {
			if ($value['type_id'] == $form_data[$selector_name]) {
				$data['additional_params'] = $value['additional_params'];
				break;
			}
		}

		$data['display_array'] = $this->preprocess($selector_name, $selector_display_name, $dependent_form_array, $form_data, array($sku_data, $duo_message_data));

		$data['form_data'] = $form_data;
		$data['method_key'] = 'create';

		// Separate this function (save_additional_param)
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			
			$method = $this->input->post('method_name');
			$selected_additional_params_array = $this->get_additional_params_array($method, $dependent_form_array);
			$selected_additional_param = $selected_additional_params_array['name'];
			$error_array = $this->validate_form($selected_additional_params_array, FALSE, $form_data);

			if ($error_array['success'] == FALSE) {
				exit;
			}

			if(!empty($error_array['errors'])) {
				$data['errors'] = $error_array['errors'];
				$data['values'] = $_POST;
				$data['selected_additional_param'] = $selected_additional_param;

				$this->load->view('form/view', $data);
				return;
			}

			$this->$method($_POST);
			// If callback success/error 
		}

		$data['errors'] = array();
		$data['values'] = array();
		$data['selected_additional_param'] = NULL;
		$this->load->view('form/view', $data);
	}

	// Loop through the dependent_form_array to fetch the params and pass to the validate_form function
	private function get_additional_params_array($method, $dependent_form_array) {
		foreach ($dependent_form_array as $dependent_form_value) {
			foreach ($dependent_form_value['additional_params'] as $key => $value) {
				if (in_array($method, $value)) {
					return $value;
				}
			}
		}
	}

	private function create_sku($values) {
		echo "model calls and inserts go here";
		// push_endpoint();
		// return $ids;
		// push_NF_Legacy();
		return;
	}

	private	function create_duo_message($values) {
		echo "model calls and inserts go here";
		return;
	}

	private function parse_JSON($form_JSON) {
		$form_array = json_decode($form_JSON, TRUE);

		if (json_last_error() != JSON_ERROR_NONE) {
			echo "JSON file could not be parsed: ";
			switch (json_last_error()) {
				case JSON_ERROR_DEPTH:
					echo "Maximum stack depth exceeded.";
				break;
				case JSON_ERROR_STATE_MISMATCH:
					echo "Underflow or the modes mismatch.";
				break;
				case JSON_ERROR_CTRL_CHAR:
					echo "Unexpected control character found.";
				break;
				case JSON_ERROR_SYNTAX:
					echo "Syntax error, malformed JSON.";
				break;
				case JSON_ERROR_UTF8:
					echo "Malformed UTF-8 characters, possibly incorrectly encoded.";
				break;
				default:
					echo "Unknown error.";
				break;
			}
		} else {
			return $form_array;
		}
	}

	private function preprocess($selector_name, $selector_display_name, $dependent_form_array, $form_data, $additional_data = array()) {
		$display_array = array();

		// For the selector, fetch the value from the dependent_form_array
		foreach ($dependent_form_array as $dependent_form_type_array) {
			if ($dependent_form_type_array['type_id'] == $form_data[$selector_name]) {
				$display_array[$selector_display_name] = $dependent_form_type_array['name'];
				break;
			}
		}
		
		// Preprocess the dependent form array for display
		// Traverse the params and create an array with display_name:value pairs
		foreach ($dependent_form_type_array['params'] as $param_value) {

			$name = $param_value['name'] ?? NULL;
			if(!empty($name) and array_key_exists($name, $form_data)) {
				// Convert value to readable display if enum/dropdown/radio
				if ($param_value['type'] == DEPFORM_TYPE_ENUM || $param_value['type'] == DEPFORM_TYPE_RADIO || $param_value['type'] == DEPFORM_TYPE_DROPDOWN ) {
					$source_type = $param_value['source_type'] ?? NULL;

					// For dynamic AJAX src, get the depends_on name to fetch the value from the POST data, then traverse the dependent form JSON again to fetch the AJAX url
					$depends_on = $param_value['depends_on'] ?? NULL;
					if (isset($depends_on)) {
						$depends_on_value = $form_data[$depends_on];
						foreach ($dependent_form_type_array['params'] as $AJAX_src_value) {
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

					$options = get_options_from_source($source_type, $param_value, $depends_on_value, $AJAX_url);

					if($options['success'] == TRUE) {
						$form_value = $options['data'][$form_data[$param_value['name']]];
					} else {
						$form_value = $options['errmsg'];
					}

				} elseif ($param_value['type'] == DEPFORM_TYPE_TABLE) {
					$form_value = array();

					foreach ($param_value['params'] as $table_param_key => $table_param_value) {

						$field_names[$table_param_value['name']] = $table_param_value['display_name'];

						// Convert value to readable display if enum/dropdown/radio
						foreach ($form_data[$param_value['name']] as $row_key => $row_value) {
							if ($table_param_value['type'] == DEPFORM_TYPE_ENUM || $table_param_value['type'] == DEPFORM_TYPE_RADIO || $table_param_value['type'] == DEPFORM_TYPE_DROPDOWN) {
								$values = $table_param_value['values'] ?? NULL;
								foreach ($row_value as $col_key => $col_value) {
									if ($col_value == $row_value[$table_param_value['name']]) {
										$form_data[$param_value['name']][$table_param_key][$row_key][$col_key] = $values[$row_value[$table_param_value['name']]];
									}
								}
							}
						}
					}

					$form_value['data'] = $form_data[$param_value['name']];
					$form_value['field_names'] = $field_names;

				} else {
					$form_value = $form_data[$param_value['name']];
				}
				$display_array[$param_value['display_name']] = $form_value;
			}
		}

		// Preprocess the additional parameter array for display
		foreach ($dependent_form_type_array['additional_params'] as $additional_param_key => $additional_param_value) {
			
			$display_array['additional_params'][$additional_param_value['name']]['is_tabular'] = $additional_param_value['is_tabular'];
			$display_array['additional_params'][$additional_param_value['name']]['actions'] = $additional_param_value['actions'];

			$included_fields = $this->fetch_included_fields($additional_param_value['conditions'], $form_data);

			$field_names = array();
			foreach ($additional_param_value['params'] as $param_key => $param_value) {

				if (in_array($param_value['name'],$included_fields)) {

					$field_names[$param_value['name']] = $param_value['display_name'];
					// echo "<pre>";
					// print_r ($param_value);
					// echo "</pre>";
					
					// echo "<pre>";
					// print_r ($values);
					// echo "</pre>";
					// echo "<pre>";
					// print_r ($additional_data[$additional_param_key]);
					// echo "</pre>";

					// Place comments here (snapshot of the array)
					if (array_key_exists($additional_param_key, $additional_data)) {
						// Convert value to readable display if enum/dropdown/radio
						foreach ($additional_data[$additional_param_key] as $row_key => $row_value) {
							if ($param_value['type'] == DEPFORM_TYPE_ENUM || $param_value['type'] == DEPFORM_TYPE_RADIO || $param_value['type'] == DEPFORM_TYPE_DROPDOWN) {
								$values = $param_value['values'] ?? NULL;
								foreach ($row_value as $col_key => $col_value) {
									if ($col_value == $row_value[$param_value['name']]) {
										$additional_data[$additional_param_key][$row_key][$col_key] = $values[$row_value[$param_value['name']]];
									}
								}
							}
						}
					}

				}

			}

			if (array_key_exists($additional_param_key, $additional_data)) {
				$display_array['additional_params'][$additional_param_value['name']]['data'] = $additional_data[$additional_param_key];
			}
			
			$display_array['additional_params'][$additional_param_value['name']]['field_names'] = $field_names;
		}


		// echo "<pre>";
		// print_r ($display_array);
		// echo "</pre>";
		return $display_array;
	}

	public function fetch_included_fields($conditions, $form_data) {
		// Traverse the conditions key-value pairs, and append the "all" and matching condition values to the included_fields array
		$included_fields = array();
		foreach ($conditions as $condition => $fields) {
			if($condition == "all") {
				$included_fields = explode(',',$fields);
			} else {
				$condition_array = explode('&',$condition);
				$conditions_matched = TRUE;
				foreach ($condition_array as $cond_val) {
					$cond_val_pair = explode('=',$cond_val);
					if($form_data[$cond_val_pair[0]] != $cond_val_pair[1]) {
						$conditions_matched = FALSE;
					}
				}
				if ($conditions_matched == TRUE) {
					$included_fields = array_merge($included_fields, explode(',',$fields));
				}
			}
		}
		return $included_fields;
	}

	public function display_array_to_html($display_array, $additional_params, $selected_additional_param, $values, $errors, $action_key, $form_data) {

		foreach ($display_array as $key => $value) {
			if($key != 'additional_params') {
				// Remove after table preprocessing is implemented
				if(is_array($value)) {
					display_only_field($key, "");
					$field_names = $value['field_names'];
					$data = $value['data'] ?? NULL;
					$this->to_table($field_names, $data, TRUE);
				} else {
					display_only_field($key, $value);
				}
				echo "<br/>";
			}
		}

		echo "<br/>";

		foreach ($display_array['additional_params'] as $additional_param_name => $additional_param_value) {
			echo "$additional_param_name ";
			if ($additional_param_name == $selected_additional_param) {
				$this->build_modal($additional_params, $additional_param_name, $additional_param_value['actions'][$action_key], $form_data, $values, $errors);
			} else {
				$this->build_modal($additional_params, $additional_param_name, $additional_param_value['actions'][$action_key], $form_data);
			}

			$field_names = $additional_param_value['field_names'];
			$data = $additional_param_value['data'] ?? NULL;
			if($additional_param_value['is_tabular']) {
				$this->to_table($field_names, $data, TRUE, "<th><a href=''>Edit</a> <a href=''>Delete</a></th>");
			} else {
				$this->to_list($field_names, $data);
			}
		}

	}

	private function build_modal($additional_params, $additional_param_name, $method_name, $form_data, $values = array(), $errors = array()) {
		$modified_key = preg_replace('/\s+/', '_', strtolower($additional_param_name));
		echo "<a href='' data-toggle='modal' data-target='#".$modified_key."Modal'>+</a>
					<div class='modal fade' id='".$modified_key."Modal' tabindex='-1' role='dialog' aria-labelledby='".$modified_key."ModalLabel' aria-hidden='true'>
						<div class='modal-dialog' role='document'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title' id='createModalLabel'>Insert to $additional_param_name</h5>
									<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
										<span aria-hidden='true'>&times;</span>
									</button>
								</div>
								<div class='modal-body'>";
		echo form_open('form/view');
		$this->build_modal_form($additional_params, $additional_param_name, $form_data, $values, $errors);
		hidden_field("method_name", $method_name);
		
		echo "			</div>
								<div class='modal-footer'>
									<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
									<button type='submit' class='btn btn-primary'>Insert</button>
								</div>";
		echo form_close();
		echo "					</div>
						</div>
					</div><br/>";
	}

	private function build_modal_form($additional_params, $additional_param_name, $form_data, $values = array(), $errors = array()) {
		foreach ($additional_params as $additional_param_key => $additional_param_value) {
			if($additional_param_value['name'] == $additional_param_name) {
				$included_fields = $this->fetch_included_fields($additional_param_value['conditions'], $form_data);
				foreach ($additional_param_value['params'] as $param_key =>$param_value) {
					if (!in_array($param_value['name'], $included_fields)) {
						unset($additional_param_value['params'][$param_key]);
					}
				}

				generate_HTML_from_params($additional_param_value['params'], $values, $errors);
				break;
			}
		}
	}

	// $tfoot parameter (add Row), $actions from dev
	private function to_table($field_names, $data, $can_edit = FALSE, $action_html = "", $tfoot_html = "") {
		echo "<table class='table'><thead><tr>";
		foreach ($field_names as $display_name) {
			echo "<th>$display_name</th>";
		}
		if (!empty($action_html)) {
			echo "<th>Actions</th>";
		}
		echo "</tr></thead><tbody>";
		if (!empty($data)) {
			foreach ($data as $row_values) {
				echo "<tr>";
				foreach ($row_values as $col_value) {
					echo "<td>$col_value</td>";
				}
				if ($can_edit) {
					echo $action_html;
				}
				echo "</tr>";
			}
		}
		echo "</tbody><tfoot>";
		echo $tfoot_html;
		echo "<tfoot></table>";
	}

	private function to_list($field_names, $data) {
		if (!empty($data)) {
			foreach ($data as $row_values) {
				foreach ($row_values as $col_key => $col_value) {
					echo $field_names[$col_key] . ": $col_value<br/>";
				}
				echo "<br/>";
			}
		}
	}

	// Returns the dependent form array with the given type ID
	private function get_selected_form_array($form_array, $type_id) {
		foreach ($form_array as $form_key => $form_value) {
			if ($type_id == $form_value['type_id']){
				return $form_value;
			}
		}
	}

	private function validate_form($selected_form_array, $is_main, $form_data = array()) {
		$return_array = array();

		if ($is_main == TRUE) {
			// Add main form validation
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('description', 'Description', 'required');
			$this->form_validation->set_rules('keyword', 'Keyword', 'required');
		}
		// $included_fields = $this->fetch_included_fields($additional_param_value['conditions'], $form_data);


		// Set validation rules based on the selected type
		$conditions = $selected_form_array['conditions'] ?? NULL;
		if (set_dependent_form_validation_rules($selected_form_array['params'], $conditions, $form_data, $_POST) == TRUE){
			$res = $this->form_validation->run();
			if (!$res){
				$errors = retrieve_error_messages($selected_form_array['params'], $_POST);
				$return_array['errors'] = $errors;
			}
			$return_array['success'] = TRUE;
		} else {
			$return_array['success'] = FALSE;
		}
		return $return_array;
	}

	public function build_select_options($form_array, $type_id = NULL) {
		$option_HTML = generate_option('', ' -- select an option -- ', 'selected', 'disabled');
		foreach ($form_array as $form_key => $form_value) {
			$selected = mark_option($form_value['type_id'], $type_id, "selected");
			$option_HTML .= generate_option($form_value['type_id'], $form_value['name'], $selected);
		}
		return $option_HTML;
	}

	private function result_to_json($form_array, $result) {
		$json = array();

		foreach ($form_array as $form_key => $form_value) {
			if ($result['targetSelect'] == $form_value['type_id']){
				// Traverse target_nodes
				foreach ($form_value['params'] as $param_key => $param_value) {
					$model_property = explode('.', $param_value['target_node']);
					$json[$model_property[0]][$model_property[1]] = $result[$param_value['name']];
				}
			}
		}

		// Hardcoded main form entry (experience)
		$json[$model_property[0]]['EXPERIENCE'] = $result['experience'];

		return $json;
	}

}