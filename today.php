<?php

// page meta settings
$page_title = 'Today';
$page_slug  = '/today.php';
$page_lead  = 'Today\'s time entries from your Harvest account with task details.';

// next/prev nav
$days_ago = $_GET["ago"];
if( $days_ago ) :
  $prev_url = $page_slug . '?ago=' . ( $days_ago + 1 );
  $next_url = ( $days_ago > 1 ) ? $page_slug . '?ago=' . ( $days_ago - 1 ) : $page_slug;
else :
  $prev_url = $page_slug . '?ago=1';
  $next_url = null;
endif;

// date stuff
// date_default_timezone_set($time_zone);
$today = ( $days_ago ) ? strtotime("-$days_ago day") : strtotime('now');
$day_of_year = date('z', $today);
$year = date('Y', $today);

require_once( dirname(__FILE__) . '/header.php' ); ?>

<div class="container py-5">
  <div class="row">
    <header class="col-sm">
      <h1><?= $page_title ?></h1>
      <?php 
      if( $page_lead ) 
        echo "<p>$page_lead</p>"; 
      if( $dashboard_url ) 
        echo "<p><a href='$dashboard_url' target='_blank' class='btn btn-lg btn-info'>Harvest Dashboard</a></p>"; 
      ?>
    </header><!-- .col -->
  </div><!-- .row -->
</div><!-- .container -->

<div class="bg-light border-bottom border-top py-5">
  <div class="container">
    <div class="row">
      <div class="col-sm">

        <nav class="btn-group mb-3" role="group" aria-label="Next/Previous Navigation">
          <a class="btn btn-link" href="<?= $prev_url ?>" title="Day before this">&larr;</a>
          <span class="btn"><h5 class="m-0"><?php echo date('D, M j', $today ); ?></h5></span>
          <?php if( $next_url ) : ?>
            <a class="btn btn-link" href="<?= $next_url ?>" Title="Day after this">&rarr;</a>
          <?php endif; ?>
        </nav>

        <?php 
        // get harvest data
        $result = $api->getDailyActivity( $day_of_year, $year );
        if( $result->isSuccess() ) :
        	$total_hours = 0; ?>
          <div class="d-flex flex-wrap d-flex justify-content-between align-items-stretch">
            <?php foreach( $result->data->dayEntries as $entry ) : ?>
              <!-- <div class="col-6"> -->
              <section class="card mb-4">
                <header class="card-header">
                  <h2 class="text-dark h3"><?php echo $entry->get( 'project' ); ?></h2>
                  <p class="text-dark m-0"><?php echo $entry->get( 'client' ); ?></p>
                </header>
                <div class="card-body">
                  <p class="text-dark">
                    <span class="display-4 text-primary"><?php echo $entry->get( 'hours' ); ?></span> 
                    <small>hrs</small>
                  </p>
                  <p class="text-muted font-weight-light m-0">
                    <?php echo $entry->get( 'notes' ); ?>
                  </p>
                </div>
              </section>
              <!-- </div> -->

              <?php 
              // add $entry hours to daily total
              $total_hours += $entry->get( 'hours' );

            endforeach; ?>
          </div>
          <section class="mb-4">
            <h1 class="">Daily Total</h1>
            <p class="">
              <span class="display-4 text-primary"><?= $total_hours ?></span> <span>hrs</span>
            </p>
          </section>

        <?php endif; ?>

      </div><!-- .col -->
    </div><!-- .row -->
  </div><!-- .container -->
</div><!-- .bg-light -->

<?php require_once( dirname(__FILE__) . '/footer.php' ); ?>