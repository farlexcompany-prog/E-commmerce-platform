<?php
// Simple password gate for the upload tool. CHANGE THE PASSWORD BELOW before going online.
session_start();

const BLOG_ADMIN_PASSWORD = "12345678";

$loginError = "";
if (isset($_POST["admin_password"])) {
    if (hash_equals(BLOG_ADMIN_PASSWORD, (string)$_POST["admin_password"])) {
        session_regenerate_id(true);
        $_SESSION["blog_admin"] = true;
        header("Location: " . strtok($_SERVER["REQUEST_URI"], "?"));
        exit;
    }
    $loginError = "Wrong password.";
}

if (empty($_SESSION["blog_admin"])):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin login | Farlex</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; display: flex; justify-content: center; padding-top: 90px; }
        form { background: #fff; padding: 30px; border-radius: 10px; width: 320px; }
        h1 { color: #183b65; font-size: 22px; margin-top: 0; }
        input { width: 100%; padding: 12px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 5px; margin-top: 8px; }
        button { margin-top: 16px; padding: 12px 22px; background: #183b65; color: #fff; border: 0; border-radius: 5px; cursor: pointer; }
        .err { color: red; }
    </style>
</head>
<body>
<form method="POST">
    <h1>Blog admin</h1>
    <?php if ($loginError): ?><p class="err"><?php echo htmlspecialchars($loginError); ?></p><?php endif; ?>
    <input type="password" name="admin_password" placeholder="Password" required autofocus>
    <button type="submit">Log in</button>
</form>
</body>
</html>
<?php
    exit;
endif;

