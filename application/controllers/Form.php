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

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$type_id = trim($this->input->post('targetSelect'));
			$selected_form_array = $this->get_selected_form_array($form_array, $type_id);
			
			$errors = $this->validate_form($selected_form_array);

			if (!empty($errors)){
				$data['type_id'] = $type_id;
				$data['values'] = $_POST;
				$data['error_array'] = $errors;
				$this->load->view('form/survey', $data);
				return;
			}

			echo "<pre>";
			print_r($_POST);
			echo "</pre>";

			$result = $this->result_to_json($form_array, $_POST);
			echo "<pre>";
			print_r($result);
			echo "</pre>";

			$this->view();
			exit;
		}

		$data['type_id'] = "";
		$data['values'] = NULL;
		$data['error_array'] = NULL;
		$this->load->view('form/survey', $data);
	}

	public function view() {
		echo "view time";
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

	// Returns the dependent form array with the given type ID
	private function get_selected_form_array($form_array, $type_id) {
		foreach ($form_array as $form_key => $form_value) {
			if ($type_id == $form_value['type_id']){
				return $form_value;
			}
		}
	}

	private function validate_form($selected_form_array) {
		$errors = array();

		// Add main form validation (experience)
		$this->form_validation->set_rules('experience', 'Experience', 'required');
		// Set validation rules based on the selected type
		set_dependent_form_validation_rules($selected_form_array['params']);

		$res = $this->form_validation->run();
		if (!$res){
			$errors = retrieve_error_messages($selected_form_array['params']);
		}
		return $errors;
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