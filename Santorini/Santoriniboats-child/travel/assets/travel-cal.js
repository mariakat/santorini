document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var bookingDates = bookingOBJ.bookingDates;
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events:
           bookingDates
            // { // this object will be "parsed" into an Event Object
            //     title: 'Full Day Cruise # 8397374 - Archimidis Mertzanos', // a property!
            //     start: '2022-12-26', // a property!
            // },


    });
    // console.log(calendar);
    // console.log(bookingDates);
    // console.log(bookingDates[0].name);
    // var al = '';
    // for (const key in bookingDates) {
    //     const value = bookingDates[key];
    //     console.log(key, value);
    //
    // }


    calendar.render();


});

