<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Role extends DataMapper 
{
	var $has_many = array(
		"user",
		"permission" => array(
			"join_table" => "ci_permissions_roles",
			"reciprocal" => TRUE			
		),
		'related_role' => array(
            'class' => 'role',
            'other_field' => 'role',
            'reciprocal' => TRUE
        ),
        'role' => array(
            'other_field' => 'related_role',
            'reciprocal' => TRUE
        )
	);
	
	function __construct($id=NULL)
	{
		parent::__construct($id);
	}
	
	function post_model_init($from_chache = FALSE)
	{
	}
}