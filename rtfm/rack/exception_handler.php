<?php
namespace Rack;

class ExceptionHandler {
  
  public static $exception;
  
  public static $body = "<h1></h1>";
  
  public static $levels = array(
    0                  => 'Error',
    E_ERROR            => 'Error',
    E_WARNING          => 'Warning',
    E_PARSE            => 'Parsing Error',
    E_NOTICE           => 'Notice',
    E_CORE_ERROR       => 'Core Error',
    E_CORE_WARNING     => 'Core Warning',
    E_COMPILE_ERROR    => 'Compile Error',
    E_COMPILE_WARNING  => 'Compile Warning',
    E_USER_ERROR       => 'User Error',
    E_USER_WARNING     => 'User Warning',
    E_USER_NOTICE      => 'User Notice',
    E_STRICT           => 'Runtime Notice'
  );
  
  public static function handle($e){
    self::$exception = $e;
    if (ob_get_level() > 0) ob_clean();
    self::display_error();
    exit(1);
  }
  
  private static function level(){
    $error_key = self::$exception->getCode();
    return array_key_exists($error_key, self::$levels) ? self::$levels[$error_key] : $error_key;
  }
  
  private static function file_info(){
    return self::$exception->getFile().":".self::$exception->getLine();
  }
  
  public static function context(){
    $string = "";
    if ( ! file_exists(self::$exception->getFile())) return array();
    $file = file(self::$exception->getFile(), FILE_IGNORE_NEW_LINES);
    array_unshift($file, '');
    if (($start = self::$exception->getLine() - 3) < 0) $start = 0;
    if (($length = (self::$exception->getLine() - $start) + 3 + 1) < 0) $length = 0;
    foreach(array_slice($file, $start, $length, true) as $line => $text){
      $string .= "#".$line." ".htmlentities($text)."\n";
    }
    return $string;
  }
  
  private static function display_error(){
    $output = '<!DOCTYPE HTML>
    <html lang="en-US">
    <head>
      <meta charset="UTF-8">
      <title>Exception::'.self::level().'</title>
      <style type="text/css">
      *{outline:none;font-size:1em;margin:0;padding:0}
       img,iframe,fieldset,object,table{border:none}
       caption,th{text-align:left}
       td{vertical-align:top}
       sub,sup{vertical-align:baseline}
       ol,ul{list-style-type:none}
       table{border-collapse:collapse;border-spacing:0}
       th,h1,h2,h3,h4,h5,h6{font-weight:400}
       body{font:14px/1.231 Arial, Verdana, sans-serif;background:#fafafa;color:#333;padding:20px}
       h1{font-size:24px;font-weight:bold;margin-bottom:10px}
       p{margin-bottom:10px;}
       pre{font-size: 12px;padding: 10px;margin-bottom: 20px;background: #eee;border: 1px solid #ddd;}
       </style>
    </head>
    <body>
      <h1>Exception::'.self::level().'</h1>
      <p>Showing '.self::file_info().'</p>
      <pre>'.self::$exception->getMessage().'</pre>
      <p>Source (around line <strong>#'.self::$exception->getLine().'</strong>):</p>
      <pre><code>'.self::context().'</code></pre>
      <p>Backtrace:</p>
      <pre><code>'.self::$exception->getTraceAsString().'</code></pre>
    </body>
    </html>';
    return new \Rack\Response($output, 500);
  }
  
}