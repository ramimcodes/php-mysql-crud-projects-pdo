<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=productsdb', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$statement = $pdo->prepare("SELECT * FROM products ORDER BY created_at DESC");
$statement->execute();
$data = $statement->fetchAll(PDO::FETCH_ASSOC);

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
        <h1 class="text-center">Product Crud - php & mysql</h1>
        <div class="mt-5">
            <a href="create-new-product.php" class="btn btn-success">Create New Product</a>
            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">Created</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)) : ?>
                        <?php foreach ($data as $i => $product) : ?>
                            <tr>
                                <th scope="row"><?php echo $i + 1; ?></th>
                                <td>
                                    <img width="50px" src="<?php echo $product['image'] ?? ''; ?>" alt="">
                                </td>
                                <td><?php echo $product['title'] ?? ''; ?></td>
                                <td><?php echo $product['description'] ?? ''; ?></td>
                                <td>$<?php echo $product['price'] ?? ''; ?></td>
                                <td><?php echo $product['created_at'] ?? ''; ?></td>
                                <td>
                                    <a href="update.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form style="display: inline-block;" action="delete.php" method="post">
                                        <input name="id" type="hidden" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>