<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Project extends CI_Controller {
     
    function Project()
    {
        parent::__construct();
         
        if(!$this->session->userdata('logged'))
            redirect('login');
    }
     
    public function index()
    {
        redirect('dashboard');
    }
    
    /*

    Project(): construtor da classe. Carrega o construtor da classe pai e verifica se o usuário está logado.
    
    index(): método padrão. Como este controlador não será acessado diretamente através do endereço /project, vamos redirecionar o usuário para o controlador Dashboard.
    
    tasks($project_id): este é o método responsável por exibir o quadro de tarefas propriamente dito. Este quadro vai conter quatro colunas identificando o estágio (ou status) de cada tarefa a ser executada. Como vamos fazer a parte das tarefas somente no próximo artigo, deixei um “TODO” para depois. Após o TODO, carregamos os dados do projeto de acordo com o ID e definimos algumas variáveis para a visão: o título da página (page_title), o projeto, o usuário atual e um array com todos os usuários relacionados ao projeto. Este último é criado a partir do resultado do método get_related_users, que retornará um array de usuários. Este array será reorganizado de maneira que o ID seja a chave (array[ID] = user). Finalmente, carregamos a visão “task_board” utilizando a biblioteca de template.
    
    add(): método para adicionar um novo projeto. Além de definir as variáveis básicas do projeto, também definimos o array de usuários relacionados ao projeto. Quando percorremos esse array, se o ID for igual ao ID do usuário logado ($value['id'] == $this->session->userdata(‘user’)), atribuímos 1 para indicar que este usuário está selecionado – na visão isso será reproduzido em checkboxes de usuários relacionados. Depois carregamos a visão “project_add”.
    
    edit($id): método para editar um projeto. Semelhante ao método add(), porém este recebe um ID como parâmetro e carrega os dados de acordo com esse ID de projeto.
    
    save(): método responsável por salvar os dados do projeto no banco de dados. Primeiramente carregamos o modelo dos projetos, depois definimos o array a ser inserido, utilizando o usuário da sessão e o nome e descrição do projeto enviados por post. Na sequência recebemos o ID do projeto, caso seja um novo projeto a variável $project_id será falso. Assim podemos identificar se é um novo projeto e criar (create($sql_data)) ou atualizar (update($project_id, $sql_data)) de acordo. Posteriormente apagamos os usuários relacionados do banco de dados e criamos novos registros de acordo com o que o usuário selecionou. Finalmente, redirecionamos o usuário para o método tasks(). Se ocorreu algum problema e a variável $project_id for 0 ou false, o usuário será redirecionado para o controlador.



    */


    public function tasks($project_id)
    {
        // Load tasks
        $this->load->model('task_model');
        $tasks = $this->task_model->get($project_id);
         
        foreach ($tasks as $task) {
            if ($task['status'] == 0) {
                $data['stories'][] = $task;
            } elseif ($task['status'] == 1) {
                $data['tasks'][] = $task;
            } elseif ($task['status'] == 2) {
                $data['tests'][] = $task;
            } elseif ($task['status'] == 3) {
                $data['done'][] = $task;
            }
        }
 
        // Load project info
        $this->load->model('project_model');
        $project = $this->project_model->get($project_id);
         
        $data['page_title'] = "Project: ".$project['name'];
        $data['project']    = $project_id;
         
        $data['current_user'] = $this->session->userdata('user');
         
        $db_users = $this->project_model->get_related_users($project_id);
        $users = array();
        foreach ($db_users as $user) {
            $users[$user['id']] = $user;
        }
        $data['users'] = $users;
         
        // Load text helper to be used in the view
        $this->load->helper('text');
         
        // Load View
        $this->template->show('task_board', $data);
    }
 
    public function add()
    {
        $this->load->model('project_model');
         
        $data['page_title']  = "New Project";
        $data['user']        = '';
        $data['name']        = '';
        $data['description'] = '';
         
        $users = $this->project_model->get_related_users();
        foreach ($users as $key => $value) {
            if($value['id'] == $this->session->userdata('user'))
                $users[$key]['project'] = 1;
            else
                $users[$key]['project'] = 0;
        }
        $data['users'] = $users;
         
        $this->template->show('project_add', $data);
    }
 
    public function edit($id)
    {
        $this->load->model('project_model');
         
        $data = $this->project_model->get($id);
         
        $data['page_title']  = "Edit Project #".$id;
        $data['project']  = $id;
        $data['users'] = $this->project_model->get_related_users($id);
         
        $this->template->show('project_add', $data);
    }
     
    public function save()
    {
        $this->load->model('project_model');
         
        $sql_data = array(
            'user'        => $this->session->userdata('user'),
            'name'        => $this->input->post('name'),
            'description' => $this->input->post('description')
        );
         
        $project_id = $this->input->post('id');
         
        if ($project_id)
            $this->project_model->update($project_id,$sql_data);
        else
            $project_id = $this->project_model->create($sql_data);
             
        // Related users
        $this->project_model->delete_related($project_id);
         
        $users = $this->input->post('users');
        foreach ($users as $user) {
            $sql_data = array(
                'user' => $user,
                'project' => $project_id
            );
            $this->project_model->create_related($sql_data);
        }
 
        if ($project_id)
            redirect('project/tasks/'.$project_id);
        else
            redirect('project');
    }
}