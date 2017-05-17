<?php
Class Article_model extends CI_Model
{
	function datatable()
	{
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,title,description,date_format(createdDate,"%d-%m-%Y"),status,headlineDisp')
		->unset_column('id')
		->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)')
		->edit_column('headlineDisp', '$1', 'check_is_default(headlineDisp,id)')		
		->from('articles');
		echo $this->datatables->generate();
	}
	
	function get_actions($id)
	{
		$content='
		<a href="'.base_url().'admin/article/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
			<i class="entypo-pencil"></i>
			Edit
		</a>

		<a href="'.base_url().'admin/article/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
			<i class="entypo-cancel"></i>
			Delete
		</a>
		';
		return $content;
	}

	public function insert_article()
	{	
		$this->load->model("crop_model");
		$title=$this->input->post('title');
		$description=$this->input->post('description');
		$status=$this->input->post('status');		
		$headlineDisp=$this->input->post('headlineDisp');
		$randomnumber=$this->input->post('randomnumber');
		$this->load->model("commonfn");
		$permalink = $this->commonfn->get_permalink($title,"articles","perLink","id");
		$data = array(
			'title' => $title,
			'description' => $description,
			'createdDate' => date('Y-m-d H:i:s'),
			'status'=>$status,
			'headlineDisp'=>$headlineDisp,
			'perLink'=> $permalink
			);
		$this->db->insert('articles', $data);	
		$article_id = $this->db->insert_id();
		$folder_name = "articles/";
		$file_name_ar = $this->session->userdata('article_temp');
		$file_name = $file_name_ar["$randomnumber"];					
		$old_physical_path = asset_admin_path()."upload/articles_temp/".$file_name;
		if(!file_exists(asset_admin_path()."upload/articles")){
			mkdir(asset_admin_path()."upload/articles",0777);
		}
		$new_physical_path = asset_admin_path()."upload/articles/".$file_name;
		if (file_exists(asset_admin_path()."upload/articles_temp/".$file_name)) 
		{
			if (copy($old_physical_path, $new_physical_path)) {
				unlink($old_physical_path);
			}
		}
		$insert_id = $this->db->insert_id();
		$data = array(
			'detailid' => $article_id,
			'dir' => 'articles/',
			'name' => $file_name,
			'type'=>'art',
			'default_pic' => 'y'	
			);				
		$this->db->insert('photos', $data);	
		return true;
	}

	public function edit_article()
	{
		$id = $this->input->post('id');
		$title=$this->input->post('title');
		$description=$this->input->post('description');
		$status=$this->input->post('status');		
		$headlineDisp=$this->input->post('headlineDisp');
		
		$data = array(
			'title' => $title,
			'description' => $description,
			'createdDate' => date('Y-m-d H:i:s'),
			'status'=>$status,
			'headlineDisp'=>$headlineDisp
			);
		
		$this->db->where('id', $id);
		$this->db->update('articles', $data);	
		return true;
	}

	public function delete_article($id)
	{
		$this->db->delete('articles', array('id' => $id));
	}

	public function chng_stats()
	{

		$articleid=$this->input->post('articleid');
		$status=$this->input->post('status');
		$action = $this->input->post('actn');

		if($articleid > 0 && $status !='')
		{
			if($action=='stats_col')
			{
				$data = array(
					'status'=>$status
					);
			}
			else if($action=='default_col'){
				$data = array(
					'headlineDisp'=>$status
					);
			}
			$this->db->where('id', $articleid);
			$this->db->update('articles', $data);
		}	
	}


}
?>