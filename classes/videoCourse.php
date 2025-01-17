<?php
require_once 'user.php';
require_once 'course.php';

class VideoCourse extends Course
{
    private $videoUrl;

    public function __construct($title, $description, $price, $categoryId, $thumbnail, $videoUrl)
    {
        parent::__construct();
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->categoryId = $categoryId;
        $this->thumbnail = $thumbnail;
        $this->videoUrl = $videoUrl;
    }

    public function addCourse()
    {
       
    }

    public function displayCourse()
    {
        return [
            'type' => 'Video',
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->categoryId,
            'thumbnail' => $this->thumbnail,
            'videoUrl' => $this->videoUrl,
        ];
    }
}

?>