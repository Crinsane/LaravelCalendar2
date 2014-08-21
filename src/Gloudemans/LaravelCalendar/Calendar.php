<?php namespace Gloudemans\LaravelCalendar;

class Calendar {

	/**
	 * The year of the calendar
	 *
	 * @var int
	 */
	protected $year;

	/**
	 * The month of the calendar
	 *
	 * @var int
	 */
	protected $month;

	/**
	 * The days of the calendar
	 *
	 * @var array
	 */
	protected $days;

	/**
	 * The events for the calendar
	 *
	 * @var array
	 */
	protected $events;

	/**
	 * Instance of the calendar parser
	 *
	 * @var Gloudemans\LaravelCalendar\CalendarParser
	 */
	protected $parser;

	/**
	 * Calendar Constructor
	 *
	 * @param Gloudemans\LaravelCalendar\CalendarParser  $parser
	 */
	public function __construct(CalendarParser $parser)
	{
		$this->parser = $parser;
	}

	/**
	 * Set the year of the calendar
	 *
	 * @param int  $year
	 */
	public function setYear($year)
	{
		$this->year = $year;
	}

	/**
	 * Get the year of the calendar
	 *
	 * @param int  $year
	 */
	public function getYear()
	{
		return $this->year;
	}

	/**
	 * Set the month of the calendar
	 *
	 * @param int  $month
	 */
	public function setMonth($month)
	{
		$this->month = $month;
	}

	/**
	 * Get the month of the calendar
	 *
	 * @param int  $month
	 */
	public function getMonth()
	{
		return $this->month;
	}

	/**
	 * Set the days of the calendar
	 *
	 * @param array  $days
	 */
	public function setDays(array $days)
	{
		$this->days = $days;
	}

	/**
	 * Get the days of the calendar
	 *
	 * @return array
	 */
	public function getDays()
	{
		return $this->days;
	}

	/**
	 * Set the events for the calendar
	 *
	 * @param array  $events
	 */
	public function setEvents(array $events)
	{
		$this->events = $events;
	}

	/**
	 * Get the events of the calendar
	 *
	 * @return array
	 */
	public function getEvents()
	{
		return $this->events;
	}

	/**
	 * Dump the calendar to the screen
	 *
	 * @return string
	 */
	public function dump()
	{
		return $this->parser->parse($this);
	}

	/**
	 * Called when trying to echo the class
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->dump();
	}

}