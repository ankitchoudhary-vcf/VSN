<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyAPP\Chat;

        require dirname(__DIR__).'/vendor/autoload.php';

        $server = IoServer::factory(
                new HttpServer(
                        new WsServer(
                                new chat()
                        )
                ),
                8282
        );

        $server->run();
?>