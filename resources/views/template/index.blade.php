@extends('layouts.app')

@section('content')
<div class="flex justify-between mb-8">
    <ul class="flex items-start" style="list-style-type: none; padding-left: 0">
        <li class="mr-8" id="yourtemplatestab">
            <a id="yourtemplates" class="text-orange hover:text-orange-dark text-xl">
                Your templates
            </a>
        </li>
        <li id="teamtemplatestab" class="mr-8">
            <a id="teamtemplates" class="text-orange hover:text-orange-dark text-xl">
                Team templates
            </a>
        </li>
    </ul>
    <ul style="list-style-type: none;">
        <li class="">
            <a class="button" href="{{ route('template.create') }}">
                Add new template
            </a>
        <li>
    </ul>
</div>
<div id="yourjobstable">
    @livewire('template-list')
</div>
@endsection