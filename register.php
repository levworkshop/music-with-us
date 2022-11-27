<?php

require_once './config/db.php';
require_once './providers/database.php';

use Mwu\Pdo\Database;

session_start();
$_SESSION['token'] = sha1('Aa$124$!re');

$message = '';

define('UPLOAD_MAX_SIZE', 1024 * 1024 * 2);
$ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$upload_dir = __DIR__ . "/upload/";

if (isset($_POST['submit']) && !empty($_SESSION['token'])) {

    $name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

    try {
        if (empty($name) || empty($email) || empty($password)) {
            throw new Exception("All fields are required");
        }

        if (
            empty($_FILES['image']) ||
            $_FILES['image']['error'] !== UPLOAD_ERR_OK
        ) {
            throw new Exception("Error uploading file.<br>{$_FILES['image']['error']}");
        }

        $tmp_name = $_FILES['image']['tmp_name'];

        if (is_uploaded_file($tmp_name)) {

            if ($_FILES['image']['size'] > UPLOAD_MAX_SIZE) {
                throw new Exception("File is too large.");
            }

            $file_info = pathinfo($_FILES['image']['name']);
            $file_ext = strtolower($file_info['extension']);

            if (!in_array($file_ext, $ext)) {
                throw new Exception("Only files of type: 'jpg', 'jpeg', 'png', 'gif', 'webp' are allowed");
            }

            $upload_file = $upload_dir . date('Y.m.d.H.i.s') . '-' . basename($_FILES['image']['name']);

            if (
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    $upload_file
                )
            ) {
                $conn = new Database();
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $result = $conn->dbQuery(
                    "INSERT INTO users (`id`, `name`, `email`, `password`) VALUES(NULL,?,?,?)",
                    [$name, $email, $hash]
                );

                if ($conn->get('affected') > 0) {
                    header('location: login.php');
                } else {
                    throw new Exception("Error. Check your input...");
                }
            } else {
                throw new Exception("Upload failed. check permission and path of upload folder");
            }
        }
    } catch (Exception $err) {
        $message = $err->getMessage();
    }
}

include_once './inc/doc-start.php';
include_once './inc/header.php';
?>

<main>
    <h2 class="my-3 text-center">Register</h2>

    <form action="register.php" method="POST" enctype="multipart/form-data" class="p-4 mx-auto form">
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
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

        <input type="submit" name="submit" value="Sign Up" class="btn btn-primary">

        <div class="text-danger">
            <p><?= $message ?></p>
        </div>
    </form>
</main>

<?php
include_once './inc/footer.php';
include_once './inc/doc-end.php';
