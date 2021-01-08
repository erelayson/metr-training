<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicesku_model extends CI_Model {

	public function __construct(){
    $this->load->database();
  }

  public function get_promoserviceskus(){
    $query = $this->db->get('PROMO_SERVICE_SKU');
    return $query->result_array();
  }

  public function get_serviceskus(){
    $query = $this->db->get('SERVICE_SKU');
    return $query->result_array();
  }

  public function set_servicesku(){
    $this->load->helper('url');

    $data = array(
        'promo_sku' => $this->input->post('promo_sku'),
        'service_sku' => $this->input->post('service_sku'),
    );

    return $this->db->insert('PROMO_SERVICE_SKU', $data);
  }

  public function delete_servicesku(){
    $this->load->helper('url');
    $this->db->delete('PROMO_SERVICE_SKU', array(
    	'promo_sku' => $this->input->post('promo_sku'),
    	'service_sku' => $this->input->post('service_sku')
    ));
  }

}