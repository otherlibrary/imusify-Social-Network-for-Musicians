<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model("article_model");
	}

	function index($perlink = NULL)
	{
		$m[]=array("img"=>img_url()."vedio-img1.jpg");
		$m[]=array("img"=>img_url()."vedio-img2.jpg");
		$m[]=array("img"=>img_url()."img3.jpg");
		$m[]=array("img"=>img_url()."img4.jpg");
		$m[]=array("img"=>img_url()."vedio-img2.jpg");
		$m[]=array("img"=>img_url()."vedio-img5.jpg");
		$m[]=array("img"=>img_url()."img3.jpg");
		$m[]=array("img"=>img_url()."img4.jpg");
		$m[]=array("img"=>img_url()."vedio-img1.jpg");
		
		$article_details = $this->article_model->get_details("perLink='".$perlink."'");
		if(!empty($article_details)){
			$data = array(
				'news'=>$m,
				'article_details' => $article_details[0]
				);	
			$template_arry['MainPanel'] = "main.html";
			$template_arry['leftPanel'] = "left_panel.html";
			$template_arry['popUpContent'] = "article/article.html";
			$template_arry['articleRow'] = "article/similar_artical_row.html";
			$template_arry['rightPanel']="right_panel.html";
			$template_arry['contentPanel']="headlines.html";
			$template_arry['newsRow']="news_row.html";
			$template_arry['playerPanel']="player_panel.html";						
			$template_arry['bigPlayerPanel']="big_player.html";
			$data1=get_template_content($template_arry,$data);
			$a['data'] = $data1;
			$a['current_tm']='article';
			$this->load->view('home',$a);
		}
		else{

		}
		
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */