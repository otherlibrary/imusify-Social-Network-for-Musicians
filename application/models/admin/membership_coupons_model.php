<?php
Class membership_coupons_model extends CI_Model
{
	function datatable()
	{
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,code,month_limit,space_allowed,type,date_format(createdDate,"%d-%m-%Y"),status')
		->unset_column('id')
		->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)')
		/*->edit_column('type', '$1', 'check_type(type)')		*/
		->from('membership_coupons');
		echo $this->datatables->generate();
	}
	
	function get_actions($id)
	{
		/*<a href="'.base_url().'admin/notification/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
			<i class="entypo-pencil"></i>
			Edit
		</a>*/
		$content='		
		<a href="'.base_url().'admin/membership_coupons/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
			<i class="entypo-pencil"></i>
			Edit
		</a>

		<a href="'.base_url().'admin/membership_coupons/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
			<i class="entypo-cancel"></i>
			Delete
		</a>
		';
		return $content;
	}

	public function insert_coupon()
	{
		$type=$this->input->post('type');
		$code=$this->input->post('code');
		$month_limit=$this->input->post('month_limit');
		$space_allowed=$this->input->post('space_allowed');		
		$status=$this->input->post('status');

		$code_exist_check = getvalfromtbl("id","membership_coupons","code = '".$code."' AND type = '".$type."'","single");
		if($code_exist_check > 0)
		{
			return false;
		}else{
			if($type == 's')
			{
				$data = array(
					'code' => $code,
					'month_limit'=>$month_limit,
					'space_allowed'=>$space_allowed,
					'type'=>$type,
					'createdDate'=>date('Y-m-d H:i:s'),
					'status'=>$status,
					'planId'=>'0'
					);
			}else if($type == 't'){
				$space_allowed = "-1";
				$planId = getvalfromtbl("id","membership_plan","plan_id='premium'","single");
				$data = array(
					'code' => $code,
					'month_limit'=>$month_limit,
					'space_allowed'=>$space_allowed,
					'type'=>$type,
					'createdDate'=>date('Y-m-d H:i:s'),
					'status'=>$status,
					'planId'=>$planId
				);
			}


			$this->db->insert('membership_coupons', $data);
			$detail_id =  $this->db->insert_id();	
			return true;
		}

		
	}

	public function edit()
	{	

		$type=$this->input->post('type');
		$code=$this->input->post('code');
		$month_limit=$this->input->post('month_limit');
		$space_allowed=$this->input->post('space_allowed');		
		$status=$this->input->post('status');

		$code_exist_check = getvalfromtbl("id","membership_coupons","id != '".$id."' AND code = '".$code."' AND type = '".$type."'","single");
		if($code_exist_check > 0)
		{
			return false;
		}else{
			if($type == 's')
			{
				$data = array(

					'month_limit'=>$month_limit,
					'space_allowed'=>$space_allowed,
					'status'=>$status
					);
			}else if($type == 'p'){
				$space_allowed = "-1";
				$data = array(
					'month_limit'=>$month_limit,
					'space_allowed'=>$space_allowed,					
					'status'=>$status
					);
			}
			$this->db->where('id', $id);
			$this->db->update('membership_coupons', $data);
			$detail_id =  $this->db->insert_id();	
			return true;
		}
	}

	public function delete_code($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('membership_coupons', array('id' => $id));
	}

	public function chng_stats()
	{
		$couponid=$this->input->post('couponid');
		$status=$this->input->post('status');
		if($couponid > 0 && $status !='')
		{
			$data = array(
				'status'=>$status
				);
			$this->db->where('id', $couponid);
			$this->db->update('membership_coupons', $data);
		}	
	}


}
?>