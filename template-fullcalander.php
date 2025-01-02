<?php
// Template Name: Full calendar

get_header();
?>

<div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h5 align="center">How to create a dynamic event calendar in HTML and PHP</h5>
        <div id="calendar"></div>
      </div>
    </div>
  </div>
<!-- Start popup dialog box -->
  <div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Add New Event</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="img-container">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="event_name">Event name</label>
                  <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Enter your event name">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="event_start_date">Event start</label>
                  <input type="date" name="event_start_date" id="event_start_date" class="form-control onlydatepicker" placeholder="Event start date">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="event_end_date">Event end</label>
                  <input type="date" name="event_end_date" id="event_end_date" class="form-control" placeholder="Event end date">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="save_event()">Save Event</button>
        </div>
      </div>
    </div>
  </div>
  <!-- End popup dialog box -->

  <br>
  <center>Developed by <a href="https://shinerweb.com/">Shinerweb</a></center>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          selectable: true
        });
        calendar.render();
    });
    
  $(document).ready(function () {
    display_events();
  });

/*
  function display_events() {
    var events = new Array();
    $.ajax({
      url: 'display_event.php',
      dataType: 'json',
      success: function (response) {
        var result = response.data;
        $.each(result, function (i, item) {
          events.push({
            event_id: result[i].event_id,
            title: result[i].title,
            start: result[i].start,
            end: result[i].end,
            color: result[i].color,
            url: result[i].url
          });
        });

        var calendar = $('#calendar').fullCalendar({
          defaultView: 'month',
          timeZone: 'local',
          editable: true,
          selectable: true,
          selectHelper: true,
          select: function (start, end) {
            $('#event_start_date').val(moment(start).format('YYYY-MM-DD'));
            $('#event_end_date').val(moment(end).format('YYYY-MM-DD'));
            $('#event_entry_modal').modal('show');
          },
          events: events,
          eventRender: function (event, element, view) {
            element.bind('click', function () {
              alert(event.event_id);
            });
          }
        });
      },
      error: function (xhr, status) {
        alert(response.msg);
      }
    });
  }
*/

/*
function display_events() {
    var events = new Array();
    $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            'action': 'get_events' // AJAX action to retrieve events
        },
        dataType: 'json',
        success: function (response) {
            var result = response.data;
            $.each(result, function (i, item) {
            
                events.push({
                    // event_id: result[i].event_id,
                    title: result[i].event_name,
                    start: result[i].event_start_date,
                    end: result[i].event_end_date,
                    // color: result[i].color,
                    // url: result[i].url
                });
            });

            var calendar = $('#calendar').fullCalendar({
                defaultView: 'month',
                timeZone: 'local',
                editable: true,
                selectable: true,
                selectHelper: true,
                select: function (start, end) {
                    $('#event_start_date').val(moment(start).format('YYYY-MM-DD'));
                    $('#event_end_date').val(moment(end).format('YYYY-MM-DD'));
                    $('#event_entry_modal').modal('show');
                },
                events: events,
                eventRender: function (event, element, view) {
                    element.bind('click', function () {
                        // alert(event.event_id);
                    });
                }
            });
        },
        error: function (xhr, status) {
            alert('Failed to retrieve events.');
        }
    });
}
*/


function display_events() {
    var events = [];
    var events_data = <?php echo json_encode(get_option('events_data', array())); ?>;
    
    // Check if events_data contains data
    if (Object.keys(events_data).length > 0) {
        $.each(events_data, function (eventId, eventData) {
            events.push({
                eventId: eventId,
                title: eventData.event_name,
                start: eventData.event_start_date,
                end: eventData.event_end_date,
                // Add any other properties you need for events
            });
        });
    }
    
    var calendar = $('#calendar').fullCalendar({
        defaultView: 'month',
        timeZone: 'local',
        editable: true,
        selectable: true,
        selectHelper: true,
        select: function (start, end) {
            $('#event_start_date').val(moment(start).format('YYYY-MM-DD'));
            $('#event_end_date').val(moment(end).format('YYYY-MM-DD'));
            $('#event_entry_modal').modal('show');
        },
        events: events,
        eventRender: function (event, element, view) {
            element.bind('click', function () {
                // Handle event click if needed
                // alert(event.eventId);
                var checkstr =  confirm('are you sure you want to delete this?');
                if(checkstr == true){
                    delete_event(event.eventId);
                }else{
                    return false;
                }
            });
        }
    });
}



/*
  function save_event() {
    var event_name = $("#event_name").val();
    var event_start_date = $("#event_start_date").val();
    var event_end_date = $("#event_end_date").val();
    if (event_name == "" || event_start_date == "" || event_end_date == "") {
      alert("Please enter all required details.");
      return false;
    }
    
    $.ajax({
      url: '<?php echo admin_url('admin-ajax.php'); ?>',
      type: "POST",
      data: {
        action : 'save_events',
        event_name: event_name,
        event_start_date: event_start_date,
        event_end_date: event_end_date
      },
      success: function (response) {
          var myArray = JSON.parse(response);
          console.log(myArray.status);
        $('#event_entry_modal').modal('hide');
        if (myArray.status == true) {
          alert(myArray.msg);
          location.reload();
        } else {
          alert(myArray.msg);
        }
      }
    });
    return false;
    
  }
*/

function save_event() {
    var event_name = $("#event_name").val();
    var event_start_date = $("#event_start_date").val();
    var event_end_date = $("#event_end_date").val();
    
    if (event_name === "" || event_start_date === "" || event_end_date === "") {
        alert("Please enter all required details.");
        return false;
    }
    
    $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            'action': 'save_events_data', // AJAX action to save event data
            'event_name': event_name,
            'event_start_date': event_start_date,
            'event_end_date': event_end_date
        },
        // dataType: 'json',
        success: function (response) {
            console.log('response =>'+response);
            var myArray = JSON.parse(response);
            console.log(myArray);
            $('#event_entry_modal').modal('hide');
            if (myArray.status === true) {
                alert(myArray.msg);
                location.reload();
            } else {
                alert(myArray.msg);
            }
        },
        error: function (xhr, status) {
            console.log('ajax error = ' + xhr.statusText);
            alert('Failed to save event data.');
        }
    });
    return false;
}


function delete_event(eID) {
    
    var event_id = eID;
    $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            'action': 'delete_events_data', // AJAX action to save event data
            'event_id' : event_id
        },
        // dataType: 'json',
        success: function (response) {
            console.log('response =>'+response);
            var myArray = JSON.parse(response);
            console.log(myArray);
            $('#event_entry_modal').modal('hide');
            if (myArray.status === true) {
                alert(myArray.msg);
                location.reload();
            } else {
                alert(myArray.msg);
            }
        },
        error: function (xhr, status) {
            console.log('ajax error = ' + xhr.statusText);
            alert('Failed to save event data.');
        }
    });
}

</script>
<?php
get_footer();