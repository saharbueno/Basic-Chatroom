<?php

    // get command
    $command = $_GET['command'];


    // command: save
    // inputs:  a nickname and a message
    // outputs: a copy of the message, or 'error'
    if ($command == 'save') {

        $nickname = $_POST['nickname'];
        $message  = $_POST['message'];

        // basic validation
        if ($nickname && $message) {
            // save this message into our database
            $db = new SQLite3(  getcwd() . '/database/messages.db'  );
            $sql = "INSERT INTO messages (nickname, message) VALUES (:nick, :msg)";
            $statement = $db->prepare($sql);
            $statement->bindParam(':nick', $nickname);
            $statement->bindParam(':msg', $message);
            $statement->execute();

            print $nickname . ": " . $message;
        }

        else {
            print "MISSINGDATA";
        }

        $db->close();
        unset($db);

    }
    
    // command: get_all_messages
    // inputs:  none
    // output:  all previous messages, formatted as a JSON array
    if ($command == 'get_all_messages') {
        $db = new SQLite3(  getcwd() . '/database/messages.db'  );
        $sql = "SELECT nickname, message FROM messages";
        $statement = $db->prepare($sql);
        $result = $statement->execute();

        $return_array = array();
        while ($temp_array = $result->fetchArray()) {


            array_push( $return_array, $temp_array['nickname'] . ": " . $temp_array['message']);

        }

        $db->close();
        unset($db);

        print json_encode($return_array);
       
    }


?>