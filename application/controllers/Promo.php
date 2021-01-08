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

  public function search(){
    $data['title'] = 'Search results';

    $data['promos'] = $this->promo_model->search_promos($_POST['search']);

    $this->load->view('templates/header', $data);
    $this->load->view('promo/index', $data);
    $this->load->view('templates/footer');
  }

  public function view($keyword){
    if ($keyword == NULL){
      show_404();
    }

    $data['promo'] = $this->promo_model->get_promos($keyword);

    if (empty($data['promo'])){
      show_404();
    }

    $data['title'] = "";

    $this->load->view('templates/header', $data);
    $this->load->view('promo/view', $data);
    $this->load->view('templates/footer');
  }

  public function create(){
    $this->load->helper('form');
    $this->load->library('form_validation');

    $data['title'] = 'Create a promo';

    $this->form_validation->set_rules('keyword', 'Keyword', 'required|is_unique[PROMO.keyword]|max_length[30]');
    $this->form_validation->set_rules('name', 'Name', 'required|max_length[30]');
    $this->form_validation->set_rules('description', 'Description', 'required');
    $this->form_validation->set_rules('expiry', 'Expiry', 'required|numeric|greater_than_equal_to[1]');
    $this->form_validation->set_rules('renewal', 'Renewal', 'required|numeric|greater_than_equal_to[0]');

    if ($this->form_validation->run() === FALSE){
        $this->load->view('templates/header', $data);
        $this->load->view('promo/create');
        $this->load->view('templates/footer');

    }
    else{
        $this->promo_model->set_promo();
        redirect('promo');
    }
  }

  public function edit($keyword = NULL){
    $this->load->helper('form');
    $this->load->library('form_validation');
    $data['promo'] = $this->promo_model->get_promos($keyword);

    if (empty($data['promo'])){
      show_404();
    }

    $data['title'] = $data['promo']['keyword'];

    $this->form_validation->set_rules('name', 'Name', 'required|max_length[30]');
    $this->form_validation->set_rules('description', 'Description', 'required');
    $this->form_validation->set_rules('expiry', 'Expiry', 'required|numeric');
    $this->form_validation->set_rules('renewal', 'Renewal', 'required|numeric|greater_than_equal_to[0]');

    if ($this->form_validation->run() === FALSE){
        $this->load->view('templates/header', $data);
        $this->load->view('promo/update', $data);
        $this->load->view('templates/footer');

    }
    else{
        echo $this->promo_model->update_promo();
        redirect('promo');
    }
  }

  public function delete($keyword = NULL){
    $this->promo_model->delete_promo($keyword);
    redirect('promo');
  }

  public function toggle($keyword = NULL){
    $this->promo_model->toggle_promo($keyword);
    redirect('promo');
  }

}