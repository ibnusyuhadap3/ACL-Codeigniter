<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends DataMapper 
{
	var $has_one = array(
		"role" => array(
			"join_table" => "ci_roles_users"
		)
	);
		
	function __construct($id=NULL)
	{
		parent::__construct($id);
	}
	var $validation = array(
		'password' =>array(
			'label' => 'Password',
			'rules' => array('required','trim','encrypt')
		),
		'email' => array(
			'label' => 'Email',
			'rules' => array('required','valid_email','trim')
		)
	);
	
	function login()
	{
		$u = new User();
		$u->where('email',$this->email)->get();
		$this->validate()->get();
		if($this->exists())
		{
			return TRUE;
		}else{
			$this->error_message('login','<b class="text-danger">Wrong password or email</b>');
			return FALSE;
		}
	}
	
	function _encrypt($field)
	{
		if(!empty($this->{$field}))
		{
			$salt = sha1(md5($this->{$field}));
			$this->{$field} = md5($salt . $this->{$field});
		}
	}
	
	function post_model_init($from_cache = FALSE)
	{
	}
	
}

/* End of file template.php */
/* Location: ./application/models/template.php */
