<tbody>
	@foreach($rows as $row)
		@include('laravel-calendar::body.row', ['row' => $row])
	@endforeach
</tbody>