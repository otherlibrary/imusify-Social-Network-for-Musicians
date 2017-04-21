
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('check_status'))
{
    function check_status($status = '',$id)
    {
        return ($status == 'y') ? '<div class="make-switch switch-small" data-uid="'.$id.'" data-col="stats_col" data-on="success" data-off="danger"> <input type="checkbox" checked=""> </div>' : '<div class="make-switch switch-small" data-uid="'.$id.'" data-col="stats_col" data-on="success" data-off="danger"><input type="checkbox">
							</div>';
    }   
}

if ( ! function_exists('check_is_default'))
{
    function check_is_default($isDefault = '',$id)
    {
        return ($isDefault == 'y') ? '<div class="make-switch switch-small" data-uid="'.$id.'" data-col="default_col" data-on="success" data-off="danger"> <input type="checkbox" checked=""> </div>' : '<div class="make-switch switch-small" data-uid="'.$id.'" data-col="default_col" data-on="success" data-off="danger"><input type="checkbox">
							</div>';
    }   
}

if ( ! function_exists('check_user_status'))
{
    function check_user_status($status = '',$id)
    {
        return ($status == 'y') ? '<div class="make-switch switch-small" data-uid="'.$id.'" data-on="success" data-off="danger"> <input type="checkbox" checked=""> </div>' : '<div class="make-switch switch-small" data-uid="'.$id.'" data-on="success" data-off="danger"><input type="checkbox">
							</div>';
    }   
}

if ( ! function_exists('check_type'))
{
    function check_type($type)
    {
        return ($type == 'p') ? 'Primary' : 'Secondary';
    }   
}
/* End of file MY_datatable_helper.php */
/* Location: ./application/helpers/MY_datatable_helper.php */  