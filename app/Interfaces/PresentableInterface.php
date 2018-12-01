<?php

namespace App\Interfaces;

use App\Presenters\Presenter;

interface PresentableInterface
{
    public function getPresenterClass(): string;

    public function present(): Presenter;
}
