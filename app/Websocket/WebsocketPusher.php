<?php


namespace App\Websocket;


use Illuminate\Support\Facades\Log;

class WebsocketPusher
{
    private static $socket = null;
    /**
     * Pushes message to the socket
     *
     * @param string $message
     */
    public static function push(string $message) :void
    {
        try {
            $socket = self::getSocket();
            if ($socket) {
                $socket->send($message);
            }
        } catch (\ZMQSocketException $e) {
            Log::error( "ZMQ Socket Send Exception", [$e->getMessage(), $e->getTrace()]);
        }
    }

    private static function getSocket() :?\ZMQSocket
    {
        try {
            if (!self::$socket) {
                self::$socket = (new \ZMQContext())->getSocket('tcp://127.0.0.1:5555');
            }

            return self::$socket;
        } catch (\ZMQSocketException $e) {
            Log::error( "ZMQ Exception", [$e->getMessage(), $e->getTrace()]);
            return null;
        }
    }
}
