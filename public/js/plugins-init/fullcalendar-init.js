document.addEventListener('DOMContentLoaded', function () {
  var Calendar = FullCalendar.Calendar;
  var Draggable = FullCalendar.Draggable;

  var containerEl = document.getElementById('external-events');
  var calendarEl  = document.getElementById('calendar');
  var skeletonEl = document.getElementById('calendar-skeleton');
  let currentViewType = 'dayGridMonth';

  // ----------------------------------------------------------------
  // External draggable events (optional)
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
  var calendar = new FullCalendar.Calendar(calendarEl, {
    googleCalendarApiKey: 'AIzaSyBmc1F4_InL0ulB_Jdl51m6iHchXTj_4r4',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    initialDate: new Date().toISOString().slice(0,10),
    navLinks: false,
    editable: false,
    droppable: false,
    dayMaxEvents: false,
    slotMinTime: '07:00:00',
    slotDuration: '00:15:00',
    slotLabelInterval: '00:15:00',
    slotEventOverlap: false,
    eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },

    // ------------------------
    // Use eventSources ONLY
    // ------------------------
    eventSources: [
      // Booking events
      {
        events: function(fetchInfo, successCallback, failureCallback) {
          skeletonEl.style.display = 'flex';
          let mode = currentViewType === 'dayGridMonth' ? 'month' : 'detail';
          if (currentViewType === 'timeGridWeek') mode = 'week';

          fetch(`/organizer/business/bookings/json-public?mode=${mode}`)
            .then(res => res.json())
            .then(data => {
              skeletonEl.style.display = 'none';
              successCallback(data);
            })
            .catch(err => {
              skeletonEl.style.display = 'none';
              failureCallback(err);
            });
        }
      },

      // Malaysia holidays
      {
        googleCalendarId: 'en.malaysia#holiday@group.v.calendar.google.com',
        className: 'fc-holiday',
        allDay: true,
        display: 'auto',
        color: '#487af7ff',
        editable: false,
        overlap: false,
        extendedProps: {
          isHoliday: true
        }
      }

    ],


    dateClick: function(info) {
      if (info.view.type === 'dayGridMonth') {
        calendar.changeView('timeGridDay', info.dateStr);
      }
    },

    moreLinkClick: function(info) {
      calendar.changeView('timeGridDay', info.date);
      return 'none';
    },

    datesSet: function(info) {
      currentViewType = info.view.type;
      calendar.refetchEvents();
    },

    eventContent: function(arg) {

      // console.log('Event Data:', arg.event); 
      // Ignore holiday events in month view
      


      const count = arg.event.extendedProps.count || 'loading..';

      // Month view bookings
      if (arg.view.type === 'dayGridMonth') {

        const wrapper = document.createElement('div');
        wrapper.classList.add('fc-event-custom', 'fc-month-summary');

        if (arg.event.extendedProps.description) {
          const shortTitle = arg.event.title.split('(')[0].trim();

          const holidayDiv = document.createElement('div');
          holidayDiv.classList.add('fc-holiday');
          holidayDiv.innerText = shortTitle;

          wrapper.appendChild(holidayDiv);

        } else {
          const titleDiv = document.createElement('div');
          titleDiv.classList.add('fc-event-title');
          titleDiv.innerText = arg.event.title;

          const countDiv = document.createElement('div');
          countDiv.classList.add('fc-event-count');
          countDiv.innerText = (arg.event.extendedProps.count || '0') + ' bookings';

          wrapper.appendChild(titleDiv);
          wrapper.appendChild(countDiv);
        }

        return { domNodes: [wrapper] };
      }


      // WEEK VIEW
      if (arg.view.type === 'timeGridWeek') {
        if (arg.event.allDay) return { html: '' };

        const start = arg.event.start;
        const end = arg.event.end;
        const slot = arg.event.extendedProps?.slot ?? '';
        const formatTime = d => d ? d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
        return {
          html: `
            <div class="fc-week-event">
              <div class="fc-week-time">${formatTime(start)} - ${formatTime(end)}</div>
              <div class="fc-week-title">${arg.event.title}</div>
              <div class="fc-week-slot">${slot}</div>
            </div>
          `
        };
      }

      // DAY VIEW
      const customer = arg.event.extendedProps.customer || '';
      const phone = arg.event.extendedProps.phone || '';
      const title = arg.event.title;
      const slotName = arg.event.extendedProps.slot || '';
      const isDeposit = arg.event.extendedProps.is_deposit === true;
      const balance = arg.event.extendedProps.balance || '';
      const deposit = arg.event.extendedProps.deposit || '';

      let paymentHtml = isDeposit
        ? `Deposit RM${deposit} | Balance: RM${balance}`
        : `Full Payment`;

      if (!arg.event.extendedProps.description) {
        return {
          html: `
            <div class="fc-event-custom" style="display:flex; flex-direction:column; gap:4px; font-size:11px;">
              <div style="display:flex; justify-content:space-between; font-weight:bold;">
                <span>Booking Info: </span>
                <span>Customer Info: </span>
              </div>
              <div style="display:flex; justify-content:space-between; flex-wrap:wrap;">
                <span>${arg.timeText}</span>
                <span>${customer}</span>
              </div>
              <div style="display:flex; justify-content:space-between; flex-wrap:wrap;">
                <span>${title}</span>
                <span>${phone}</span>
              </div>
              <div style="display:flex; justify-content:space-between; flex-wrap:wrap;">
                <span>${slotName}</span>
              </div>
              <div style="display:flex; justify-content:space-between; flex-wrap:wrap;">
                <span>${paymentHtml}</span>
              </div>
            </div>
          `
        };
      }
    },

    eventClick: function(info) {
      info.jsEvent.preventDefault();

      if (info.view.type === 'dayGridMonth') {

        const date = info.event.startStr;
        const packageName = info.event.title;

        window.open(
          `/organizer/business/bookings?date=${date}&event_search=${encodeURIComponent(packageName)}`,
          '_blank'
        );

        return;
      }

      const bookingCode = info.event.extendedProps.code;

      if (bookingCode) {
        window.open(`/organizer/business/bookings?search=${bookingCode}`, '_blank');
      }
    }


  });

  calendar.render();

});
