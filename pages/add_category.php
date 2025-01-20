<?php
require_once '../classes/db.php';
require_once '../classes/category.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $category_name = trim($_POST['category_name']);

    if (!empty($category_name)) {
        $db = new Database();
        $category = new Category($db);

        $existingCategories = $category->getCategories(); 
        $categoryExists = false;

        foreach ($existingCategories as $existingCategory) {
            if (strtolower($existingCategory['name']) == strtolower($category_name)) {
                $categoryExists = true;
                break;
            }
        }

        if ($categoryExists) {
            echo "<script>alert('This category already exists'); window.location.href='categories_dashbord.php';</script>";
        } else {
            if ($category->createCategory($category_name)) {
                header('Location: categories_dashbord.php');
                exit;
            } else {
                echo "Error: Unable to add category.";
            }
        }
    } else {
        echo "Category name cannot be empty.";
    }
}
?>
