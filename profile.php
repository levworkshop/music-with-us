<?php

require_once './config/db.php';
require_once './providers/database.php';

use Mwu\Pdo\Database;

session_start();

if (!isset($_SESSION['userId'])) {
    header('location: login.php');
}

$conn = new Database();
$result = $conn->dbQuery(
    "SELECT * FROM articles WHERE user_id=?",
    [$_SESSION['userId']]
);

include_once './inc/doc-start.php';
include_once './inc/header.php';
?>

<main class="text-center">
    <h2 class="my-3">My Profile</h2>

    <div class="m-4">
        <div class="mb-3">
            <label for="about" class="form-label">About me</label>
            <textarea placeholder="write something" class="form-control" id="about" name="about" rows="3"></textarea>
        </div>
    </div>

    <h5>My Articles</h5>

    <ul class="list-group">
        <?php
        foreach ($result as $row) {
            $col = <<<COL
                    <li class="list-group-item">
                        {$row->title}
                        <a class="btn btn-light" href="edit.php?id={$row->id}">
                            <i class="bi-pencil"></i>
                        </a>
                        <a class="btn btn-light">
                            <i class="bi-trash"></i>
                        </a>
                    </li>
                    COL;

            echo $col;
        }
        ?>
    </ul>

</main>

<?php
include_once './inc/footer.php';
include_once './inc/doc-end.php';
