<?php
Class order_model extends CI_Model
{
	function __construct(){
		parent::__construct();
		$this->sess_userid = $this->session->userdata('user')->id;
		$this->load->helper('string');
	}

	function check_order_exist($trackid = null){
		$response = array();
		$this -> db -> select('id');
		$this -> db -> from('orders');
		$where = "(trackId='".$trackid."' AND userId = '".$this->sess_userid."' AND status = 'p')";
		$this -> db -> limit(1);
		$this->db->where($where);	   
		$query = $this -> db -> get();	   

		if($query -> num_rows() > 0)
		{
			$row = $query->row_array();
			$this->load->model("icart");
			$order = $this->get_order_details($row["id"]);
			$order['id']=$row['id'];
			
			$order['is_commercial']=false;
			$commercial_id=getvalfromtbl('id','buy_usage_types','type="l"','single');
			if($commercial_id > 0 && $order["usage_id"] == $commercial_id){
				$order['is_commercial']=true;
			}

			/*$order['is_exclusive_flag']=false;
			$exclusive_id=getvalfromtbl('id','buy_exclusive_types','type="e"','single');
			if($exclusive_id > 0 && $order["exclusive_id"] == $exclusive_id){
				$order['is_exclusive']=true;
			}*/


			$response = $order;
			
		}
		/*$track_common_detail = $this->track_detail->get_song_details($trackid);
		$response["track"] = $track_common_detail;*/
		
		return $response;
	}	

	function create_album_order($albumid = null,$total)
	{
		$response = array('status'=>'fail','msg'=>'');
		if($albumid != null)
		{
			$random_no = random_string('numeric', 15);
			$insert_array = array(
				'userId' => $this->sess_userid,
				'albumId' => $albumid,
				'trackId' => 0,
				'usage_id' => 0,
				'exclusive_id' => 0,
				'filetype_id' => 0,
				'createdDate' => date('Y-m-d H:i:s'),
				'order_random_id' => $random_no,
				'total' => $total,
				'is_album' => 'y'
				);	

			$orderid = $this->db->insert("orders",$insert_array);
			$response["status"] = "success";
			$response["msg"] = "success";
			$response["orderid"] = $orderid;
			$response["random_no"] = $random_no;
		}

		return $response;

	}

	function create_order($trackid = null,$usage_id = null){
		$response = array('status'=>'fail','msg'=>'');
		if($trackid != null && $usage_id != null)
		{

			$insert_array = array(
				'userId' => $this->sess_userid,
				'trackId' => $trackid,
				'createdDate' => date('Y-m-d H:i:s'),
				'usage_id' => $usage_id,
				'is_usage_type' => 'y',
				'order_random_id' => random_string('numeric', 15)
				);
			$orderid = $this->db->insert("orders",$insert_array);
			$response["status"] = "success";
			$response["msg"] = "success";
			$response["orderid"] = $orderid;
		}
		return $response;
	}	

	function update_order($orderid,$update_array = array()){
		$response = array('status'=>'fail','msg'=>'');
		if(!empty($update_array))
		{	
			$this->db->where("id",$orderid);
			$this->db->update("orders",$update_array);
			if ($this->db->_error_message()) {
				$response["status"] = "fail";
				$response["msg"] = 'Error! ['.$this->db->_error_message().']';
			} 
			else{
				$response["status"] = "success";
				$response["msg"] = 'Order updated successfully.';
			} 
		}
		return $response; 
	}

	function get_order_details($order_id,$subdetail_id_flag = false){
		$this->db->select('o.*,but.type as licenceusagetype');
		$this->db->where('o.id',$order_id);
		$this->db->from('orders as o');
		$this->db->join('buy_usage_types as but', 'but.id = o.usage_id','left');
		$this->db->limit(1);
		$query = $this-> db-> get();
		$order_data = $query->row_array();
		$order_data["avail_filetypes"] = $this->get_available_file_types($order_data);
		$order_data["details"] = $this->get_order_sub_details($order_data["id"],$subdetail_id_flag);
		return $order_data;
	} 


	function get_available_file_types($order_data){
		$response = array();
		$filetype_ar = explode(",",$order_data["filetype_id"]);
		/*printr( $filetype_ar);*/
		if(!empty($filetype_ar))
		{
			$this->db->select("*");	
			$this->db->from('tracktypes');
			$this->db->where_in('id', $filetype_ar);
			$query_res = $this->db->get();
			$records_count = $query_res->num_rows();
			if($records_count > 0)
			{
				/*foreach files $order_data["order_random_id"].".".$ext;   avail_filetypes base_url().'download/'.$order_data["order_random_id"],*/
				foreach ($query_res->result_array() as $row) {
					$row["href"] = base_url().'download/'.$order_data["order_random_id"].$row["extension"];
					$response[] = $row;
				}
			}
		}
		return $response;
	}	

	function get_order_sub_details($order_id,$subdetail_id_flag){

		/*echo "l";
		echo " Id: ".$subdetail_id;*/
		$fields = "od.*,tlt.name";
		if($subdetail_id_flag == true)
		{
			$fields = "od.id";
		}	

		$this->db->select($fields);
		$this->db->from('orders_details as od');
		$this->db->join('track_licence_types as tlt', 'od.licenceId = tlt.id','left');
		$this->db->where('orderId', $order_id);
		$query = $this->db->get();
		$records_count = $query->num_rows();
		if($records_count > 0)
		{
			$response = array();
			foreach ($query->result_array() as $row)
			{
				$response[] = $row;
			}
		}
		return $response;
	}

	function album_order_success($order_id,$charge_arr = array(),$order_data = array())
	{
		$this->load->helper(array('dompdf', 'file'));
		$completed_date = date('Y-m-d H:i:s');
		$update_array = array(	
			'status'=>'c',
			'successObj'=>$charge_arr,
			'completedDate' => $completed_date
			);
		$this->db->where("id",$order_id);
		$insert_id = $this->db->update("orders",$update_array);

		
		$this->load->model("album_model");
		$albumdata = $this->album_model->get_album_details($order_data["albumId"]);
		$artist_name_ar = getvalfromtbl("*","users","id='".$albumdata["userId"]."'");

		if(!empty($albumdata["list"]))
		{
			$track_ids = array();
			foreach ($albumdata["list"] as $key => $value) {
				
			}
		}


		/*send mail*/
		$mail_array = array(
			'username' => $this->session->userdata('user')->firstname." ".$this->session->userdata('user')->lastname,
			'title' => $albumdata["title"],
			'track_download_url' => ""
			);

		$abc = $this->template->load('mail/email_template','mail/album_buy_accept',$mail_array,TRUE);
		send_mail(ADMIN_MAIL,$this->session->userdata('user')->email,"Track bought successfully.",$abc,true,$agreement_pdf);

		/*To owner*/
		$mail_array1 = array(
			'username' => $this->session->userdata('user')->firstname." ".$this->session->userdata('user')->lastname,
			'title' => $trackdata["title"],
			'licence_list'=>$licence_list,
			'composer_price' => $order_data["licenceComposerPrice"],
			'licenceTotalPrice'=> $order_data["licenceTotalPrice"],
			);

		$abc1 = $this->template->load('mail/email_template','mail/track_bought_owner',$mail_array1,TRUE);
		send_mail(ADMIN_MAIL,$this->session->userdata('user')->email,"Track bought successfully.",$abc1);
		/*To owner ends*/
		
		/*send mail ends*/
		$response["status"] = "success";
		$response["msg"] = "";
		return $response;
	}

	function order_success($order_id,$charge_arr = array(),$order_data = array()){
		$completed_date = date('Y-m-d H:i:s');
		$update_array = array(	
			'status'=>'c',
			'successObj'=>$charge_arr,
			'completedDate' => $completed_date
			);
		$this->db->where("id",$order_id);
		$insert_id = $this->db->update("orders",$update_array);

		$this->load->model("track_detail");
		$trackdata = $this->track_detail->get_song_details($order_data["trackId"]);
		$artist_name_ar = getvalfromtbl("*","users","id='".$trackdata["userId"]."'");

		/*Generate pdf*/
		$licence_list = $order_data["details"];

		$this->load->helper(array('dompdf', 'file'));

		/*printr($licence_list);
		exit;*/

		$data = array(
			'is_album' =>$order_data["is_album"], 
			'title'=>$trackdata["title"],
			'artist_name'=>$artist_name_ar["firstname"]." ".$artist_name_ar["lastname"],
			'price'=>$order_data["total"],
			'real_name'=>$artist_name_ar["firstname"]." ".$artist_name_ar["lastname"],
			'purchased_date'=> date('F j, Y g:i a',strtotime($completed_date)),
			'licence_list'=>$licence_list,
			'track_download_url' => $order_data["avail_filetypes"],   
			'licence_download_url' => base_url().'download/'.$order_data["order_random_id"]."/licence"
			);
		$html = $this->load->view('song_contract', $data, true);

		/*Check if track_licences folder exist or not*/
		$folder_name = "track_licences";
		if(file_exists(asset_upload_path().$folder_name)==false){
			mkdir(asset_upload_path().$folder_name,0777);
		}	
		/*Check if track_licences folder exist or not ends*/
		$data = pdf_create($html, '', false);
		$agreement_pdf = asset_upload_path().$folder_name.'/'.$order_data["order_random_id"].'.pdf';
		write_file($agreement_pdf, $data);
		/*Generate pdf ends*/
		/*send mail*/
		$mail_array = array(
			'username' => $this->session->userdata('user')->firstname." ".$this->session->userdata('user')->lastname,
			'title' => $trackdata["title"],
			'track_download_url' => $order_data["avail_filetypes"],   
			'licence_download_url' => base_url().'download/'.$order_data["order_random_id"]."/licence"
			);

		$abc = $this->template->load('mail/email_template','mail/track_buy_accept',$mail_array,TRUE);
		send_mail(ADMIN_MAIL,$this->session->userdata('user')->email,"Track bought successfully.",$abc,true,$agreement_pdf);

		/*To owner*/
		$mail_array1 = array(
			'username' => $this->session->userdata('user')->firstname." ".$this->session->userdata('user')->lastname,
			'title' => $trackdata["title"],
			'licence_list'=>$licence_list,
			'composer_price' => $order_data["licenceComposerPrice"],
			'licenceTotalPrice'=> $order_data["licenceTotalPrice"],
			);

		$abc1 = $this->template->load('mail/email_template','mail/track_bought_owner',$mail_array1,TRUE);
		send_mail(ADMIN_MAIL,'',"Track bought successfully.",$abc1);
		/*To owner ends*/
		
		/*send mail ends*/
		/*unset session*/
		$this->session->unset_userdata('order_'.$order_id);
		$response["status"] = "success";
		$response["msg"] = "";
		return $response;
	}
}
?>