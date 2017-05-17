<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Following extends MY_Controller {
	
	function __construct()
	{
		parent::__construct("","front");
		$this->load->model("following_model");
		$this->rec_to_dis = 5;
		$this->rec_to_dis_detail_com = 5;
	}

	function index($page = NULL)
	{
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);
		$this->config->set_item('title','Following');
		$param_array_counter = array('counter'=>'yes');
		$total_records = $this->following_model->get_user_feed($param_array_counter);
		if($total_records > $this->rec_to_dis)
		{
			$loadmore = "true";
			$load_url = "following/";
			$load_cont = "#users_feed";
			$load_tmpl = "following_tab_render";
			$load_extra_class = "following_loadmore";

		}else{
			$loadmore = "";
		}	
		/*echo "<pre>";
			echo $total_records;
		exit;*/
		if($page != NULL && $page > 0)
		{	
			$start_limit = (($page - 1)*$this->rec_to_dis) ;	
			$new_limit = $start_limit.",".$this->rec_to_dis;					
			$last_page = ceil($total_records/$this->rec_to_dis);
			$param_array_2 = array('start_limit'=>$start_limit,'limit'=>$this->rec_to_dis);	
			$data_array = $this->following_model->get_user_feed($param_array_2);
			$final_array["feeds"] = $data_array;	
			$final_array["page"] = $page+1;
			$final_array["last_page"] = $last_page;
			header('Content-Type: application/json');
			echo json_encode( $final_array );
			exit;
		}	

		$array = array('limit'=>$this->rec_to_dis);
		$feeds = $this->following_model->get_user_feed($array);

		/*dump($feeds);*/

		$feed_articles = $this->following_model->article_suggestions();
		$param_array = array('limit'=>4);
		$folloing_suggestions = $this->following_model->follow_suggestions($param_array);
		$data = array(			
			'follow'=>$folloing_suggestions,
			'feeds' => $feeds,
			'articles' => $feed_articles,
			'loadmore' => $loadmore,
			'load_more_url' => $load_url,
			'template' => $load_tmpl,
			'container' => $load_cont,
			'load_extra_class' => $load_extra_class,

		);
		/*echo "<pre>";
			print_r($data);		*/
		$template_arry['MainPanel'] = "main.html";
		$template_arry['leftPanel'] = "left_panel.html";
		$template_arry['rightPanel'] = "right_panel.html";
		$template_arry['bigPlayerPanel'] = "big_player.html";
		
		$template_arry['contentPanel'] = "following/following.html";
		$template_arry['following_tab_render'] = "following/tab_render.html";
		$template_arry['member-list'] = "following/member-list.html";
		$template_arry['external_link'] = "following/external_link.html";
		$template_arry['textfeed'] = "following/textfeed.html";
		
		$template_arry['comment-list']="following/comment_row.html";
		if($loadmore == "true")
			$template_arry['loadMore'] = "general/loadmore.html";

		$template_arry['follow-member']="following/follow-member.html";		
		$template_arry['article']="following/article.html";
		$template_arry['playerPanel']="player_panel.html";
		$data1=get_template_content($template_arry,$data);
		$a['data'] = $data1;
		$a['redirectURL']=base_url();
		$this->load->view('home',$a);
	}

	function detail($feedlogId = NULL,$pageno = NULL)
	{
		$feed_exist_check = getvalfromtbl("id","feed_log",'id="'.$feedlogId.'"',"single");
		$this->config->set_item('title','Feed Detail');
		if(!$feed_exist_check > 0)
		{
			redirect("home","refresh");
		}
		$param_array_counter = array('counter'=>'yes','feedlogId'=>$feedlogId);
		$total_records = $this->following_model->get_feed_comments($param_array_counter);
		if($total_records > $this->rec_to_dis_detail_com)
		{
			$loadmore = "true";
			$load_url = "following/feedcomment/";
			$load_cont = "#users_feed";
			$load_tmpl = "external_link";
		}else{
			$loadmore = "";
		}	
		if($page != NULL && $page > 0)
		{	
			$start_limit = (($page - 1)*$this->rec_to_dis_detail_com) ;	
			$new_limit = $start_limit.",".$this->rec_to_dis_detail_com;					
			$last_page = ceil($total_records/$this->rec_to_dis_detail_com);
			$param_array_2 = array('start_limit'=>$start_limit,'limit'=>$this->rec_to_dis_detail_com,'feedlogId'=>$feedlogId);	
			$data_array = $this->following_model->get_feed_comments($param_array_2);
			$final_array["data"] = $data_array;	
			$final_array["page"] = $page+1;
			$final_array["last_page"] = $last_page;
			header('Content-Type: application/json');
			echo json_encode( $final_array );
			exit;
		}	

		$array = array('cond'=>'AND fl.id ="'.$feedlogId.'"');
		$feeds = $this->following_model->get_user_feed($array);
		$feed_articles = $this->following_model->article_suggestions();
		$param_array = array('limit'=>4);
		$folloing_suggestions = $this->following_model->follow_suggestions($param_array);
		$data = array(			
			'follow'=>$folloing_suggestions,
			'feeds' => $feeds,
			'articles' => $feed_articles,
			'loadmore' => $loadmore,
			'load_more_url' => $load_url,
			'template' => $load_tmpl,
			'container' => $load_cont
		);
		/*echo "<pre>";
			print_r($data);*/		
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['bigPlayerPanel']="big_player.html";

		$template_arry['contentPanel'] = "following/following_detail.html";
		$template_arry['member-list'] = "following/member-list.html";
		$template_arry['external_link'] = "following/external_link.html";
		$template_arry['comment-list'] = "following/comment_row.html";

		if($loadmore == "true")
			$template_arry['loadMore'] = "general/loadmore.html";

		$template_arry['follow-member']="following/follow-member.html";		
		$template_arry['article']="following/article.html";
		$template_arry['playerPanel']="player_panel.html";
		$data1=get_template_content($template_arry,$data);
		$a['data'] = $data1;
		$a['redirectURL']=base_url();
		$this->load->view('home',$a);
	}


	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */