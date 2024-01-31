
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {

        themeSystem: 'bootstrap5',
        selectable: true,
        editable: true,
        allDay: true,
        eventSources: [{
            events: [{
                title: "event3",
                start: "2023-10-30T12:30:00"
            }],
        }],
        select: function (info) {
            const start = info.startStr; // Start date as a string
            const end = info.endStr; // End date as a string

            // set values in inputs
            jQuery(document).ready(function ($) {
                $('#event-modal').find('input[name=evtStart]').val(start);
                $('#event-modal').find('input[name=evtEnd]').val(end);

                // show modal dialog
                $('#event-modal').modal('show');

                // Uncomment this code to handle form submission
                $("#event-modal").find('form').on('submit', function (e) {
                    e.preventDefault(); // Prevent the form from submitting normally

                    // Use the 'ajaxurl' variable provided by WordPress
                    $.ajax({
                        url: ajaxurl,
                        type: 'post',
                        data: {
                            action: 'save_event_data',
                            formData: $(this).serialize()
                        },
                        success: function (response) {
                            // if saved, close modal
                            $("#event-modal").modal('hide');

                            // refetch event source, so the event will be shown in the calendar
                            $("#calendar").fullCalendar('refetchEvents');
                        }
                    });
                });
            });
        }




    });

    calendar.render();
});
