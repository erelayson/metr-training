<?php
class News extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('news_model');
    $this->load->helper('url_helper');
  }

  public function index(){
    $this->load->helper('form');
    $data['news'] = $this->news_model->get_news();
    $data['title'] = 'News archive';

    $this->load->view('templates/header', $data);
    $this->load->view('news/index', $data);
    $this->load->view('templates/footer');
  }

  public function view($id = NULL){
    $data['news_item'] = $this->news_model->get_news($id);

    if (empty($data['news_item'])){
      show_404();
    }

    $data['title'] = $data['news_item']['title'];

    $this->load->view('templates/header', $data);
    $this->load->view('news/view', $data);
    $this->load->view('templates/footer');
  }

  public function create(){
    $this->load->helper('form');
    $this->load->library('form_validation');

    $data['title'] = 'Create a news item';

    $this->form_validation->set_rules('title', 'Title', 'required');
    $this->form_validation->set_rules('text', 'Text', 'required');

    if ($this->form_validation->run() === FALSE){
        $this->load->view('templates/header', $data);
        $this->load->view('news/create');
        $this->load->view('templates/footer');

    }
    else{
        $this->news_model->set_news();
        $this->load->view('news/success_insert');
    }
  }

  public function edit($id = NULL){
    $this->load->helper('form');
    $this->load->library('form_validation');
    $data['news_item'] = $this->news_model->get_news($id);

    if (empty($data['news_item'])){
      show_404();
    }

    $data['title'] = $data['news_item']['title'];

    $this->form_validation->set_rules('title', 'Title', 'required');
    $this->form_validation->set_rules('text', 'Text', 'required');

    if ($this->form_validation->run() === FALSE){
        $this->load->view('templates/header', $data);
        $this->load->view('news/update', $data);
        $this->load->view('templates/footer');

    }
    else{
        $this->news_model->update_news();
        $this->load->view('news/success_update');
    }
  }

  public function delete($id = NULL){

    $this->news_model->delete_news($id);
    $this->load->view('news/success_delete');
  }
}