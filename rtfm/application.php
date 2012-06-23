<?php
define("BASE_PATH", __DIR__);
require 'utils'.DS.'autoloader.php';
Utils\Autoloader::require_base('rack');
Utils\Autoloader::require_base('dispatch');

// --------------------------------------------------------------
// Set the error reporting and display levels.
// --------------------------------------------------------------
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set('error_log', APP_PATH.DS.'log'.DS.'php.log');

spl_autoload_register(array('Utils\\Autoloader', 'load'));

set_exception_handler(function($e){
    Rack\ExceptionHandler::handle($e);
});

set_error_handler(function($number, $error, $file, $line) {
  Rack\ExceptionHandler::handle(new ErrorException($error, $number, 0, $file, $line));
});

register_shutdown_function(function(){
  if ( !is_null($error = error_get_last())){
    extract($error);
    Rack\ExceptionHandler::handle(new ErrorException($message, $type, 0, $file, $line));
  }
});

class Application {
  
  public function __construct(){
    $this->request = new Rack\Request($_SERVER);
    $body = Dispatch\Dispatcher::handle_request($this->request);
    $this->response = new Rack\Response($body, 200);
  }  
}

$APP = new Application();