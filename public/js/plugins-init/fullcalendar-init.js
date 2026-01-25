
document.addEventListener('DOMContentLoaded', function () {
  var Calendar = FullCalendar.Calendar;
  var Draggable = FullCalendar.Draggable;

  var containerEl = document.getElementById('external-events');
  var calendarEl  = document.getElementById('calendar');
var skeletonEl = document.getElementById('calendar-skeleton');
  let rawEvents = []; // store original events
  let currentViewType = 'dayGridMonth'; // default

  // ----------------------------------------------------------------
  // External draggable events (optional – kept from your code)
  // ----------------------------------------------------------------
  if (containerEl) {
    new Draggable(containerEl, {
      itemSelector: '.external-event',
      eventData: function (eventEl) {
        return { title: eventEl.innerText };
      }
    });
  }

  // ----------------------------------------------------------------
  // Calendar init
  // ----------------------------------------------------------------
  var calendar = new Calendar(calendarEl, {
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },

    initialDate: new Date().toISOString().slice(0, 10),
    navLinks: false,
    editable: false,
    droppable: false,
    dayMaxEvents: true,
    slotMinTime: '07:00:00',
    slotDuration: '00:20:00',
    slotLabelInterval: '00:20:00',
    slotEventOverlap: false,
    eventTimeFormat: {
      hour: 'numeric',
      minute: '2-digit',
      meridiem: 'short'
    },

    // ------------------------------------------------------------
    // Fetch events ONCE and store raw version
    // ------------------------------------------------------------
    events: function (fetchInfo, successCallback, failureCallback) {

      // Show skeleton before fetch
      skeletonEl.style.display = 'flex';

      const mode = currentViewType === 'dayGridMonth' ? 'month' : 'detail';

      fetch(`/bookings/json-public?mode=${mode}`)
        .then(res => res.json())
        .then(data => {
          // Hide skeleton after fetch
          skeletonEl.style.display = 'none';
          successCallback(data);
        })
        .catch(err => {
          skeletonEl.style.display = 'none';
          failureCallback(err);
        });
    },



    datesSet: function (info) {
      currentViewType = info.view.type;
      calendar.refetchEvents();
    },

    // ------------------------------------------------------------
    // Custom event rendering
    // ------------------------------------------------------------
    eventContent: function (arg) {
      const count = arg.event.extendedProps.count || 'loading..';
      // MONTH VIEW → summary only
      if (arg.view.type === 'dayGridMonth') {
        return {
          html: `
            <div class="fc-event-custom fc-month-summary">
              <div class="fc-event-title">${arg.event.title}</div>
              <div class="fc-event-count">
                ${count} bookings
              </div>
            </div>
          `
        };
      }

      // WEEK / DAY VIEW → detailed
      const customer    = arg.event.extendedProps.customer || '';
      const phone       = arg.event.extendedProps.phone || '';
      const title       = arg.event.title;
      const slotName    = arg.event.extendedProps.slot || '';
      const isDeposit  = arg.event.extendedProps.is_deposit === true;
      const balance     = arg.event.extendedProps.balance || '';
      
      let paymentHtml = '';
      if (isDeposit) {
        paymentHtml = `<div class="fc-event-customer-name">
                          Deposit | Balance: RM${balance}
                        </div>`;
      } else {
        paymentHtml = `<div class="fc-event-customer-name">
                        Full Payment
                      </div>`;
      }
      


      return {
        html: `
          <div class="fc-event-custom" style="
            display: flex; 
            flex-direction: column; 
            gap: 4px;
            font-size: 11px;
          ">

            <!-- Top row: time + title + slot -->
            <div style="display: flex; justify-content: space-between; font-weight: bold;">
              <span>${arg.timeText}</span>
              ${paymentHtml}
              <span>${title} - ${slotName}</span>
            </div>

            <!-- Bottom row: customer + phone + payment -->
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
              <span>Customer Info: </span>
              <span>${customer} - ${phone}</span>

          </div>
        `
      };

    },
    eventClick: function(info) {
      // Prevent default browser behavior
      info.jsEvent.preventDefault();

      // Get booking code or ID from extendedProps
      const bookingCode = info.event.extendedProps.booking_id; // or id

      if (bookingCode) {
        // Redirect to booking details page
        window.open(`/booking/details/${bookingCode}`, '_blank');
      }
    },
  });

  calendar.render();
});

// ------------------------------------------------------------------
// Helper: group events for MONTH view
// ------------------------------------------------------------------
function groupEventsForMonth(events) {
  const map = {};

  events.forEach(event => {
    const date = event.start.split('T')[0];
    const pkg  = event.title;

    const key = `${date}_${pkg}`;

    if (!map[key]) {
      map[key] = {
        title: pkg,
        start: date,
        allDay: true,
        count: 0,
        backgroundColor: event.backgroundColor,
        borderColor: event.borderColor
      };
    }

    map[key].count++;
  });

  return Object.values(map).map(e => ({
    title: e.title,
    start: e.start,
    allDay: true,
    backgroundColor: e.backgroundColor,
    borderColor: e.borderColor,
    extendedProps: {
      count: e.count
    }
  }));
}


