<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Permission extends DataMapper 
{
	var $has_many = array(
		"role" => array(
			"join_table" => "ci_permissions_roles",
			"reciprocal" => TRUE
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