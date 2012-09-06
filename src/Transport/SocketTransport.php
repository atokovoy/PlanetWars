<?php
namespace Transport;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class SocketTransport implements Transport
{
    private $connection;

    private $timeout = 5;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function hasData()
    {
        $status = stream_get_meta_data($this->connection);
        return $status["unread_bytes"];
    }

    public function send($data)
    {
//        echo "sent\n";
        $len = strlen($data);
        $msg = pack("S", $len) . $data;
        if (false === @socket_write($this->connection, $msg)) {
            throw new \Exception\TransportException('broken pipe');
        }
//        echo "sent & go out\n";
    }

    public function receive()
    {
//        echo "receive \n";
        $timeout = $this->timeout;
        $length = @socket_read($this->connection, 2);
//        echo "first round\n";
        if ('' == $length) {
            while (('' == $length) && $timeout) {
                sleep(1);
                $length = @socket_read($this->connection, 2);
                $timeout--;
                echo $timeout."\n";
            }

            if (0 == $timeout) {
//                echo "timeout\n";
                throw new \Exception\TransportException('timeout');
            }
        }
        $length = unpack("S", $length);
        $data = @socket_read($this->connection, $length[1]);
//        echo "receive & go out\n";
        return $data;
    }
}
