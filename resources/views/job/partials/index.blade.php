    <table class="table is-striped datatable">
        <thead>
            <tr>
                <th>Status</th>
                <th>Name</th>
                <th>Schedule</th>
                <th>Last Run</th>
                <th>URI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jobs as $job)
                <tr @if ($job->isAwol()) class="is-danger" @endif>
                    <td width="5%">
                        @include('job.partials.status')
                    </td>
                    <td width="20%">
                        <a href="{{{ route('job.show', $job->id) }}}">
                            {{ $job->name }}
                        </a>
                    </td>
                    <td width="10%">{{ $job->getSchedule() }}</td>
                    <td width="15%" title="{{ $job->getLastRun() }}">{{ $job->getLastRunDiff() }}</td>
                    <td>{{ $job->uri() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
