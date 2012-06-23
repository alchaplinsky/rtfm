<?php
namespace Rack;

class Cookie {
  
  public static function write($key, $val = "", $time = 0){
    return setcookie($key, $val, time()+$time*60, "/", "");
  }
  
  public static function read($key){
    return !empty($_COOKIE[$key]) ? $_COOKIE[$key] : false;
  }
  
  public static function destroy($key){
     setcookie($key, "", time() + 0, "/");
  }

}