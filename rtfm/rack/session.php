<?php
namespace Rack;

class Session {
  
  private $session = array();
  
  public static function load(){
    session_start();
    foreach($_SESSION as $key => $value){
      self::write($key, $value);
    }
  }
  
  public static function write($key, $value){
    $_SESSION[$key] = self::$session[$key] = $value;
  }
  
  public static function read($key){
    return !empty(self::$session[$key]) ? self::$session[$key] : false;
  }
  
}