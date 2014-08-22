<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use PhpAmqpLib\Connection\AMQPConnection;

$console = new Application('My Silex Application', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);

/**
 * RabbitMQ Consumer
 */
$console
    ->register('rabbitmq:consumer')
    ->setDefinition(array(
            new InputOption('type', 't', InputOption::VALUE_REQUIRED, 'Message type'),
        ))
    ->setDescription('Consumes a queue message')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {

            $connections = array(
                'default' => new \PhpAmqpLib\Connection\AMQPLazyConnection('localhost', 5672, 'guest', 'guest', '/')
            );


            /** Exercice 1: Create a consumer and use it! */
            $channel = $connections['default']->channel();

            $channel->queue_declare('hello', false, false, false, false);

            echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

            $callback = function($msg) {
                echo " [x] Received ", $msg->body, "\n";
            };

            $channel->basic_consume('hello', '', false, true, false, false, $callback);

            while(count($channel->callbacks)) {
                $channel->wait();
            }
        })
;

/**
 * RabbitMQ Consumer THUMPER Alvaro Videla
 */
$console
    ->register('rabbitmqThumper:consumer')
    ->setDefinition(array(
        new InputOption('type', 't', InputOption::VALUE_REQUIRED, 'Message type'),
    ))
    ->setDescription('Consumes a queue message')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {

        $connections = array(
            'default' => new \PhpAmqpLib\Connection\AMQPLazyConnection('localhost', 5672, 'guest', 'guest', '/')
        );

        $type = $input->getOption('type');

        /** Exercice 2: Create a consumer and use it! */

        $myConsumer = function($msg)
        {
            echo $msg, "\n";
        };

        $consumer = new Thumper\Consumer($connections['default']);
        $consumer->setExchangeOptions(array('name' => 'Exchange', 'type' => 'direct'));
        $consumer->setQueueOptions(array('name' => 'queue-'.$type));
        $consumer->setRoutingKey($type);
        $consumer->setCallback($myConsumer); //myConsumer could be any valid PHP callback
        $consumer->consume(5);






    })
;

return $console;
