<?php

class Category
{

    public $counter;

    public $id;

    public $title;

    public $list_attributes = [];

    public function __construct($id, $title, $list_attributes)
    {
        $this->id = $id;
        $this->title = $title;
        $this->counter = 0;
        $this->list_attributes = $list_attributes;
    }


}

?>