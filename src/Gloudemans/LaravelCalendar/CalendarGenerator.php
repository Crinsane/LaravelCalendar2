<?php namespace Gloudemans\LaravelCalendar;

use InvalidArgumentException;
use Carbon\Carbon;

class CalendarGenerator {

	/**
	 * Instance of the Calendar class
	 *
	 * @var Gloudemans\LaravelCalendar\Calendar
	 */
	protected $calendar;

	/**
	 * Calendar Generator Constructor
	 *
	 * @param Gloudemans\LaravelCalendar\Calendar  $calendar
	 */
	public function __construct(Calendar $calendar)
	{
		$this->calendar = $calendar;
	}

	/**
	 * Generate the calendar for the given year and month
	 *
	 * @param  int    $year
	 * @param  int    $month
	 * @param  array  $events
	 * @return Gloudemans\LaravelCalendar\Calendar
	 */
	public function generate($year, $month, $events = [])
	{
		$this->validateArguments($year, $month, $events);

		$this->calendar->setDays(
			$this->getMonthDays($year, $month)
		);

		$this->calendar->setEvents($events);

		return $this->calendar;
	}

	/**
	 * Get the days of the given month in the given year
	 *
	 * @param  int    $year
	 * @param  int    $month
	 * @return array
	 */
	public function getMonthDays($year, $month)
	{
		$firstDay = Carbon::createFromDate($year, $month, 1)->startOfDay();
		$lastDay = $firstDay->copy()->endOfMonth();

		while($firstDay < $lastDay)
		{
			$days[] = $firstDay->copy();
			$firstDay->addDay();
		}

		return $days;
	}

	/**
	 * Validate the supplied arguments
	 *
	 * @param  int    $year
	 * @param  int    $month
	 * @param  array  $events
	 * @return void
	 */
	protected function validateArguments($year, $month, $events)
	{
		$this->validateYear($year);
		$this->validateMonth($month);
		$this->validateEvents($events);
	}

	/**
	 * Validate the supplied year
	 *
	 * @param  int  $year
	 * @return void
	 */
	protected function validateYear($year)
	{
		if(empty($year) || ! is_numeric($year) || $year <= 0)
			throw new InvalidArgumentException('Please supply the calendar with a valid year in the format "YYYY", got "' . $year . '".');
	}

	/**
	 * Validate the supplied month
	 *
	 * @param  int  $month
	 * @return void
	 */
	protected function validateMonth($month)
	{
		if(empty($month) || ! is_numeric($month) || $month < 1 || $month > 12)
			throw new InvalidArgumentException('Please supply the calendar with a valid month in the format "MM", got "' . $month . '".');
	}

	/**
	 * Validate the supplied events
	 *
	 * @param  array  $events
	 * @return void
	 */
	protected function validateEvents($events)
	{
		if( ! is_array($events))
			throw new InvalidArgumentException('Please supply the calendar with valid events, these should be an array of events with the day of the month as key.');
	}

}