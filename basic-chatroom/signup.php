<!doctype html>
<html>
    <head>
        <title>Signup Page</title>
        <style>
            #error {
                width: 350px;
                height: auto;
                background-color: red;
                color: white;
                margin-bottom: 20px;
                text-align: center;
            }
            
        </style>
    </head>
    <body>
        <h1>Chatroom: Signup</h1>
    <?php
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
         <div id="error">This username is already in use. Please pick another one. </div>
    <?php
        }
    ?>
        <form method="POST" action="signup.php">
            username: <input type="text" name="username"><br>
            password: <input type="text" name="password"><br>
            <input type="submit" value="Sign Up">
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
                        // if result is found, send the user an error
                        header("Location: signup.php?error=invalid");
                        exit();
                    } else {
                        // if result is not found, add the username and password to the database
                        // set up a SQL query to insert the username and password into the db 
                        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
                        $statement = $db->prepare($sql);
                        $statement->bindValue(':username', $username);
                        $statement->bindValue(':password', $password);

                        // execute the query
                        $result = $statement->execute();
                        // check if the query was successful
                        if ($result) {
                            // send user to login page with success message
                            header("Location: login.php?signup=success");// print "success";
                            exit();
                        } else {
                            // handle the error
                            header("Location: signup.php?error=invalid");
                            exit();
                        }
                        // send user to login page with success message
                        header("Location: login.php?signup=success");
                        exit();
                    }
    
                    $db->close();
                    unset($db);

                } else if ($username == "" || $password == "") {
                    // send them back to login
                    header("Location: signup.php?error=forgot");
                    exit();
                }
            }
    ?>
    </body>
</html>