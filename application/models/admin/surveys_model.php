<?php
Class Surveys_model extends CI_Model
{
	function datatable()
	{
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,title,description,voteUp,voteDown,date_format(createdDate,"%d-%m-%Y"),status,isCompleted')
		->unset_column('id')
		->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)')	
		->edit_column('isCompleted', '$1', 'check_is_default(isCompleted,id)')			
		->from('voting_survey');
		echo $this->datatables->generate();
	}
	
	function get_actions($id)
	{
		$content='
		<a href="'.base_url().'admin/surveys/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
			<i class="entypo-pencil"></i>
			Edit
		</a>

		<a href="'.base_url().'admin/surveys/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
			<i class="entypo-cancel"></i>
			Delete
		</a>
	';
	return $content;
}

public function insert_survey()
{		
		$title=$this->input->post('title');
		//$options=$this->input->post('options');
		$description = $this->input->post('description');
		$status=$this->input->post('status');		
		
		$data = array(
		   'title' => $title,
		   'description' => $description,
		   'createdDate' => date('Y-m-d H:i:s'),
		   'status'=>$status,
		   'endDate'=>date('Y-m-d H:i:s')
		);
		$this->db->insert('voting_survey', $data);	
		$surveyId = $this->db->insert_id();

		/*$options_insert_array  = array();
		$j = 0;
		foreach ($options as $key => $value) {
			$options_insert_array[$j]["surveyId"] = $surveyId; 	
			$options_insert_array[$j]["title"] = $value; 	
			$options_insert_array[$j]["createdDate"] = date('Y-m-d H:i:s');
			$j++;
		}
		$this->db->insert_batch("voting_survey_options",$options_insert_array);*/
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

public function delete_survey($id)
{
	$this->db->delete('voting_survey', array('id' => $id));
}

public function chng_stats()
{
	
	$surveyid=$this->input->post('surveyid');
	$status=$this->input->post('status');
	$action = $this->input->post('actn');

	if($surveyid > 0 && $status !='')
	{
		if($action=='stats_col')
		{
			$data = array(
			   'status'=>$status
			);
		}
		else if($action=='default_col'){
			$data = array(
			   'isCompleted'=>$status
			);
		}
		$this->db->where('id', $surveyid);
		$this->db->update('voting_survey', $data);
	}	
}


}
?>