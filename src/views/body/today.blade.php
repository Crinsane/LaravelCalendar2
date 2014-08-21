<td class="laravel-calendar-cell today">
	<span>{{ $day->format('j') }}</span>
	@include('laravel-calendar::body.events', ['events' => $events])
</td>