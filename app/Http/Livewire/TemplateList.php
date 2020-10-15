<?php

namespace App\Http\Livewire;

use App\Models\Template;
use Livewire\Component;

class TemplateList extends Component
{
    public $filter = '';

    public $teams = false;

    public function render()
    {
        return view('livewire.template-list', [
            'filteredTemplates' => $this->filterTemplates(),
        ]);
    }

    public function filterTemplates()
    {
        $query = auth()->user()->templates()
                    ->with(['user', 'team'])
                    ->where('name', 'like', "%{$this->filter}%");
        if ($this->teams) {
            $teamIds = auth()->user()->teams()->get()->pluck('id')->values()->toArray();
            $query = $query->whereIn('team_id', $teamIds);
        }

        return $query->orderBy('name')->get();
    }
}
