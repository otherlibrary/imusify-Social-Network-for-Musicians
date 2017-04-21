<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class notifications extends MY_Controller {

	function __construct()
	{
		parent::__construct("","front");
		$this->load->model('commonfn');
		$this->rec_to_dis = 10;
		
	}
	function index($page = NULL)
	{
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);


		$this->config->set_item('title','All Notifications');
		$total_records = $this->commonfn->get_unread_notifications(null,null,"counter");

		if($page != NULL && $page > 0)
		{
			$new_limit = $start_limit.",".$this->rec_to_dis;					
			$last_page = ceil($total_records/$this->rec_to_dis);	
			$data_array = $this->commonfn->get_unread_notifications("",$new_limit,"","",$start_limit);
			$final_array["data"] = $data_array;	
			$final_array["page"] = $page+1;
			$final_array["last_page"] = $last_page;
			header('Content-Type: application/json');
			echo json_encode( $final_array );
			exit;
		}

		$data_array = $this->commonfn->get_unread_notifications("",$this->rec_to_dis,null,50,50);
		$tabid = "popular-songs";
		$class = "browse-songs";
		$songs_ul = "true";
		//$cls_active = "popular_song_active";
		$header_tmp = "true";

		if($total_records > $this->rec_to_dis)
		{
			$loadmore = "true";
			$load_url = "explore/popular-songs/";
			$load_cont = "#popular-songs";
			$load_tmpl = "browse_pop_new_songs";
		}else{
			$loadmore = "";
		}
		$recom_Arr = array(
			'data_array' => $data_array,
			'tabid' => $tabid,
			'class_nm' => $class,
			'loadmore' => $loadmore,
			'load_more_url' => $load_url,
			'template' => $load_tmpl,
			'container' => $load_cont,
			'header_tmp' => $header_tmp,
		);

		/*dump($data_array);*/
		
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['rightPanel']="right_panel.html";
		
		$template_arry['notificationAllRow'] = "notifications/notification_row.html"; 

		if($loadmore == "true")
			$template_arry['loadMore'] = "general/loadmore.html";

		$template_arry['contentPanel'] = "notifications/notifications.html";

		$template_arry['playerPanel']="player_panel.html";
		$data1=get_template_content($template_arry,$recom_Arr );

		$a['data'] = $data1;
		$a['redirectURL']=base_url();
		$a['current_tm']='recomended';
		$this->load->view('home',$a);

	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */