<div class="row">
  <div class="col-md-2"></div>
  <div class="col-md-8">
    <div class="card">
    <div class="card-body">
      <div id="calendar"></div>
        </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    // page is now ready, initialize the calendar...
    $('#calendar').fullCalendar({
        // put your options and callbacks here
        dayClick: function() {
          alert('a day has been clicked!');
        },
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listMonth'
        },
        events: {
        url: "<?php echo site_url(); ?>/resolucion_conflictos/Consultar_fechas/calendario",
        cache: true
    },
        defaultView: 'month',
        defaultDate: date
    })

});
</script>
