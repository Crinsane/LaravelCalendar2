<?php namespace Gloudemans\LaravelCalendar;

use Illuminate\View\Factory;
use Carbon\Carbon;

class CalendarParser {

	public function __construct(Factory $view)
	{
		$this->view = $view;
	}

	/**
	 * Create the string representation of the calendar
	 *
	 * @param  Gloudemans\LaravelCalendar\Calendar  $calendar
	 * @return string
	 */
	public function parse(Calendar $calendar)
	{
		$days = $calendar->getDays();
		$events = $calendar->getEvents();

		$daysBefore = $this->calculateDaysBeforeFirstDay(head($days));
		$daysAfter = $this->calculateDaysAfterLastDay(last($days));

		$body = $this->getCalendarBody($daysBefore, $daysAfter, $days, $events);
		$header = $this->getCalendarHeader(head($days));

		$data = compact('body', 'header');

		return $this->renderCalendarView('calendar', $data);
	}

	/**
	 * Calculate the number of days from monday of the first week until the first day
	 *
	 * @param  Carbon\Carbon  $firstDay
	 * @return int
	 */
	protected function calculateDaysBeforeFirstDay($firstDay)
	{
		$firstDayOfWeek = $firstDay->copy()->startOfWeek();

		return $firstDay->diffInDays($firstDayOfWeek);
	}

	/**
	 * Calculate the number of days the last day until the sunday of the last week
	 *
	 * @param  Carbon\Carbon  $lastDay
	 * @return int
	 */
	protected function calculateDaysAfterLastDay($lastDay)
	{
		$lastDayOfWeek = $lastDay->copy()->endOfWeek();

		return $lastDay->diffInDays($lastDayOfWeek);
	}

	/**
	 * Calculate the body rows of the calendar
	 *
	 * @param  int    $daysBefore
	 * @param  int    $daysAfter
	 * @param  array  $days
	 * @param  array  $events
	 * @return array
	 */
	protected function getCalendarBody($daysBefore, $daysAfter, $days, $events)
	{
		$rowNumber = 0;
		$dayCount = 0;
		$rows = [];

		$rows = $this->generateEmptyBeforeCells($daysBefore, $rows, $rowNumber, $dayCount);
		$rows = $this->generateDayCells($days, $events, $rows, $rowNumber, $dayCount);
		$rows = $this->generateEmptyAfterCells($daysAfter, $rows, $rowNumber, $dayCount);

		return $rows;
	}

	/**
	 * Generate the empty cells before the first day
	 *
	 * @param  array  $days
	 * @param  array  $rows
	 * @param  int    $rowNumber
	 * @param  int    $dayCount
	 * @return array
	 */
	protected function generateEmptyBeforeCells($days, $rows, &$rowNumber, &$dayCount)
	{
		for($i = 0; $i < $days; $i++)
		{
			if($dayCount % 7 == 0) $rowNumber++;
			$rows[$rowNumber][++$dayCount] = $this->renderCalendarView('body.empty');
		}

		return $rows;
	}

	/**
	 * Generate the cells for the days
	 *
	 * @param  array  $days
	 * @param  array  $events
	 * @param  array  $rows
	 * @param  int    $rowNumber
	 * @param  int    $dayCount
	 * @return array
	 */
	protected function generateDayCells($days, $events, $rows, &$rowNumber, &$dayCount)
	{
		foreach($days as $day)
		{
			if($dayCount % 7 == 0) $rowNumber++;

			$view = $day->isToday() ? 'body.today' : 'body.cell';
			$dayEvents = isset($events[$day->day]) ? is_array($events[$day->day]) ? $events[$day->day] : [$events[$day->day]] : [];

			$rows[$rowNumber][++$dayCount] = $this->renderCalendarView($view, ['day' => $day, 'events' => $dayEvents]);
		}

		return $rows;
	}

	/**
	 * Generate the empty cells after the last day
	 *
	 * @param  array  $days
	 * @param  array  $rows
	 * @param  int    $rowNumber
	 * @param  int    $dayCount
	 * @return array
	 */
	protected function generateEmptyAfterCells($days, $rows, &$rowNumber, &$dayCount)
	{
		for($i = 0; $i < $days; $i++)
		{
			if($dayCount % 7 == 0) $rowNumber++;
			$rows[$rowNumber][++$dayCount] = $this->renderCalendarView('body.empty');
		}

		return $rows;
	}

	/**
	 * Get the calendar header
	 *
	 * @param  Carbon\Carbon  $firstDay
	 * @return array
	 */
	protected function getCalendarHeader($firstDay)
	{
		$firstWeekDay = $firstDay->copy()->startOfWeek();

		$header[] = $this->generateCalenderHeader($firstDay);
		$header[] = $this->generateWeekdayHeader($firstWeekDay);

		return $header;
	}

	/**
	 * Generate the calendar top header
	 *
	 * @param  Carbon\Carbon  $day
	 * @return array
	 */
	protected function generateCalenderHeader($day)
	{
		$prev = $this->generatePrevLink($day);
		$next = $this->generateNextLink($day);

		$header[] = $this->renderCalendarView('header.prev', compact('prev'));
		$header[] = $this->renderCalendarView('header.month', compact('day'));
		$header[] = $this->renderCalendarView('header.next', compact('next'));

		return $header;
	}

	/**
	 * Generate the weekday calender header
	 *
	 * @param  Carbon\Carbon  $day
	 * @return array
	 */
	protected function generateWeekdayHeader($day)
	{
		for($i = 0; $i < 7; $i++)
		{
			$header[] = $this->renderCalendarView('header.cell', compact('day'));
			$day->addDay();
		}

		return $header;
	}

	/**
	 * Generate the previous link
	 *
	 * @param  Carbon\Carbon  $day
	 * @return string
	 */
	protected function generatePrevLink($day)
	{
		$prevMonth = $day->copy()->subMonth();

		return $this->generateLink($prevMonth->year, $prevMonth->month);
	}

	/**
	 * Generate the next link
	 *
	 * @param  Carbon\Carbon  $day
	 * @return string
	 */
	protected function generateNextLink($day)
	{
		$nextMonth = $day->copy()->addMonth();

		return $this->generateLink($nextMonth->year, $nextMonth->month);
	}

	/**
	 * Generate a link for the navigation
	 *
	 * @param  int  $year
	 * @param  int  $month
	 * @return string
	 */
	protected function generateLink($year, $month)
	{
		return '?year=' . $year . '&month=' . $month;
	}

	/**
	 * Render the calendar view and return the generated html
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @return string
	 */
	protected function renderCalendarView($view, $data = [])
	{
		$view = $this->view->make('laravel-calendar::' . $view, $data);

		return $view->render();
	}

}