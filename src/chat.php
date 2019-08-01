<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Server started";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

            $conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
            if(!$conn){
                die('Error connecting to database: ' . mysql_error());
            }

            $data = json_decode($msg, true);
            $username = $data['username'];
            $data['dt'] = date('d-m-Y h:m:s');
            $sentOn = date('Y-m-d h:m:s');
            $message = $data['msg'];


            // OBTINEM PREVIOUS ID
            $result = mysqli_query($conn, 'SELECT id FROM messages ORDER BY id DESC LIMIT 1');
            $row = mysqli_fetch_assoc($result);
            $prev_id = (int)$row['id'];
            $data['id'] = $prev_id + 1;
            

            // OBTINEM current_chat
            $result = mysqli_query($conn, "SELECT current_chat FROM accounts WHERE username = '$username' LIMIT 1");
            $row = mysqli_fetch_assoc($result);
            $current_chat = (string)$row['current_chat'];
            $data['current_chat'] = $current_chat;

            // INSERARE IN BAZA DE DATE A MESAJULUI
            // VERIFICAM DACA ENCRYPT E 1 SAU 0 IN BAZA DE DATE
            $result = mysqli_query($conn, "SELECT encrypt FROM accounts WHERE username = '$username' LIMIT 1");
            $row = mysqli_fetch_assoc($result);

            if((int)$row['encrypt'] == 1){
                $encrypted_message = $data['encrypted'];
                $result1 = mysqli_query($conn, "SELECT rotor1 FROM accounts WHERE username = '$username' LIMIT 1");
                $row1 = mysqli_fetch_assoc($result1);
                $r1 = (int)$row1['rotor1'];
        
                $result2 = mysqli_query($conn, "SELECT rotor2 FROM accounts WHERE username = '$username' LIMIT 1");
                $row2 = mysqli_fetch_assoc($result2);
                $r2 = (int)$row2['rotor2'];
        
                $result3 = mysqli_query($conn, "SELECT rotor3 FROM accounts WHERE username = '$username' LIMIT 1");
                $row3 = mysqli_fetch_assoc($result3);
                $r3 = (int)$row3['rotor3'];
                mysqli_query($conn, "INSERT INTO messages(senter, receiver, message, encrypted_message, time, rotor1, rotor2, rotor3, encrypted) VALUES('$username', '$current_chat', '$message', '$encrypted_message', '$sentOn', '$r1', '$r2', '$r3', 1)");
            } else
                mysqli_query($conn, "INSERT INTO messages(senter, receiver, message, time) VALUES('$username', '$current_chat', '$message', '$sentOn')");






        foreach ($this->clients as $client) {
            $client->send(json_encode($data));
            
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    /*public function getRotorsFromDB($username){
        $result = mysqli_query($conn, "SELECT rotor1 FROM accounts WHERE username = '$username' LIMIT 1");
        $row = mysqli_fetch_assoc($result);
        $r1 = (int)$row['rotor1'];

        $result = mysqli_query($conn, "SELECT rotor2 FROM accounts WHERE username = '$username' LIMIT 1");
        $row = mysqli_fetch_assoc($result);
        $r2 = (int)$row['rotor2'];

        $result = mysqli_query($conn, "SELECT rotor3 FROM accounts WHERE username = '$username' LIMIT 1");
        $row = mysqli_fetch_assoc($result);
        $r3 = (int)$row['rotor3'];

        return $rotors = array($r1, $r2, $r3);
    }

    public function encrypt($username, $text, $rotor1, $rotor2, $rotor3){
        $text = strtolower($text);
        $lgt = strlen($text);
        $user = $username;
        $rotor1 = getRotor(getRotorsFromDB($user)[0]);
        $rotor2 = getRotor(getRotorsFromDB($user)[1]);
        $rotor3 = getRotor(getRotorsFromDB($user)[2]);

        $j = 97;

        for($i = 0; $i <= 25; $i++){
            $alph[$i] = $j;
            $j++;
        }
        for($i = 0; $i <= $lgt - 1; $i++){
            if($text[$i] == ' ')
                $eText[$i] = ' ';
                else{
            // Obtinem indicele
                    for($j = 0; $j <= 25; $j++)
                        if($text[$i] == chr($alph[$j]))
                            $index = $j;
    
                    if($i % 4 != 0 && $i % 16 != 0){
                        $eText[$i] = chr($rotor1[$index]);
                        $rotor1 = moveRotor($rotor1); //Mutarea primului rotor, la fiecare litera
                    } 
                    if($i % 4 == 0){
                        $eText[$i] = chr($rotor2[$index]);
                        $rotor2 = moveRotor($rotor2);
                        $rotor1 = moveRotor($rotor1);
                        $rotor1 = moveRotor($rotor1);
                    }
                    if($i % 16 == 0){
                        $eText[i] = chr($rotor3[$index]);
                        $rotor3 = moveRotor($rotor3);
                        $rotor1 = moveRotor($rotor1);
                        $rotor1 = moveRotor($rotor1);
                        $rotor1 = moveRotor($rotor1);
                    }
                    return implode('', $eText); 
            }
    
    
        }
    }

    public function getRotor($pos){
        $j = 97;
        for($i = 0; $i <= 25; $i++){
            $alph[$i] = $j;
            $j++;
        }
        $j = 0;
        for($i = $pos - 1; $i <= 25; $i++){
            $rotor[$j] = $alph[$i];
            $j++;
        }

        if(sizeof($rotor) != 26){
            for($i = 0; $i <= $pos - 2; $i++){
                $rotor[$j] = $alph[$i];
                $j++;
            }
        }
    
        return $rotor;
    }
    public function moveRotor($rotor){

        // Permutare circulara spre stanga
        $x = $rotor[0];
        
        for($i = 1; $i <= 25; $i++)
            $rotor[$i - 1] = $rotor[$i];
        $rotor[25] = $x;
    
    
        return $rotor;
    }*/
}
