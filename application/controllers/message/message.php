<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends MY_Controller {

	function __construct()
	{
		parent::__construct("","front");
		//$this->load->model('user_profile');
		$this->load->model('conversation_model');
		$this->load->model('message_model');
	}

	function index($conv_id=0)
	{		
		$ajax=$this->config->item('ajax');
		$conv_panel=$this->input->post('conv_panel');
		$this->config->set_item('title','Conversations');
		if($conv_id==0)
		{
			$query="select conversation_id from message where from_id= ? or to_id= ? order by id asc limit 1"; 
			$conv_id = $this->db->query($query,array($this->session->userdata('user')->id,$this->session->userdata('user')->id))->row()->conversation_id;
		}
		
		$member_list = $this->conversation_model->getConversation($conv_id);
		$messages = $this->message_model->getMessages($conv_id);
		
		/*dump($this->commonfn->get_unread_notifications());
		exit;*/

		if($ajax && !$conv_panel)
		{
			$conversation =array(
			'message'=>$messages
			);
		}else{
			$conversation =array(
			'member_list'=>$member_list,
			'message'=>$messages
			);
		}
		
		
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['rightPanel']="right_panel.html";		
		$template_arry['contentPanel']="message/message.html";			
		$template_arry['memberRow']="message/member_row.html";
		$template_arry['memberList']="message/member_list.html";
		$template_arry['setTemplate']="message/mesages_list.html";
		$template_arry['chatBox']="message/chat_box.html";
		$template_arry['playerPanel']="player_panel.html";
						
		$data1=get_template_content($template_arry,$conversation);
		
		$a['data'] = $data1;
		$a['redirectURL']=base_url();
		$a['current_tm']='profile';
		$this->load->view('home',$a);
	}
	

	function new_message($conv_id=0){
		
		$ajax=$this->config->item('ajax');
		$conv_panel=$this->input->post('conv_panel');
		$this->config->set_item('title','New Message');
		
		if($conv_id==0)
		{
			$query="select conversation_id from message where from_id= ? or to_id= ? order by id asc limit 1"; 
			$conv_id = $this->db->query($query,array($this->session->userdata('user')->id,$this->session->userdata('user')->id))->row()->conversation_id;
		}
		
		$member_list = $this->conversation_model->getConversation($conv_id);
		
		if($ajax && !$conv_panel)
		{
			$conversation =array(
				'member_list'=>$member_list
			);
		}else{
			$conversation =array(
				'member_list'=>$member_list
			);
		}
		
		
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['rightPanel']="right_panel.html";

		$template_arry['contentPanel']="message/message.html";

		$template_arry['setTemplate']="message/new_message.html";

		$template_arry['memberRow']="message/member_row.html";
		$template_arry['memberList']="message/member_list.html";
		//$template_arry['chatBox']="message/chat_box.html";
		
		$template_arry['playerPanel']="player_panel.html";
						
		$data1=get_template_content($template_arry,$conversation);
		
		$a['data'] = $data1;
		$a['redirectURL']=base_url();
		$a['current_tm']='profile';
		$this->load->view('home',$a);



	}


}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */