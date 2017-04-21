<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
		var $template_data = array();
		function __construct()
		{
			$this->CI =& get_instance();
		}
		function set($name, $value)
		{
			$this->template_data[$name] = $value;
		}
	
		function load($template = '', $view = '' , $view_data = array(), $return = FALSE)
		{               
			
			$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));			
			return $this->CI->load->view($template, $this->template_data, $return);
		}
		function load_main($view = '', $view_data = array(), $return = FALSE)
		{
			$user_id = $this->CI->session->userdata('adminuser')->id;
			$user_type = $this->CI->session->userdata('adminuser')->usertype;
			
			if($user_type=='a')
			{
				$query =   $this->CI->db->query("select * from site_modules");
			}
			else if($user_type=='s')
			{
				$query =   $this->CI->db->query("select sm.id,sm.title,sm.module,sm.icon from site_modules as sm left join user_perm as up on sm.id=up.module_id where up.user_id=".$user_id);
			}
			$nav=array();
			$nav['nav_list'][]="Dashboard";
			$nav['nav_list_url'][] = "admin";
			$nav['nav_list_icon'][] = "fa fa-dashboard";
			foreach ($query->result() as $row)
			{
				$nav['nav_list'][] = $row->title;
				$nav['nav_list_url'][] = 'admin/'.$row->module;
				$nav['nav_list_icon'][] =$row->icon;
			}
			
			$this->set('nav_list', $nav['nav_list']);
			//$this->set('nav_sub_list',array());
			//$this->set('nav_sub_list_url',array());
			$this->set('nav_list_icon',$nav['nav_list_icon'] );
			$this->set('nav_list_url', $nav['nav_list_url']);
			$this->load(ADMIN_DIR.'/template', ADMIN_DIR."/".$view, $view_data, $return);
		}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */