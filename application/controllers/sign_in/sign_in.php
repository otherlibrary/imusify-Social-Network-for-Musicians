<?php if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class sign_in extends MY_Controller
{
    function __construct()
    {
        parent::__construct("", "nologin");
    }

    function index()
    {
        $pictures = ["vedio-img1.jpg", "vedio-img2.jpg", "img3.jpg", "img4.jpg", "vedio-img2.jpg", "vedio-img5.jpg", "img3.jpg", "img4.jpg", "vedio-img1.jpg",];
        foreach ($pictures as $pic) {
            $data['news'] = ["img" => img_url() . $pic];
        }
        $data['parenturl'] = ($this->input->get('parenturl') != "") ? $this->input->get('parenturl') : "";

        $this->config->set_item('title', 'Sign In');

        $template_array = [
            'MainPanel'      => "main.html",
            'leftPanel'      => "left_panel.html",
            'popUpContent'   => "sign_in/sign_in.html",
            'rightPanel'     => "right_panel.html",
            'contentPanel'   => "headlines.html",
            'newsRow'        => "news_row.html",
            'playerPanel'    => "player_panel.html",
            'bigPlayerPanel' => "big_player.html",
        ];

        $tpl_content['data']       = get_template_content($template_array, $data);
        $tpl_content['current_tm'] = 'sign_in';
        $this->load->view('home', $tpl_content);
    }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */