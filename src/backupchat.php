<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;



class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Server started\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
        $data = json_decode($username, true);
        $user = $data['username'];
        $db = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
        mysqli_query($db, "UPDATE accounts SET connection = '$conn->resourceId' WHERE username = '$user'");
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        session_start();

        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);

        if(isset($_SESSION['username']))
            $username = $_SESSION['username'];
        //$data['from'] = $user;
        $data['dt'] = date('d-m-Y h:m:s');
        $sentOn = $data['dt'];

        $message = $data['msg'];
        $username = $data['username'];
        $sentOn = date('Y-m-d h:m:s');
        //$chat = $data['chat'];
        //$chat = $toUser;

        $users = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
        $result = mysqli_query($users, "SELECT current_chat FROM accounts WHERE username = '$username' LIMIT 1");
        $getChat = mysqli_fetch_assoc($result);
        $chat = $getChat['current_chat'];


        $messageConn = mysqli_connect("localhost", "robertfedus", "r4997740", "messages");
        $case1 = strtolower($username . "_" . $chat);
        $case2 = strtolower($chat . "_" . $username);
        $checkTable1 = mysqli_query($messageConn, "SELECT 1 FROM $case1 LIMIT 1");
        $checkTable2 = mysqli_query($messageConn, "SELECT 1 FROM $case2 LIMIT 1");

        mysqli_connect("localhost", "robertfedus", "r4997740", "messages");

        if($checkTable1){

            mysqli_query($messageConn, "INSERT INTO $case1 VALUES(null, '$username', '$message', '$sentOn')");
         } else if($checkTable2){
            mysqli_query($messageConn, "INSERT INTO $case2 VALUES(null, '$username', '$message', '$sentOn')");
         }

        

        $toSend = 49;
        foreach ($this->clients as $client) {
            if ($from == $client) {
                $data['username'] = "You";
            } else{
                $data['username'] = $data['username'];
            }

            if ($toSend == $client->resourceId)
                $client->send(json_encode($data));
            if ($from == $client)
                $client->send(json_encode($data));

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