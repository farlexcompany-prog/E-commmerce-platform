
<?php
require __DIR__ . "/auth.php";
require __DIR__ . "/database.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $meta_title = trim($_POST["meta_title"] ?? "");
    $meta_description = trim($_POST["meta_description"] ?? "");
    $status = $_POST["status"] ?? "draft";
    $publish_date_raw = trim($_POST["publish_date"] ?? "");
    $publish_date = null;
    if ($publish_date_raw !== "") {
        $ts = strtotime($publish_date_raw);
        $publish_date = $ts ? date("Y-m-d H:i:s", $ts) : null;
    }

    if ($title === "" || $content === "") {
        $message = "Please enter the blog title and content.";
        $messageType = "error";

    } elseif (!in_array($status, ["draft", "published"], true)) {
        $message = "Invalid publishing status.";
        $messageType = "error";

    } elseif (
        !isset($_FILES["image"]) ||
        $_FILES["image"]["error"] !== UPLOAD_ERR_OK
    ) {
        $message = "Please select a blog image.";
        $messageType = "error";

    } else {

        $tmpFile = $_FILES["image"]["tmp_name"];
        $fileSize = $_FILES["image"]["size"];

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmpFile);

        $allowedTypes = [
            "image/jpeg" => "jpg",
            "image/png"  => "png",
            "image/webp" => "webp"
        ];

        if (!isset($allowedTypes[$mime])) {
            $message = "Only JPG, PNG and WEBP images are allowed.";
            $messageType = "error";

        } elseif ($fileSize > 5 * 1024 * 1024) {
            $message = "Image size must be below 5 MB.";
            $messageType = "error";

        } else {

            $uploadDirectory = __DIR__ . "/../uploads/blogs/";

            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0755, true);
            }

            $imageName = bin2hex(random_bytes(16))
                       . "." . $allowedTypes[$mime];

            $destination = $uploadDirectory . $imageName;

            if (move_uploaded_file($tmpFile, $destination)) {

                $image_url = "uploads/blogs/" . $imageName;

                $slug = strtolower(
                    preg_replace(
                        '/[^a-z0-9]+/i',
                        '-',
                        trim($title)
                    )
                );

                $slug = trim($slug, "-") . "-" . time();

                $sql = "INSERT INTO blogs
                    (title, slug, content, image_url,
                     meta_title, meta_description, status, publish_date)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($conn, $sql);

                if ($stmt) {

                    mysqli_stmt_bind_param(
                        $stmt,
                        "ssssssss",
                        $title,
                        $slug,
                        $content,
                        $image_url,
                        $meta_title,
                        $meta_description,
                        $status,
                        $publish_date
                    );

                    if (mysqli_stmt_execute($stmt)) {
                        $message = "Blog uploaded successfully!";
                        $messageType = "success";
                    } else {
                        unlink($destination);
                        $message = "Database error. Please try again.";
                        $messageType = "error";
                    }

                    mysqli_stmt_close($stmt);

                } else {
                    unlink($destination);
                    $message = "Could not prepare database query.";
                    $messageType = "error";
                }

            } else {
                $message = "Image upload failed.";
                $messageType = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Upload Blog | Farlex</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 750px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h1 {
            color: #183b65;
        }

        label {
            display: block;
            margin-top: 18px;
            font-weight: bold;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            min-height: 180px;
        }

        button {
            margin-top: 22px;
            padding: 13px 25px;
            background: #183b65;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .success {
            color: green;
            margin: 15px 0;
        }

        .error {
            color: red;
            margin: 15px 0;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Farlex - Upload New Blog</h1>

    <?php if ($message): ?>
        <p class="<?php echo htmlspecialchars($messageType); ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Blog Title</label>
        <input type="text" name="title" required>

        <label>Blog Content</label>
        <textarea name="content" required></textarea>

        <label>Blog Image</label>
        <input type="file" name="image"
               accept=".jpg,.jpeg,.png,.webp" required>

        <label>SEO Meta Title</label>
        <input type="text" name="meta_title">

        <label>SEO Meta Description</label>
        <textarea name="meta_description"></textarea>

        <label>Publishing Status</label>
        <select name="status">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>

        <label>Schedule publish date &amp; time (optional)</label>
        <input type="datetime-local" name="publish_date">
        <small>Leave status as Draft and set a date: the blog goes live automatically at that time.</small>

        <button type="submit">Upload Blog</button>

    </form>

</div>

</body>
</html>
