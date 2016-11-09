<?php

use Phalcon\DI;
use Phalcon\DI\InjectionAwareInterface;
use Phalcon\Exception;
use Phalcon\Mvc\View\Simple as ViewSimple;

/**
 * Class EmailHelper
 * @package Classes
 */
class EmailHelper implements InjectionAwareInterface
{
  protected $_di;

  private $_default_options = [
    "secured" => false,
    "from_email" => "no-reply@mailer.dev",
    "from_name" => "no-reply"
  ];
  private $_options = null;
  public function __construct($options = null)
  {
    if (!isset($options['host']) || !$options['host']) {
      throw new \Exception('SMTP_host is required');
    }
    if (!isset($options['port']) || !$options['port']) {
      throw new \Exception('SMTP_port is required');
    }
    if (!isset($options['name']) || !$options['name']) {
      throw new \Exception('SMTP_name is required');
    }
    if (!isset($options['pass']) || !$options['pass']) {
      throw new \Exception('SMTP_pass is required');
    }
    $this->_options = array_merge($this->_default_options, $options);
  }

  public function getClient() {
    $mail = new \PHPMailer();
    $mail->isSMTP();
    $mail->Host = $this->_options['host'];
    $mail->Port = $this->_options['port'];
    $mail->Username = $this->_options['name'];
    $mail->Password = $this->_options['pass'];
    $mail->SMTPAuth = true;
    $mail->SMTPAutoTLS = false;
    $mail->setFrom($this->_options['from_email'], $this->_options['from_name']);
    return $mail;
  }

  public function setDI(\Phalcon\DiInterface  $dependencyInjector)
  {
    $this->_di = $dependencyInjector;
  }

  public function getDI()
  {
    return $this->_di;
  }

  public function sendEmail($receiverEmail, $receiverName, $subject, $body) {
    $mail = $this->getClient();
    $mail->addAddress($receiverEmail, $receiverName);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = 'Atl body';
    if(!$mail->send()){
      return false;
    }

    return true;
  }
}
