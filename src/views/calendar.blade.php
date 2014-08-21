<table class="laravel-calendar-table table table-bordered table-hover">
	@include('laravel-calendar::header', ['rows' => $header])
	@include('laravel-calendar::body', ['rows' => $body])
</table>
