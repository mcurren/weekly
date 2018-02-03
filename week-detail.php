<?php 

// page meta settings
$page_title = 'This Week (Detail)';
$page_slug  = '/week-detail.php';
$page_lead  = 'Weekly totals/averages from your Harvest account with additional task details.';

require_once( dirname(__FILE__) . '/header.php' ); 

// create project reference array to match ids to names
// project->id => project->name
$projects = array();
$result = $api->getProjects();
if( $result->isSuccess() ) {
  $project_data = $result->data;
	foreach( $project_data as $project ) {
		$projects[$project->id] = $project->name;
	}
}

?>

<h1>Entries for the Week</h1>

<?php 
$range = Harvest_Range::thisWeek( "CST" );
$result = $api->getUserEntries( $user_id, $range, $project_id = null );
if( $result->isSuccess() ) {
  $dayEntries = $result->data;
}

$weekly_total = 0;

date_default_timezone_set($time_zone);
$last_day_of_week = date('z', strtotime($date));

foreach( $dayEntries as $entry ) : 
	$notes = $entry->notes;
	$hours = $entry->hours;
	$date = $entry->get('spent-at');

	$project_id = $entry->get('project-id');

	$weekly_total += $hours;
	?>
	<div class="entry">
		<?php 
		if($projects) 
			echo '<h2>' . $projects[$project_id] . '</h2>'; 
		?>
		<p><?= $notes ?></p>
		<p><?= $hours ?> hours</p>
	</div>
<?php 
endforeach;

$last_day_of_week = date('z', strtotime($date));
?>

<h1>Total Weekly Time</h1>
<p><?= $weekly_total ?> hours</p>

<h1>Daily Average</h1>
<p><?= $weekly_total / date('w', strtotime($date)) ?> hours</p>

<?php require_once( dirname(__FILE__) . '/footer.php' ); ?>