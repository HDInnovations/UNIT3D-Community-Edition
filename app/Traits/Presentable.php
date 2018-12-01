<?php

namespace App\Traits;

use App\Presenters\Presenter;

trait Presentable
{
    /**
     * @return Presenter
     */
    public function present(): Presenter {
        return app($this->getPresenterClass(), ['entity' => $this]);
    }
}
