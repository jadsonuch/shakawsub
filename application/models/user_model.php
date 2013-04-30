<?php
class User_model extends CI_Model {

    private $salt = 'r4nd0m';
    public $USER_LEVEL_ADMIN = 1;
    public $USER_LEVEL_PM = 2;
    public $USER_LEVEL_DEV = 3;


	public function get($id = false)
	{
	    if ($id) $this->db->where('id', $id);
	    $this->db->order_by('email', 'asc');
	    $get = $this->db->get('user');
	    if($id) return $get->row_array();
	    if($get->num_rows > 0) return $get->result_array();
	    return array();
	}

	public function validate($email, $password)
	{
	    $this->db->where('email', $email)->where('password', sha1($password.$this->salt));
	    $get = $this->db->get('user');
	 
	    if($get->num_rows > 0) return $get->row_array();
	    return array();
	}

}