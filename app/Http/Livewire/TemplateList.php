<?php

namespace App\Http\Livewire;

use App\Template;
use Livewire\Component;

class TemplateList extends Component
{
    public $filter = '';

    public function render()
    {
        return view('livewire.template-list', [
            'filteredTemplates' => $this->filterTemplates(),
        ]);
    }

    public function filterTemplates()
    {
        return auth()->user()->templates()->with(['user', 'team'])
            ->where('name', 'like', "%{$this->filter}%")
            ->orderBy('name')
            ->get();
    }
}
