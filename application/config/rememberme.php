<?php

// if your authorize function is a model, specify the model name here so that 
// this model will be instantiated
$config['requiremodel'] = 'Ilogin';

// If your authorize function is a custom library, specify the library name here
// so that this library will be instatiated
$config['requirelibrary'] = '';

// Provide the reference to your authorize function here as a string
$config['authfunc'] = 'Ilogin::cookie_login';

?>