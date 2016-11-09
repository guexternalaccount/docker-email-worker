<?php

  require_once __DIR__ . '/../vendor/autoload.php';
  define('HOST', 'queue');
  define('PORT', 5672);
  define('USER', 'magneto');
  define('PASS', 'gu123451');
  define('VHOST', 'magneto_vhost');
  //If this is enabled you can see AMQP output on the CLI
  define('AMQP_DEBUG', true);