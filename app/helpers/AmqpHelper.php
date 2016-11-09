<?php

use Phalcon\DI;
use Phalcon\Exception;
use Phalcon\DI\InjectionAwareInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;


class AmqpHelper implements InjectionAwareInterface
{
  protected $_di;

  private $_default_options = [
    
  ];
  private $_options = null;

  private $connection = null;
  private $channel;

  public function __construct($options = null)
  {
    if (!isset($options['host']) || !$options['host']) {
      throw new \Exception('AMQP host is required');
    }
    if (!isset($options['port']) || !$options['port']) {
      throw new \Exception('AMQP port is required');
    }
    if (!isset($options['user']) || !$options['user']) {
      throw new \Exception('AMQP user is required');
    }
    if (!isset($options['pass']) || !$options['pass']) {
      throw new \Exception('AMQP pass is required');
    }
    if (!isset($options['vhost']) || !$options['vhost']) {
      throw new \Exception('AMQP vhost is required');
    }

    $this->_options = array_merge($this->_default_options, $options);
  }

  public function setDI(\Phalcon\DiInterface  $dependencyInjector)
  {
    $this->_di = $dependencyInjector;
  }

  public function getDI()
  {
    return $this->_di;
  }

  public function getAmpqChannel () { 
    if($this->connection == null ){
      $this->connection =  new AMQPStreamConnection($this->_options['host'],
                                                     $this->_options['port'], 
                                                     $this->_options['user'], 
                                                     $this->_options['pass'],
                                                     $this->_options['vhost']);
      $this->channel = $this->connection->channel();
    }
    return $this->channel;
  }

  public function shutdown () {
    if($this->channel){
      $this->channel->close();
    }
    if($this->connection) {
      $this->connection->close();      
    }
    register_shutdown_function('shutdown', $this->channel, $this->connection);
  } 
  
}
