<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Dashboard extends CI_Controller {
 
    function Dashboard()
    {
        parent::__construct();
 
        if(!$this->session->userdata('logged'))
            redirect('login');
    }
 
    public function index()
    {
        $data['page_title']  = "Dashboard";
 
        // Load View
        $this->template->show('dashboard', $data);
    }
 
}