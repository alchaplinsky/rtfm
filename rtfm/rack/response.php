<?php
namespace Rack;

class Response {
  
  private $status;

  private $headers;

  private $body;

  private $statuses = array(
    100 => 'Continue',
    101 => 'Switching Protocols',
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    207 => 'Multi-Status',
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    307 => 'Temporary Redirect',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Request Entity Too Large',
    414 => 'Request-URI Too Long',
    415 => 'Unsupported Media Type',
    416 => 'Requested Range Not Satisfiable',
    417 => 'Expectation Failed',
    422 => 'Unprocessable Entity',
    423 => 'Locked',
    424 => 'Failed Dependency',
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported',
    507 => 'Insufficient Storage',
    509 => 'Bandwidth Limit Exceeded'
    );
  
  public function __construct($body = "", $status = 200, $headers = array()){
    $this->headers = array_merge(array("Content-Type" => "text/html; charset=utf-8"), $headers);
    $this->status = $status;
    $this->body = $body;
    $this->send();
  }

  public function send(){
    if ( ! headers_sent()){ 
      $this->send_headers();
    }
    echo (string) $this->body;
    $this->log_response();
  }
  
  private function send_headers(){
    $protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
    header($protocol.' '.$this->status.' '.$this->statuses[$this->status]);
    foreach ($this->headers as $name => $value){	
      header($name.': '.$value, true);
    }
  }
  
  private function log_response(){
    Logger::write("Completed ".$this->status." ".$this->statuses[$this->status]." at ".date("Y-m-d H:i:s")."\n");
  }
  
}