<?php
namespace Dispatch;

class Dispatcher {
  
  public static function handle_request($request){
    $action = Router::find($request);
    $controller = self::parse_controller($action[1]);
    $params = array('request'=>$request, 'params' => self::extract_params($action[0], $request));
    $controller_obj = new $controller($params);
    return $controller_obj->_call(self::parse_action($action[1]));
  }
  
  private static function parse_controller($controller_action){
    $array = explode("#", $controller_action);
    return ucfirst($array[0])."Controller";
  }
  
  private static function parse_action($controller_action){
    $array = explode("#", $controller_action);
    return $array[1];
  }
  
  private static function extract_params($route, $request){
    $route_chunks = explode("/", substr($route, 1));
    $request_chunks =  explode('/', substr($request->path(), 1));
    $params = array();
    for($i = 0; $i < count($route_chunks); $i++){
      if(preg_match('/^:/', $route_chunks[$i]) && !empty($request_chunks[$i])){
        $key = substr($route_chunks[$i], 1); 
        $params[$key] = $request_chunks[$i];
      }
    }
    return array_merge($request->params(), $params);
  }
}