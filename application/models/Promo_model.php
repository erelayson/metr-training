<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promo_model extends CI_Model {

  public function __construct(){
    $this->load->database();
  }

  public function get_promos($keyword = FALSE){
    if ($keyword === FALSE){
      $query = $this->db->get('PROMO');
      return $query->result_array();
    }

    $query = $this->db->get_where('PROMO', array('keyword' => $keyword));
    return $query->row_array();
  }

  public function search_promos($string = FALSE){
    if ($string === FALSE){
      $query = $this->db->get('PROMO');
      return $query->result_array();
    }

    $query = $this->db->like('keyword', $string, 'both')->or_like('name', $string, 'both')->or_like('description', $string, 'both')->get('PROMO'); 
    return $query->result_array();
  }

  public function set_promo(){
    $this->load->helper('url');

    $data = array(
        'keyword' => $this->input->post('keyword'),
        'name' => $this->input->post('name'),
        'description' => $this->input->post('description'),
        'expiry' => $this->input->post('expiry'),
        'expiry_unit' => $this->input->post('expiry_unit'),
        'renewal' => $this->input->post('renewal')
    );

    return $this->db->insert('PROMO', $data);
  }

  public function update_promo($keyword = FALSE){
    $this->load->helper('url');

    $data = array(
      'keyword' => $this->input->post('keyword'),
      'name' => $this->input->post('name'),
      'description' => $this->input->post('description'),
      'expiry' => $this->input->post('expiry'),
      'expiry_unit' => $this->input->post('expiry_unit'),
      'renewal' => $this->input->post('renewal')
    );
    return $this->db->replace('PROMO', $data);
  }

  public function delete_promo($keyword = FALSE){
    $this->load->helper('url');
    $promo_skus = $this->db->get_where('PROMO_SKU', array('promo' => $keyword))->result_array();
    foreach ($promo_skus as $promo_sku) {
      $this->db->delete('PROMO_SERVICE_SKU', array('promo_sku' => $promo_sku['keyword']));
    }
    $this->db->delete('PROMO_SKU', array('promo' => $keyword));
    return $this->db->delete('PROMO', array('keyword' => $keyword));
  }

  public function toggle_promo($keyword = FALSE){
    $this->load->helper('url');

    $query_activated = $this->db->select(array('status','activated'))->get_where('PROMO', array('keyword' => $keyword));

    if ($query_activated->row_array()['status']) {
      $promo_data = array(
        'status' => '0'
      );
    } else {
      $promo_data = array(
        'status' => '1'
      );
      if (!$query_activated->row_array()['activated']){
        $promo_data['activated'] = '1';
        // Cascade activation
        $promosku_data = array(
          'status' => '1',
        );

        $this->db->where('promo', $keyword)->set($promosku_data);
        $this->db->update('PROMO_SKU');
      }
    }
    $this->db->where('keyword', $keyword)->set($promo_data);
    $this->db->update('PROMO');
  }
}