<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicesku extends CI_Controller {

	public function __construct(){
    parent::__construct();
    $this->load->model('servicesku_model');
    $this->load->helper('url_helper');
  }

  public function index(){
    $this->load->helper('form');
    $data['title'] = 'Service SKU List';

    $data['serviceskus'] = $this->servicesku_model->get_promoserviceskus();

    $this->load->view('templates/header', $data);
    $this->load->view('servicesku/index', $data);
    $this->load->view('templates/footer');
  }

  public function create(){
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->model('promosku_model');

    $data['title'] = 'Create a Service SKU';
    $data['promoskus'] = $this->promosku_model->get_promoskus();
    $data['serviceskus'] = $this->servicesku_model->get_serviceskus();

    $this->form_validation->set_rules('service_sku', 'Service SKU', 'required|callback_check_promo_service_sku');
    $this->form_validation->set_message('check_promo_service_sku', 'Promo Service SKU combination already exists.');
    $this->form_validation->set_rules('promo_sku', 'Promo SKU', 'required');

    if ($this->form_validation->run() === FALSE){
        $this->load->view('templates/header', $data);
        $this->load->view('servicesku/create');
        $this->load->view('templates/footer');

    }
    else{
      $this->servicesku_model->set_servicesku();
      redirect('servicesku');
    }
  }

  function check_promo_service_sku() {
    $this->db->where('service_sku', $this->input->post('service_sku'));
    $this->db->where('promo_sku', $this->input->post('promo_sku'));
    $query = $this->db->get('PROMO_SERVICE_SKU');
    $num = $query->num_rows();
    if ($num > 0) {
        return FALSE;
    } else {
        return TRUE;
    }
  }

  public function delete(){
    $this->servicesku_model->delete_servicesku();
    redirect('servicesku');
  }

}
