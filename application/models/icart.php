<?php
Class icart extends CI_Model
{	
	function __construct(){
		$this->sess_userid = $this->session->userdata('user')->id;		
		$this->load->model("order_model");
		$this->load->model("track_detail");
	}
	function get_usage_types($track,$order = array(),$type = null){
		/*print_r($track);*/
		$response = array();
		$usage_type_id = (!empty($order)) ? $order["usage_id"] : 0;
		$uploader_allowed_usage_type = $track["usage_type"];
		$uploader_allowed_usage_type_ar = explode(",",$uploader_allowed_usage_type);
		$this -> db -> select('*');
		$this -> db -> from('buy_usage_types');
		if($type != null)
		{
			$this->db->where("type",$type);
		}
		$query = $this -> db -> get();	
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				/*print_r($row);
				var_dump();*/
				if(in_array($row["id"],$uploader_allowed_usage_type_ar))
				{	
					$row["row_class"] = "buy_data buy_row_li";
					if($usage_type_id > 0 && $usage_type_id == $row["id"])
					{
						$row["row_class"] .= " active";
					}				
					$row["type"] = "usage_type";				
					$response[] = $row;
				}
			}
		}
		return $response;
	}
	function set_usage_type($track,$usage_id = null,$order = array())
	{
		$response = array('status'=>'fail','msg'=>'Please try again.');
		$licence_list = $track["usage_type"];
		$found_flag = false;

		$licence_list = explode(",", $licence_list);	
		/*var_dump($licence_list);
		print $usage_id;
*/

		if(!empty($licence_list))
		{
			foreach ($licence_list as $key => $value) 
			{
                                //var_dump($value);exit;
				//if($value["id"] == $usage_id)
                                if($value == $usage_id)
				{
					$found_flag = true;
					break;
				}				
			}
		}
		/*var_dump($found_flag);*/
		if($found_flag  == true){
			if(!empty($order) && $order["id"] > 0)
			{
				if($order["usage_id"] != $usage_id)
				{
					$this->db->where("orderId",$order["id"]);
					$this->db->delete("orders_details");
				}
				$update_order_ar = array('usage_id'=>$usage_id,'is_usage_type'=>'y');
				$response = $this->order_model->update_order($order["id"],$update_order_ar);
			}
			else{
				$response = $this->order_model->create_order($track["id"],$usage_id);
			}
		}

		/*print_r($response);
		exit;*/
		return $response;
	}
	function get_exclusive_type($track,$order = array(),$type = NULL)
	{
		$response = array();
		$exclusive_type_id = (!empty($order)) ? $order["exclusive_id"] : 0;
		$this -> db -> select('*');
		$this -> db -> from('buy_exclusive_types');
		if($type != null)
		{
			$this->db->where("type",$type);
		}
		$query = $this -> db -> get();	
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$row["row_class"] = "buy_data buy_row_li";
				if($exclusive_type_id > 0 && $exclusive_type_id == $row["id"])
				{
					$row["row_class"] .= " active";
				}
				$row["type"] = "usage_type";				
				$response[] = $row;
			}
		}
		return $response;
	}	


	function set_exclusive_type($track,$exclusive_id = null,$order = array())
	{	
		
		$response = array('status'=>'fail','msg'=>'');
		$exclusive_flag = false;
		$db_exclusive_ids=$track['trackExclusiveType'];
		$db_exclusive_arr=explode(",",$db_exclusive_ids);
		
		if(!empty($order) && $order["is_usage_type"] == "y" && $order["usage_id"] > 0 && in_array($exclusive_id,$db_exclusive_arr))
		{

			$exclusive_ar = $this->get_exclusive_type($track,$order,'e');
			$db_exclusive_id = $exclusive_ar[0]["id"];
			
			if($exclusive_id > 0 && $db_exclusive_id == $exclusive_id && $order["is_commercial"]===true)
			{
				$exclusive_flag = true;

			}
			else if($exclusive_id > 0 && $db_exclusive_id != $exclusive_id){
				$exclusive_flag = true;
			}
		}


		if($exclusive_flag == true)
		{
			$update_order_ar = array('exclusive_id'=>$exclusive_id,'is_exclusive'=>'y');
			$response = $this->order_model->update_order($order["id"],$update_order_ar);	
		}		
		return $response;
	}	

	
	function set_exclusive_type_m($track,$exclusive_id = null,$order = array())
	{
		$response = array('status'=>'fail','msg'=>'');
		$exclusive_flag = false;
		if(!empty($order) && $order["is_usage_type"] == "y" && $order["usage_id"] > 0 )
		{
			
			$exclusive_ar = $this->get_exclusive_type($track,$order,'e');
			$db_exclusive_id = $exclusive_ar[0]["id"];
			
			if($exclusive_id > 0 && $db_exclusive_id == $exclusive_id && $order["is_commercial"]===true)
			{
				/*exclusive in commercial*/
				$exclusive_flag = true;
			}
			else if($exclusive_id > 0 && $db_exclusive_id != $exclusive_id){
				/*non exclusive*/
				$exclusive_flag = true;
			}
		}

		if($exclusive_flag == true)
		{
			$update_order_ar = array('exclusive_id'=>$exclusive_id,'is_exclusive'=>'y');
			$response = $this->order_model->update_order($orderId,$update_order_ar);	
		}		
		return $response;
	}	
	function get_file_type($track,$order = array())
	{
		$response = array();	
		if(!empty($track["trackFileTypes"]))
		{
			foreach ($track["trackFileTypes"] as $key => $value) {
				$value["row_class"] = "buy_data buy_row_li";
				$value["type"] = "trackfile_type";						
				$response[] = $value;
			}
		}
		return $response;
	}
	function set_file_type($track = null,$filetypes = null,$order=array()){
		$response = array('status'=>'fail','msg'=>'');
		$orderId = $order["id"];
		if(!empty($order) && $order["is_usage_type"] == "y" && $order["usage_id"] > 0 && $order["is_exclusive"] == "y" && $filetypes != "")
		{
			$update_order_ar = array('filetype_id'=>$filetypes,'is_filetype'=>'y');
			$response = $this->order_model->update_order($orderId,$update_order_ar);
		}		
		return $response;
	}
	function get_licences($track,$order = array())
	{	

		$response = array();	
		$set_price_flag = false;
		$exclusive_price_flag = false;
		$licence_selected_ar = array();			
		/*if order is pending then count price latest otherwise take from db which is current flow*/
		/*printr($order);*/
		/*printr($order["details"]);*/
		$licence_selected_ar = $order["details"];
		if(!empty($licence_selected_ar))
		{
			$licence_selected_ar_id = array_column($licence_selected_ar, 'licenceId');
			//var_dump($licence_selected_ar, $licence_selected_ar_id, $order);exit;
			$licence_selected_ar_comma_sep_id = implode(",",$licence_selected_ar_id);
                        

			if(!empty($order) && $order["status"] == "p")
			{
				/*printr($licence_selected_ar);*/
				$date = date('Y-m-d H:i:s');
				$this->db->select('planId,planDetails');
				$this->db->where('userId',$track["userId"]);
				$this->db->where("$date between startDate AND endDate",true,true);
				$this->db->from('user_plan_details');
				$this->db->limit(1);
				$this->db->order_by("id","desc");
				$query_planid = $this-> db-> get();
				$user_plan_data = $query_planid->row_array();
				$song_owner_current_plan_id = $user_plan_data["planId"];
				if($song_owner_current_plan_id <= 0 || $song_owner_current_plan_id == "" || $song_owner_current_plan_id == NULL)
				{
					/*Get free plan id from user plans*/
					$song_owner_current_plan_id = getvalfromtbl("id","membership_plan","amount = '0'","single");
				}

				$lic_orig_filetype_name = getvalfromtbl("name","tracktypes","id = '".$order["filetype_id"]."'","single");

				$this->db->select("tlpd.id,tlpd.licenceId,tlpd.licencePrice,tlt.".$lic_orig_filetype_name." as licenceMainPrice");	
				$this->db->from('track_licence_price_details as tlpd');
				$this->db->join('track_licence_types as tlt', 'tlpd.licenceId = tlt.id','left');
				$this->db->where('trackId', $track["id"]);
				$this->db->where_in('licenceId', $licence_selected_ar_comma_sep_id);
				$query_res = $this->db->get();
				$licence_price_rows = $query_res->result_array();
				foreach ($licence_price_rows as $key => $value) {
					$db_selected_licence_price_ar[$value["licenceId"]] =  $value["licencePrice"];
				}
			}
			else{
				$db_selected_licence_price_ar = array();
				foreach ($licence_selected_ar as $key => $value) {
					$db_selected_licence_price_ar[$value["licenceId"]] =  $value["licenceTotalPrice"];
				}			
			}			
		}
		
		if(!empty($order))
		{
			if(!empty($track))
			{
				/*printr($track["trackFileTypes"]);*/
				foreach ($track["trackFileTypes"] as $key => $value) {
					/*print "z";
					printr($value);
					print "order";
					printr($order); 
					print "track";
					printr($track); */
					if($value["id"] == $order["filetype_id"] && $order["filetype_id"] != $track["track_buyable_current_type"])
					{
						$set_price_flag = true;
						$cur_user_percent = getvalfromtbl("pricing","tracktypes","id='".$order["filetype_id"]."'","single");
						/*Db % may be 120 of wav file*/
						$cur_track_percent = getvalfromtbl("pricing","tracktypes","id='".$track["track_buyable_current_type"]."'","single");
						/*echo "true";
						echo $cur_user_percent." ".$cur_track_percent;*/
					}
				}
			}


			if($order["exclusive_id"] && $order["exclusive_id"] > 0)
			{
				$exclusive_percent = getvalfromtbl("pricing","buy_exclusive_types","id='".$order["exclusive_id"]."' and type='e'","single");
				if($exclusive_percent > 0)
				{
					$exclusive_price_flag = true;
				}
			}
		}
		
		
		/*Wave file and selected for download as mp3 ends*/
		$where = " (ttd.trackId='".$track["id"]."' AND tlt.lic_type = '".$order["licenceusagetype"]."') ";
		if($cond != NULL)
			$where .= " AND ".$cond;
		$this->db->select('ttd.licencePrice,tlt.*');
		$this->db->from('track_licence_price_details as ttd');
		$this->db->join('track_licence_types as tlt', 'tlt.id = ttd.licenceId','left');
		$this->db->where($where);
		if($limit != NULL)
			$this ->db-> limit($limit);
		$query = $this->db->get();
		/*echo $this->db->last_query();*/
		$records_count = $query->num_rows();
		if($counter != NULL)
			return $records_count;		
		if($records_count > 0)
		{
			$i = 1;
			if($start_limit != NULL)
				$i = $start_limit+1;
			foreach ($query->result_array() as $row)
			{
				/*print_r($row);*/
				$row["row_class"] = 'licence_type_sel_js';
				$row["type"] = '';	
				if($set_price_flag == true)
				{
					$new_price = ($row["licencePrice"] * $cur_user_percent) / $cur_track_percent;
					$row["licencePrice"] = $new_price;
				}
				if($exclusive_price_flag == true)
				{
					$new_price = ($row["licencePrice"] *  $exclusive_percent) / 100;
					$row["licencePrice"] = $new_price;
				}
                                if(!empty ($licence_selected_ar_id)){
                                    if(in_array($row["id"],$licence_selected_ar_id))
                                    {
                                            $row["row_class"] .= " active";	
                                            $row["preselected"] .= "yes";
                                            $row["licenceTotalPrice"] = $db_selected_licence_price_ar[$row["id"]];	

                                    }
                                }
				
				$response[] = $row;
			}
		}
		/*printr($response);*/
		return $response;
	}
	function set_licences($trackid = null,$orderId = null,$values = null)
	{
		$response = array('status'=>'fail','msg'=>'');
		$order_check_exist = getvalfromtbl("id,orderId","orders_details","orderId = '".$orderId."' AND licenceId = '".$values."'","multiple");
		/*echo $this->db->last_query();*/
		$order_details = getvalfromtbl("filetype_id","orders","id = '".$orderId."'","single");
		//echo $this->db->last_query();
                
		$lic_orig_filetype_name = getvalfromtbl("name","tracktypes","id = '".$order_details."'","single");
                //var_dump($order_details, $lic_orig_filetype_name);exit;
		/*printr($lic_orig_filetype_name);*/
		if($order_check_exist && $order_check_exist["id"] > 0)
		{
			/*Delete code*/
			$response_del = $this->db->delete('orders_details', array('id' => $order_check_exist["id"]));
			if($response_del)
			{
				$response["status"] = "success";
				$response["status"] = "Licence deleted successfully.";
			}
			else{
				$response["status"] = "success";
				$response["status"] = "Licence not deleted successfully.";
			}
		}
		else
		{
			$values = explode(",",$values);
			if(!empty($values))
			{
				$this->db->select("tlpd.id,tlpd.licenceId,tlpd.licencePrice");	
				$this->db->from('track_licence_price_details as tlpd');
				$this->db->join('track_licence_types as tlt', 'tlpd.licenceId = tlt.id','left');
				$this->db->where('trackId', $trackid);
				$this->db->where_in('licenceId', $values);
				$query_res = $this->db->get();
				$licence_price_rows = $query_res->result_array();
				if(!empty($licence_price_rows))
				{
					$insert_order_det_ar = array();
					$i = 0;
					foreach ($licence_price_rows as $key => $value1) {

						$insert_order_det_ar[$i]["orderId"] = $orderId;
						$insert_order_det_ar[$i]["licenceId"] = $value1["licenceId"];

						$i++;
					}
					/*printr($insert_order_det_ar);*/
					$status = $this->db->insert_batch("orders_details",$insert_order_det_ar);
					$response["status"] = "success";
					$response["msg"] = "Licence added to cart successfully.";
				}
			}
			else{
				$response["status"] = "fail";
				$response["msg"] = "Something went wrong please try again.";
			}
		}
		return $response;
	}
}
?>