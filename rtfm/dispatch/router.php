<?php
namespace Dispatch;

class Router {
  
  public static $get_routes = array();
  public static $post_routes = array();
  public static $put_routes = array();
  public static $delete_routes = array();
  
  public static function draw($callback){
    $router = new self;
    return $callback($router);
  }
  
  public function get($route, $match, $options = array()){
    $this->set_routes("get", $route, $match, $options);
  }
  
  public function post($route, $match, $options = array()){
    $this->set_routes("post", $route, $match, $options);
  }
  
  public function put($route, $match, $options = array()){
    $this->set_routes("put", $route, $match, $options);
  }
  
  public function delete($route, $match, $options = array()){
    $this->set_routes("delete", $route, $match, $options);
  }
  
  public function resources($resource, $callback = false){
    $resource_name = $resource;
    if(!empty($this->nested)) $resource = $this->nested."/:id/".$resource;
    $this->get("/$resource/new", "$resource_name#make");
    $this->get("/$resource/:id/edit", "$resource_name#edit");
    $this->get("/$resource/:id", "$resource_name#show");
    $this->get("/$resource", "$resource_name#index");
    $this->put("/$resource/:id", "$resource_name#update");
    $this->delete("/$resource/:id",  "$resource_name#destroy");
    $this->post("/$resource", "$resource_name#create");
    if($callback){
      $this->nested = $resource;
      $callback($this);
    }
    $this->nested = false;
  }
  
  public function scope($namespace, $callback){
    $this->namespace = $namespace;
    $callback($this);
    $this->namespace = false;
  }
  
  public static function find($request){
    require APP_PATH.DS."config".DS."routes.php";
    $routes = strtolower($request->method())."_routes";
    foreach(self::$$routes as $key => $value){
      if(preg_match(self::pattern($key), $request->path())) return array($key, $value);
    }
    throw new \Exception("No route matched ".$request->path());
  }
  
  public static function show_routes(){
    echo "<pre>";
    echo "GET => <br />";
    print_r(self::$get_routes);
    echo "POST<br />";
    print_r(self::$post_routes);
    echo "PUT<br />";
    print_r(self::$put_routes);
    echo "DELETE<br />";
    print_r(self::$delete_routes);
  }
  
  private function set_routes($method, $route, $match, $options){
    $routes_array = $method."_routes"; 
    if(!empty($this->namespace)){
      $route = "/".$this->namespace.$route;
      $match = $this->namespace."\\".$match;
    }
    $this->$routes_array($route, $match);
  }
  
  private function get_routes($route, $match){
    self::$get_routes[$route] = $match; 
  }
  
  private function post_routes($route, $match){
    self::$post_routes[$route] = $match; 
  }
  
  private function put_routes($route, $match){
    self::$put_routes[$route] = $match; 
  }
  
  private function delete_routes($route, $match){
    self::$delete_routes[$route] = $match; 
  }
  
  private static function pattern($string){
    $string = preg_replace('/:[a-z]+/','[a-z0-9_-]+', str_replace(array("/", "."), array("\/", "\."), $string));
    return "/^".$string."$/";
  }
  
}