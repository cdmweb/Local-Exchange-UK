/**
 *  Zebra_DatePicker
 *
 *  Zebra_DatePicker is a small, compact and highly configurable date picker plugin for jQuery
 *
 *  Visit {@link http://stefangabos.ro/jquery/zebra-datepicker/} for more information.
 *
 *  For more resources visit {@link http://stefangabos.ro/}
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @version    1.1.2 (last revision: September 11, 2011)
 *  @copyright  (c) 2011 Stefan Gabos
 *  @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 *  @package    Zebra_DatePicker
 */
;(function($) {

    $.Zebra_DatePicker = function(element, options) {

        var defaults = {

            // days of the week; Sunday to Saturday
            days:               ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],

            // direction of the calendar
            // a positive or negative integer: n (a positive integer) creates a future-only calendar beginning at n days
            // after today; -n (a negative integer); if n is 0, the calendar has no restrictions. use boolean true for
            // a future-only calendar starting with today and use boolean false for a past-only calendar ending today.
            //
            // you may also set this property to an array with two elements where the first one is the direction of the
            // calendar as described above while the second one is the number of selectable days in the given direction
            // (the second value is discarded if the first value is "0"!)
            //
            // [1, 7] - a future-only calendar, starting tomorrow, with the next seven days after that being selectable
            // [true, 7] - a future-only calendar, starting today, with the next seven days after that being selectable
            //
            // note that "disabled_dates" property will still apply!
            //
            // default is 0 (no restrictions)
            direction:          0,

            // an array of disabled dates in the following format: 'day month year weekday' where "weekday" is optional
            // and can be 0-6 (Saturday to Sunday); the syntax is similar to cron's syntax: the values are separated by
            // spaces and may contain * (asterisk) - (dash) and , (comma) delimiters:
            // ['1 1 2012'] would disable January 1, 2012;
            // ['* 1 2012'] would disable all days in January 2012;
            // ['1-10 1 2012'] would disable January 1 through 10 in 2012;
            // ['1,10 1 2012'] would disable January 1 and 10 in 2012;
            // ['1-10,20,22,24 1-3 *'] would disable 1 through 10, plus the 22nd and 24th of January through March for every year;
            // ['* * * 0,6'] would disable all Saturdays and Sundays;
            // default is FALSE, no disabled dates
            disabled_dates:     false,

            // week's starting day
            // valid values are 0 to 6, Sunday to Saturday
            // default is 1, Monday
            first_day_of_week:  1,

            // format of the returned date
            // accepts the following characters for date formatting: d, D, j, l, N, w, S, F, m, M, n, Y, y borrowing syntax from (PHP's date function)
            // default is Y-m-d
            format:             'Y-m-d',

            // months names
            months:             ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],

            // the offset, in pixels (x, y), to shift the date picker's position relative to the top-left of the icon
            // that toggles the date picker
            // default is [20, -5]
            offset:             [20, -5],

            // should the element the calendar is attached to, be read-only?
            // if set to TRUE, a date can be set only through the date picker and cannot be enetered manually
            // default is TRUE
            readonly_element:   true,

            // should an extra column be shown, showing the number of each week?
            // anything other than FALSE will enable this feature, and use the given value as column title
            // i.e. show_week_number: 'Wk' would enable this feature and have "Wk" as the column's title
            // default is FALSE
            show_week_number:   false,

            // how should the date picker start
            // valid values are "days", "months" and "years"
            // default is "days"
            view:               'days',

            // days of the week that are considered "weekend days"
            // valid values are 0 to 6, Sunday to Saturday
            // default values are 0 and 6 (Saturday and Sunday)
            weekend_days:       [0, 6]

        }

        // private properties
        var view, datepicker, icon, header, daypicker, monthpicker, yearpicker, current_system_month, current_system_year,
            current_system_day, first_selectable_month, first_selectable_year, first_selectable_day, selected_month,
            selected_year, default_day, default_month, default_year, disabled_dates, shim, direction, last_selectable_date;

        var plugin = this;

        plugin.settings = {}

        // the jQuery version of the element
        // "element" (without the $) will point to the DOM element
        var $element = $(element);

        /**
         *  Constructor method. Initializes the date picker.
         *
         *  @return void
         */
        var init = function() {

            plugin.settings = $.extend({}, defaults, options);

            // if the element should be readonly, set the "readonly" attribute
            if (plugin.settings.readonly_element) $element.attr('readonly', 'readonly');

            // save the default view for later
            view = plugin.settings.view;

            // create the calendar icon
            var html = '<button type="button" class="Zebra_DatePicker_Icon">Pick a date</button>';

            // convert to a jQuery object
            icon = $(html);

            // a reference to the icon, as a global property
            plugin.icon = icon;

            // the calendars direction
            direction =

                // future-only
                (
                    !$.isArray(plugin.settings.direction) &&
                    (plugin.settings.direction === true || to_int(plugin.settings.direction) > 0)
                ) || (
                    $.isArray(plugin.settings.direction) && plugin.settings.direction.length == 2 &&
                    (plugin.settings.direction[0] === true || to_int(plugin.settings.direction[0]) > 0)
                ) ? true :

                // past-only
                (
                    !$.isArray(plugin.settings.direction) &&
                    (plugin.settings.direction === false || to_int(plugin.settings.direction) < 0)
                ) || (
                    $.isArray(plugin.settings.direction) && plugin.settings.direction.length == 2 &&
                    (plugin.settings.direction[0] === false || to_int(plugin.settings.direction[0]) < 0)
                ) ? false :

                // no restrictions
                0;

            // by default, we assume that the first selectable date is the current system date
            var date = new Date();

            // extract the date parts
            // also, save the current system month/day/year - we'll use them to highlight the current system date
            first_selectable_month = date.getMonth();
            current_system_month = date.getMonth();
            first_selectable_year = date.getFullYear();
            current_system_year = date.getFullYear();
            first_selectable_day = date.getDate();
            current_system_day = date.getDate();

            // if calendar is future-only or past-only
            if (direction !== 0) {

                // we add/substract that number to first selectable date
                // use the Date object to normalize the date
                // for example, 2011 05 33 will be transformed to 2011 06 02
                date = new Date(
                    first_selectable_year,
                    first_selectable_month,
                    first_selectable_day + to_int($.isArray(plugin.settings.direction) ? plugin.settings.direction[0] : plugin.settings.direction)
                );

                // re-extract the date parts
                first_selectable_month = date.getMonth();
                first_selectable_year = date.getFullYear();
                first_selectable_day = date.getDate();

            }

            // if calendar has a direction and a time span
            if (direction !== 0 && $.isArray(plugin.settings.direction) && plugin.settings.direction.length == 2) {

                // we add/substract the number of selectable days
                // use the Date object to normalize the date
                // for example, 2011 05 33 will be transformed to 2011 06 02
                date = new Date(
                    first_selectable_year,
                    first_selectable_month,
                    first_selectable_day + ((direction > 0 ? 1 : -1) * to_int(plugin.settings.direction[1]))
                );

                // the last selectable date, as an integer in the form of YYYYMMDD
                last_selectable_date = to_int(str_concat(date.getFullYear(), str_pad(date.getMonth(), 2), str_pad(date.getDate(), 2)));
                
            }

            // if first selectable date is disabled, find the actual first selectable date
            if (is_disabled(str_concat(
                first_selectable_year,
                str_pad(first_selectable_month, 2),
                str_pad(first_selectable_day, 2)
            ))) {

                // loop
                while (

                    // until we find the first selectable year
                    is_disabled(first_selectable_year)

                ) {

                    // if calendar is past-only, decrement the year
                    if (!direction) first_selectable_year--;

                    // otherwise, increment the year
                    else first_selectable_year++;

                    // because we've changed years, reset the month to January
                    first_selectable_month = 0;

                }

                // loop
                while (

                    // until we find the first selectable month
                    is_disabled(str_concat(first_selectable_year, str_pad(first_selectable_month, 2)))

                ) {

                    // if calendar is past-only, decrement the month
                    if (!direction) first_selectable_month--;

                    // otherwise, increment the month
                    else first_selectable_month++;

                    // if we moved to a following year
                    if (first_selectable_month > 11) {

                        // increment the year
                        first_selectable_year++;

                        // reset the month to January
                        first_selectable_month = 0;

                    // if we moved to a previous year
                    } else if (first_selectable_month < 0) {

                        // decrement the year
                        first_selectable_year--;

                        // reset the month to January
                        first_selectable_month = 0;

                    }

                    // because we've changed months, reset the day to the first day of the month
                    first_selectable_day = 1;

                }

                // loop
                while (

                    // until we find the first selectable day
                    is_disabled(str_concat(
                        first_selectable_year,
                        str_pad(first_selectable_month, 2),
                        str_pad(first_selectable_day, 2)
                    ))

                ) {

                    // if calendar is past-only, decrement the day
                    if (!direction) first_selectable_day--;

                    // otherwise, increment the day
                    else first_selectable_day++;

                    // use the Date object to normalize the date
                    // for example, 2011 05 33 will be transformed to 2011 06 02
                    date = new Date(first_selectable_year, first_selectable_month, first_selectable_day);

                    // re-extract date parts from the normalized date
                    // as we use them in the current loop
                    first_selectable_year = date.getFullYear();
                    first_selectable_month = date.getMonth();
                    first_selectable_day = date.getDate();

                }

            }

            // by default, only clicking the calendar icon shows the date picker
            // if text box is readonly
            // clicking the text box will also show the date picker

            // attach the click event
            (plugin.settings.readonly_element ? icon.add($element) : icon).bind('click', function(e) {

                e.preventDefault();

                // if the date picker is visible, hide it
                if (datepicker.css('display') != 'none') plugin.hide();

                // if the date picker is not visible
                else {

                    // get the default date, from the element, and check if it represents a valid date, according to the required format
                    var default_date = check_date($element.val());

                    // if the value represents a valid date
                    if (default_date) {

                        // extract the date parts
                        // we'll use these to highlight the default date in the date picker and as starting point to
                        // what year and month to start the date picker with
                        // why sepparate values? because selected_* will change as user navigates within the date picker
                        default_month = default_date.getMonth();
                        selected_month = default_date.getMonth();
                        default_year = default_date.getFullYear();
                        selected_year = default_date.getFullYear();
                        default_day = default_date.getDate();

                        // if
                        if (

                            // the default date represents a disabled date
                            is_disabled(str_concat(
                                default_year,
                                str_pad(default_month, 2),
                                str_pad(default_day, 2)
                            ))

                        ) {

                            // the calendar will start with the first selectable year/month
                            selected_month = first_selectable_month;
                            selected_year = first_selectable_year;

                        }

                    // if a default value is not available, or value does not represent a valid date
                    } else {

                        // the calendar will start with the first selectable year/month
                        selected_month = first_selectable_month;
                        selected_year = first_selectable_year;

                    }

                    // generate the appropriate view
                    manage_views();

                    // show the date picker
                    plugin.show();

                }

            });

            icon.insertAfter(element);

            // generate the container that will hold everything
            var html = '' +
                '<div class="Zebra_DatePicker">' +
                    '<table class="dp_header">' +
                        '<tr>' +
                            '<td class="dp_previous">&laquo;</td>' +
                            '<td class="dp_caption">&nbsp;</td>' +
                            '<td class="dp_next">&raquo;</td>' +
                        '</tr>' +
                    '</table>' +
                    '<table class="dp_daypicker"></table>' +
                    '<table class="dp_monthpicker"></table>' +
                    '<table class="dp_yearpicker"></table>' +
                '</div>';

            // create a jQuery object out of the HTML above and create a reference to it
            datepicker = $(html);

            // a reference to the calendar, as a global property
            plugin.datepicker = datepicker;

            // create references to the different parts of the date picker
            header = datepicker.find('table.dp_header').first();
            daypicker = datepicker.find('table.dp_daypicker').first();
            monthpicker = datepicker.find('table.dp_monthpicker').first();
            yearpicker = datepicker.find('table.dp_yearpicker').first();

            // inject the container into the DOM
            $('body').append(datepicker);

            // add the mouseover/mousevents to all to the date picker's cells
            // except those that are not selectable
            datepicker.
                delegate('td:not(.dp_disabled, .dp_weekend_disabled, .dp_not_in_month, .dp_blocked, .dp_week_number)', 'mouseover', function() {
                    $(this).addClass('dp_hover');
                }).
                delegate('td:not(.dp_disabled, .dp_weekend_disabled, .dp_not_in_month, .dp_blocked, .dp_week_number)', 'mouseout', function() {
                    $(this).removeClass('dp_hover');
                });

            // prevent text highlighting for the text in the header
            // (for the case when user keeps clicking the "next" and "previous" buttons)
            disable_text_select(header.find('td'));

            // event for when clicking the "previous" button
            header.find('.dp_previous').bind('click', function() {

                // if button is not disabled
                if (!$(this).hasClass('dp_blocked')) {

                    // if view is "months"
                    // decrement year by one
                    if (view == 'months') selected_year--;

                    // if view is "years"
                    // decrement years by 12
                    else if (view == 'years') selected_year -= 12;

                    // if view is "days"
                    // decrement the month and
                    // if month is out of range
                    else if (--selected_month < 0) {

                        // go to the last month of the previous year
                        selected_month = 11;
                        selected_year--;

                    }

                    // generate the appropriate view
                    manage_views();

                }

            });

            // attach a click event to the caption in header
            header.find('.dp_caption').bind('click', function() {

                // if current view is "days"
                // make the current view be "months"
                if (view == 'days') view = 'months';

                // if current view is "months"
                // make the current view be "years"
                else if (view == 'months') view = 'years';

                // for any other case
                // make the current view be "days"
                else view = 'days';

                // generate the appropriate view
                manage_views();

            });

            // event for when clicking the "next" button
            header.find('.dp_next').bind('click', function() {

                // if button is not disabled
                if (!$(this).hasClass('dp_blocked')) {

                    // if view is "months"
                    // increment year by 1
                    if (view == 'months') selected_year++;

                    // if view is "years"
                    // increment years by 12
                    else if (view == 'years') selected_year += 12;

                    // if view is "days"
                    // increment the month and
                    // if month is out of range
                    else if (++selected_month == 12) {

                        // go to the first month of the next year
                        selected_month = 0;
                        selected_year++;

                    }

                    // generate the appropriate view
                    manage_views();

                }

            });

            // attach a click event for the cells in the day picker
            daypicker.delegate('td:not(.dp_disabled, .dp_weekend_disabled, .dp_not_in_month, .dp_week_number)', 'click', function() {

                // set the currently selected and formated date as the value of the element the plugin is attached to
                $element.val(format(new Date(selected_year, selected_month, to_int($(this).html()))));

                // hide the date picker
                plugin.hide();

            });

            // attach a click event for the cells in the month picker
            monthpicker.delegate('td:not(.dp_disabled)', 'click', function() {

                // get the month we've clicked on
                var matches = $(this).attr('class').match(/dp\_month\_([0-9]+)/);

                // set the selected month
                selected_month = to_int(matches[1]);

                // direct the user to the "days" view
                view = 'days';

                // generate the appropriate view
                manage_views();

            });

            // attach a click event for the cells in the year picker
            yearpicker.delegate('td:not(.dp_disabled)', 'click', function() {

                // set the selected year
                selected_year = to_int($(this).html());

                // direct the user to the "months" view
                view = 'months';

                // generate the appropriate view
                manage_views();

            });

            // bind some events to the document
            $(document).bind({

                //whenever anything is clicked on the page
                'mousedown': plugin._mousedown,
                'keyup': plugin._keyup

            });

            // parse the rules for disabling dates and turn them into arrays of arrays

            // array that will hold the rules for disabling dates
            disabled_dates = [];

            // iterate through the rules for disabling dates
            $.each(plugin.settings.disabled_dates, function() {

                // split the values in rule by white space
                var rules = this.split(' ');

                // there can be a maximum of 4 rules (days, months, years and, optionally, day of the week)
                for (var i = 0; i < 4; i++) {

                    // if one of the values is not available
                    // replace it with a * (wildcard)
                    if (!rules[i]) rules[i] = '*';

                    // if rule contains a comma, create a new array by splitting the rule by commas
                    // if there are no commas create an array containing the rule's string
                    rules[i] = ($.inArray(',', rules[i]) > -1 ? rules[i].split(',') : new Array(rules[i]));

                    // iterate through the items in the rule
                    for (var j = 0; j < rules[i].length; j++) {

                        // if item contains a dash (defining a range)
                        if ($.inArray('-', rules[i][j]) > -1) {

                            // get the lower and upper limits of the range
                            var limits = rules[i][j].match(/^([0-9]+)\-([0-9]+)/);

                            // if range is valid
                            if (null != limits) {

                                // iterate through the range
                                for (var k = to_int(limits[1]); k <= to_int(limits[2]); k++) {

                                    // if value is not already among the values of the rule
                                    // add it to the rule
                                    if ($.inArray(k, rules[i]) == -1) rules[i].push(k + '');

                                }

                                // remove the range indicator
                                rules[i].splice(j, 1);

                            }

                        }

                    }

                    // iterate through the items in the rule
                    // and make sure that numbers are numbers
                    for (j = 0; j < rules[i].length; j++) rules[i][j] = (isNaN(to_int(rules[i][j])) ? rules[i][j] : to_int(rules[i][j]));

                }

                // add to the list of processed rules
                disabled_dates.push(rules);

            });

        }

        /**
         *  Hides the date picker.
         *
         *  @return void
         */
        plugin.hide = function() {

            // hide the iFrameShim in Internet Explorer 6
            iframeShim('hide');

            // hide the date picker
            datepicker.css('display', 'none');

        }

        /**
         *  Shows the date picker.
         *
         *  @return void
         */
        plugin.show = function() {

            // generate the appropriate view
            manage_views();

            var

                // get the date picker width and height
                datepicker_width = datepicker.outerWidth(),
                datepicker_height = datepicker.outerHeight(),

                // compute the date picker's default left and top
                left = icon.offset().left + plugin.settings.offset[0],
                top = icon.offset().top - datepicker_height + plugin.settings.offset[1],

                // get browser window's width and height
                window_width = $(window).width(),
                window_height = $(window).height(),

                // get browser window's horizontal and vertical scroll offsets
                window_scroll_top = $(window).scrollTop(),
                window_scroll_left = $(window).scrollLeft();

            // if date picker is outside the viewport, adjust its position so that it is visible
            if (left + datepicker_width > window_scroll_left + window_width) left = window_scroll_left + window_width - datepicker_width;
            if (left < window_scroll_left) left = window_scroll_left;
            if (top + datepicker_height > window_scroll_top + window_height) top = window_scroll_top + window_height - datepicker_height;
            if (top < window_scroll_top) top = window_scroll_top;

            // make the date picker visible
            datepicker.css({
                'left':     left,
                'top':      top
            });

            // fade-in the date picker
            // for Internet Explorer < 9 show the date picker instantly or fading alters the font's weight
            datepicker.fadeIn($.browser.msie && $.browser.version.match(/^[6-8]/) ? 0 : 150, 'linear');

            // show the iFrameShim in Internet Explorer 6
            iframeShim();

        }

        /**
         *  Checks if a string represents a valid date according to the format defined by the "format" property.
         *
         *  @param  string  str_date    A string representing a date, formatted accordingly to the "format" property.
         *                              For example, if "format" is "Y-m-d" the string should look like "2011-06-01"
         *
         *  @return boolean             Returns TRUE if string represents a valid date according formatted according to
         *                              the "format" property or FALSE otherwise.
         *
         *  @access private
         */
        var check_date = function(str_date) {

            // if value is given
            if ($.trim(str_date) != '') {

                var

                    // prepare the format by removing white space from it
                    // and also escape characters that could have special meaning in a regular expression
                    format = escape_regexp(plugin.settings.format.replace(/\s/g, '')),

                    // allowed characters in date's format
                    format_chars = ['d','D','j','l','N','S','w','F','m','M','n','Y','y'],

                    // "matches" will contain the characters defining the date's format
                    matches = new Array,

                    // "rexeps" will contain the regular expression built for each of the characters used in the date's format
                    regexp = new Array;

                // iterate through the allowed characters in date's format
                for (var i = 0; i < format_chars.length; i++)

                    // if character is found in the date's format
                    if ((position = format.indexOf(format_chars[i])) > -1)

                        // save it, alongside the character's position
                        matches.push({character: format_chars[i], position: position});

                // sort characters defining the date's format based on their position, ascending
                matches.sort(function(a, b){ return a.position - b.position });

                // iterate through the characters defining the date's format
                $.each(matches, function(index, match) {

                    // add to the array of regular expressions, based on the character
                    switch (match.character) {

                        case 'd': regexp.push('0[1-9]|[12][0-9]|3[01]'); break;
                        case 'D': regexp.push('[a-z]{3}'); break;
                        case 'j': regexp.push('[1-9]|[12][0-9]|3[01]'); break;
                        case 'l': regexp.push('[a-z]+'); break;
                        case 'N': regexp.push('[1-7]'); break;
                        case 'S': regexp.push('st|nd|rd|th'); break;
                        case 'w': regexp.push('[0-6]'); break;
                        case 'F': regexp.push('[a-z]+'); break;
                        case 'm': regexp.push('0[1-9]|1[012]+'); break;
                        case 'M': regexp.push('[a-z]{3}'); break;
                        case 'n': regexp.push('[1-9]|1[012]'); break;
                        case 'Y': regexp.push('[0-9]{4}'); break;
                        case 'y': regexp.push('[0-9]{2}'); break;

                    }

                });

                // if we have an array of regular expressions
                if (regexp.length) {

                    // we will replace characters in the date's format in reversed order
                    matches.reverse();

                    // iterate through the characters in date's format
                    $.each(matches, function(index, match) {

                        // replace each character with the appropriate regular expression
                        format = format.replace(match.character, '(' + regexp[regexp.length - index - 1] + ')');

                    });

                    // the final regular expression
                    regexp = new RegExp('^' + format + '$', 'ig');

                    // if regular expression was matched
                    if ((segments = regexp.exec(str_date.replace(/\s/g, '')))) {

                        // check if date is a valid date (i.e. there's no February 31)

                        var original_day,
                            original_month,
                            original_year,
                            english_days   = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
                            english_months = ['January','February','March','April','May','June','July','August','September','October','November','December'],
                            iterable,

                            // by default, we assume the date is valid
                            valid = true;

                        // reverse back the characters in the date's format
                        matches.reverse();

                        // iterate through the characters in the date's format
                        $.each(matches, function(index, match) {

                            // if the date is not valid, don't look further
                            if (!valid) return true;

                            // based on the character
                            switch (match.character) {

                                case 'm':
                                case 'n':

                                    // extract the month from the value entered by the user
                                    original_month = to_int(segments[index + 1]);

                                    break;

                                case 'd':
                                case 'j':

                                    // extract the day from the value entered by the user
                                    original_day = to_int(segments[index + 1]);

                                    break;

                                case 'D':
                                case 'l':
                                case 'F':
                                case 'M':

                                    // if day is given as day name, we'll check against the names in the used language
                                    if (match.character == 'D' || match.character == 'l') iterable = plugin.settings.days;

                                    // if month is given as month name, we'll check against the names in the used language
                                    else iterable = plugin.settings.months;

                                    // by default, we assume the day or month was not entered correctly
                                    valid = false;

                                    // iterate through the month/days in the used language
                                    $.each(iterable, function(key, value) {

                                        // if month/day was entered correctly, don't look further
                                        if (valid) return true;

                                        // if month/day was entered correctly
                                        if (segments[index + 1].toLowerCase() == value.substring(0, (match.character == 'D' || match.character == 'M' ? 3 : value.length)).toLowerCase()) {

                                            // extract the day/month from the value entered by the user
                                            switch (match.character) {

                                                case 'D': segments[index + 1] = english_days[key].substring(0, 3); break;
                                                case 'l': segments[index + 1] = english_days[key]; break;
                                                case 'F': segments[index + 1] = english_months[key]; original_month = key + 1; break;
                                                case 'M': segments[index + 1] = english_months[key].substring(0, 3); original_month = key + 1; break;

                                            }

                                            // day/month value is valid
                                            valid = true;

                                        }

                                    });

                                    break;

                                case 'Y':

                                    // extract the year from the value entered by the user
                                    original_year = to_int(segments[index + 1]);

                                    break;

                                case 'y':

                                    // extract the year from the value entered by the user
                                    original_year = '19' + to_int(segments[index + 1]);

                                    break;

                            }
                        });

                        // if everything is ok so far
                        if (valid) {

                            // generate a Date object using the values entered by the user
                            var date = new Date(original_year, original_month - 1, original_day);

                            // if, after that, the date is the same as the date entered by the user
                            if (date.getFullYear() == original_year && date.getDate() == original_day && date.getMonth() == (original_month - 1))

                                // return the date as JavaScript date object
                                return date;

                        }

                    }

                }

                // if script gets this far, return false as something must've went wrong
                return false;

            }

        }

        /**
         *  Prevents the possibility of selecting text on a given element. Used on the "previous" and "next" buttons
         *  where text might get accidentally selected when user quickly clicks on the buttons.
         *
         *  Code by http://chris-barr.com/index.php/entry/disable_text_selection_with_jquery/
         *
         *  @param  jQuery Element  el  A jQuery element on which to prevents text selection.
         *
         *  @return void
         *
         *  @access private
         */
        var disable_text_select = function(el) {

            // if browser is Firefox
			if ($.browser.mozilla) el.css('MozUserSelect', 'none');

            // if browser is Internet Explorer
            else if ($.browser.msie) el.bind('selectstart', function() { return false });

            // for the other browsers
			else el.mousedown(function() { return false });

        }

        /**
         *  Escapes special characters in a string, preparing it for use in a regular expression.
         *
         *  @param  string  str     The string in which special characters should be escaped.
         *
         *  @return string          Returns the string with escaped special characters.
         *
         *  @access private
         */
        var escape_regexp = function(str) {

		  return str.replace(/([-.*+?^${}()|[\]\/\\])/g, '\\$1');

        }

        /**
         *  Formats a JavaScript date object to the format specified by the "format" property.
         *  Code taken from http://electricprism.com/aeron/calendar/
         *
         *  @param  date    date    A valid JavaScrip date object
         *
         *  @return void
         *
         *  @access private
         */
        var format = function(date) {

            var result = '',

                // extract parts of the date:
                // day number, 1 - 31
                j = date.getDate(),

                // day of the week, 0 - 6, Sunday - Saturday
                w = date.getDay(),

                // the name of the day of the week Sunday - Saturday
                l = plugin.settings.days[w],

                // the month number, 1 - 12
                n = date.getMonth() + 1,

                // the month name, January - December
                f = plugin.settings.months[n - 1],

                // the year (as a string)
                y = date.getFullYear() + '';

            // iterate through the characters in the format
            for (var i = 0; i < plugin.settings.format.length; i++) {

                // extract the current character
                var chr = plugin.settings.format.charAt(i);

                // see what character it is
                switch(chr) {

                    // year as two digits
                    case 'y': y = y.substr(2);

                    // year as four digits
                    case 'Y': result += y; break;

                    // month number, prefixed with 0
                    case 'm': n = str_pad(n, 2);

                    // month number, not prefixed with 0
                    case 'n': result += n; break;

                    // month name, three letters
                    case 'M': f = f.substr(0, 3);

                    // full month name
                    case 'F': result += f; break;

                    // day number, prefixed with 0
                    case 'd': j = str_pad(j, 2);

                    // day number not prefixed with 0
                    case 'j': result += j; break;

                    // day name, three letters
                    case 'D': l = l.substr(0, 3);

                    // full day name
                    case 'l': result += l; break;
                     
                    // ISO-8601 numeric representation of the day of the week, 1 - 7
                    case 'N': w++;

                    // day of the week, 0 - 6
                    case 'w': result += w; break;

                    // English ordinal suffix for the day of the month, 2 characters
                    // (st, nd, rd or th (works well with j))
                    case 'S':

                        if (j % 10 == 1 && j != '11') result += 'st';

                        else if (j % 10 == 2 && j != '12') result += 'nd';

                        else if (j % 10 == 3 && j != '13') result += 'rd';

                        else result += 'th';

                        break;

                    // this is probably the separator
                    default: result += chr;

                }

            }

            // return formated date
            return result;

        }

        /**
         *  Generates the day picker view, and displays it
         *
         *  @return void
         *
         *  @access private
         */
        var generate_daypicker = function() {

            var

                // get the number of days in the selected month
                days_in_month = new Date(selected_year, selected_month + 1, 0).getDate(),

                // get the selected month's starting day (from 0 to 6)
                first_day = new Date(selected_year, selected_month, 1).getDay(),

                // how many days are there in the previous month
                days_in_previous_month = new Date(selected_year, selected_month, 0).getDate(),

                // how many days are there to be shown from the previous month
                days_from_previous_month = first_day - plugin.settings.first_day_of_week;

            // the final value of how many days are there to be shown from the previous month
            days_from_previous_month = days_from_previous_month < 0 ? 7 + days_from_previous_month : days_from_previous_month;

            // manage header caption and enable/disable navigation buttons if necessary
            manage_header(plugin.settings.months[selected_month] + ', ' + selected_year);

            // start generating the HTML
            var html = '<tr>';

            // if a column featuring the number of the week is to be shown
            if (plugin.settings.show_week_number)

                // column title
                html += '<th>' + plugin.settings.show_week_number + '</th>';

            // name of week days
            // show only the first two letters
            // and also, take in account the value of the "first_day_of_week" property
            for (var i = 0; i < 7; i++)

                html += '<th>' + plugin.settings.days[(plugin.settings.first_day_of_week + i) % 7].substr(0, 2) + '</th>';

            html += '</tr><tr>';

            // the calendar shows a total of 42 days
            for (var i = 0; i < 42; i++) {

                // seven days per row
                if (i > 0 && i % 7 == 0) html += '</tr><tr>';

                // if week number is to be shown
                if (i % 7 == 0 && plugin.settings.show_week_number) {

                    var

                        // first day of the year
                        first_day_of_year = new Date(selected_year, 0, 1),

                        // current date
                        current_date = new Date(selected_year, selected_month, i),

                        // compute the current week's number
                        week_number = Math.ceil((((current_date - first_day_of_year) / 86400000) + current_date.getDay() + 1) / 7);

                    // add week number
                    html += '<td class="dp_week_number">' + week_number + '</td>';

                }

                // the number of the day in month
                var day = (i - days_from_previous_month + 1);

                // if this is a day from the previous month
                if (i < days_from_previous_month)

                    html += '<td class="dp_not_in_month">' + (days_in_previous_month - days_from_previous_month + i + 1) + '</td>';

                // if this is a day from the next month
                else if (day > days_in_month)

                    html += '<td class="dp_not_in_month">' + (day - days_in_month) + '</td>';

                // if this is a day from the current month
                else {

                    var

                        // get the week day (0 to 6, Sunday to Saturday)
                        weekday = (plugin.settings.first_day_of_week + i) % 7,

                        // current date, as an integer in the form of YYYYMMDD
                        now = to_int(str_concat(selected_year, str_pad(selected_month, 2), str_pad(day, 2)));

                        class_name = '';

                    // if date needs to be disabled
                    if (

                        // current date is in the array of disabled dates
                        is_disabled(now) ||

                        // a date span exists
                        (undefined != last_selectable_date && (

                            // and date is outside the limit
                            (plugin.settings.direction[0] > 0 && now > last_selectable_date) ||
                            (plugin.settings.direction[0] <= 0 && now < last_selectable_date)

                        ))

                    )

                        // if day is in weekend
                        if ($.inArray(weekday, plugin.settings.weekend_days) > -1) class_name = 'dp_weekend_disabled';

                        // if work day
                        else class_name += ' dp_disabled';

                    // if there are no direction restrictions
                    else {

                        // if day is in weekend
                        if ($.inArray(weekday, plugin.settings.weekend_days) > -1) class_name = 'dp_weekend';

                        // highlight the currently selected date
                        if (selected_month == default_month && selected_year == default_year && default_day == day) class_name += ' dp_selected';

                        // highlight the current system date
                        else if (selected_month == current_system_month && selected_year == current_system_year && current_system_day == day) class_name += ' dp_current';

                    }

                    // print the day of the month
                    html += '<td' + (class_name != '' ? ' class="' + $.trim(class_name) + '"' : '') + '>' + str_pad(day, 2) + '</td>';

                }

            }

            // wrap up generating the day picker
            html += '</tr>';

            // inject the day picker into the DOM
            daypicker.html($(html));

            // make the day picker visible
            daypicker.css('display', '');

        }

        /**
         *  Generates the month picker view, and displays it
         *
         *  @return void
         *
         *  @access private
         */
        var generate_monthpicker = function() {

            // manage header caption and enable/disable navigation buttons if necessary
            manage_header(selected_year);

            // start generating the HTML
            var html = '<tr>';

            // iterate through all the months
            for (var i = 0; i < 12; i++) {

                // three month per row
                if (i > 0 && i % 3 == 0) html += '</tr><tr>';

                var class_name = 'dp_month_' + i,

                    // current month as an integer
                    now = to_int(str_concat(selected_year, str_pad(i, 2)));

                // if month needs to be disabled
                if (is_disabled(now)) class_name += ' dp_disabled';

                // highlight the currently selected month
                else if (current_system_month == i && current_system_year == selected_year) class_name += ' dp_current';

                // first three letters of the month's name
                html += '<td class="' + $.trim(class_name) + '">' + plugin.settings.months[i].substr(0, 3) + '</td>';

            }

            // wrap up
            html += '</tr>';

            // inject into the DOM
            monthpicker.html($(html));

            // make the month picker visible
            monthpicker.css('display', '');

        }

        /**
         *  Generates the year picker view, and displays it
         *
         *  @return void
         *
         *  @access private
         */
        var generate_yearpicker = function() {

            // manage header caption and enable/disable navigation buttons if necessary
            manage_header(selected_year - 7 + ' - ' + (selected_year + 4));

            // start generating the HTML
            var html = '<tr>';

            // we're showing 9 years at a time, current year in the middle
            for (var i = 0; i < 12; i++) {

                // three years per row
                if (i > 0 && i % 3 == 0) html += '</tr><tr>';

                var class_name = '',
                    
                    // current year as an integer
                    now = to_int(selected_year - 7 + i);

                // if year needs to be disabled
                if (is_disabled(now)) class_name += ' dp_disabled';

                // highlight the currently selected year
                else if (current_system_year == (selected_year - 7 + i)) class_name += ' dp_current';

                // first three letters of the month's name
                html += '<td' + ($.trim(class_name) != '' ? ' class="' + $.trim(class_name) + '"' : '') + '>' + (selected_year - 7 + i) + '</td>';

            }

            // wrap up
            html += '</tr>';

            // inject into the DOM
            yearpicker.html($(html));

            // make the year picker visible
            yearpicker.css('display', '');

        }

        /**
         *  Generates an iFrame shim in Internet Explorer 6 so that the date picker appears above select boxes.
         *
         *  @return void
         *
         *  @access private
         */
        var iframeShim = function(action) {

            // this is necessary only if browser is Internet Explorer 6
    		if ($.browser.msie && $.browser.version.match(/^6/)) {

                // if the iFrame was not yet created
                // "undefined" evaluates as FALSE
                if (!shim) {

                    // the iFrame has to have the element's zIndex minus 1
                    var zIndex = to_int(datepicker.css('zIndex')) - 1;

                    // create the iFrame
                    shim = jQuery('<iframe>', {
                        'src':                  'javascript:document.write("")',
                        'scrolling':            'no',
                        'frameborder':          0,
                        'allowtransparency':    'true',
                        css: {
                            'zIndex':       zIndex,
                            'position':     'absolute',
                            'top':          -1000,
                            'left':         -1000,
                            'width':        datepicker.outerWidth(),
                            'height':       datepicker.outerHeight(),
                            'filter':       'progid:DXImageTransform.Microsoft.Alpha(opacity=0)',
                            'display':      'none'
                        }
                    });

                    // inject iFrame into DOM
                    $('body').append(shim);

                }

                // what do we need to do
                switch (action) {

                    // hide the iFrame?
                    case 'hide':

                        // set the iFrame's display property to "none"
                        shim.css('display', 'none');

                        break;

                    // show the iFrame?
                    default:

                        // get date picker top and left position
                        var offset = datepicker.offset();

                        // position the iFrame shim right underneath the date picker
                        // and set its display to "block"
                        shim.css({
                            'top':      offset.top,
                            'left':     offset.left,
                            'display':  'block'
                        });

                }

            }

        }

        /**
         *  Checks if, according to the direction of the calendar and/or the values defined by the "disabled_dates"
         *  property, a day, a month or a year needs to be disabled.
         *
         *  @param  integer now     An integer representing the value that needs to be checked.
         *
         *                          A value in the form of YYYYMMDD will validate a date;
         *                          A value in the form of YYYYMM will validate a month;
         *                          A value in the form of YYYY will validate a year;
         *
         *  @return boolean         Returns TRUE if the given value is valid or FALSE otherwise
         *
         *  @access private
         */
        var is_disabled = function(now) {

            // if there is a direction restriction
            if (direction !== 0) {

                // get the length of the argument
                var len = (now + '').length;

                // if we're checking days
                if (len == 8 && (

                    // calendar is future-only but current day is before the first selectable day
                    (direction && now < str_concat(first_selectable_year, str_pad(first_selectable_month, 2), str_pad(first_selectable_day, 2))) ||

                    // calendar is past-only but current day is after the last selectable day
                    (!direction && now > str_concat(first_selectable_year, str_pad(first_selectable_month, 2), str_pad(first_selectable_day, 2)))

                    // day needs to be disabled
                    )) return true;

                // if we're checking months
                else if (len == 6 && (

                    // calendar is future-only but current month has no selectable days
                    (direction && now < str_concat(first_selectable_year, str_pad(first_selectable_month, 2))) ||

                    // calendar is past-only but current month has no selectable days
                    (!direction && now > str_concat(first_selectable_year, str_pad(first_selectable_month, 2)))

                    // month needs to be disabled
                    )) return true;

                // if we're checking years
                else if (len == 4 && (

                    // calendar is future-only but current year has no selectable days
                    (direction && now < first_selectable_year) ||

                    // calendar is past-only but current yar has no selectable days
                    (!direction && now > first_selectable_year)

                    // year needs to be disabled
                    )) return true;

            }

            // if there are rules for disabling dates
            if (disabled_dates) {

                // convert the argument to a string
                now = now + '';

                // extract the year and the month from "now"
                var year = to_int(now.substr(0, 4)),
                    month = to_int(now.substr(4, 2)) + 1,
                    day = to_int(now.substr(6, 2)),

                    // by default, we assume the day/month/year is not to be disabled
                    disabled = false;

                // iterate through the rules for disabling dates
                $.each(disabled_dates, function() {

                    // if the date is to be disabled, don't look any further
                    if (disabled) return;

                    var rule = this;

                    // if the rules apply for the current year
                    if ($.inArray(year, rule[2]) > -1 || $.inArray('*', rule[2]) > -1)

                        // if the rules apply for the current month
                        if ((undefined != month && $.inArray(month, rule[1]) > -1) || $.inArray('*', rule[1]) > -1)

                            // if the rules apply for the current day
                            if ((undefined != day && $.inArray(day, rule[0]) > -1) || $.inArray('*', rule[0]) > -1) {

                                // if day is to be disabled whatever the day
                                // don't look any further
                                if (rule[3] == '*') return (disabled = true);

                                // get the weekday
                                var weekday = new Date(year, month - 1, day).getDay();

                                // if weekday is to be disabled
                                // don't look any further
                                if ($.inArray(weekday, rule[3]) > -1) return (disabled = true);

                            }

                });

                // if the day/month/year needs to be disabled
                if (disabled) return true;

            }

            // if script gets this far it means that the day/month/year doesn't need to be disabled
            return false;

        }

        /**
         *  Sets the caption in the header of the date picker and enables or disables navigation buttons when necessary.
         *
         *  @param  string  caption     String that needs to be displayed in the header
         *
         *  @return void
         *
         *  @access private
         */
        var manage_header = function(caption) {

            // update the caption in the header
            header.find('.dp_caption').html(caption);

            // if calendar is future-only or past-only
            if (direction !== 0) {

                // get the current year and month
                var year = selected_year,
                    month = selected_month,
                    value;

                // if current view is showing days
                if (view == 'days') {

                    // if calendar is future-only and decrementing the month gets us out of range
                    if (direction && --month < 0) {

                        // go to the previous year
                        month = 11;
                        year--;

                    // calendar is past-only and incrementing the month, gets us out of range
                    } else if (!direction && ++month > 11) {

                        // go to the next year
                        month = 0;
                        year++;

                    }

                    // the value that needs to be checked later by "is_disabled"
                    value = str_concat(year, str_pad(month, 2));

                // if current view is showing months
                } else if (view == 'months') {

                    // if calendar is future-only, decrement the year
                    if (direction) year--;

                    // if calendar is past-only, increment the year
                    else year++;

                    // the value that needs to be checked later by "is_disabled"
                    value = year;

                // if current view is showing years
                } else if (view == 'years') {

                    // if calendar is future-only, decrement year by 7
                    if (direction) year -= 7;

                    // if calendar is past-only, increment year by 7
                    else year += 7;

                    // the value that needs to be checked later by "is_disabled"
                    value = year;

                }

                // if the month/year is disabled
                if (is_disabled(value)) {

                    // disable it
                    header.find(direction ? '.dp_previous' : '.dp_next').addClass('dp_blocked');
                    header.find(direction ? '.dp_previous' : '.dp_next').removeClass('dp_hover');

                // otherwise, "previous" or "next" buttons must be clickable
                } else header.find(direction ? '.dp_previous' : '.dp_next').removeClass('dp_blocked');

            }

        }

        /**
         *  Shows the appropriate view (days, months or years) according to the current value of the "view" property.
         *
         *  @return void
         *
         *  @access private
         */
		var manage_views = function() {

            // if the day picker was not yet generated
            if (daypicker.text() == '' || view == 'days') {

                // if the day picker was not yet generated
                if (daypicker.text() == '') {

                    // temporarily make the date picker visible
                    // so that we can later grab its width and height
                    datepicker.css({
                        'left':     -1000,
                        'display':  'block'
                    });

    				// generate the day picker
    				generate_daypicker();

                    // get the day picker's width and height
                    var width = daypicker.outerWidth(),
                        height = daypicker.outerHeight();

                    // adjust the size of the header 
                    header.css('width', width);

                    // make the month picker have the same size as the day picker
                    monthpicker.css({
                        'width':    width,
                        'height':   height
                    });

                    // make the year picker have the same size as the day picker
                    yearpicker.css({
                        'width':    width,
                        'height':   height
                    });

                    // hide the date picker again
                    datepicker.css({
                        'display':  'none'
                    });

                // if the day picker was previously generated at least once
				// generate the day picker
                } else generate_daypicker();

                // hide the year and the month pickers
                monthpicker.css('display', 'none');
                yearpicker.css('display', 'none');

            // if the view is "months"
            } else if (view == 'months') {

                // generate the month picker
                generate_monthpicker();

                // hide the day and the year pickers
                daypicker.css('display', 'none');
                yearpicker.css('display', 'none');

            // if the view is "years"
            } else if (view == 'years') {

                // generate the year picker
                generate_yearpicker();

                // hide the day and the month pickers
                daypicker.css('display', 'none');
                monthpicker.css('display', 'none');

            }
		
		}

        /**
         *  Left-pad a string to a certain length with zeroes.
         *
         *  @param  string  str     The string to be padded.
         *
         *  @param  integer len     The length to which the string must be padded
         *
         *  @return string          Returns the string left-padded with leading zeroes
         *
         *  @access private
         */
        var str_pad = function(str, len) {

            // make sure argument is a string
            str += '';

            // pad with leading zeroes until we get to the desired length
            while (str.length < len) str = '0' + str;

            // return padded string
            return str;

        }

        /**
         *  Concatenates any number of arguments and returns them as string.
         *
         *  @return string  Returns the concatenaded values.
         *
         *  @access private
         */
        var str_concat = function() {

            var str = '';

            // concatenate as string
            for (var i = 0; i < arguments.length; i++) str += (arguments[i] + '');

            // return the concatenated values
            return str;

        }

        /**
         *  Returns the integer representation of a string
         *
         *  @return int     Returns the integer representation of the string given as argument
         *
         *  @access private
         */
        var to_int = function(str) {

            // as the "direction" property can be true or false, make sure we interpret them as "0"
            return parseInt((str === true || str === false ? 0 : str) , 10);

        }

        /**
         *  Function to be called when the "onKeyUp" event occurs
         *
         *  Why as a sepparate function and not inline when binding the event? Because only this way we can "unbind" it
         *  if the date picker is destroyed
         *
         *  @return boolean     Returns TRUE
         *
         *  @access private
         */
        plugin._keyup = function(e) {

            // if the date picker is visible
            // and the pressed key is ESC
            // hide the date picker
            if (datepicker.css('display') == 'block' || e.which == 27) plugin.hide();

            return true;

        }

        /**
         *  Function to be called when the "onMouseDown" event occurs
         *
         *  Why as a sepparate function and not inline when binding the event? Because only this way we can "unbind" it
         *  if the date picker is destroyed
         *
         *  @return boolean     Returns TRUE
         *
         *  @access private
         */
        plugin._mousedown = function(e) {

            // if the date picker is visible
            if (datepicker.css('display') == 'block') {

                // if we clicked the date picker's icon, let the onClick event of the icon to handle the event
                // (we want it to toggle the date picker)
                if ($(e.target).get(0) === icon.get(0)) return true;

                // if what's clicked is not inside the date picker
                // hide the date picker
                if ($(e.target).parents().filter('.Zebra_DatePicker').length == 0) plugin.hide();

            }

            return true;

        }

        // initialize the plugin
        init();

    }

    $.fn.Zebra_DatePicker = function(options) {

        return this.each(function() {

            // if element has a date picker already attached
            if (undefined != $(this).data('Zebra_DatePicker')) {

                // get reference to the previously attached date picker
                var plugin = $(this).data('Zebra_DatePicker');

                // remove the attached icon and calendar
                plugin.icon.remove();
                plugin.datepicker.remove();

                // remove associated event handlers from the document
                $(document).unbind('keyup', plugin._keyup);
                $(document).unbind('mousedown', plugin._mousedown);

            }

            // create a new instance of the plugin
            var plugin = new $.Zebra_DatePicker(this, options);

            // save a reference to the newly created object
            $(this).data('Zebra_DatePicker', plugin);

        });

    }

})(jQuery);