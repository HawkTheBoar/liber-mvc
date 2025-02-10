<?php include_once 'views/components/head.php'; ?>
<body>
    <?php 
        foreach($params as $message){
            echo "<p>$message</p>";
        }
    ?>
    <h1>Login</h1>
    <form method="post" action="/auth/login">
        <input placeholder="E-mail" type="text" name="username"/>
        <input placeholder="Password" type="password" name="password"/>
        <button type="submit">Log In</button>
    </form>
</body>
</html>