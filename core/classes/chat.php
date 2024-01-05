<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
 
class Chat implements MessageComponentInterface {
    protected $clients;
    public $userObj, $data;
 
    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->userObj = new \MyApp\User;
    }
 
    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $query);
        if($data = $this->userObj->getUserBySession($query['token'])){
            $this->data  = $data;
            $conn->data  = $data;
            $this->clients->attach($conn);
            $this->userObj->updateConnection($conn->resourceId, $data->userID);
            echo "New connection! ({$data->username})\n";
        }
    }
 
    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        $data  = json_decode($msg, true);
        $sendTo  = $this->userObj->userData($data['sendTo']);

        $send['sendTo']        = $sendTo->userID;
        $send['by']            = $from->data->userID;
        $send['profileImage']  = $from->data->profileImage;
        $send['username']      = $from->data->username;
        $send['type']          = $data['type'];
        $send['data']          = $data['data'];

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                if($client->resourceId == $sendTo->connectionID || $from == $client){
                    $client->send(json_encode($send));
                }
            }
        }
    }
 
    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
 
        echo "Connection {$conn->resourceId} has disconnected\n";
    }
 
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
 
        $conn->close();
    }
}