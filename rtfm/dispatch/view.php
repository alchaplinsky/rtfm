<?php
namespace Dispatch;

class View {
  
  public function __construct($layout, $params, $format){
    $this->layout = $layout;
    $this->format = $format;
    $this->params = $params;
  }
  
  public function render($template){
    $content = $this->get_contents($template);
    $this->content_for('view', function() use($content){
      return $content;
    });
    return $this->layout != false ? $this->get_contents("layouts".DS.$this->layout) : $this->yield();
  }
  
  public function content_for($name, $method){
    $this->contents[$name] = $method;
  }
  
  public function yield($name = "view"){
    if (array_key_exists($name, $this->contents)) {
      return $this->contents[$name]();
    } 	
  }
  
  private function get_contents($file){
    if(file_exists($file = APP_PATH.DS."app".DS."views".DS.$file.".".$this->format.".php")){
      extract($this->params);
      ob_start();
      include $file;
      return ob_get_clean();
    }else{  
      throw new \Exception("Missing template ".$file);
    }
  }
  
  
  
}