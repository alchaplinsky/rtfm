<?php
namespace Rack;

class Logger {
  
  public static function write($content, $file = "application"){
    file_put_contents(APP_PATH.DS.'log'.DS.'application.log', $content.PHP_EOL, LOCK_EX | FILE_APPEND);
  }
  
}