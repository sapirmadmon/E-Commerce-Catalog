<?php

class Attribute
{

    public $id;

    public $title;

    public $arrLabel = [];

    public function __construct($id, $title, $arrLabel)
    {
        $this->id = $id;
        $this->title = $title;
        $this->arrLabel = $arrLabel;
    }

   
}

?>