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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $previous_image = $article->image_file;
    
    if ($previous_image) {
        unlink("../uploads/$previous_image");
    }

    $article->setImageFile($conn, null);
    Url::redirect("/admin/edit-article-image.php?id={$article->id}");
}



?>

<?php require '../includes/header.php'; ?>

<h2>Delete Article Image</h2>

<?php if ($article->image_file) : ?>
    <img src="/uploads/<?= $article->image_file; ?>">
<?php endif ?>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="edit-article-image.php?id=<?= $article->id; ?>">Cancel</a>


    <button>Upload</button>
</form>


<?php require '../includes/footer.php'; ?>