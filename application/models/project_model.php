<?php
 
class Project_model extends CI_Model {
 
    /*

    create($data): cria um registro no banco de dados, tabela “project”. Se correr tudo bem retorna o ID do projeto utilizando $this->db->insert_id(). Esta função retornará o ID da última entrada inserida, sempre que a tabela tiver uma chave simples.
    
    create_related($data): cria um registro na tabela relacionamento “user_project” e retorna se correu tudo bem.
    
    update($id, $data): atualiza um registro na tabela “project” utilizando o $id no where.
    
    get($id = false): busca dados de um ou mais projetos. Se o ID for passado como parâmetro, a cláusula where será definida e apenas o projeto com esse ID será buscado no banco de dados. Caso contrário todos os projetos serão retornados. Em ambos em casos vamos retornar apenas o array (row_array() ou result_array()). Poderíamos retornar todo o objeto (row() ou result()), mas nesse caso não precisamos. Caso não encontre nenhum projeto, retornamos uma array vazio (dessa maneira podemos utilizar em um foreach sem problemas). É importante notar que em cada return, o método termina naquele momento, por isso não precisamos de um else em cada if. Por exemplo, se o $id foi passado como parâmetro o primeiro return será executado e o método terminará nesse comando, as duas linhas depois disso não serão executadas.
    
    get_user_owned($user): busca os projetos criados pelo usuário $user.
    
    get_user_related($user): busca os projetos relacionados com o usuário $user. Isso é feito através do join com a tabela relacionamento “user_project”.
    
    get_related_users($id = false): busca os usuários relacionados com o projeto. Se o ID não for enviado, todos os usuários são retornados. Isso é para o caso de um novo projeto estar sendo criado – quando precisamos buscar todos os usuários para que se possa relacionar quais estarão associados ao projeto. Caso o ID seja passado como parâmetro, o join será do tipo left join (o último parâmetro do $this->db->join). Assim todos os usuário serão retornados, mas os que estiverem relacionados vão conter o campo user_project.project, enquanto os não relacionados vão conter NULL.
    
    delete($id): método para apagar um projeto.
    
    delete_related($id): método para apagar o relacionamento para um projeto.

    */

    public function create($data)
    {
        $insert = $this->db->insert('project', $data);
        if($insert)
            return $this->db->insert_id();
        else
            return false;
    }
 
    public function create_related($data)
    {
        $insert = $this->db->insert('user_project', $data);
        return $insert;
    }
 
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $update = $this->db->update('project', $data);
        return $update;
    }
 
    public function get($id = false)
    {
        if ($id) $this->db->where('id', $id);
        $this->db->order_by('name', 'asc');
        $get = $this->db->get('project');
 
        if($id) return $get->row_array();
        if($get->num_rows > 1) return $get->result_array();
        return array();
    }
     
    public function get_user_owned($user)
    {
        $this->db->where('user', $user);
        $this->db->order_by('name', 'asc');
        $get = $this->db->get('project');
 
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
     
    public function get_user_related($user)
    {
        $this->db->select('p.*, u.project');
        $this->db->from('project p');
        $this->db->join('user_project u', 'p.id = u.project');
        $this->db->where('u.user', $user);
        $this->db->order_by('p.name', 'asc');
        $get = $this->db->get();
 
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
     
    public function get_related_users($id = false)
    {
        if($id)
            $this->db->select('u.*, up.project');
        else
            $this->db->select('u.*');
         
        $this->db->from('user u');
        if($id)
            $this->db->join('user_project up', 'up.user = u.id and up.project = '.$id, 'left');
        $this->db->order_by('u.email', 'asc');
        $get = $this->db->get();
 
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
 
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('project');
    }
 
    public function delete_related($id)
    {
        $this->db->where('project', $id);
        $this->db->delete('user_project');
    }
}