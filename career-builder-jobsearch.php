<?php
/*
Plugin Name: career builder job search 
Plugin URI: http://www.phpmentors.in/career_builder_jobsearch
Description: A  plugin that is used to search and fetch jobs from career builder api..
Version: 1.2
Author: ashwini singh
Author URI: http://www.phpmentors.in
License: GPL2
*/
error_reporting(0);
/* add action  */
add_action( 'widgets_init', 'career_builder_jobsearch_fun' );
function career_builder_jobsearch_fun() 
{
register_widget( 'career_builder_jobsearch' );
}
class career_builder_jobsearch extends WP_Widget {

	// constructor
	function career_builder_jobsearch() {
		/* Widget settings. */
		$widget_ops = array( 'description' => __('A  plugin that is used to search and fetch jobs from career builder api.') );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'career_builder_jobsearh' );

		/* Create the widget. */
		$this->WP_Widget( 'career_builder_jobsearh', __('career builder jobsearh', 'career builder jobsearh'), $widget_ops, $control_ops );
	}

	// widget form creation
	function form($instance) {	
?>

		<!-- Widget cbj_title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'cbj_title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'cbj_title' ); ?>" name="<?php echo $this->get_field_name( 'cbj_title' ); ?>" value="<?php echo $instance['cbj_title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cbj_DeveloperKey' ); ?>"><?php _e('Developer Key:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'cbj_DeveloperKey' ); ?>" name="<?php echo $this->get_field_name( 'cbj_DeveloperKey' ); ?>" value="<?php echo $instance['cbj_DeveloperKey']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cbj_keywords' ); ?>"><?php _e('Keywords:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'cbj_keywords' ); ?>" name="<?php echo $this->get_field_name( 'cbj_keywords' ); ?>" value="<?php echo $instance['cbj_keywords']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cbj_CountryCode' ); ?>"><?php _e('Country Code:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'cbj_CountryCode' ); ?>" name="<?php echo $this->get_field_name( 'cbj_CountryCode' ); ?>" value="<?php echo $instance['cbj_CountryCode']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cbj_PerPage' ); ?>"><?php _e('No. of jobs:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'cbj_PerPage' ); ?>" name="<?php echo $this->get_field_name( 'cbj_PerPage' ); ?>" value="<?php echo $instance['cbj_PerPage']; ?>" style="width:100%;" />
		</p>
			<p>
			<label for="<?php echo $this->get_field_id( 'cbj_daysBackToLook' ); ?>"><?php _e('number of days back to look(1-30):', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'cbj_daysBackToLook' ); ?>" name="<?php echo $this->get_field_name( 'cbj_daysBackToLook' ); ?>" value="<?php echo $instance['cbj_daysBackToLook']; ?>" style="width:100%;" />
		</p>
	<?php }

	// widget update
	function update($new_instance, $old_instance) {
	
		$instance = $old_instance;

		$instance['cbj_title'] = strip_tags( $new_instance['cbj_title'] );
		$instance['cbj_DeveloperKey'] = strip_tags( $new_instance['cbj_DeveloperKey'] );
		$instance['cbj_keywords'] = strip_tags( $new_instance['cbj_keywords'] );
		$instance['cbj_CountryCode'] = strip_tags( $new_instance['cbj_CountryCode'] );
		$instance['cbj_PerPage'] = strip_tags( $new_instance['cbj_PerPage'] );
		$instance['cbj_daysBackToLook'] = strip_tags( $new_instance['cbj_daysBackToLook'] );
		return $instance;
	}

	// widget display
	function widget($args, $instance) {
	
		extract( $args );

		/* Our variables from the widget settings. */
		$cbj_title=$instance['cbj_title'];
		$cbj_DeveloperKey = $instance['cbj_DeveloperKey'];
		$cbj_keywords=$instance['cbj_keywords'];
		$cbj_CountryCode=$instance['cbj_CountryCode'];
		$cbj_PerPage=$instance['cbj_PerPage'];
		$daysBackToLook=$instance['cbj_daysBackToLook'];
		/* Before widget (defined by themes). */
		
		echo $before_widget;
		if($cbj_DeveloperKey!=''){   
		$xml=simplexml_load_file("http://api.careerbuilder.com/V1/jobsearch?DeveloperKey=$cbj_DeveloperKey&keywords=$cbj_keywords&CountryCode=$cbj_CountryCode&PerPage=$cbj_PerPage&PostedWithin=$daysBackToLook");
		}
?>
<style>
#jobs {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    width: 100%;
    border-collapse: collapse;
}

#jobs td, #jobs th {
    font-size: 1em;
    border: 1px solid #98bf21;
    padding: 3px 7px 2px 7px;
}

#jobs th {
    font-size: 1.1em;
    text-align: left;
    padding-top: 5px;
    padding-bottom: 4px;
    background-color: #A7C942;
    color: #ffffff;
}


#jobs tr.alt td {
    color: #000000;
    background-color: #EAF2D3;
}

#sidebar .widget {
background-color: white;
border: 1px solid #E6E6E6;
margin: 0 0 15px; }
</style>

<?php if(isset($xml->Results)) {?>
<?php echo '<div class="widget-text wp_widget_plugin_box">'; 
 echo $before_title . $cbj_title . $after_title; ?>
<table id="jobs">


<?php foreach($xml->Results->JobSearchResult as $result)
{?>
<tr><td><?php echo $result->Company; ?></td><td><a href="<?php echo $result->JobDetailsURL; ?>" target="_blank">more info</a></td></tr>
	<?php }?>
	</table>
	<?php echo '</div>';
   echo $after_widget; ?>

			
			
	
	<?php } } }




?>
