$(document).ready(function() {

    $('#datepicker-example1').Zebra_DatePicker();

    $('#datepicker-example2').Zebra_DatePicker({
        direction: 1    // boolean true would've made the date picker future only
                        // but starting from today, rather than tomorrow
    });

    $('#datepicker-example3').Zebra_DatePicker({
        direction: true,
        disabled_dates: ['* * * 0,6']   // all days, all monts, all years as long
                                        // as the weekday is 0 or 6 (Sunday or Saturday)
    });

    $('#datepicker-example4').Zebra_DatePicker({
        direction: [1, 10]
    });

    $('#datepicker-example5').Zebra_DatePicker({
        format: 'M d, Y'
    });

    $('#datepicker-example6').Zebra_DatePicker({
        show_week_number: 'Wk'
    });

    $('#datepicker-example7').Zebra_DatePicker({
        view: 'years'
    });

});