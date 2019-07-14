<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;



class Chat implements MessageComponentInterface {
    protected $clients;
    private $myConn;
    private $myUser;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Server started\n";
    }

    private function getEncryption($user, $action){
        $conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");

        switch($action){
            case "bool":
                $result = mysqli_query($conn, "SELECT encrypt FROM accounts WHERE username = '$user' LIMIT 1");
                return mysqli_fetch_field($result);
                break;
            
            case "rotors":
                $result1 = mysqli_query($conn, "SELECT rotor1 FROM accounts WHERE username = '$user' LIMIT 1");
                $result2 = mysqli_query($conn, "SELECT rotor2 FROM accounts WHERE username = '$user' LIMIT 1");
                $result3 = mysqli_query($conn, "SELECT rotor3 FROM accounts WHERE username = '$user' LIMIT 1");
                $rotor1 = mysqli_fetch_assoc($result1);
                $rotor2 = mysqli_fetch_assoc($result2);
                $rotor3 = mysqli_fetch_assoc($result3);
                $rotors = array($rotor1, $rotor2, $rotor3);

                return $rotors;
                break;
        }

    }

    private function encrypt($user, $msg){
        $length = strlen($msg);

        $rotors = $this->getEncryption($user, "rotors");

        return $length;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        $this->myConn = $conn->resourceId;
        echo "New connection! ({$conn->resourceId})\n";
        

        //$user = $data['username'];
        //$db = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
        //mysqli_query($db, "UPDATE accounts SET connection = '$conn->resourceId' WHERE username = '$user'");
    }

    public function onMessage(ConnectionInterface $from, $msg) {


        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);

        //$data['from'] = $user;
        $data['dt'] = date('d-m-Y h:m:s');
        $sentOn = $data['dt'];

        $message = $data['msg'];
        $username = $data['username'];
        $sentOn = date('Y-m-d h:m:s');
        //$chat = $data['chat'];
        //$chat = $toUser;
        echo $this->getEncryption($username, "bool");
       
        if($this->getEncryption($username, "bool") == 1){
            $this->encrypt($username, $message);
            echo "Message is sent encrypted.\n";
            echo "\nMessage length: " . $this->encrypt($username, $message) . "\n";
        } 
            
        $users = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
        $result = mysqli_query($users, "SELECT current_chat FROM accounts WHERE username = '$username' LIMIT 1");
        $getChat = mysqli_fetch_assoc($result);
        $chat = $getChat['current_chat'];


        $messageConn = mysqli_connect("localhost", "robertfedus", "r4997740", "messages");
        $case1 = strtolower($username . "_" . $chat);
        $case2 = strtolower($chat . "_" . $username);
        $checkTable1 = mysqli_query($messageConn, "SELECT 1 FROM $case1 LIMIT 1");
        $checkTable2 = mysqli_query($messageConn, "SELECT 1 FROM $case2 LIMIT 1");


        if($checkTable1){

            mysqli_query($messageConn, "INSERT INTO $case1 VALUES(null, '$username', '$message', '$sentOn')");
         } else if($checkTable2){
            mysqli_query($messageConn, "INSERT INTO $case2 VALUES(null, '$username', '$message', '$sentOn')");
         }

        $username = $data['username'];
        $this->myUser = $username;
        echo "The ID is: " . $this->myConn . "\n";
        $id = $this->myConn;
        mysqli_query($users, "UPDATE accounts SET connection = '$id' WHERE username = '$username'");
        $result = mysqli_query($users, "SELECT current_chat FROM accounts WHERE username = '$username'");
        while($row = mysqli_fetch_array($result))
            $current_chat = $row['current_chat'];

        $result = mysqli_query($users, "SELECT connection FROM accounts WHERE username = '$current_chat'");
        while($row = mysqli_fetch_array($result))
            $current_chat_connection = $row['connection'];
        
        $toSend = $current_chat_connection;
        foreach ($this->clients as $client) {
            if ($from == $client && $data['username'] != 'connecting...') {
                //$data['username'] = "You";
            } else{
                $data['username'] = $data['username'];
            }


            if($data['username'] != 'connecting...' && $data['msg'] != 'connecting...'){
                if ($toSend == $client->resourceId)
                    $client->send(json_encode($data));
                if ($from == $client)
                    $client->send(json_encode($data));
            }

        }

        return $username;
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        /*if(isset($this->myUser)){
            $users = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
            mysqli_query($users, "UPDATE accounts SET connection = 0 WHERE username = '$this->myUser'");
        }*/

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}