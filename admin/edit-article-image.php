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

    //Checks if the user uploaded a file and other errors
    try {
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception("No file uploaded");
            case UPLOAD_ERR_INI_SIZE:
                throw new Exception("File too large");
            default:
                throw new Exception("An error occured");
        }
        //Checks if the file size is bigger than 1MB if it is throws an execption
        if ($_FILES['file']['size'] > 1000000) {
            throw new Exception('File is too large');
        }
        //Alowed file types
        $mime_types = ['image/gif', 'image/png', 'image/jpeg'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES['file']['tmp_name']);
        //Checks if the file types are allowed if they are, uploads it
        if (!in_array($mime_type, $mime_types)) {
            throw new Exception("Invalid file type");
        }

        $pathInfo = pathinfo($_FILES['file']['name']);

        $base = $pathInfo['filename'];

        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base); 
       
        $base = mb_substr($base, 0, 200); 

        $filename = $base . "." . $pathInfo['extension'];

        
        $destination = "../uploads/$filename";

        $i = 1;

        //Checks to see if the filename already exists and as long it exists it changes it's name
        while (file_exists($destination)) {
            $filename = $base . "-$i." . $pathInfo['extension'];
            $destination = "../uploads/$filename";

            $i++;
        }

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {

            $previous_image = $article->image_file;
            //Deletes the edited image file
            if ($previous_image) {
                unlink("../uploads/$previous_image");
            }

            $article->setImageFile($conn, $filename);
            Url::redirect("/admin/edit-article-image.php?id={$article->id}");
        } else {

            throw new Exception('Unable to move uploaded file');
        }
    } catch (Exception $e) {
        $error =  $e->getMessage();
    }
}



?>

<?php require '../includes/header.php'; ?>

<h2>Edit Article Image</h2>

<?php if ($article->image_file) : ?>
    <img src="/uploads/<?= $article->image_file; ?>">
    <a class="delete" href="delete-article-image.php?id=<?= $article->id; ?>">Delete</a>
<?php endif ?>

<?php if (isset($error)) : ?>

    <p><?= $error ?></p>
<?php endif ?>


<form method="post" enctype="multipart/form-data">
    <div>
        <label for="file">Image File</label>
        <input type="file" name="file" id="file">
    </div>


    <button>Upload</button>
</form>


<?php require '../includes/footer.php'; ?>