<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Dashboard extends CI_Controller {
 
    private $STATUS;

    function Dashboard()
    {
        parent::__construct();
 
        $this->STATUS = array(
                    0 => 'To Do',
                    1 => 'In Progress',
                    2 => 'Testing',
                    3 => 'Done'
                );

        if(!$this->session->userdata('logged'))
            redirect('login');
    }
 
    public function index()
    {
        //Load models
        $this->load->model('project_model');
        $this->load->model('task_model');
         
        //Load projects
        $projects = $this->project_model->get_user_related($this->session->userdata('user'));
         
        foreach ($projects as $key => $project) {
            $projects[$key]['tasks'] = $this->task_model->get_project_user_tasks($project['id'], $this->session->userdata('user'));
        }
     
        $data['projects'] = $projects;
         
        // Load tasks
        $data['tasks'] = $this->task_model->get_user_tasks($this->session->userdata('user'));
         
        $data['page_title']  = "Dashboard";
        $data['status'] = $this->STATUS;
         
        // Load View
        $this->template->show('dashboard', $data);
    }

}