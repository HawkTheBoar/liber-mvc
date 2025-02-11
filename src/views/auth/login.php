<?php include_once 'views/components/head.php'; ?>
<body>
    <?php 
        if(isset($success)) {
            echo "<div class='success'>$success</div>";
        }
        if(isset($error)) {
            echo "<div class='error'>$error</div>";
        }
    ?>
    <h1>Login</h1>
    <form method="post" action="/auth/login">
        <input placeholder="E-mail" type="text" name="email"/>
        <input placeholder="Password" type="password" name="password"/>
        <button type="submit">Log In</button>
    </form>
</body>
</html>