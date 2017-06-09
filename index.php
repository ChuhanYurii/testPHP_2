<?php
    ini_set('error_reporing', E_ALL);
    ini_set('display_errors', 1);

    require_once 'product.php';
    require_once 'config.php';
    
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $idForDelete = (int)$_GET['id'];
        Product::delete($mysqli, $idForDelete);
    }

    if (isset($_GET['action']) && $_GET['action'] == 'update') {
        $idForUpdate = (int)$_GET['id'];
        $currentProduct = Product::newInstance($mysqli, $idForUpdate);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'add_new') {
        $title = htmlspecialchars(trim($_POST['title']));
        $price = (int)htmlspecialchars(trim($_POST['price']));
        $image = htmlspecialchars(trim($_POST['image']));
        $description = htmlspecialchars(trim($_POST['description']));
        
        $newProduct = Product::newEmptyInstance();
        $newProduct->setTitle($title);
        $newProduct->setPrice($price);
        $newProduct->setImage($image);
        $newProduct->setDescription($description);
        $newProduct->save($mysqli);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $title = htmlspecialchars(trim($_POST['title']));
        $price = (int)htmlspecialchars(trim($_POST['price']));
        $image = htmlspecialchars(trim($_POST['image']));
        $description = htmlspecialchars(trim($_POST['description']));

        $idForUpdate = (int)$_POST['id'];
        
        $updProduct = Product::newInstance($mysqli, $idForUpdate);
        $updProduct->setTitle($title);
        $updProduct->setPrice($price);
        $updProduct->setImage($image);
        $updProduct->setDescription($description);
        $updProduct->save($mysqli);
    }
    
    $total_products_count = Product::count($mysqli);
    
    $products = Product::find($mysqli);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Test</title>
    </head>
    <body>
        <p>Общее количество продуктов: <?=$total_products_count;?></p>
        <table style="border:1px solid black;">
            <tr>
                <th>Название</th>
                <th>Цена</th>
                <th>Изображение</th>
                <th>Описание</th>
            </tr>
            <? if ($products !== false && count($products) > 0) { ?>
                <? foreach ($products as $product) { ?>
                    <tr>
                        <td><?=$product->getTitle(); ?></td>
                        <td><?=$product->getPrice(); ?></td>
                        <td><?=$product->getImage(); ?></td>
                        <td><?=$product->getDescription(); ?></td>
                        <td>
                            <a href="/?action=update&id=<?=$product->getId(); ?>">Редактировать</a>
                            <a href="/?action=delete&id=<?=$product->getId(); ?>">Удалить</a>
                        </td>
                    </tr>
                <? } ?>
            <? } else { ?>
                <tr>
                    <td colspan="4">Список продуктов пуст</td>
                </tr>
            <? } ?>
        </table>

        <form action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
            <input type="hidden" name="action" value= 
            <? if (isset($_GET['action']) && $_GET['action'] == 'update')  echo "update"; else echo "add_new";?>  />
            <? if (isset($_GET['action']) && $_GET['action'] == 'update')  
                echo '<input type="hidden" name="id" value='.$idForUpdate.' />';?>  
            <br/>
            Название: <input type="text" name="title" id="title" 
                value=<?if (isset($currentProduct)) echo('"' . $currentProduct->getTitle(). '"'); ?> ><br/>
            Цена: <input type="text" name="price" id="price" 
                value=<?if (isset($currentProduct)) echo($currentProduct->getPrice()); ?> ><br/>
            Изображение: <input type="text" name="image" id="image" 
                value=<?if (isset($currentProduct)) echo($currentProduct->getImage()); ?> ><br/>
            Описание: <input type="text" name="description" id="description" size = 100 
                value=<?if (isset($currentProduct)) echo('"' . $currentProduct->getDescription() . '"'); ?> ><br/>
            <button type="submit">
                <? if (isset($_GET['action']) && $_GET['action'] == 'update')  echo "Обновить"; else echo "Добавить";?>
            </button>
        </form>
    </body>
</html>