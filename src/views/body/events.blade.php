@if($events)
	<ul class="list-unstyled">
		@foreach($events as $event)
			<li>{{ $event->event }}</li>
		@endforeach
	</ul>
@endif