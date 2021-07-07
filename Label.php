<?php

class Label
{

    public $id;

    public $title;

    public $counter;

    public function __construct($id, $title)
    {
        $this->id = $id;
        $this->title = $title;
        $this->counter = 0;
    }

}

?>