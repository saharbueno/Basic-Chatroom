<!doctype html>
<html>
    <head>
        <title>Login Page</title>
        <style>
            #error {
                width: 350px;
                height: auto;
                background-color: red;
                color: white;
                margin-bottom: 20px;
                text-align: center;
            }
            #success {
                width: 350px;
                height: auto;
                background-color: purple;
                color: white;
                margin-bottom: 20px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1>Chatroom: Login</h1>
    <?php
        // check if user was sent successful sign up variable after signing up
        $signup = $_GET['signup'];

        if ($signup == "success") {
        // send user a message saying they signed up and can now log in
    ?>
        <div id="success">You have successfully signed up! Please login to use the chatroom.</div>
    <?php
        } 
        // check if user forgot any values
        $error = $_GET['error'];

        if ($error == "forgot") {
        // send user a message saying they forgot a value to login
    ?>
        <div id="error">You did not provide a valid username/password. Please provide both in order to login.</div>
    <?php
        }
        // check if user input any invalid values
        $error = $_GET['error'];

        if ($error == "invalid") {
        // send user a message saying they input the wrong values
    ?>
         <div id="error">You did not provide a valid username/password. Please provide the correct username/password.</div>
    <?php
        }
    ?>
        <form method="POST" action="login.php">
            username: <input type="text" name="username"><br>
            password: <input type="text" name="password"><br>
            <input type="submit" value="Login">
        </form> 

        <?php
            // check for variables first 
            if (isset($_POST['username']) && isset($_POST['password'])) {
                // grab data from the user
                $username = $_POST['username'];
                $password = $_POST['password'];

                if ($username && $password) {

                    // connect to database
                    $db = new SQLite3(  getcwd() . '/database/users.db'  );

                    // set up a SQL query to get username and password from db 
                    $sql = "SELECT username FROM users WHERE username == :username AND password == :password";
                    $statement = $db->prepare($sql);
                    $statement->bindValue(':username', $username);
                    $statement->bindValue(':password', $password);

                    // get results
                    $result = $statement->execute();
                    $answer = $result->fetchArray();

                    // check if the result contains a row with the username and password
                    if ($answer) {
                        // if result is found, send user to chatroom
                        header("Location: chatroom.php?username=" . $username);
                        exit();
                    } else {
                        // if result is not found, send user back to login.php with an error
                        header("Location: login.php?error=invalid");
                        exit();
                    }
    
                    $db->close();
                    unset($db);

                } else if ($username == "" || $password == "") {
                    // send them back to login
                    header("Location: login.php?error=forgot");
                    exit();
                }
            }
    ?>
    </body>
</html>