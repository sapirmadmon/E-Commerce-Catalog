<?php

class Product
{

    public $id;

    public $title;

    public $categories;

    public $price;

    public $labels = [];

    public function __construct($id, $title, $categories, $price, $labels)
    {
        $this->id = $id;
        $this->title = $title;
        $this->categories = $categories;
        $this->price = $price;
        $this->labels = $labels;
    }


}
?>