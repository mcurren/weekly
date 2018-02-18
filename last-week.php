<?php 

// page meta settings
$page_title = 'Last Week';
$page_slug  = '/last-week.php';
$page_lead  = 'Daily tallies and Weekly totals/averages from your Harvest account.';

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

<!-- <code> -->
<?php 
// date vars
// date_default_timezone_set($time_zone);
$today = strtotime('last saturday');
$weekdays_so_far = date('w', $today);
$day_of_year = date('z', $today);
$year = date('Y', time($today));

// create day of year array for Harvest
$days_in_week = array();
for ($x = 0; $x < $weekdays_so_far; $x++) {
	$this_day_of_year = $day_of_year-$x;
	$days_in_week[] = $this_day_of_year;
	// echo '<pre>This Day: ' . $this_day_of_year . '<br>x: ' . $x . '</pre>';
}
$days_in_week = array_reverse($days_in_week);
?>

<?php 
	// echo '<pre>';
	// echo 'Timezone: ' . $time_zone . '<br>';
	// echo 'Today: ' . date('D', $today) . ' (' . date('z', $today) . ')<br>';
	// $today_harvest_format = date('D, d M Y', $today);
	// echo strtotime($today_harvest_format) . ' | ' . $today . '<br>';
	// echo 'Year: ' . $year . '<br>';
	// echo 'Weekdays so far: ';
	// var_dump($days_in_week);
	// echo '</pre>';

	// echo '<pre>';
	// foreach( $days_in_week as $day ) {
	// 	echo $day . '<br>';
	// }
	// echo '</pre>';
?>
<!-- </code> -->

<div class="bg-light border-bottom border-top py-5">
	<div class="container">
		<div class="row row-eq-height">

			<?php 
			$weekly_total = 0;
			$days_in_week_total = 0;
			foreach( $days_in_week as $day ) :

				$result = $api->getDailyActivity( $day, $year );
				if( $result->isSuccess() ) :
					$entry_timestamp = strtotime($result->data->forDay);
					$entry_label = date( 'D', $entry_timestamp );
					if( !in_array( $entry_label, array('Sat', 'Sun') ) ) :

						// tally weekly/daily activity
						$days_in_week_total++;
						$daily_total = 0;
					  foreach( $result->data->dayEntries as $entry ) 
					  	$daily_total += $entry->get( 'hours' );

					  // calculate class based on $daily_target
					  $add_class = ( $daily_total >= $daily_target ) ? 'success' : 'warning';
						?>
						<section class="col-sm my-2">
							<div class="text-center card">
								<header class="card-header">
								  <h1 class="text-uppercase text-dark m-0 h3" data-date="<?= $result->data->forDay ?>" data-day="<?= $day ?>">
								  	<?= $entry_label ?> 
								  	<span class="date d-inline-block text-center ml-2 align-top">
								  		<span class="date__month d-block"><?php echo date( 'M', strtotime($result->data->forDay) ); ?></span>
								  		<span class="date__day"><?php echo date( 'j', strtotime($result->data->forDay) ); ?></span>
							  		</span>
								  </h1>
								</header>
							  <div class="card-body">
							  	<p class="">
								  	<span class="display-4 text-<?= $add_class ?>"><?= $daily_total ?></span> 
							  		<small class="text-secondary d-none d-lg-inline">hrs</small>
							  	</p>
						  	</div>
						  </div>
					  </section>
						<?php

					endif; // is weekday
				endif; // $result->isSuccess

				// tally weekly total
				$weekly_total += $daily_total;
			endforeach;
			?>

		</div><!-- .row -->
	</div><!-- .container -->
</div><!-- .bg-light -->

<div class="container py-5">
	<div class="row d-flex justify-content-around">

	  <section class="col-sm col-md-5 col-lg-4">
	  	<div class="card text-center my-2">
	  		<header class="card-header">
					<h1 class="text-uppercase text-dark m-0 h2">Total</h1>
				</header>
				<div class="card-body">
					<span class="display-3 text-primary"><?= $weekly_total ?></span> 
					<span class="d-none d-lg-inline text-muted">hrs</span>
				</div>
			</div>
		</section>

	  <section class="col-sm col-md-5 col-lg-4">
	  	<div class="card text-center my-2">
	  		<header class="card-header">
					<h1 class="text-uppercase text-dark m-0 h2">Average</h1>
				</header>
				<div class="card-body">
					<?php 
					$weekly_average = round( ($weekly_total / $days_in_week_total), 2 );
					$add_class = ( $weekly_average >= $daily_target ) ? ' text-success' : ' text-warning';
					?>
					<span class="display-3 <?= $add_class ?>"><?= $weekly_average ?></span> 
					<span class="d-none d-lg-inline text-muted">hrs</span>
				</div>
			</div>
		</section>

	</div><!-- .row -->
</div><!-- .container -->

<div class="container pb-5">
	<div class="row">
		<div class="col-sm text-center">
			<?php 
			// calculate alert based on $daily_target
			$daily_target_third = $daily_target / 3;
			if( $weekly_average <= $daily_target_third ) {
				$alert_class = 'danger';
				$alert_text  = 'Work harder!';
			} elseif( $weekly_average <= ($daily_target_third * 2) ) { 
				$alert_class = 'warning';
				$alert_text  = 'Keep working, you can do better!';
			} else {
				$alert_class = 'success';
				$alert_text  = 'Good work, keep it up!';
			} ?>
			<div class="alert alert-<?= $alert_class ?>" role="alert">
			  <h5 class="alert-heading m-0"><?= $alert_text ?></h5>
			</div>
		</div><!-- .col -->
	</div><!-- .row -->
</div><!-- .container -->

<?php require_once( dirname(__FILE__) . '/footer.php' ); ?>