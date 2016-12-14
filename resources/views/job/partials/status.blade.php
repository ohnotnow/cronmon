<span class="icon is-small">
    @if ($job->isAwol())
        @if ($job->is_silenced)
            <i class="fa fa-bell-o" title="Awol - silenced"></i>
        @else
            <i class="fa fa-bell animated infinite flip" title="Awol - alerting"></i>
        @endif
    @else
        <i class="fa fa-check" aria-hidden="true" title="OK"></i>
    @endif
</span>
