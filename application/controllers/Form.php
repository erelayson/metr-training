<?php

class Form extends CI_Controller {

  public function index() {
    $this->load->helper(array('form','url'));
    $this->load->library('form_validation');

    $fileName = $this->config->item('dependent_form_json');

    $data['formJSON'] = file_get_contents("application/config/" . $fileName);

    $formJSON = json_decode($data['formJSON'], true);

    $this->form_validation->set_rules('targetSelect', 'Participant Details', 'required');

    if (json_last_error() == JSON_ERROR_NONE){
      // Check if the json file follows the format for generating a form
      // for ($i=0; $i < count($formJSON); $i++) { 
      //   echo $formJSON[$i]['name'] . " " . $formJSON[$i]['type_id'] . "<br/>";
      //   for ($j=0; $j < count($formJSON[$i]['params']); $j++) { 
      //     echo $formJSON[$i]['params'][$j]['name'] . " " . $formJSON[$i]['params'][$j]['is_required'];
      //     echo "<br/>";
      //   }
      //   echo "<br/>";
      // }

      if ($this->form_validation->run() === FALSE){
        $this->load->view('form/survey', $data);

      }
      else{
        $errorList = "";
        for ($i=0; $i < count($formJSON); $i++) {
          if ($_REQUEST['targetSelect'] == $formJSON[$i]['type_id']){
            for ($j=0; $j < count($formJSON[$i]['params']); $j++) {
              if ($formJSON[$i]['params'][$j]['is_required']) {
                if (array_key_exists($formJSON[$i]['params'][$j]['name'], $_REQUEST)) {
                  if ($_REQUEST[$formJSON[$i]['params'][$j]['name']] == "") {
                    $errorList .= $formJSON[$i]['params'][$j]['name'] . " cannot be null<br/>";
                  }
                } else {
                  $errorList .= $formJSON[$i]['params'][$j]['name'] . " cannot be null<br/>";
                }
              }
            }
          }
        }
        if($errorList){
          echo $errorList;
        } else {
          print_r($_REQUEST);
        }
      }
    } else {
      echo $fileName . " is not a valid JSON file.";
      switch (json_last_error()) {
        case JSON_ERROR_DEPTH:
          echo " Maximum stack depth exceeded.";
        break;
        case JSON_ERROR_STATE_MISMATCH:
          echo " Underflow or the modes mismatch.";
        break;
        case JSON_ERROR_CTRL_CHAR:
          echo " Unexpected control character found.";
        break;
        case JSON_ERROR_SYNTAX:
          echo " Syntax error, malformed JSON.";
        break;
        case JSON_ERROR_UTF8:
          echo " Malformed UTF-8 characters, possibly incorrectly encoded.";
        break;
        default:
          echo " Unknown error.";
        break;
      }
    }
  }

}