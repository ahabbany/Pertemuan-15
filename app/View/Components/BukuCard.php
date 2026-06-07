<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BukuCard extends Component
{
    public $buku;
    public $showActions;
    public $showCheckbox;

    public function __construct($buku, $showActions = true, $showCheckbox = false)
    {
        $this->buku = $buku;
        $this->showActions = $showActions;
        $this->showCheckbox = $showCheckbox;
    }

    public function render(): View|Closure|string
    {
        return view('components.buku-card');
    }
}