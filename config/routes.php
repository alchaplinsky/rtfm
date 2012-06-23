<?php
Dispatch\Router::draw(function($R){

  $R->get( '/',              'pages#index'   );
  $R->get( '/page/one/:id',  'pages#index'   );
  $R->get( '/hello/world',   'pages#welcome' );
  $R->get( '/play/game',     'pages#welcome' );
  $R->post('/articles/update', 'articles#update');
  
  $R->resources('articles', function($R){
      $R->resources('comments');
  });
  
  $R->scope('admin', function($R){
      $R->resources('pages');
      $R->resources('articles');
  });
  
  $R->get('/posts', 'posts#index');
  
  $R->scope('console', function($R){
      $R->get('/yahoo', 'aba#show');
  });
  
});