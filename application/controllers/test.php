<?php
  class Test extends CI_Controller{
    
    public function __construct()
    {
      parent::__construct();      
      //$this->fbauth = $this->config->item('fbauth');
      //$this->load->library('facebook',$this->fbauth);
      //$this->load->helper('date');
    }
    
    public function index(){
      echo 'ようこそsynergystudy.meへ';
    }
    
  }
?>