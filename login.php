<?php
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/database.php";
    
    $email = $mysqli->real_escape_string($_POST["email"]);
    $sql = "SELECT * FROM user WHERE email = '$email'";
    
    $result = $mysqli->query($sql);
    
    if ($mysqli->error) {
        die("Query error: " . $mysqli->error);
    }

    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($_POST["password"], $user["password_hash"])){
            session_start();   

            $_SESSION["user_id"] = $user["id"];
            header("Location: index.php");
            exit;
        };
    }
    $is_invalid = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    
    <h1>Login</h1>
    <?php if ($is_invalid): ?>
        <p style="color: red;">Invalid email or password</p>
    <?php endif; ?>
    
    <form method ="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        
        <button type="submit">Login</button>
    </form>
    </body>
</html>