<?php
class PagesController extends \ApplicationController {
  
  public $temp;
  
  function index(){
    $this->temp = false;
  }
  
  function welcome(){
    return "Welcome";
  }
}