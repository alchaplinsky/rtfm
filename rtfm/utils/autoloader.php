<?php
namespace Utils;

class Autoloader {
  
  public static $types = array("Controller", "Uploader", "Mailer", "Helper");
  
  public static $aliases = array(
    'Html' => 'Helper\\Html',
    'Url' => 'Helper\\Url',
    'File' => 'Helper\\File',
    'String' => 'Helper\\String'
  );
  
  public static function load($class_name){
    if(array_key_exists($class_name, self::$aliases)){
      return class_alias(self::$aliases[$class_name], $class_name);
    }else {
      $file = self::build_path($class_name);
      if(file_exists($file)) require $file;
    }
  }
  
  private static function class_type($class_name){
    foreach(self::$types as $type){
      if (preg_match('/'.$type.'$/', $class_name)){
        return strtolower($type);
      }
    return "model";
    }
  }
  
  private static function build_path($class_name){
    $segments = explode('\\', $class_name);
    $type = self::class_type($class_name);
    array_push($segments, self::camel_to_underscore(array_pop($segments)));
    $file = strtolower(implode(DS, $segments));
    return APP_PATH.DS."app".DS.$type."s".DS.$file.".php";
  }
  
  public static function camel_to_underscore($class_name) {
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class_name));
  }
  
  public static function require_base($dir_name){
    $path = BASE_PATH.DS.$dir_name;
    foreach(array_diff(scandir($path), array(".", "..")) as $file){
      require $path.DS.$file; 
    }
  }
  
}