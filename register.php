<?php

require_once './config/db.php';
require_once './providers/database.php';

use Mwu\Pdo\Database;

session_start();
$_SESSION['token'] = sha1('Aa$124$!re');

$message = '';

if (isset($_POST['submit']) && !empty($_SESSION['token'])) {

    $name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

    if (!empty($name) && !empty($email) && !empty($password)) {
        $conn = new Database();
        $result = $conn->dbQuery(
            "INSERT INTO users (`id`, `name`, `email`, `password`) VALUES(NULL,?,?,?)",
            [$name, $email, $password]
        );

        // todo: test condition
        var_dump($conn->get('affected'));
        if ($conn->get('affected') > 0) {
            header('location: login.php');
        }
    } else {
        $message = "All fields are required";
    }
}

include_once './inc/doc-start.php';
include_once './inc/header.php';
?>

<main>
    <h2 class="my-3 text-center">Register</h2>

    <form action="register.php" method="POST" accept-charset="UTF-8" class="p-4 mx-auto form">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="your@email.com">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

        <input type="submit" name="submit" value="Sign Up" class="btn btn-primary">

        <div class="text-danger"><?= $message ?></div>
    </form>
</main>

<?php
include_once './inc/footer.php';
include_once './inc/doc-end.php';
