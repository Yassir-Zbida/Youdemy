<?php
require_once '../classes/db.php';
require_once '../classes/category.php';
session_start();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $category_id = $_GET['id'];

    $db = new Database();
    $category = new Category($db);

    if ($category->deleteCategory($category_id)) {
        header('Location: categories_dashbord.php?message=Category deleted successfully');
        exit;
    } else {
        header('Location: categories_dashbord.php?message=Error deleting category');
        exit;
    }
} else {
    header('Location: categories_dashbord.php?message=Invalid category ID');
    exit;
}
?>
