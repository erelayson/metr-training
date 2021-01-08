<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promosku_model extends CI_Model {

	public function __construct(){
    $this->load->database();
  }

  public function get_promoskus($keyword = FALSE){
    if ($keyword === FALSE){
      $query = $this->db->get('PROMO_SKU');
      return $query->result_array();
    }
    $query = $this->db->get_where('PROMO_SKU', array('keyword' => $keyword));
    return $query->row_array();
  }

  public function check_promo_status($keyword = NULL){
    if ($keyword){
      $query = $this->db->select('activated')->get_where('PROMO', array('keyword' => $keyword));
      return $query->row_array();
    }
  }

  public function set_promosku($status){
    $this->load->helper('url');

    $data = array(
        'keyword' => $this->input->post('keyword'),
        'name' => $this->input->post('name'),
        'description' => $this->input->post('description'),
        'price' => $this->input->post('price'),
        'promo' => $this->input->post('promo'),
        'status' => $status
    );

    return $this->db->insert('PROMO_SKU', $data);
  }

  public function delete_promosku($keyword = FALSE){
    $this->load->helper('url');
    $this->db->delete('PROMO_SERVICE_SKU', array('promo_sku' => $keyword));
    return $this->db->delete('PROMO_SKU', array('keyword' => $keyword));
  }
}