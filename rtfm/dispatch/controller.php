<?php
namespace Dispatch;

class Controller {
  
  protected $layout = "application";
  
  public function __construct($params){
    foreach($params as $key => $value)
      $this->$key = $value;
  }
  
  public function _call($action){
    $this->$action();
    $view = new View($this->layout, $this->_user_vars, $this->request->format());
    $template = $this->_template_path().'/'.$action;
    return $view->render($template);
  }
  
  private function _template_path(){
    $template = preg_replace(array("/Controller$/", "/\\\/"), array("", DS), get_class($this));
    return strtolower($template);
  }
  
  public function __set($name, $value){
    $this->$name = $this->_user_vars[$name] = $value;
  }
  
}