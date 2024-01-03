<!DOCTYPE html>
<html>
    <head>
        <title>Let's Chat!</title>
        <style>
            #previous_messages {
                width: 100%;
                height: 300px;
                resize: none;
                overflow-y: scroll;
            }
            .hidden {
                display: none;
            }
        </style>
        <script src="helpers.js"></script>
    </head>
    <body>
        <h1>Let's Chat!</h1>

        <div id="panel_login">
            <a href="login.php"><button id="login">Click Here to Login</button></a>
            <a href="signup.php"><button id="signup">Click Here to Sign Up</button></a>
        </div>

        <?php
            $username = $_GET['username'];

            if ($username) {
        ?>
        <div id="panel_chat">
            <textarea id="previous_messages" readonly></textarea>
            <input type="text" id="message">
            <button id="button_sendmessage">Send Message</button>
        </div>

        <script>
            // store nickname from user
            let userNickname = "<?php echo $username; ?>";
            let panel_login = document.querySelector('#panel_login');
            let isScrolling;
            let newMessage;

            // hide the login panel
            panel_login.classList.add('hidden');

            // get the textarea element
            const previousMessages = document.querySelector('#previous_messages');

            // when the user types in a new chat message
            document.querySelector('#button_sendmessage').onclick = function(e) {

                // contact the server with our message AND our nickname
                performFetch({
                    url: 'api.php?command=save',
                    method: 'post',
                    data: {
                        nickname: userNickname,
                        message: document.querySelector('#message').value
                    },
                    success: function(data) {
                        //console.log("SUCCESS");
                        //console.log(data);
                        if (data != "MISSINGDATA") {
                            document.querySelector('#previous_messages').value += data + "\n";
                            // clear the input box
                            document.getElementById('message').value = '';
                        }

                    },
                    error: function(error) {
                        console.log("ERROR");
                    }
                })

            }

            previousMessages.addEventListener("mouseover", function() {
                console.log("over");
                isScrolling = true;
            });
            previousMessages.addEventListener("mouseout", function() {
                isScrolling = false;
                console.log("out");
            });

            function getAllMessages() {

                performFetch({
                    url: 'api.php',
                    method: 'get',
                    data: {
                        command: 'get_all_messages'
                    },
                    success: function(data) {
                        //console.log(data);

                        // take what the server gave us and turn it into a JS object
                        data = JSON.parse( data );

                        //console.log(data);

                        document.querySelector('#previous_messages').value = '';

                        for (let i = 0; i < data.length; i++) {
                            document.querySelector('#previous_messages').value += data[i] + "\n";
                        }
                        if (isScrolling == false) {
                                previousMessages.scrollTop = previousMessages.scrollHeight;
                        }

                    },
                    error: function(error) {
                        console.log(error);
                    }
                })
            }

            setInterval(
                getAllMessages,
                2000
            );

        </script>
        <?php
            }
        ?>
    </body>

</html>
