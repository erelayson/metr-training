<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promosku extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('promosku_model');
    $this->load->helper('url_helper');
  }

	public function index(){
		$data['title'] = 'Promo SKU List';

    $data['promoskus'] = $this->promosku_model->get_promoskus();

    $this->load->view('templates/header', $data);
    $this->load->view('promosku/index', $data);
    $this->load->view('templates/footer');
	}

  public function view($keyword = NULL){
    $data['promosku'] = $this->promosku_model->get_promoskus($keyword);

    if (empty($data['promosku'])){
      show_404();
    }

    $data['title'] = "";

    $this->load->view('templates/header', $data);
    $this->load->view('promosku/view', $data);
    $this->load->view('templates/footer');
  }

  public function create(){
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->model('promo_model');

    $data['title'] = 'Create a Promo SKU';
    $data['promos'] = $this->promo_model->get_promos();

    $this->form_validation->set_rules('keyword', 'Keyword', 'required|is_unique[PROMO_SKU.keyword]|max_length[30]');
    $this->form_validation->set_rules('name', 'Name', 'required|max_length[30]');
    $this->form_validation->set_rules('description', 'Description', 'required');
    $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than_equal_to[0]');
    $this->form_validation->set_rules('promo', 'Promo', 'required');

    if ($this->form_validation->run() === FALSE){
        $this->load->view('templates/header', $data);
        $this->load->view('promosku/create');
        $this->load->view('templates/footer');

    }
    else{
      $status_query = $this->promosku_model->check_promo_status($_POST['promo']);
      $this->promosku_model->set_promosku($status_query['activated']);
      redirect('promosku');
    }
  }

  public function delete($keyword = NULL){
    $this->promosku_model->delete_promosku($keyword);
    redirect('promosku');
  }

}