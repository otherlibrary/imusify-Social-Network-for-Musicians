<?php
Class stripe_operation extends CI_Model
{
	function __construct(){
		parent::__construct();
	}

	function user_stripe_connect_check(){
		$user_id = $this->session->userdata('user')->id;
		$this -> db -> select('stripe_connect');
		$this -> db -> from('users');
		$where = "(id='".$user_id."')";
		$this -> db -> limit(1);
		$this->db->where($where);	   
		$query = $this -> db -> get();	   

		if($query -> num_rows() > 0)
		{
			$row = $query->row_array();
			
			if($row["stripe_connect"] == "y")
			{
				$response["status"] = "success";
				$response["msg"] = "Stripe account is connected successfully.";
			}
			else if($row["stripe_connect"] == "n")
			{
				$response["status"] = "error";
				$response["msg"] = "Stripe account is not connected yet.";
			}
		}
		return $response;
	}

	function insert_authorized_user_data($resp = null)
	{
		$response = array('status'=>'success','msg'=>'You are connected with stripe successfully.');
		if($resp && $resp["stripe_user_id"])
		{
			$user_id = $this->session->userdata('user')->id;
			$insert_array = array(	
				'user_id'=>$user_id,
				'access_token'=>$resp["access_token"],
				'livemode'=>$resp["livemode"],
				'refresh_token'=>$resp["refresh_token"],
				'token_type'=>$resp["token_type"],
				'stripe_publishable_key'=>$resp["stripe_publishable_key"],
				'stripe_user_id'=>$resp["stripe_user_id"],
				'scope'=>$resp["scope"]
				);
			$insert_id = $this->db->insert("stripe_connect_users",$insert_array);
			if($insert_id > 0)
			{
				$update_array = array('stripe_connect'=>'y');
				$this->db->where('id',$user_id);
				$this->db->update("users",$update_array);
			}
			else{
				$response["status"] = "error";
				$response["msg"] = "error";
			}
		}
		else{
			$response["status"] = "error";
			$response["msg"] = "error";
		}
		return $response;		   
	}


	function get_stripe_accountid($trackId){	
		$i = 1;
		$output = array();		
		$userId = ($userId != NULL) ? $userId : $this->sess_userid;	
		$where = " (tt.status='y') AND tt.id='".$trackId."'";
		$this->db->select('scu.stripe_user_id');
		$this->db->from('tracks as tt');
		$this->db->join('stripe_connect_users as scu', 'tt.userId = scu.user_id','left');
		$this->db->where($where);
		$this ->db-> limit(1);
		$query = $this->db->get();
		$records_count = $query->num_rows();
		if($records_count > 0)
		{
			$row = $query->row_array();
			return $row["stripe_user_id"];
		}
		return 0;
	}

	function buy_album_check($albumid = null){
		$user_id = $this->session->userdata('user')->id;
		$set_price_flag = false;
		$exclusive_price_flag = false;
		$response = array('status'=>'success','msg'=>'');
		if($albumid == null)
		{
			$response["status"] = "error";
			return $response;
		}
		$this->load->model("album_model");
		$album_details =  $this->album_model->get_album_details($albumid);
		$date = date('Y-m-d H:i:s');
		$this->load->model("order_model");
		$response = $this->order_model->create_album_order($albumid,$album_details["price"]);
		/*$response["composer_price"] = $order_composer_price;
		$response["imusify_price"] = $order_imusify_price;*/
		$response["order_id"] = $response["order_id"];
		$response["total"] = $album_details["price"]*100;
		$response["title"] = $album_details["title"];
		$response["order_random_id"] = $response["random_no"];
		return $response;
	}

	function buy_song_check($values = null,$trackid = null){
		$user_id = $this->session->userdata('user')->id;
		$set_price_flag = false;
		$exclusive_price_flag = false;
		$response = array('status'=>'success','msg'=>'');
		if($values == null && $trackid == null)
		{
			$response["status"] = "error";
			return $response;
		}
		$this->load->model("track_detail");
		$track_data = $this->track_detail->get_song_details($trackid);

		

		$date = date('Y-m-d H:i:s');
		$this->db->select('planId,planDetails');
		$this->db->where('userId',$track_data["userId"]);
		$this->db->where("$date between startDate AND endDate",true,true);
		$this->db->from('user_plan_details');
		$this->db->limit(1);
		$this->db->order_by("id","desc");
		$query_planid = $this-> db-> get();
		$user_plan_data = $query_planid->row_array();
		$song_owner_current_plan_id = $user_plan_data["planId"];
		if($song_owner_current_plan_id <= 0 || $song_owner_current_plan_id == "" || $song_owner_current_plan_id == NULL)
		{
			$song_owner_current_plan_id = getvalfromtbl("id","membership_plan","amount = '0'","single");
		}
		/*print "a";*/
		if(!empty($track_data))
		{
			/*print "b";*/
			if($user_id != $track_data["userId"])
			{
				/*print "c";*/
				/*printr($track_data);*/
				$order =  $track_data["order"];
				$lic_orig_filetype_name = getvalfromtbl("name","tracktypes","id = '".$order["filetype_id"]."'","single");
				/*printr($order);*/
				if(!empty($order))
				{
					if(!empty($track_data))
					{
						foreach ($track_data["trackFileTypes"] as $key => $value) {
							if($value["id"] == $order["filetype_id"] && $order["filetype_id"] != $track_data["track_buyable_current_type"])
							{
								$set_price_flag = true;
								$cur_user_percent = getvalfromtbl("pricing","tracktypes","id='".$order["trackfiletype"]."'","single");
								/*Db % may be 120 of wav file*/
								$cur_track_percent = getvalfromtbl("pricing","tracktypes","id='".$track_data["track_buyable_current_type"]."'","single");
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

				$licence_selected_ar = $track_data["order"]["details"];
				$values = array_column($licence_selected_ar, 'licenceId');
				$licence_selected_ar_comma_sep_id = implode(",",$licence_selected_ar_id);
				if(!empty($values))
				{
					/*print("J");*/
					$this->db->select("tlpd.id,tlpd.licenceId,tlpd.licencePrice,tlt.".$lic_orig_filetype_name." as licenceMainPrice");	
					$this->db->from('track_licence_price_details as tlpd');
					$this->db->join('track_licence_types as tlt', 'tlpd.licenceId = tlt.id','left');
					$this->db->where('trackId', $trackid);
					$this->db->where_in('licenceId', $values);
					$query_res = $this->db->get();
					$licence_price_rows = $query_res->result_array();	
					/*echo $this->db->last_query();
					printr($licence_price_rows);*/

					$this->db->select("*");	
					$this->db->from('pricing_split');
					$this->db->where('planId',$song_owner_current_plan_id);
					$this->db->where_in('licenceId', $values);

					$query_pricing_res = $this->db->get();
					$query_pricing_rows = $query_pricing_res->result_array();
					
					if(!empty($query_pricing_rows))
					{
						$new_pricing_rows_ar = array();
						foreach ($query_pricing_rows as $key => $value_k) {
							$new_pricing_rows_ar[$value_k["licenceId"]] = $value_k;
						}
					}	

					$composer_price = 0;
					$imusify_price = 0;
					$total = 0;	

					/*printr($licence_price_rows);*/
					if(!empty($licence_price_rows))
					{
						/*foreach ($licence_price_rows as $key => $value) {
							$total += $value["licencePrice"];
							$split_ar = $new_pricing_rows_ar[$value["licenceId"]];
							$imusify_price += ($value["licencePrice"] * $split_ar["imusify"]) / 100;
							$composer_price += ($value["licencePrice"] * $split_ar["composer"]) / 100 ;
						}*/
						$update_order_det_ar = array();
						$order_id = $track_data["order"]["id"];
						foreach ($licence_price_rows as $key => $value1) {
							$split_ar = $new_pricing_rows_ar[$value1["licenceId"]];
							if($set_price_flag == true)
							{
								$new_price = ($value1["licencePrice"] * $cur_user_percent) / $cur_track_percent;
								$value1["licencePrice"] = $new_price;
							}
							if($exclusive_price_flag == true)
							{
								$new_price = ($value1["licencePrice"] *  $exclusive_percent) / 100;
								$value1["licencePrice"] = $new_price;
							}
							$order_total += $value1["licencePrice"];
							$order_imusify_price += ($value1["licencePrice"] * $split_ar["imusify"]) / 100;
							$order_composer_price += ($value1["licencePrice"] * $split_ar["composer"]) / 100 ;
							$imusify_order_detail_price = ($value1["licencePrice"] * $split_ar["imusify"]) / 100;
							$composer_order_detail_price = ($value1["licencePrice"] * $split_ar["composer"]) / 100 ;
							/*$update_order_det_ar["orderId"] = $track_data["order"]["id"];
							$update_order_det_ar["licenceId"] = $value1["licenceId"];*/
							$update_order_det_ar["licenceMainPrice"] = $value1["licenceMainPrice"];
							$update_order_det_ar["licenceTotalPrice"] = $value1["licencePrice"];
							$update_order_det_ar["licenceComposerPer"] = $split_ar["composer"];
							$update_order_det_ar["licenceComposerPrice"] = $composer_order_detail_price;
							$update_order_det_ar["licenceImusifyPer"] = $split_ar["imusify"];
							$update_order_det_ar["licenceImusifyPrice"] = $imusify_order_detail_price;
							$this->db->where('orderId',$order_id);
							$this->db->where('licenceId',$value1["licenceId"]);
							$this->db->update("orders_details",$update_order_det_ar);
						}
						$order_update_ar = array(
							'total'=>$order_total,
							'imusifyPrice'=>$order_imusify_price,
							'composerPrice'=>$order_composer_price
							);
						$this->db->update("orders",$order_update_ar);
					}
					$response["composer_price"] = $order_composer_price;
					$response["imusify_price"] = $order_imusify_price;
					$response["order_id"] = $order_id;
					$response["total"] = $order_total*100;
					$response["title"] = $track_data["title"];
					$response["order_random_id"] = $order["order_random_id"];
				}
			}	
			else{
				$response["status"] = "error";
				$response["msg"] = "You can not buy your own song.";
			}
		}
		else{
			$response["status"] = "error";
			$response["msg"] = "Invalid track.Please try again";

		}
		return $response;

	}



}
?>