<?php
// Create new page
function custom_menu()
{
  add_menu_page(
    'Cruises Options',
    'Cruises Options',
    'edit_posts',
    'cruise_options',
    'my_admin_page_contents',
    'dashicons-media-spreadsheet'
  );
}
add_action('admin_menu', 'custom_menu');

function my_admin_page_contents()
{
?>
  <!DOCTYPE html>
  <html lang='en'>

  <head>
    <meta charset='utf-8' />

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js'></script>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>

    <script>
      document.addEventListener('DOMContentLoaded', function() {


        var calendarEl = document.getElementById('calendar');
        var feed_url = '<?php echo get_stylesheet_directory_uri() ?>/page-templates/events_data.json';
        var calendar = new FullCalendar.Calendar(calendarEl, {

          themeSystem: 'bootstrap5',
          selectable: true,
          editable: true,
          allDay: true,
          eventSources: [{
            url: feed_url,
            method: 'GET',
            failure: function() {
              alert('There was an error fetching events!');
            },
            color: 'blue',
            textColor: 'white',
          }],

          select: function(info) {

            const start = info.startStr;
            const end = info.endStr;
            const event_id = info.eventId;
            const event_id_str = event_id ? event_id.toString() : '';
            

            jQuery(document).ready(function($) {

              $('#event-modal').find('input[name=evtStart]').val(start);
              $('#event-modal').find('input[name=evntEnd]').val(end);
              $('#event-modal').find('input[name=evntID]').val(event_id_str);

              $('#event-modal').modal('show');

              $("#event-modal").find('form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                  url: ajaxurl,
                  type: 'POST',
                  data: {
                    action: 'save_event_data',
                    formData: $(this).serialize()
                  },
                  success: function(response) {
                    $("#event-modal").modal('hide');
                    $("#calendar").fullCalendar('refetchEvents');
                  }
                });

              });

            });

          },
          eventClick: function(info) {
            jQuery('#eventTitle').text(info.event.title);
            jQuery('#eventId').text(info.event.id);
            jQuery('#EvntstartDate').text(info.event.startStr);
            jQuery('#EventendDate').text(info.event.endStr);
            // Get the product ID from the selected event
            var productId = info.event.extendedProps.productId;
            // Set the product ID into the modal
            jQuery('#eventProductId').text(productId);
            jQuery('#deleteEventBtn').on('click', function() {
              info.event.remove();
              jQuery('#eventModal').modal('hide');
            });
            jQuery('#eventModal').modal('show');
            
          },
        });

        calendar.render();

      });
    </script>

  </head>

  <body>

    <h1 style="margin:50px auto!important;">Cruises Settings</h1>

    <div id='calendar'></div>

    <div class="modal" id="event-modal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex flex-row align-items-baseline">
              <?php
              $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
              );
              $products = new WP_Query($args);
              ?>
              <select class="me-2" id="productsSelect" name="productsSelect">
                <?php
                if ($products->have_posts()) {
                  while ($products->have_posts()) {
                    $products->the_post();
                ?>
                    <option id="<?php echo get_the_id(); ?>">
                      <?php echo get_the_title(); ?>
                    </option>
                <?php }
                }
                wp_reset_postdata();
                ?>
              </select>
            </div>
            <form name="save-event" method="post">
              <div class="form-group">
                <label>Event start</label>
                <input type="text" id="evtStart" name="evtStart" class="form-control col-xs-3" />
              </div>
              <div class="form-group">
                <label>Event end</label>
                <input type="text" id="evntEnd" name="evntEnd" class="form-control col-xs-3" />
              </div>
              <div class="form-group">
                <label>Event ID</label>
                <input type="text" id="evntID" name="evntID" class="form-control col-xs-3" disabled />
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" onClick="submitForm()" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p id="eventProductId"></p>
            <p id="eventTitle"></p>
            <p id="eventId"></p>
            <p id="EvntstartDate"></p>
            <p id="EventendDate"></p>
       
          </div>
          <div class="modal-footer">
            <button type="button" onclick="deleteButton()" class="btn btn-danger" id="deleteEventBtn">Delete Event</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      function submitForm(id) {
        var productId = jQuery('#productsSelect option:selected').attr('id');
        var title = jQuery('#productsSelect option:selected').val();
        var start = jQuery('#evtStart').val();
        var end = jQuery('#evntEnd').val();

        var idField = jQuery('#evntID');
        var eventId = idField.val() || Math.floor(Math.random() * 100) + 1;

        idField.val(eventId);

        var data = {
          id: eventId,
          productId: productId,
          title: title,
          start: start,
          end: end
        };

        var dataToSend = {
          action: 'save_event_data',
          data: encodeURIComponent(JSON.stringify(data))
        };

        jQuery.ajax({
          type: 'POST',
          url: ajaxurl,
          data: dataToSend,
          success: function(response) {
            location.reload();         
          },
          error: function(error) {
            console.error('Error writing to JSON file:', error);
          }
        });
      }
</script>
<script>
      // Import jQuery
       <?php wp_enqueue_script('jquery'); ?>

      function deleteButton(id,productId,title,start,end) {
        console.log("ok");

        var id = jQuery("#eventId").text();
        var productId = jQuery("#eventProductId").text();
        var title = jQuery("#eventTitle").text();
        var start = jQuery("#EvntstartDate").text();
        var end = jQuery("#EventendDate").text();

        var data = {
          id: id,
          productId: productId,
          title: title,
          start: start,
          end: end
        };

        var dataToSend = {
          action: 'delete_event_data',
          data: encodeURIComponent(JSON.stringify(data))
        };

        jQuery.ajax({
          type: 'POST',
          url: ajaxurl,
          data: dataToSend,
          success: function(response) {
            location.reload();          },
          error: function(error) {
            console.error('Error writing to JSON file:', error);
          }
        });
      }
    </script>

  </body>

  </html>
<?php
}
?>