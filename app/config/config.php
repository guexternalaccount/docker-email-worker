<?php
/**
 * @copyright Copyright (c) 2011 - 2015 Oleksandr Torosh (http://yonastudio.com)
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Application;

class Config
{

  public static function get ()
  {
    
    $application = include_once APPLICATION_PATH . '/config/environment/' . APPLICATION_ENV . '.php';

    $config = [
      'mail' => (isset($application['mail'])) ? $application['mail'] : null,
      'amqp' => (isset($application['amqp'])) ? $application['amqp'] : null,
    ];

    return new \Phalcon\Config($config);
  }
}
