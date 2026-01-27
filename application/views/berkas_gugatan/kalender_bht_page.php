<style>
  #calendar {
    margin-top: 1rem;
  }

  .fc .fc-daygrid-day-frame {
    min-height: 130px;
    /* naikkan dari 110 → 150 */
  }

  /* Supaya event tetap rapi */
  .fc .fc-daygrid-day-events {
    margin-top: 4px;
  }

  /* Header hari sejajar */
  .fc .fc-col-header-cell {
    padding: 6px 0;
  }

  /* Hilangkan konflik card */
  .fc {
    --fc-border-color: #dee2e6;
  }
</style>

<div class="container-fluid">
  <?= $breadcrumb ?>
  <div class="card">
    <div class="card-header fw-semibold">
      Kalender BHT
    </div>
    <div class="card-body">
      <div id="calendar"></div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      themeSystem: 'bootstrap5',
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      height: 'auto',
      aspectRatio: 1.8,
      lazyFetching: true,
      events: function(info, successCallback, failureCallback) {
        const start = info.startStr.split('T')[0];
        const end = info.endStr.split('T')[0];

        fetch(`/kalender_bht/events?start=${start}&end=${end}`, {
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
          })
          .then(response => response.json())
          .then(data => {
            successCallback(data);
          })
          .catch(error => {
            failureCallback(error);
          });
      },
      loading: function(isLoading) {
        if (isLoading) {
          swalLoadingPopUp()
        } else {
          swalLoadingClose()
        }
      },
      eventClick: function(info) {
        info.jsEvent.preventDefault(); // don't let the browser navigate

        if (info.event.url) {
          window.open(info.event.url, 'Snopzer', 'left=20,top=20,width=1200,height=800,toolbar=1,resizable=0');
        }
      }
    });

    calendar.render();
  });
</script>