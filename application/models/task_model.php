<?php
 
class Task_model extends CI_Model {
 
    /*

    update($project, $id, $data): o método de atualização dos dados recebe como argumento o ID do projeto e da tarefa que está sendo editada.
    
    get($project, $id = false, $status = false): o método get busca os dados das tarefas de acordo com o projeto. Se o $id for informado, o método busca os dados dessa tarefa. Se o $status for informado, o método retorna as tarefas de acordo com o status (que indica a fase) – isso será utilizado para montar o quadro de tarefas.
    
    get_user_tasks($user): busca as tarefas de um usuário.
    
    get_project_user_tasks($project, $user): busca as tarefas de um usuário em um projeto.
    
    get_related_users($project): busca os usuários relacionados a um projeto.


    */

    public function create($data)
    {
        $this->db->select_max('id');
        $this->db->where('project', $data['project']);
        $get = $this->db->get('task');
         
        if($get->num_rows > 0) {
            $row = $get->row_array();
            $data['id'] = $row['id'] + 1;
        } else
            $data['id'] = 1;
         
        $insert = $this->db->insert('task', $data);
        return $insert;
    }
 
 
    public function update($project, $id, $data)
    {
        $this->db->where('project', $project);
        $this->db->where('id', $id);
        $update = $this->db->update('task', $data);
        return $update;
    }
 
    public function get($project, $id = false, $status = false)
    {
        $this->db->where('project', $project);
        if ($id) $this->db->where('id', $id);
        if ($status) $this->db->where('status', $status);
        $this->db->order_by('status', 'asc');
        $this->db->order_by('priority', 'asc');
        $get = $this->db->get('task');
 
        if ($id) return $get->row_array();
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
     
    public function get_user_tasks($user)
    {
        $this->db->select('t.*');
        $this->db->distinct();
        $this->db->from('task t');
        $this->db->where('t.user', $user);
        $this->db->where('t.status !=', 3);
        $this->db->order_by('t.status', 'desc');
        $get = $this->db->get();
 
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
     
    public function get_project_user_tasks($project, $user)
    {
        $this->db->select('t.*');
        $this->db->distinct();
        $this->db->from('task t');
        $this->db->where('t.user', $user);
        $this->db->where('t.project', $project);
        $this->db->where('t.status !=', 3);
        $this->db->order_by('t.status', 'desc');
        $get = $this->db->get();
 
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
     
    public function get_related_users($project)
    {
        $this->db->select('u.*, up.project');
         
        $this->db->from('user u');
        $this->db->join('user_project up', 'up.user = u.id and up.project = '.$project, 'left');
        $this->db->order_by('u.email', 'asc');
        $get = $this->db->get();
 
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
 
    public function delete($project, $id)
    {
        $this->db->where('project', $project);
        $this->db->where('id', $id);
        $this->db->delete('task');
    }
 
}