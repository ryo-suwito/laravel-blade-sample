<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class ReadonlyInputOrLink extends Component
{
    public $item;
    public $name;
    public $url;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $item,
        string $name,
        string $url = null
    )
    {
        $this->item = $item;
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.readonly-input-or-link', [
            'item' => $this->item,
            'name' => $this->name,
            'url' => $this->url,
        ]);
    }
}
