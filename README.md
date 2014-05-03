ACL-Codeigniter
=======================================

ACL-Codeigniter is an access control list library for codeigniter. This library is created to help you
in making protection system based on permissions and access level.


INSTALLATION
=======================================

First, you should to install datamapper orm in your codeigniter (I assume you have been installed datamapper successfully). Download ACL-Codeigniter. After that, follow this steps:
<ol>
<li>Open datamapper.php inside application/library of your codeigniter, then change <pre><code>$config['prefix'] = '' </code></pre> with <pre><code>$config['prefix'] = 'ci_'</code></pre></li>
<li>On your database, create tables based on sql folder. So in your database, you have tables like below:
<ol>
<li>ci_roles</li>
<li>ci_users</li>
<li>ci_roles_users</li>
<li>ci_permissions</li>
<li>ci_roles_permissions</li>
</ol>
</li>
<li>In ACL-Codeigniter folder, inside application/library, copy Acl.php to application/library of your codeigniter.</li>
<li>In ACL-Codeigniter folder that you have downloaded, inside application/library, copy role.php, user.php, permission.php, to application/library of your codeigniter</li>
</ol>

USAGE
========================================

Lets take a simple case. You have welcome controller, but you want user must login and have access to watch welcome_message view, so here a simple code

<pre><code>&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->library("acl");
		$status = $this->acl->get_login('contoh@contoh.com','coba');
		if($status and $this->acl->get_user_permissions()[0] == 'site_login')
		{
			$this->load->view('welcome_message');				
		}else{
			echo 'you can't access the content';
		}
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
</code></pre>

Lets take another example, your welcome_message view has "editor" as access level. You want welcome_message view only can open if someone has same level or parent level.
This mean, welcome_message view can't open if level someone is a child. So, here a simple code

<pre><code>
&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->library("acl");
		$status = $this->acl->get_login('contoh@contoh.com','coba');
		if($status and $this->acl->get_user_permissions()[0] == 'site_login' and $this->acl->can_see(3))
		{
			$this->load->view('welcome_message');				
		}else{
			echo 'you can't access the content';
		}
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
</code></pre>

METHODS
===================================
<ol>
<li>
Check login method<br>
<pre><code>$this->acl->get_login($email, $password)</code></pre>
This method is to check email and password pair for login.
</li>
<li>
Get grand user data<br>
<pre><code>$this->acl->get_user()</code></pre>
This method is to get grand current user data. If you want to get grand data of specific user, then do like below
<pre><code>$this->acl->get_user(1)</code></pre>
This method is to get grand data of user with id = 1.
</li>
<li>
Get user role<br>
<pre><code>$this->acl->get_user_role()</code></pre>
This method is to get role of current user. I am not a fan of one user many roles. So this result will only show one user one role.
If you want to get role of specific user, do like below
<pre><code>$this->acl->get_user_role(1)</code></pre>
This method is to get role of user with id = 1.
</li>
<li>
Get user permissions<br>
<pre><code>$this->acl->get_user_permissions()</code></pre>
This method is to get permissions of current user. If you want to get permissions of specific user, do like below
<pre><code>$this->acl->get_user_permissions(1)</code></pre>
This method is to get permissions of user with id = 1.
<li>
Get grand data of access level<br>
<pre><code>$this->acl->access_level()</code></pre>
This method is to get access level between each roles. You can see who is parent or who is child.
</li>
<li>
Check if user has access to see<br>
<pre><code>$this->acl->can_see($id)</code></pre>
This method is to check if role of someone has access to see specific role (based on id). If specific role is same level or child of current user role, then this method will return true.
</li>
</ol>
