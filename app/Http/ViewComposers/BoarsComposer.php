<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;

class BoarsComposer
{
    public function compose(View $view)
    {
        $view->with('auth', Auth::check());
    }
}