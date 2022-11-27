<?php
require_once './config/db.php';
require_once './providers/database.php';

use Mwu\Pdo\Database;

session_start();

$_SESSION['token'] = sha1('Aa$124$!re');

$message = '';

if (isset($_SESSION['userId'])) {
    unset($_SESSION['userId']);
}

if (isset($_POST['submit']) && !empty($_SESSION['token'])) {

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

    if (!empty($email) && !empty($password)) {
        $conn = new Database();
        $result = $conn->dbQuery(
            "SELECT * FROM users WHERE email=?",
            [$email]
        );

        if (count($result) > 0) {
            $row = $result[0];

            if (!password_verify($password, $row->password)) {
                $message = "Failed to login, check password";
                return;
            }

            $_SESSION['userId'] = $row->id;
            $_SESSION['userName'] = $row->name;

            header('location: profile.php');
        } else {
            $message = "Failed to login, check email and password";
        }
    } else {
        $message = "All fields are required";
    }
}

include_once './inc/doc-start.php';
include_once './inc/header.php';
?>

<main>
    <h2 class="text-center my-3">Login</h2>

    <form method="POST" action="login.php" accept-charset="UTF-8" class="p-4 mx-auto form">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="your@email.com">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

        <input type="submit" name="submit" value="Login" class="btn btn-primary">

        <div class="text-danger"><?= $message ?></div>
    </form>
</main>

<?php
include_once './inc/footer.php';
include_once './inc/doc-end.php';
