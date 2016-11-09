<?php

use Phalcon\Cli\Task;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MainTask extends Task
{
    public function mainAction()
    {
        $this->readMessage();
    }

    protected function readMessage(){
      $mailer = $this->getDi()->get('email');
      $consumerTag = 'consumer';
      $amqp = $this->getDi()->get('amqp');
      $config = $this->getDi()->get('config');

      $queue = $config->amqp->queue;
      $exchange = $config->amqp->exchange;
      $channel = $amqp->getAmpqChannel();
    
      $channel->queue_declare($queue, false, true, false, false);
      $channel->exchange_declare($exchange, 'direct', false, true, false);
      $channel->queue_bind($queue, $exchange);
      
      $channel->basic_consume($queue, 
                              $consumerTag, 
                              false, 
                              false, 
                              false, 
                              false, 
                              function  ($message) use ($mailer)
                                  {
                                    $mailContent = json_decode($message->body);
                                    $receiverEmail = $mailContent->receiverEmail;
                                    $receiverName = $mailContent->receiverName;
                                    $subject = $mailContent->subject;
                                    $content = $mailContent->content;
                                  
                                    if($mailer->sendEmail($receiverEmail, $receiverName, $subject, $content)) {
                                      $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);    
                                    }
                                  }
      );
    
      // Loop as long as the channel has callbacks registered
      while (count($channel->callbacks)) {
          $channel->wait();
      }
    }
}