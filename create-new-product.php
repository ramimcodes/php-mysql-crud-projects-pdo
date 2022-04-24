<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=productsdb', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$title = $description = $price = '';
$validation_field = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date = date('Y-m-d H:i:s');

    if (empty($title)) {
        $validation_field[] = 'title is requird';
    }
    if (empty($description)) {
        $validation_field[] = 'description is required';
    }
    if (empty($price)) {
        $validation_field[] = 'price is required';
    }

    if (!is_dir('uploads')) {
        mkdir('uploads');
    };

    if (empty($validation_field)) {
        $image = $_FILES['image'] ?? null;
        $image_full_path = '';
        if ($image && $image['tmp_name']) {
            $image_full_path = 'uploads/' . randomFileGenerator(8) . '/' . $image['name'];
            mkdir(dirname($image_full_path));
            move_uploaded_file($image['tmp_name'], $image_full_path);
        }
        $statement = $pdo->prepare('INSERT INTO products (image, title, description, price, created_at)
       VALUES(:image, :title, :description, :price, :date)');
        $statement->bindValue(':image', $image_full_path);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':date', $date);
        $statement->execute();
        header('Location: index.php');
    }
}

function randomFileGenerator($n)
{
    $characters = '0123456789abcdefghiJklmnopqrstuvwxyzABDCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- boostrap style sheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- custom css -->
    <link rel="stylesheet" href="app.css">
    <title>PRODUCT_CRUD</title>
</head>

<body>
    <main class="container p-5">
        <h1 class="text-center">Create-New-Product</h1>
        <div class="w-75 d-flex justify-content-center">
            <?php if (!empty($validation_field)) : ?>
                <?php foreach ($validation_field as $error) : ?>
                    <div class="alert alert-danger ms-2">
                        <?php echo $error ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="mt-5 d-flex justify-content-center">
            <form class="w-75" action="" method="Post" enctype="multipart/form-data">
                <div class="mb-5">
                    <a class="btn btn-sm btn-success text-white" href="index.php">Back Home</a>
                </div>
                <div class="mb-3">
                    <div class="input-group mb-3">
                        <input name="image" type="file" class="form-control" id="inputGroupFile02">
                        <label class="input-group-text" for="inputGroupFile02">Upload Image</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Title</label>
                    <input name="title" type="text" class="form-control" value="<?php echo $title; ?>">
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" type="text" class="form-control"><?php echo $description; ?></textarea>
                </div>
                <div class="mb-3">
                    <label>Price</label>
                    <input value="<?php echo $price; ?>" name="price" type="number" step=".01" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </main>
</body>

</html>