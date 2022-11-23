<?php

if (!isset($_GET['id'])) {
    echo "<div class=\"text-danger\">Undefined article</div>";
} else {
    // 1. get data of article from database
    // 2. create class for article and use it to manage page
}

include_once './inc/doc-start.php';
include_once './inc/header.php';
?>

<main class="text-center">
    <h2 class="my-3">About</h2>

    <form>
        <div class="m-4">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title">
            </div>
        </div>
        <div class="m-4">
            <div class="mb-3">
                <label for="about" class="form-label">Body</label>
                <textarea class="form-control" id="body" name="body" rows="3"></textarea>
            </div>
        </div>

        <input type="submit" name="submit" value="Submit">
    </form>
</main>

<?php
include_once './inc/footer.php';
include_once './inc/doc-end.php';
