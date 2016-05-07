<?php
Class sub_admin_model extends CI_Model
{
	function datatable()
	{
		$this->load->library('Datatables');
		$this->load->helper('datatable_helper');
		$this->datatables->select('id,username,email,description,status')
		->unset_column('id')
		->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)') 
		->from('users')
		->where('usertype = "s"');

		echo $this->datatables->generate();
	}
	
	function get_actions($id)
	{
		$content='
		<a href="'.base_url().'admin/sub_admin/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
			<i class="entypo-pencil"></i>
			Edit
		</a>
		<a href="'.base_url().'admin/sub_admin/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
			<i class="entypo-cancel"></i>
			Delete
		</a>

		<a href="'.base_url().'admin/user/profile/'.$id.'" class="btn btn-info btn-sm btn-icon icon-left">
			<i class="entypo-info"></i>
			Profile
		</a>';
		return $content;
	}
	
	function delete($id)
	{
		
		$this->db->query("delete from users where id='$id'"); 
	}
	
	function chng_status($user_id,$status)
	{
		$data = array(
			'status'=>$status
			);
		$this->db->where('id', $user_id);
		$this->db->update('users', $data);
		
	}
	
	function user_permissions($flag = "default",$user_id)
	{
		
		$user_exists_role = array();
		
		$data = array();
		if($flag == "roles")	
			$this -> db -> select('module_id');
		else 
			$this -> db -> select('id,module_id');

		$this -> db -> from('user_perm');	   
		$where = "(user_id='".$user_id."')";
		$this->db->where($where);	   
		$query = $this -> db -> get();	   
		if($query -> num_rows() > 0)
		{
			
			if($flag == "roles")
			{
				//$user_exists_role = $query->result_array();
				$data = $query->result_array();	
				//print_r($data);	
				foreach ($data as $row)
				{
					$user_exists_role[] = $row["module_id"];
				}				
			}else{
				foreach ($query->result_array() as $row)
				{
					$user_exists_role[] = $row;
				}		
			}	
		}		
		return $user_exists_role;		
	}
	
	function add_subadmin($firstname,$lastname,$username,$email,$roles_array){
		if($firstname != "" && $username != "" && $email != "" && !empty($roles_array))
		{
			/*dump($roles_array);*/
			$check_sub_admin_exist = getvalfromtbl("id","users","type = 's' AND (username = '".$username."' OR  email='".$email."')","single");
			$response = array();
			$data = array();
			if($check_sub_admin_exist)
			{
				$response["status"] = "error";
				$response["msg"] = "Sub admin already exists.";

			}else{
				$rand_password = mt_rand(1,999999);

				$insert_ar = array(
					'firstname'=>$firstname,
					'lastname'=>$lastname,
					'username'=>$username,
					'email'=>$email,
					'password'=>md5($rand_password),
					'status' => 'y',
					'usertype' => 's',
					'created' => date('Y-m-d H:i:s')
					);
				$this->db->insert("users",$insert_ar);
				$user_id = $this->db->insert_id();
				if(!empty($roles_array))
				{
					$i = 0;
					foreach($roles_array as $roles1) {
						$data[$i]["user_id"] = $user_id;
						$data[$i]["module_id"] = $roles1;
						$i++;
					};			
					/*dump($data);*/
					$this->db->insert_batch('user_perm', $data);
				}
				if($user_id > 0)
				{
					$response["status"] = "success";
					$response["msg"] = "Sub admin added successfully.";
					$data_mail = array(
						'login_url' => base_url()."admin/login",
						'username' => $uname,
						'Email' => $email,
						'password' => $rand_password
						);			
					$abc=$this->template->load('mail/email_template','mail/subadmin_register',$data_mail,TRUE);
					send_mail(ADMIN_MAIL,$email,"Successfully registered with Imusify",$abc);
				}
			}
		}
		return $response;
	}


	function edit($roles_array,$user_id)
	{
		$user_exists_perm = $this->user_permissions("roles",$user_id);
		$data = array();
		
		$post_count = count($roles_array);
		$db_count = count($user_exists_perm);				
		if($db_count > 0)
		{
			$diff = array_diff($user_exists_perm,$roles_array);

			if(!empty($diff))
			{
				$this->db->where('user_id', $user_id);
				$this->db->where_in('module_id', $diff);
				$this->db->delete('user_perm');
			}
					//echo $this->db->last_query();
		}
		
		$diff1 = array_diff($roles_array,$user_exists_perm);

		if(!empty($diff1))
		{
			foreach($diff1 as $roles1) {
					//$arr[$i] = $roles;
				$data[$i]["user_id"] = $user_id;
				$data[$i]["module_id"] = $roles1;

				$i++;
			};					
			$this->db->insert_batch('user_perm', $data);
		}

	}
}
?>