<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    
    private $LEVEL;
    
	private $error = false;

    function User() {
        parent::__construct();
        if(!$this->session->userdata('logged'))
            redirect('login');
 
        $this->LEVEL = array(
            1 => 'Full Access',
            2 => 'Project Manager',
            3 => 'Developer'
        );
    }
 
	public function index()
	{
	    // Load open transports
	    $this->load->model('user_model');
	    $data['users'] = $this->user_model->get(false);
	    $data['level_list'] = $this->LEVEL;
	 
	    $data['page_title']  = "Users";
	 
	    // Load View
	    $this->template->show('users', $data);
	}

	public function add()
	{
	    $data['page_title']  = "New User";
	    $data['email']    = '';
	    $data['password'] = '';
	    $data['level']    = '1';
	    $data['level_list'] = $this->LEVEL;
	 
	 	if($this->error)
    		$data['error'] = $this->error;

	    $this->template->show('users_add', $data);
	}
	 
	public function edit($id)
	{
	    $this->load->model('user_model');
	    $data = $this->user_model->get($id);
	 
	    $data['password'] = '';
	    $data['page_title']  = "Edit User #".$id;
	 
	    $data['level_list'] = $this->LEVEL;
	 
		if($this->error)
    		$data['error'] = $this->error;

	    $this->template->show('users_add', $data);
	}

	public function remove($id)
	{
	    $this->load->model('user_model');
	    $this->user_model->delete($id);
	 
	    redirect('user');
	}

	/*public function save()
	{
		//A primeira coisa que ele faz é carregar o user_model.
	    $this->load->model('user_model');
	 
	 	//Então nós buscamos os valores de POST – primeiro o email e o nível (level).
	    $sql_data = array(
	        'email'    => $this->input->post('email'),
	        'level'    => $this->input->post('level')
	    );
	 
	 	//Depois nós verificamos se o usuário vai definir uma nova senha (no caso de novos registros isso sempre será feito).
	 	//Se a senha deve ser modificada, nós também buscamos o valor do POST.
	    if($this->input->post('reset_password')){
	        $sql_data['password'] = $this->input->post('password');
	    }
	 
	 	//Para identificar se precisamos atualizar ou criar uma nova entrada, nós verificamos o ID. Se o campo escondido com o ID foi definido, nós atualizamos o registro. Caso contrário nós criamos uma nova.
	    if ($this->input->post('id'))
	        $this->user_model->update($this->input->post('id'),$sql_data);
	    else
	        $this->user_model->create($sql_data);
	 
	 	//Na última linha nós redirecionamos o usuário de volta para o controlador user, que vai carregar o método index por padrão.
	    redirect('user');
	}*/

	public function save()
	{
	    if($this->input->post('cancel') !== FALSE)
	        redirect('user');
	 
	    $user_id = $this->input->post('id');
	 
	    $this->load->library('form_validation');
	 
	    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
	    $this->form_validation->set_rules('password', 'Password', 'trim|required');
	    $this->form_validation->set_rules('level', 'Level', 'required');
	 
	    if($this->form_validation->run() === false)  {
	        $this->error = true;
	 
	        if ($user_id)
	            $this->edit ($user_id);
	        else
	            $this->add ();
	 
	        return;
	    }
	 
	    $this->load->model('user_model');
	 
	    $sql_data = array(
	        'email'    => $this->input->post('email'),
	        'level'    => $this->input->post('level')
	    );
	 
	    if($this->input->post('reset_password')){
	        $sql_data['password'] = $this->input->post('password');
	    }
	 
	    if ($user_id)
	        $this->user_model->update($user_id,$sql_data);
	    else
	        $this->user_model->create($sql_data);
	 
	    redirect('user');
	}

}