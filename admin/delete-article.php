<?php
require '../includes/init.php';
$conn = require '../includes/db.php';

Auth::requireLogin();

if (isset($_GET['id'])) {
    $article = Article::getByID($conn, $_GET['id']);

    if (!$article) {
        die("Article not found");
    }
} else {
    die("ID not supplied, article not found");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if ($article->delete($conn)) {
        Url::redirect("/admin");
    }
}

?>

<?php require '../includes/header.php'; ?>

<h2>Delete Article</h2>

<form method="post">
    <p>Are you sure?</p>
    <button>Delete</button>
    <a href="article.php?id=<?= $article->id; ?>">Cancel</a>
</form>

<?php require '../includes/footer.php'; ?>