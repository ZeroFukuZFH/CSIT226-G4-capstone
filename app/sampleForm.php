

<!-- QUICK TUTORIAL FOR XAMPP DEVELOPMENT -->

<!--    IMPORTANT FOOTNOTES !!!
    
    POST - send to database
    GET - recieve from database

    MAKE SURE TO APPEND NAME ATTRIBUTE TO YOUR INPUT FRAGMENTS,
    PHP WILL THEN SAVE THAT AS AN INDEX IN THEIR VARIABLE MAP CALLED 
    $_POST[NAME] (if post) OR $_GET[NAME] (if get) WHEREIN $NAME IS 
    THE NAME OF THE TRIGGER/INPUT (ex. input,button,textfield or any 
    type of trigger)

    include - include other php scripts

    include 'sample.php'
    
    alternatively if your php logic is only required within a form, you can
    separate the html and php logic in their respective files and
    include the php file inside the form 

    ex. 
        |- sampleForm.php
        |- sampleForm.html

        in your sampleForm.html : 

        reuse above html data...
        <form id="user_form" method="post" action="sampleForm.php"> // ADD sampleForm.php
        
-->

<!DOCTYPE html>
    <html>
        <head>
            <title> </title>
            <link rel="stylesheet" href="sampleForm.css">

        </head>
        <body>
            
        <!-- SET METHOD TO POST -->
            <form id="user_form" method="post">
                <label for="user_name"> username</label>
                <input type="text" placeholder="enter your username..." id="user_name" name="user_name">
                <label for="user_name"> password </label>
                <input type="password" placeholder="enter your password..." id="password" name="password">
                <input type="submit" name="submit">
            </form>

        <!-- SET METHOD TO GET -->
            <form method="GET">
                <button type="submit" name="fetch" value="true">Fetch Accounts</button>
            </form>
            
        </body>
    </html>


<!-- HANDLING POST AND GET REQUESTS 

    NOTE !! - this should be in a separate file called config but for
    the purpose of this tutorial its here 
-->

<?php

    /*  PREREQUISITES
        create a database named sampleDatabase and then
        create a table named account

            CREATE TABLE account (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL
            );
    */

    // MACROS
    $table_name = 'account';

    // connect to db 
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $database_schema = 'sampleDatabase';
    

    $connect = new mysqli($host,$user,$pass,$database_schema);
        
    // error handling for connection to db
    if($connect->connect_error){
        die("Connection Failed". $connect->connect_error);
    }

    // POST - trigger only if submit button is pressed 
    if(isset($_POST['submit'])){

        // example access to the inputs from above html script, 
        $user_name = $_POST['user_name'];
        $user_password = $_POST['password'];

        // INSERT VALUES TO MYSQL
        // set sql query up
        $insert = "INSERT INTO `$table_name`(username,password) VALUES (
            '$user_name',
            '$user_passWord'
        )";

        // error handling for insertion query

        if($connect->query($insert) === TRUE) {
            echo "<script>alert('New record created successfully!');</script>";
        } else {
            $errorMsg = addslashes($connect->error);
            echo "<script>alert('Error: " . $errorMsg . "');</script>";
        }

    }  

    // GET - trigger only if fetch button is pressed 
    if(isset($_GET['fetch'])){
        $select = "SELECT username FROM `$table_name`";

        $result = $connect->query($select);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                echo "name : ". $row['username'];
            }
        } else {
            echo "<script>alert('0 results');</script>";
        }
    }

    // make sure to close connection if not anymore in use
    $connect->close();
?>