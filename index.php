<?php 

// page meta settings
$page_title = 'This Week';
$page_slug  = '/';
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

<div class="bg-light border-bottom border-top py-5">
	<div class="container">
		<div class="row row-eq-height">

			<?php 
			// date vars
			date_default_timezone_set($time_zone);
			$today = date();
			$weekdays_so_far = date('w', strtotime($today));
			$day_of_year = date('z');
			$year = date('Y');

			// create day of year array for Harvest
			$days_in_week = array();
			for ($x = 0; $x <= $weekdays_so_far; $x++) {
				$this_day_of_year = $day_of_year-$x;
				$days_in_week[] = $this_day_of_year;
			}
			$days_in_week = array_reverse($days_in_week);

			$weekly_total = 0;

			foreach( $days_in_week as $day ) :
				$result = $api->getDailyActivity( $day, $year );
				if( $result->isSuccess() ) :
					$entry_label = date( 'D', strtotime($result->data->forDay) );
					// tally daily activity
					$daily_total = 0;
				  foreach( $result->data->dayEntries as $entry ) $daily_total += $entry->get( 'hours' );
				  $add_class = ( $daily_total >= $daily_target ) ? ' text-success' : 'text-warning';
					?>
					<section class="col-sm my-2">
						<div class="text-center card">
							<header class="card-header">
							  <h1 class="text-uppercase text-dark m-0 h3" data-day="<?= $result->data->forDay ?>"><?= $entry_label ?></h1>
							</header>
						  <div class="card-body">
						  	<p class="">
							  	<span class="display-4 <?= $add_class ?>"><?= $daily_total ?></span> 
						  		<small class="text-secondary d-none d-lg-inline">hrs</small>
						  	</p>
					  	</div>
					  </div>
				  </section>
				<?php
				endif;

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
					$weekly_average = $weekly_total / count($days_in_week);
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
			<?php if( $weekly_average >= ($daily_target / 2) ) : ?>
				<div class="alert alert-warning" role="alert">
				  <h5 class="alert-heading m-0">Keep at it, you can do better!</h5>
				</div>
			<?php elseif( $weekly_average >= $daily_target ) : ?>
				<div class="alert alert-success" role="alert">
				  <h5 class="alert-heading m-0">Keep up the good work!</h5>
				</div>
			<?php else : ?>
				<div class="alert alert-danger" role="alert">
				  <h5 class="alert-heading m-0">Work harder!</h5>
				</div>
			<?php endif; ?>
		</div><!-- .col -->
	</div><!-- .row -->
</div><!-- .container -->

<?php require_once( dirname(__FILE__) . '/footer.php' ); ?>