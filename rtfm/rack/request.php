<?php 
namespace Rack;

class Request {
  
  public $env;
  
  private $path;
  
  private $format;
  
  private $method;
  
  private $formats = array("html", "xml", "json", "js", "text");
  
  public function __construct($env){
    Session::load();
    $this->env = $env;
    $this->log_request();
  }
  
  public function path(){
    if(!$this->path){
      $patterns = array('/\/$/', '/\?.+$/', '/(\*|\^| |\$|\~)/');
      $path = preg_replace($patterns, "", htmlentities(urldecode($this->env["REQUEST_URI"])));
      $this->path = !empty($path) ? $path : "/";
    }
    return $this->path;
  }
  
  public function format(){
    if(!$this->format){
      $this->format = preg_match('/\.('.implode("|", $this->formats).')/', $this->path(), $matches) ? $matches[1] : "html";
    }
    return $this->format;
  }
  
  public function method(){
    if(!$this->method){
      if ($this->env['REQUEST_METHOD'] == 'POST' && array_key_exists("_method", $this->env['REQUEST_METHOD'])){
        $this->method = strtoupper($_POST["_method"]);
      }else{
        $this->method = $this->env['REQUEST_METHOD'];
      }
    }
    return $this->method;
  }
  
  public function remote_ip(){
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
      return $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])){
      return $_SERVER['REMOTE_ADDR'];
    }
  }
  
  public function is_xhr(){
    return !empty($this->env['HTTP_X_REQUESTED_WITH']) && strstr(strtolower($this->env['HTTP_X_REQUESTED_WITH']),'xmlhttprequest');
  }
  
  public function params(){
    return $_GET;
  }
  
  private function log_request(){
    Logger::write("Started ".$this->method()." ".$this->path()." for ".$this->remote_ip()." at ".date("Y-m-d H:i:s"));
  }
}