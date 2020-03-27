<div>
    <div class="flex justify-between mb-8">
        <ul class="flex items-start" style="list-style-type: none; padding-left: 0">
            <li class="mr-8 @if (! $teams) border-b-2 border-orange @endif">
                <a class="text-orange hover:text-orange-dark text-xl" wire:click.prevent="$set('teams', false)">
                    Your templates
                </a>
            </li>
            <li class="mr-8 @if ($teams) border-b-2 border-orange @endif">
                <a class="text-orange hover:text-orange-dark text-xl" wire:click.prevent="$set('teams', true)">
                    Team templates
                </a>
            </li>
        </ul>
        <ul style="list-style-type: none;">
            <li>
                <a class="button" href="{{ route('template.create') }}">
                    Add new template
                </a>
            <li>
        </ul>
    </div>

    <div class="mb-2 text-right">
        <input placeholder="Filter..." wire:model="filter" class="w-full md:w-1/4 shadow appearance-none border focus:border-grey-dark rounded py-2 px-3 text-grey-darker leading-tight" type="text" autofocus>
    </div>

    <div class="md:flex md:flex-row hidden justify-between p-4 hover:bg-grey-lightest font-semibold border-b-2 border-orange">
        <div class="flex-1 flex-col md:flex-row">
            <div class="flex flex-col md:flex-row">
                <div class="flex-initial text-center text-orange-darker pr-4 w-8">
                </div>
                <div class="flex-1 ">
                    Name
                </div>
                <div class="flex-1 text-orange-darker">Schedule</div>
                @if (auth()->user()->is_admin)
                <div class="flex-1 text-orange-darker">
                    Owner
                </div>
                <div class="flex-1 text-orange-darker">
                    Team
                </div>
                @endif
            </div>
        </div>
        <div class="flex-1 align-left text-orange-darker">
            POST URI
        </div>
    </div>

    @foreach ($filteredTemplates as $template)
    <div wire:key="{{ $template->id }}" class="flex flex-col md:flex-row justify-between p-4 mb-4 md:mb-0 shadow md:shadow-none hover:bg-grey-lightest">
        <div class="flex-1 flex-col md:flex-row">
            <div class="flex flex-col md:flex-row">
                <div class="flex-1 ">
                    <a href="{{  route('template.show', $template->id) }}" class="text-orange hover:text-orange-dark">
                        {{ $template->name }}
                    </a>
                </div>
                <div class="flex-1 text-orange-darker">{{ $template->getSchedule() }}</div>
                @if (auth()->user()->is_admin)
                <div class="flex-1 text-orange-darker">
                    <a class="text-orange" href="{{ $template->user_url }}">
                        {{ optional($template->user)->username }}
                    </a>
                </div>
                <div class="flex-1 text-orange-darker">
                    {{ optional($template->team)->name }}
                </div>
                @endif
            </div>
        </div>
        <div class="flex-1 align-left text-orange-darker">
            {{ route('api.template.create_job', $template->slug) }}
        </div>
    </div>
    @endforeach
</div>