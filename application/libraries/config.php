<?php
  $host = $_SERVER['HTTP_HOST'];
  
  //start session in all pages
  if (session_status() == PHP_SESSION_NONE) { session_start(); } //PHP >= 5.4.0
  //if(session_id() == '') { session_start(); } //uncomment this line if PHP < 5.4.0 and comment out line above
        if ($host == 'local.imusify.com' || $host == 'beta.imusify.com' || $host == 'dev.imusify.com')
	// sandbox or live
	define('PPL_MODE', 'sandbox');
        else define('PPL_MODE', 'production');
        
        
	if(PPL_MODE=='sandbox'){		
		define('PPL_API_USER', PAYPAL_SANDBOX_USERNAME);
		define('PPL_API_PASSWORD', PAYPAL_SANDBOX_PWD);
		define('PPL_API_SIGNATURE', PAYPAL_SANDBOX_SIGNATURE);
                define('PPL_RETURN_URL', 'http://'.$host.'/imusify/api/paypal/process');
                define('PPL_CANCEL_URL', 'http://'.$host.'/imusify/api/paypal/cancel');
                define('Merchant_Account_ID', '72ZYD4K4BBPBS');
//                if (stristr($_SERVER['HTTP_HOST'], 'beta.imusify')){
//                    define('PPL_RETURN_URL', 'http://local.imusify.com/imusify/api/paypal/process');
//                    define('PPL_CANCEL_URL', 'http://local.imusify.com/imusify/api/paypal/cancel');
//                }
                
	}
	else{		
		define('PPL_API_USER', PAYPAL_LIVE_USERNAME);
		define('PPL_API_PASSWORD', PAYPAL_LIVE_PWD);
		define('PPL_API_SIGNATURE', PAYPAL_LIVE_SIGNATURE);
                define('PPL_RETURN_URL', 'http://www.imusify.com/imusify/api/paypal/process');
                define('PPL_CANCEL_URL', 'http://www.imusify.com/imusify/api/paypal/cancel');
                define('Merchant_Account_ID', '');
	}
	
	define('PPL_LANG', 'EN');
	
	define('PPL_LOGO_IMG', 'http://beta.imusify.com/imusify/assets/images/logo.svg');
	
	define('PPL_CURRENCY_CODE', 'USD');
