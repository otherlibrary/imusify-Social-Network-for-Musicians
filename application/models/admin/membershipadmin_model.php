<?php
Class membershipadmin_model extends CI_Model
{
	function datatable()
	{
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,plan_id,name,SUBSTRING(`description`, 1, 100),amount,status')
		->unset_column('id')
		->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)')
		->edit_column('type', '$1', 'check_type(type)')		
		->from('membership_plan');

		echo $this->datatables->generate();
	}
	
	function get_actions($id)
	{

		$membership_model='

		<a href="'.base_url().'admin/membership/detail/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
			<i class="entypo-pencil"></i>
			Detail
		</a>	

		<a href="'.base_url().'admin/membership/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
			<i class="entypo-pencil"></i>
			Edit
		</a>

		<a href="'.base_url().'admin/membership/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
			<i class="entypo-cancel"></i>
			Delete
		</a>
		';
		return $membership_model;
	}
	
	public function edit()
	{
		
		$id = $this->input->post('id');
		$space=$this->input->post('space');
		$can_message=$this->input->post('can_message');
		$frontpage = $this->input->post('frontpage');
		$mp3_split_imusify = $this->input->post('mp3_split_imusify');
		$mp3_split_composer = $this->input->post('mp3_split_composer');
		$licence_split_imusify = $this->input->post('licence_split_imusify');
		$licence_split_composer = $this->input->post('licence_split_composer');
		$can_vote_new_features = $this->input->post('can_vote_new_features');
		$stats = $this->input->post('stats');
		$widget = $this->input->post('widget');
		$ads = $this->input->post('ads');
		$aiff_wav = $this->input->post('aiff_wav');
		$free_distribution = $this->input->post('free_distribution');
		$placement_opportunities = $this->input->post('placement_opportunities');
		
		$data = array(
			'space' => $space,		  
			'can_message' => $can_message,		  
			'frontpage' => $frontpage,		  
			'mp3_split_imusify' => $mp3_split_imusify,		  
			'mp3_split_composer' => $mp3_split_composer,		  
			'licence_split_imusify' => $licence_split_imusify,		  
			'licence_split_composer' => $licence_split_composer,		  
			'can_vote_new_features' => $can_vote_new_features,		  
			'stats' => $stats,		  
			'widget' => $widget,		  
			'ads' => $ads,		  
			'aiff_wav' => $aiff_wav,		  
			'free_distribution' => $free_distribution,
			'placement_opportunities' => $placement_opportunities
			);

		/*echo $id."  ";
		echo "<pre>";
			print_r($data);
		sleep(10);*/
		$this->db->where('planId', $id);
		$this->db->update('membership_plan_detail', $data);		
	}
	
	public function delete($id)
	{
		$this->db->delete('membership_plan', array('id' => $id));
	}
	
	public function chng_stats()
	{
		$membership_modelid=$this->input->post('membership_modelid');
		$status=$this->input->post('status');
		
		if($membership_modelid > 0 && $status !='')
		{
			$data = array(
				'status'=>$status
				);
			$this->db->where('id', $membership_modelid);
			$this->db->update('membership_plan', $data);
		}	
	}

	/*Create or update a braintree plans*/
	public function create_update_subsciption_plans($plans){
		if($plans)
		{
			foreach ($plans as $key => $value) {
				$plan_exist = getvalfromtbl("plan_id","membership_plan","plan_id = '".$value->id."'","single");
				if($plan_exist != "")
				{
					/*update details*/
					$data = array(
						'plan_id'=> $value->id,
						'name' => $value->name,
						'description' => $value->description,
						'amount' => $value->price
					);
					$this->db->where('plan_id', $value->id);
					$this->db->update('membership_plan', $data);
					/*update details ends*/

				}else{ 
					/*insert new plan*/
					$data = array(
						'plan_id'=> $value->id,
						'name' => $value->name,
						'description' => $value->description,
						'amount' => $value->price,
						'createdDate' => date('Y-m-d H:i:s')
					);
					$this->db->insert('membership_plan', $data);
					/*insert new plan ends*/
				}
			}
		}	
		else{
		}
	}
	/*Create or update a braintree plans ends*/
	
}
?>