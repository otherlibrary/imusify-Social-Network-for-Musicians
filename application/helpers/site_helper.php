<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('get_template_content')) {

    function get_template_content($array = [], $data, $mainId = "MainPanel", $array_string = NULL)
    {

        $CI =& get_instance();
        $CI->load->library('Tmpl');
        $CI->load->model('commonfn');
        $ajax = $CI->config->item('ajax');
        $temp_uimg = NULL;
        //var_dump($CI->session->all_userdata());exit;
        if ($data != '') {
            if (isset($CI->session->userdata('user')->id) && $CI->session->userdata('user')->id > 0) {
                $left_panel = true;
                $username = $CI->session->userdata('user')->username;
            } else {
                $left_panel = false;
                $username = null;
            }

            if (isset($CI->session->userdata('user')->id) && $CI->session->userdata('user')->id > 0) {
                $temp_uimg = $CI->commonfn->get_photo('p', $CI->session->userdata('user')->id, 64, 64);
                /*$temp_array = explode("/",$temp);
                $temp_uimg = end($temp_array);*/
            }

            if ($CI->router->fetch_class() != "" || $CI->router->fetch_class() != NULL)
                $classname = $CI->router->fetch_class();
            $title = ($CI->config->item('title') != '' && $CI->config->item('title') != SITE_NM) ? $CI->config->item('title') . " - " . SITE_NM : $CI->config->item('title');
            $data1 = [
                'title' => SITE_NM,
                'pg_title' => $title,
                'url' => base_url(),
                'img' => img_url(),
                'loggedin' => $left_panel,
                'username' => $username,
                'user_id' => isset($CI->session->userdata('user')->id) ? $CI->session->userdata('user')->id : 0,
                'profileLink' => isset($CI->session->userdata('user')->profileLink) ? $CI->session->userdata('user')->profileLink : " ",
                'profileImage' => $temp_uimg,
                'page_class' => $classname . "_page",
                'never_sell' => $CI->session->userdata('user')->never_sell,
            ];

            $data = objectToArray($data);
            $data = array_merge($data, $data1);
            //var_dump($data);exit;
        }
        /*dump($data);*/
        if ($ajax == true) {
            print json_encode($data);
            exit;
        } else {
            // Create factory classes
            $jQueryTmpl_Factory = new jQueryTmpl_Factory();
            $jQueryTmpl_Markup_Factory = new jQueryTmpl_Markup_Factory();
            $jQueryTmpl_Data_Factory = new jQueryTmpl_Data_Factory();

            // Create jQueryTmpl object
            $jQueryTmpl = $jQueryTmpl_Factory->create();

            // Create some data from our PHP array
            $jQueryTmpl_Data = $jQueryTmpl_Data_Factory->createFromArray($data);
            if (!empty($array)) {

                foreach ($array as $id => $path) {
                    $jQueryTmpl
                        ->template
                        (
                            $id,
                            $jQueryTmpl_Markup_Factory->createFromFile(view_path() . $path)
                        );
                }
                if (!empty($array_string)) {
                    foreach ($array_string as $id => $string) {
                        $jQueryTmpl
                            ->template
                            (
                                $id,
                                $jQueryTmpl_Markup_Factory->createFromString(view_path() . $string)
                            );
                    }
                }
            }
            // Compile a template using a shared template file, or pass in text
            /*
                ->template
                (
                    'MainPanel',
                    $jQueryTmpl_Markup_Factory->createFromFile(view_path().'main.js')
                )
                ->template
                (
                    'leftPanel',
                    $jQueryTmpl_Markup_Factory->createFromFile(view_path().'left_panel.js')
                )

                ->template
                (
                    'rightPanel',
                    $jQueryTmpl_Markup_Factory->createFromFile(view_path().'right_panel.js')
                )
                ->template
                (
                    'newHeadlines',
                    $jQueryTmpl_Markup_Factory->createFromFile(view_path().'news_headlines.js')
                )

                ->template
                (
                    'playerPanel',
                    $jQueryTmpl_Markup_Factory->createFromFile(view_path().'player_panel.js')
                );
            */

            // Use pre compiled templates to render
            $data1 = $jQueryTmpl
                ->tmpl($mainId, $jQueryTmpl_Data)
                ->getHtml();

            return $data1;
        }
    }
}
if (!function_exists('get_client_ip')) {
    /*Returns ip address*/
    function get_client_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}

/*get value from table*/
if (!function_exists('getvalfromtbl')) {
    /*Returns ip address*/
    function getvalfromtbl($column = "*", $table, $cond, $param = "multiple", $orderby = NULL)
    {
        $CI =& get_instance();
        if ($cond != NULL) {
            $cond = "WHERE  " . $cond;
        }
        if ($orderby != NULL) {
            $orderby = " ORDER BY " . $orderby;
        }
        $query = $CI->db->query("SELECT " . $column . " FROM " . $table . "  " . $cond . "  " . $orderby . " LIMIT 1");
        //print_query();
        $row = $query->result_array();
        //dump($row);
        //$occur = strpos($column,",");
        //if($column != "*" && $occur === false)
        if ($param == "multiple") {
            return $row[0];
        } else {
            return $row[0][$column];
        }
    }
}


/*function for dumping value*/

if (!function_exists('dump')) {
    function dump($arr = NULL, $session = NULL)
    {

        if ($session == "session") {
            $CI =& get_instance();
            print_r($CI->session->all_userdata());
        } else {
            if (!empty($arr)) {
                echo "<pre>";
                print_r($arr);
                echo "</pre>";
            }
        }
    }
}


/*function for printing query*/
if (!function_exists('print_query')) {
    function print_query()
    {
        $CI =& get_instance();
        echo $CI->db->last_query();
    }
}
/*function for printing query ends*/

function sortAndIndexArray($aArray, $fld)
{
    foreach ($aArray as $i => $sWord) {
        $aFinal[strtoupper(substr($sWord[$fld], 0, 1))][] = $aArray[$i];
    }
    if (isset($aFinal)) ksort($aFinal);

    return $aFinal;
}


/*function for clear inputs*/
if (!function_exists('cleanInput')) {
    function cleanInput($input)
    {
        $search = [
            '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        ];
        $output = preg_replace($search, '', $input);

        return $output;
    }
}
/*function for clear inputs*/

/*function for sanitise inputs*/
if (!function_exists('sanitize')) {
    function sanitize($input)
    {
        if (is_array($input)) {
            foreach ($input as $var => $val) {
                $output[$var] = sanitize($val);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $input = cleanInput($input);
            $output = mysql_real_escape_string($input);
        }

        return $output;
    }
}
/*function for printing query ends*/

/*function for time ago inputs*/
if (!function_exists('timeago')) {
    function timeago($datetime, $full = false)
    {
        $today = time();
        $createdday = strtotime($datetime);
        $datediff = abs($today - $createdday);
        $difftext = "";
        $years = floor($datediff / (365 * 60 * 60 * 24));
        $months = floor(($datediff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($datediff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor($datediff / 3600);
        $minutes = floor($datediff / 60);
        $seconds = floor($datediff);
        //year checker
        if ($difftext == "") {
            if ($years > 1)
                $difftext = $years . " years ago";
            elseif ($years == 1)
                $difftext = $years . " year ago";
        }
        //month checker
        if ($difftext == "") {
            if ($months > 1)
                $difftext = $months . " months ago";
            elseif ($months == 1)
                $difftext = $months . " month ago";
        }
        //month checker
        if ($difftext == "") {
            if ($days > 1)
                $difftext = $days . " days ago";
            elseif ($days == 1)
                $difftext = $days . " day ago";
        }
        //hour checker
        if ($difftext == "") {
            if ($hours > 1)
                $difftext = $hours . " hours ago";
            elseif ($hours == 1)
                $difftext = $hours . " hour ago";
        }
        //minutes checker
        if ($difftext == "") {
            if ($minutes > 1)
                $difftext = $minutes . " minutes ago";
            elseif ($minutes == 1)
                $difftext = $minutes . " minute ago";
        }
        //seconds checker
        if ($difftext == "") {
            if ($seconds > 1)
                $difftext = $seconds . " seconds ago";
            elseif ($seconds == 1)
                $difftext = $seconds . " second ago";
        }

        return $difftext;
    }
}


if (!function_exists('findExtension')) {
    function findExtension($filename)
    {
        $filename = strtolower($filename);
        $exts = explode(".", $filename);
        $n = count($exts) - 1;
        $exts = $exts[$n];

        return $exts;
    }
}

if (!function_exists('csv_enter')) {
    function csv_enter($csvfilepath, $tablename, $columnname)
    {
        $CI =& get_instance();
        $csvfile = fopen($csvfilepath, 'r');
        $data_array = [];
        $exp_array1 = explode("/", $csvfilepath);
        $file_oname = end($exp_array1);
        $i = 0;
        while (($data = fgetcsv($csvfile, 1000, ",")) !== FALSE) {
            if (!empty($data) && $data[0] != '') {
                $data_array[$i][$columnname] = $data[0];
                $data_array[$i]["parentId"] = '26';
                $data_array[$i]["type"] = "s";
                $i++;
            }
        }
        /*print_r($data_array);*/
        $CI->db->insert_batch($tablename, $data_array);
    }
}

if (!function_exists('nice_number')) {
    function nice_number($n)
    {
        /*first strip any formatting;*/
        $n = (0 + str_replace(",", "", $n));

        /*is this a number?*/
        if (!is_numeric($n)) return false;

        /*now filter it;*/
        if ($n > 1000000000000) return round(($n / 1000000000000), 1) . ' trillion';
        else if ($n > 1000000000) return round(($n / 1000000000), 1) . ' BILLION';
        else if ($n > 1000000) return round(($n / 1000000), 1) . ' MILLION';
        else if ($n > 1000) return round(($n / 1000), 1) . ' THOUSAND';

        return number_format($n);
    }
}


function objectToArray($object)
{
    if (!is_object($object) && !is_array($object)) {
        return $object;
    }
    if (is_object($object)) {
        $object = get_object_vars($object);
    }

    return array_map('objectToArray', $object);
}


function print_session()
{
    $CI =& get_instance();
    echo "<pre>";
    print_r($CI->session->all_userdata());
    echo "</pre>";
}


if (!function_exists("array_column")) {

    function array_column($array, $column_name)
    {

        return array_map(function ($element) use ($column_name) {
            return $element[$column_name];
        }, $array);

    }

}


if (!function_exists("printr")) {

    function printr($array = [], $flag = false)
    {

        echo "<pre>";
        print_r($array);
        echo "</pre>";

        if ($flag == true) {
            exit("Exit");
        }

    }

}

if (!function_exists('array_to_str_list')) {
    function array_to_str_list($source, $separator = ', ')
    {
        return rtrim(implode($separator, $source), $separator);
    }
}

/* End of file site_helper.php */
