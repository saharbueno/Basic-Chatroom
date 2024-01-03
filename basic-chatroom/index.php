<!DOCTYPE html>
<html>
    <head>
        <title>Let's Chat!</title>
        <style>
            #previous_messages {
                width: 100%;
                height: 300px;
                resize: none;
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
            <a href="signup.php"></a><button id="signup">Click Here to Sign Up</button></a>
            <input type="text" id="nickname">
            <button id="button_chat">Save Nickname & Chat</button>
        </div>

        <div id="panel_chat" class="hidden">
            <textarea id="previous_messages" readonly></textarea>
            <input type="text" id="message">
            <button id="button_sendmessage">Send Message</button>
        </div>

        <script>
            // global variables
            let userNickname;

            // get the textarea element
            const previousMessages = document.querySelector('#previous_messages');

            // figure out when the user saves their nickname
            document.querySelector('#button_chat').onclick = function(e) {

                // store the nickname for future use
                userNickname = document.querySelector('#nickname').value;

                // hide the nickname panel
                document.querySelector('#panel_login').classList.add('hidden');

                // show the chat panel
                document.querySelector('#panel_chat').classList.remove('hidden');
            }

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
                        console.log("SUCCESS");
                        console.log(data);
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

            function getAllMessages() {

                performFetch({
                    url: 'api.php',
                    method: 'get',
                    data: {
                        command: 'get_all_messages'
                    },
                    success: function(data) {
                        console.log(data);

                        // take what the server gave us and turn it into a JS object
                        data = JSON.parse( data );

                        console.log(data);

                        document.querySelector('#previous_messages').value = '';

                        for (let i = 0; i < data.length; i++) {
                            document.querySelector('#previous_messages').value += data[i] + "\n";
                        }
                        previousMessages.scrollTop = previousMessages.scrollHeight;

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

    </body>

</html>
