<?php
const APPLICATION_PATH = __DIR__;
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

require_once APPLICATION_PATH.'./../vendor/autoload.php';
require_once APPLICATION_PATH.'/config/config.php';

use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;



// Using the CLI factory default services container
$di = new CliDI();

/**
 * Register the autoloader and tell it to register the tasks directory
 */
$loader = new Loader();

$loader->registerDirs(
  [
    APPLICATION_PATH. "/tasks",
    APPLICATION_PATH. "/helpers"

  ]
);

$loader->register();
# Load config

$config = \Application\Config::get();
$di->set("config", $config);

# Config email
$di->setShared('email', function () use ($config) {
  return new EmailHelper([
    'host' => $config->mail->host,
    'port' => $config->mail->port,
    'name' => $config->mail->name,
    'pass' => $config->mail->pass,
    'secured' => $config->mail->secured,
    'from_email' => $config->mail->from_email,
    'from_name' => $config->mail->from_name
  ]);
});

# Config Ampq 
$di->setShared('amqp', function () use ($config) {
  return new AmqpHelper([
    'host' => $config->amqp->host,
    'port' => $config->amqp->port,
    'user' => $config->amqp->user,
    'pass' => $config->amqp->pass,
    'vhost' => $config->amqp->vhost
  ]);
});

// Create a console application
$console = new ConsoleApp();

$console->setDI($di);



/**
 * Process the console arguments
 */
$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments["task"] = $arg;
    } elseif ($k === 2) {
        $arguments["action"] = $arg;
    } elseif ($k >= 3) {
        $arguments["params"][] = $arg;
    }
}



try {
    // Handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(2);
}
