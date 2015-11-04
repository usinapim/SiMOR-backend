<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prueba extends CI_Controller {
    
    public function hola() {
        $this->load->view('welcome_message');
    }
}
