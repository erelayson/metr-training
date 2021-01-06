<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promo extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('promo_model');
    $this->load->helper('url_helper');
  }

  public function index(){
    $data['title'] = 'Promo List';

    $data['promos'] = $this->promo_model->get_promos();

    $this->load->view('templates/header', $data);
    $this->load->view('promo/index', $data);
    $this->load->view('templates/footer');
  }

  public function view($keyword = NULL){
    $data['promo'] = $this->promo_model->get_promos($keyword);

    if (empty($data['promo'])){
      show_404();
    }

    $data['title'] = $data['promo']['keyword'];

    $this->load->view('templates/header', $data);
    $this->load->view('promo/view', $data);
    $this->load->view('templates/footer');
  }

  public function create(){
    $this->load->helper('form');
    $this->load->library('form_validation');

    $data['title'] = 'Create a promo';

    $this->form_validation->set_rules('keyword', 'Keyword', 'required|is_unique[PROMO.keyword]');
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('description', 'Description', 'required');
    $this->form_validation->set_rules('expiry', 'Expiry', 'required|callback_datetime_valid', array('datetime_valid' => 'Invalid date received'));
    $this->form_validation->set_rules('renewal', 'Renewal', 'required|numeric');

    if ($this->form_validation->run() === FALSE){
        $this->load->view('templates/header', $data);
        $this->load->view('promo/create');
        $this->load->view('templates/footer');

    }
    else{
        $this->promo_model->set_promo();
        $this->load->view('promo/index');
    }
  }

  public function datetime_valid($datetime){
    $year = (int) substr($datetime, 0, 4);
    $month = (int) substr($datetime, 5, 2);
    $day = (int) substr($datetime, 8, 2);

    $hour = (int) substr($datetime, 11, 2);
    $min = (int) substr($datetime, 14, 2);

    return checkdate($month, $day, $year) and ($hour <= 24) and ($min < 60);
  }

  public function update(){
    
  }

  public function delete($keyword = NULL){
    $this->promo_model->delete_promo($keyword);
    $this->load->view('promo/index');
  }

}

/* End of file Promo.php */
/* Location: .//c/users/eizerr~1/appdata/local/temp/localhost.localdomain-76hrbi/Promo.php */