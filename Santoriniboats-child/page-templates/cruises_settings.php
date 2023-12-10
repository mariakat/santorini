<?php
//Create new page
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
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js'></script>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {

          themeSystem: 'bootstrap5',
          selectable: true,
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          defaultView: 'month',
          editable: true,
          eventSources: [{
            events: [{
              title: "event3",
              start: "2019-03-09T12:30:00"
            }],
            color: "black", // an option!
            textColor: "yellow" // an option!
          }],
          select: function(start, end, jsEvent, view) {
            // set values in inputs
            jQuery('#event-modal').find('input[name=evtStart]').val(
              start.format('YYYY-MM-DD HH:mm:ss')
            );
            jQuery('#event-modal').find('input[name=evtEnd]').val(
              end.format('YYYY-MM-DD HH:mm:ss')
            );

            // show modal dialog
            jQuery('#event-modal').modal('show');

            /*
            bind event submit. Will perform a ajax call in order to save the event to the database.
            When save is successful, close modal dialog and refresh fullcalendar.
            */
            /*
            $("#event-modal").find('form').on('submit', function() {
                $.ajax({
                    url: 'yourFileUrl.php',
                    data: $("#event-modal").serialize(),
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        // if saved, close modal
                        $("#event-modal").modal('hide');
                        
                        // refetch event source, so event will be showen in calendar
                        $("#calendar").fullCalendar( 'refetchEvents' );
                    }
                });
            });*/
          },
          selectHelper: true,
          selectable: true,
          snapDuration: '00:10:00'
        });

        calendar.render();
      });
    </script>

  </head>

  <body>
    <h1 style="margin:50px auto;!important">Cruises Settings</h1>
    <div id='calendar'>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">
              <script>
                info.dateStr;
              </script>
            </h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="checkbox_products">
              <?php
              $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
              );

              $products = new WP_Query($args);

              if ($products->have_posts()) {
                while ($products->have_posts()) {
                  $products->the_post();
              ?>
                  <div class="d-flex flex-row align-items-baseline">

                    <input type="checkbox" class="me-2" id="<?php the_ID(); ?>" name="<?php the_ID(); ?>">
                    <label for="<?php the_ID(); ?>"><?php echo get_the_title(); ?></label>
                  </div>


              <?php }
              }

              wp_reset_postdata();
              ?>

              <form name="save-event" method="post">
                <div class="form-group">
                  <label>Title</label>
                  <input type="text" name="title" class="form-control" />
                </div>
                <div class="form-group">
                  <label>Event start</label>
                  <input type="text" name="evtStart" class="form-control col-xs-3" />
                </div>
                <div class="form-group">
                  <label>Event end</label>
                  <input type="text" name="evtEnd" class="form-control col-xs-3" />
                </div>
              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input id="addevent" type="submit" class="btn btn-secondary" value="Disable Cruises">
          </div>
          </form>
        </div>

      </div>
    </div>
    </div>
  </body>

  </html>
<?php
}
