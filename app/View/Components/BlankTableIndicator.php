<?php

namespace App\View\Components;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class BlankTableIndicator extends Component
{
    public $data, $columns, $message, $count;

    /**
     * Create a new component instance.
     *
     * @param $data
     * @param int $columns
     * @param string $message
     */
    public function __construct ($data, $columns = 1000, $message = "Didn't find anything!")
    {
        $this->data = $data;
        $this->columns = $columns;
        $this->message = $message;
        if ($data instanceof LengthAwarePaginator || $data instanceof Collection) {
            $this->count = $data->count();
        } elseif (gettype($data) == 'array') {
            $this->count = count($data);
        } else {
            $this->count = 0;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render ()
    {
        return view('components.blank-table-indicator');
    }
}
