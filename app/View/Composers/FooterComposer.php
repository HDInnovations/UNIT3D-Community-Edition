<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Models\Page;
use Illuminate\View\View;

class FooterComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with([
            'pages' => cache()->remember('cached-pages', 3600, fn () => Page::select(['id', 'name', 'created_at'])->take(6)->get())
        ]);
    }
}
