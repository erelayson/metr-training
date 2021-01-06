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

  public function set_promo(){
    $this->load->helper('url');

    $data = array(
        'keyword' => $this->input->post('keyword'),
        'name' => $this->input->post('name'),
        'description' => $this->input->post('description'),
        'expiry' => $this->input->post('expiry'),
        'renewal' => $this->input->post('renewal')
    );

    return $this->db->insert('PROMO', $data);
  }

  public function update_promo($keyword = FALSE){
    $this->load->helper('url');

    $data = array(
        'name' => $this->input->post('name'),
        'description' => $this->input->post('description'),
        'expiry' => $this->input->post('expiry'),
        'renewal' => $this->input->post('renewal')
    );

    $this->db->where('keyword', $keyword);
    return $this->db->update('PROMO', $data);
  }

  public function delete_promo($keyword = FALSE){
    $this->load->helper('url');
    return $this->db->delete('PROMO', array('keyword' => $keyword));
  }
}

/* End of file Promo_model.php */
/* Location: .//c/users/eizerr~1/appdata/local/temp/localhost.localdomain-4lrtxd/Promo_model.php */