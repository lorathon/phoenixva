/*
Name: 			Pages / Calendar - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	1.3.0
*/



(function( $ ) {

	'use strict';

	var initCalendar = function() {
		var $calendar = $('#calendar');

		$calendar.fullCalendar({
			header: {
				left: 'title',
				right: 'prev,today,next,basicDay,basicWeek,month'
			},

			timeFormat: '', // 'HH:mm / ' for 24 hour time (only shows start time on event)

			titleFormat: {
				month: 'MMMM YYYY',      	// September 2009
				week: 'MMMM D, YYYY',      	// September 1-7, 2009
				day: 'dddd, MMMM D, YYYY' 	// Tuesday, September 8, 2009
			},

			themeButtonIcons: {
				prev: 'fa fa-caret-left',
				next: 'fa fa-caret-right',
			},
			
			events: 'events/get-json',
		});

		// FIX INPUTS TO BOOTSTRAP VERSIONS
		var $calendarButtons = $calendar.find('.fc-header-right > span');
		$calendarButtons
			.filter('.fc-button-prev, .fc-button-today, .fc-button-next')
				.wrapAll('<div class="btn-group mt-sm mr-md mb-sm ml-sm"></div>')
				.parent()
				.after('<br class="hidden"/>');

		$calendarButtons
			.not('.fc-button-prev, .fc-button-today, .fc-button-next')
				.wrapAll('<div class="btn-group mb-sm mt-sm"></div>');

		$calendarButtons
			.attr({ 'class': 'btn btn-sm btn-default' });
	};	

	$(function() {
		initCalendar();
	});

}).apply(this, [ jQuery ]);


