<?php
require_once '../user.php';
require_once './course.php';

class DocumentCourse extends Course
{
    private $documentPath;

    public function __construct($title, $description, $price, $categoryId, $thumbnail, $documentPath)
    {
        parent::__construct();
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->categoryId = $categoryId;
        $this->thumbnail = $thumbnail;
        $this->documentPath = $documentPath;
    }

    public function addCourse(){
       
    }

    public function displayCourse(){
        return [
            'type' => 'Document',
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->categoryId,
            'thumbnail' => $this->thumbnail,
            'documentPath' => $this->documentPath,
        ];
    }
}

?>