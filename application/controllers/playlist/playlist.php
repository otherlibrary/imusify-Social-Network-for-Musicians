<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Playlist extends MY_Controller {

	function __construct()
	{
		//parent::__construct("","front");
            parent::__construct();
		$this->load->model('playlist_model');	
	}

	function multidimensional_search($parents, $searched) {
	  if (empty($searched) || empty($parents)) {
	    return false;
	  }

	  foreach ($parents as $key => $value) {
	    $exists = true;
	    foreach ($searched as $skey => $svalue) {
	      $exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
	    }
	    if($exists){ return $key; }
	  }

	  return false;
	} 

	function index($playlistLink = NULL,$profileLink = NULL)
	{
		
		//add_js('all-style.js');
		$this->config->set_item('title','playlist');
		$m[]=array("img_url"=>img_url()."vedio-img1.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."vedio-img2.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."img3.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."img4.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."vedio-img2.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."vedio-img5.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."img3.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."img4.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."vedio-img1.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
	
		$playlists = $this->playlist_model->get_playlists($playlistLink,$profileLink);
		//dump($playlists);
		$middle_value = ceil(count($playlists)/2);		
		$middle_value = $middle_value-1;		
		//$final_name = ucfirst(substr($name,0,1));
		//echo $final_name;		
		$v=sortAndIndexArray($playlists,'name');
		//print_r($v);
		/*$middle_value = ceil(count($v)/2);		
		$name = $v[$middle_value-1]["name"];
		$final_name = ucfirst(substr($name,0,1));
		echo $final_name;*/
		//dump($v);exit;

		if($playlistLink != "")
		{		

			foreach($playlists as $pl)
			{
				if($pl["perLink"] == $playlistLink)
					$plid = $pl["id"];				
			}

			if($plid > 0)
			{
				$middle_value = $this->multidimensional_search($playlists, array('id'=>$plid));
				/*echo $middle_value;exit;*/
				$cur_playlist_detail = $this->playlist_model->get_playlist_songs($plid);
				$playlist_id=$plid;
			
			}
			else
				redirect(base_url(), 'refresh');
		}
		else{
			$id = $playlists[$middle_value]["id"];
			$cur_playlist_detail = $this->playlist_model->get_playlist_songs($id);
			$playlist_id=$id;
		}
		$final_pl=array();
		foreach ($playlists as $index => $playlist) {
			$class_middle='';
			if($index==$middle_value){
				$class_middle="current";
			}
			$playlist['class_middle']=$class_middle;
			array_push($final_pl, $playlist);
		}
		//dump($cur_playlist_detail);exit;
		$data = array(
			'title'=>$this->config->item('title'),
			'url' => base_url(),
			'img'=>img_url(),
			'loggedin'=>false,
			'news'=>$m,
			'playlist'=>$final_pl,
			'alphabetical'=>$v,
			'class'=>'open',
			'plid'=>$playlist_id,
			'songs' => $cur_playlist_detail["songs"],
			'current' => $middle_value,
			'current_plimg' => $playlists[$middle_value]["playlist_image"],
			'name' => $cur_playlist_detail["name"],
			'no_of_track' => $cur_playlist_detail["no_of_track"],
			'plays' => $cur_playlist_detail["plays"],
			"sets_page" => "true",
			'pl_loading_img' => img_url()."pl_loading.gif"
		);	
		
		//dump($data);
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['playlist_list']="playlist/playlist_list.html";
		$template_arry['playlistsongsHeader']="general/songs_header_four.html";
		$template_arry['songsData']="general/songs_row_four_pllist.html";
		$template_arry['playlistDetail']="playlist/playlist_info.html";
		$template_arry['playlistContent']="playlist/playlist.html";
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="headlines.html";
		$template_arry['newsRow']="news_row.html";
		$template_arry['playerPanel']="player_panel.html";
		$template_arry['bigPlayerPanel']="big_player.html";
		
		$data1=get_template_content($template_arry,$data);

		$a['data'] = $data1;
		$a['current_tm']='sign_up';
		
		$this->load->view('home',$a);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */