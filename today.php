<?php

// page meta settings
$page_title = 'Today';
$page_slug  = '/today.php';
$page_lead  = 'Today\'s time entries from your Harvest account with task details.';

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

        <?php 
        date_default_timezone_set($time_zone);
        $day_of_year = date('z');
        $year = date('Y');

        $result = $api->getDailyActivity( $day_of_year, $year );
        if( $result->isSuccess() ) {
        	$total_hours = 0;
          echo '<h1>' . $result->data->forDay . '</h1><hr>';
          foreach( $result->data->dayEntries as $entry ) {
            echo '<section class="card">';
            echo '<header class="card-header">';
            echo '<h2 class="text-dark">' . $entry->get( 'project' ) . '</h2>';
            echo '<p class="text-dark m-0">' . $entry->get( 'client' ) . '</p>';
            echo '</header>';
            echo '<div class="card-body">';
            echo '<p class="text-dark"><span class="display-4">' . $entry->get( 'hours' ) . '</span> <small>hrs</small></p>';
            echo '<p class="text-muted m-0"><span class="font-weight-bold">Notes:</span> ' . $entry->get( 'notes' ) . '</p>';
            echo '</div>';
            echo '</section>';
            $total_hours += $entry->get( 'hours' );
          }
          echo '<hr><h1>Daily Total</h1>';
          echo '<p><span class="display-4 text-primary">' . $total_hours . '</span> <span>hrs</span></p>';
        } ?>

      </div><!-- .col -->
    </div><!-- .row -->
  </div><!-- .container -->
</div><!-- .bg-light -->

<?php require_once( dirname(__FILE__) . '/footer.php' ); ?>