<?php

/**
 * Created by igorko on 09.06.17.
 */
class ApiService
{
    public $result = [
        'error'   => false,
        'message' => 'Success',
        'data'    => [],
        'debug'   => [],
    ];

    public function responseSuccess($data = [])
    {
        $this->result['data'] = $data;

        return $this->result;
    }

    public function responseError($message = 'Oops! Error!', $debug_data = [])
    {
        $this->result['error'] = true;
        $this->result['message'] = $message;
        $this->result['debug'] = $debug_data;

        return $this->result;
    }

    public function extract_req_data($req_data, $keys) {
        return array_filter($req_data, function ($key) use ($keys){
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Custom validation rule
     * because default CI alpha_dash rule not includes 'spaces' :(
     * @param $str
     *
     * @return bool
     */
    public function alpha_dash_spaces($str)
    {
        if (empty($str)) {
            return true;
        }
        return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? false : true;
    }
}