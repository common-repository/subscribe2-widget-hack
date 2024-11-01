<?php

/*

Plugin Name: Subscribe2 widget-hack

Plugin URI: http://www.litschers.com/category/wordpress/subscribe2-widget/

Description: Adds a sidebar widget for Matthew Robinson's <i>fantastic</i> <a href="http://subscribe2.wordpress.com">Subscribe2</a> plugin.  Adds a hack to allow compatibility between other plugins with the original <a href="http://subscribe2.wordpress.com/tag/widget/">Subscribe2 widget</a>

Author: Ken Litscher

Version: 1.2.1

Author URI: http://www.litschers.com



Version History

	1.0 - Initial hack-users must edit the file

	1.1 - Added "get_permalink()" - no longer need to edit this file

	1.2 - Compatibility with Subscribe2 version 3.0 and up	
	
	1.2.1 - Minor display tweaks - added to wp-plugins.org subversion

*/

function widget_s2widgethack_init() {

//Check Sidebar Widget and Subscribe2 plugins are activated

if ( !function_exists('register_sidebar_widget') || !class_exists('s2class'))

	return;


function widget_s2widgethack($args) {

	extract($args);

	$options = get_option('widget_s2widgethack');

	$title = empty($options['title']) ? __('Subscribe2') : $options['title'];

?>

		<?php echo $before_widget; ?>

			<?php echo $before_title . $title . $after_title; ?>

			<div>

			<?php

			  	global $user_ID;

				global $user_level;

				get_currentuserinfo();

				$s2permalink = get_permalink(S2PAGE);

				

				//is user logged in?

				if (!$user_ID):

					/*

					if not, show public form, use the S2PERM constant defined above.

					Perhaps use the already defined S2PAGE to query the DB to automatically grab

					permalink, but it seems like a lot DB use to do this for everypage.  Maybe

					define the permalink in the main plugin file too?									

					*/

					

					echo '

<form method="post" action="'.$s2permalink.'">
<table>
	<tr>
		<td colspan="2">Your email:&#160;</td>
	</tr>
	<tr>
		<td colspan="2"><input type="text" name="email" value="" size="20" />&#160;</td>
	</tr>
	<tr>
		<td><input type="radio" name="s2_action" value="subscribe" checked="checked" /> Subscribe 	<br />	
			<input type="radio" name="s2_action" value="unsubscribe" /> Unsubscribe &#160;</td>

		<td><input type="submit" value="Send" /></td>
	</tr>
</table>
</form>';

				else:  

				// Check to see their user level to know which link to use to subscribe page

				if ($user_level < 8) {

					$subscribe_path = get_bloginfo('url').'/wp-admin/profile.php?page=subscribe2/subscribe2.php';

					}else $subscribe_path = get_bloginfo('url').'/wp-admin/users.php?page=subscribe2/subscribe2.php';

					echo '<p>You may manage your subscription options from your <a href="'.$subscribe_path.'">profile</a></p>';

				endif;

			?>

			</div>	  

		<?php echo $after_widget; ?>

<?php

}

function widget_s2widgethack_control() {

	$options = $newoptions = get_option('widget_s2widgethack');

	if ( $_POST["s2w-submit"] ) {

		$newoptions['title'] = strip_tags(stripslashes($_POST["s2w-title"]));

	}

	if ( $options != $newoptions ) {

		$options = $newoptions;

		update_option('widget_s2widgethack', $options);

	}

	$title = htmlspecialchars($options['title'], ENT_QUOTES);

?>

			<p><label for="s2w-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="s2w-title" name="s2w-title" type="text" value="<?php echo $title; ?>" /></label></p>

			<input type="hidden" id="s2w-submit" name="s2w-submit" value="1" />

<?php

}
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.

	register_sidebar_widget('s2widgethack', 'widget_s2widgethack');

	// This registers our optional widget control form.

	register_widget_control('s2widgethack', 'widget_s2widgethack_control');

}

// Run our code later in case this loads prior to any required plugins.

add_action('plugins_loaded', 'widget_s2widgethack_init');

?>