<?php

session_start();

if (!isset($_SESSION['userId'])) {
    header('location: login.php');
}

include_once './inc/doc-start.php';
include_once './inc/header.php';
?>

<main class="text-center">
    <h2 class="my-3">My Profile</h2>
    <p></p>

</main>

<?php
include_once './inc/footer.php';
include_once './inc/doc-end.php';
