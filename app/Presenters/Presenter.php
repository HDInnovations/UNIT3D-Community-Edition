<?php

namespace App\Presenters;

class Presenter
{
    protected $entity;

    /**
     * Presenter constructor.
     *
     * @param $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
