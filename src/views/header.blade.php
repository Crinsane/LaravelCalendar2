<thead>
	@foreach($rows as $row)
		@include('laravel-calendar::header.row', ['row' => $row])
	@endforeach
</thead>