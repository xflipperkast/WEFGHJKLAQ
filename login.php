<?php
session_start();
require_once 'classes/Users.php';

$loginError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new Users();
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    if ($user->login()) {
        $_SESSION['user_id'] = $user->getId();
        header("Location: dashboard.php");
        exit();
    } else {
        $loginError = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        form {
            max-width: 300px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p.error-message {
            color: red;
            margin-top: 10px;
        }
    </style></head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label for="username">Gebruikersnaam:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="password">Wachtwoord:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
        <?php if ($loginError): ?>
            <p><?php echo $loginError; ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
