<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*

The MIT License (MIT)

Copyright (c) 2014 Ibnu Syuhada <ibnusyuhadap3@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/

// Version = 0.0.1

class Acl
{
	// get an error message when login process is fail, so set this with public
    public $error_message;
	
	// set user variable
    private $u;
	
	// set role variable
    private $r;
	
	// set ci configuration
	private $ci;
	
	// set permissions variable
	private $p;
    
	// initialize acl class
    public function __construct()
    {
		// set real ci configuration
        $this->ci =& get_instance();
        
		// set real user, role, and permission. I like to do this, because in this class, I want to split user data into
		// two part, first for current user, second for other user if needed
		// doing this to protect methods from direct access without control. 
        //$this->u = new User();
        //$this->r = new Role();
		//$this->p = new Permission();
    }
    
	// this method to detect login status
    public function get_login($email,$password)
    {
        $this->u = new User();
        $this->u->email = $email;
        $this->u->password = $password;
        if($this->u->login())
        {
            $this->ci->session->set_userdata("id",$this->u->id);
            $this->ci->session->set_userdata("logged_in",TRUE);
            return TRUE;
        }else{
            $this->error_message = $this->u->error->login;
            return FALSE;
        }
    }
        
	// get current status, this status will used to protect sytem acl from direct access
    private function current_user()
    {
		if(!$this->ci->session->userdata("logged_in"))
        {
            return FALSE;
        }else{
            $this->u = new User();
			$this->u->get_by_id($this->ci->session->userdata("id"));
            if($this->u->exists())
            {
				return $this->u;
            }else{
                return FALSE;
            }
        }
    }
    
	// method to get grand data of a user, this function must pass with current_user is true
	public function get_user($id="")
    {
		if($this->current_user())
        {
			if($id == "")
			{
				// if id is not set then it is mean current user
				return $this->current_user();
			}else{
                $this->u = new User();
				$this->u->get_by_id($id);
                if($this->u->exists())
                {
                    return $this->u;
                }else{
                    return FALSE;
                }
			}
        }else{
			return FALSE;
        }
    }
    
	// method to get user role, if id is not specified, then it is mean current user
    // I am not a fan of one user with multi role
	// so the result is not an array
	public function get_user_role($id="")
    {
        if($this->current_user())
        {
			if($id == "")
			{
                return array('id'=>$this->get_user()->role->get()->id,'name'=>$this->get_user()->role->get()->name,'has_parent'=>$this->get_user()->role->get()->has_parent);
			}else{
                return array('id'=>$this->get_user()->role->get()->id,'name'=>$this->get_user()->role->get()->name,'has_parent'=>$this->get_user()->role->get()->has_parent);
			}
        }else{
            return FALSE;
        }
    }
        
	// method to get user permissions, if id is not definied, then it is mean current user
	// one role may be contain many permissions, so the output is an array
    public function get_user_permissions($id="")
    {
        if($this->current_user())
        {
			if($id == "")
			{
				$setpermission = $this->get_user()->role->get()->permission->get();
			}else{
				$setpermission = $this->get_user($id)->role->get()->permissions->get();
			}
			$inc = -1;
			$getpermissions = array();
			foreach($setpermission as $permissions)
			{
				$inc += 1;
				$getpermissions[$inc] = $permissions->key;
			}
            return $getpermissions;				
        }else{
            return FALSE;
        }
    }
    
	// method to get permissions of a role
	// the output is an array
    public function get_role_permissions($role)
    {                
        if($this->current_user())
        {
			$this->r = new Role();
			$this->r->where("name",$role);
            $setpermissions = $this->r->get()->permission->get();
			$inc = -1;
			$getpermissions = array();
            foreach($setpermissions as $permissions)
			{
				$inc += 1;
				$getpermissions[$inc] = $permissions->key;
			}
			return $getpermissions;
        }else{
            return FALSE;
        }
    }
	
	// method to get grand hierarchy of access level
	// this method very useful to make hierarchy diagram
	public function access_level()
	{
		if($this->current_user())
        {
			$r = new Role();
			$this->r = new Role();
			$r->select('*,(SELECT COUNT(DISTINCT a.id) FROM ' .$this->ci->config->config['datamapper']['prefix']. 'roles AS a WHERE a.has_parent = ' .$this->ci->config->config['datamapper']['prefix']. 'roles.id) AS childcount');
			$r->get();
			$inc = -1;
			$hasil = array();
			foreach($r as $row)
			{
				$inc += 1;
				$hasil[$inc]['id'] = $row->id;
				$hasil[$inc]['name'] = $row->name;
				$hasil[$inc]['has_parent'] = $row->has_parent;
				$hasil[$inc]['has_child'] = $row->childcount;
			}
			return $hasil;
		}else{
			return FALSE;
		}
	}
	
	// method to check relationship between user with specific access level
	// parent level can see child but not for vice versa
	// same level can see
	// id is id of role
	public function can_see($id)
	{
		if($this->current_user())
        {
			$user_role_id = $this->get_user_role()['id'];
			$user_role_has_parent = $this->get_user_role()['has_parent'];
			if($user_role_id == $id)
			{
				return TRUE;
			}elseif($user_role_has_parent == $id)
			{
				return FALSE;
			}else{
				$data = $this->access_level();
				foreach($data as $item)
				{
					if($item['id'] == $user_role_id)
					{
						if($item['has_child'] > 0)
						{
							$x = 0;
							$parent = $item['id'];
							$result = FALSE;
							while($x < 1)
							{
								foreach($data as $level)
								{
									if($level['has_parent'] == $parent)
									{
										if($level['id'] == $id)
										{
											$x = 1;
											$result = TRUE;
											break;
										}else{
											if($level['has_child'] > 0)
											{
												$parent = $level['id'];
												break;
											}else{
												$x = 1;
												$result = FALSE;
												break;
											}
										}
									}
								}
							}
							return $result;
							break;
						}else{
							return FALSE;
							break;
						}
					}
				}
			}
		}else{
			return FALSE;
		}
	}
}