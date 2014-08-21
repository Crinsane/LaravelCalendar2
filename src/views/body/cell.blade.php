<td class="laravel-calendar-cell">
	<span>{{ $day->format('j') }}</span>
	@include('laravel-calendar::body.events', ['events' => $events])
</td>