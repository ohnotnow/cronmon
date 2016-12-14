@if (count($errors) > 0)
	<div class="notification is-danger is-outlined">
        <ul class="is-unstyled">
    		@foreach ($errors->all() as $error)
    			<li>{{ $error }}</li>
    		@endforeach
        </ul>
	</div>
@endif