<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPLazyConnection;

$connection = new AMQPLazyConnection('localhost', 5672, 'guest', 'guest', '/');

/**
 * Rabbitmq Producer Example
 */
$app->get('/{type}/{message}', function ($message, $type) use ($app, $connection) {

        /** Exercice 1: Create a publisher and send the message! */
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);

        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, '', 'hello');

        //echo " [x] sent $message "."\n";
        $channel->close();
        $connection->close();

        return $app['twig']->render('index.html', array('message' => $message, 'type' => $type));
    })
    ->assert('type', 'error|info');


/**
 * Rabbitmq Producer Example THUMPER
 */
$app->get('/Thumper/{type}/{message}', function ($message, $type) use ($app, $connection) {

        /** Exercice2 : Create a publisher and send the message! */
        $channel = $connection->channel();

        $producer = new Thumper\Producer($connection);
        $producer->setExchangeOptions(array('name' => 'Exchange', 'type' => 'direct'));
        $producer->publish($message, $type);

        $channel->close();
        $connection->close();

        return $app['twig']->render('index.html', array('message' => $message, 'type' => $type));
    })
    ->assert('type', 'error|info');



/**
 * Error handling
 */
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
