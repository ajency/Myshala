<?php

/*
 Plugin Name: Myshala CSV User Import
Plugin URI: http://ajency.in
Description: Allows the importation of users via an uploaded CSV file.
Author: Nisheed Jagadish,Andy Dunn
Version: 0.0.1
Author URI: http://ajency.in
*/

// always find line endings
ini_set('auto_detect_line_endings', true);

// add admin menu
add_action('admin_menu', 'csvuserimport_menu');



function csvuserimport_menu() {
	add_submenu_page( 'users.php', 'CSV User Import', 'Import', 'manage_options', 'csv-user-import', 'csvuserimport_page1');
}

function csvuserimport_mailer_updater($user_refid = null,$user_id=null,$user_email = null,$user_pass = null,$user_name = null  )
{
	if(empty($user_refid))
		return;

	//Update the user meta here and send mail
	update_user_meta($user_id, 'msh_remote_refid', $user_refid);

	if(!empty($user_email) && !empty($user_pass) && !empty($user_name))
	{
		$message = __( 'Hi,
					
				You\'ve been successfully registered to \'%1$s\' at
				%2$s.

				Your login credentials are as follows:

				Username:	%5$s
				Password:	%4$s
					
				Please login to the site by clicking %6$s and following the login steps' );
		add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
		wp_mail( $user_email, sprintf( __( '[%s] Registration' ), get_option( 'blogname' ) ), sprintf($message, get_option('blogname'), home_url(), $user_email ,$user_pass ,$user_name ,  home_url()));
	}

	return ;
}

// show import form
function csvuserimport_page1() {

	global $wpdb;

	if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	// if the form is submitted
	if ($_POST['mode'] == "submit") {

		$arr_rows = file($_FILES['csv_file']['tmp_name']);

		// loop around
		if (is_array($arr_rows)) {
				
			$error_email_ids = array();
			$error_sent_mail = array();//future show emails that could not be sent.
				
			foreach ($arr_rows as $row) {

				// split into values
				$row = str_replace('"','',$row);
				$arr_values = split(",", $row);
				//Only take user email which is at index 0
				$user_refid 	= trim($arr_values[0]); 
				if ($user_refid !="refno")
				{
					$user_bdate		= trim($arr_values[1]);
					$user_byear		= trim($arr_values[2]);
					$user_fname		= trim($arr_values[3]);
					$user_lname	    = trim($arr_values[4]);
					$user_username	= trim($arr_values[5]);
					$user_email	    = trim($arr_values[6]);

					$user_pass		= wp_generate_password();

					if (!$user_email) {
						$username."@donotreply.com";
					}
 
					$user_data = array('user_email' => $user_email,'user_login' => $user_username,'user_pass' => $user_pass,'role' => 'subscriber','first_name' =>$user_fname,'last_name' =>$user_lname );

					$user_id = wp_insert_user($user_data);

					update_user_meta( $user_id, 'msh_remote_bdate', $user_bdate );
					update_user_meta( $user_id, 'msh_remote_byear', $user_byear );

					if(is_wp_error($user_id))
					{
						if($user_id->get_error_code() == 'existing_user_login' || $user_id->get_error_code() == 'existing_user_email' )
						{
							$existing_user = get_user_by('email',$user_email);
							csvuserimport_mailer_updater($user_refid,$existing_user->ID);
						}
						else
						{
							//Keep track of all email ids that had some other error associated with it
							$error_email_ids[] = $user_email;
						}
					}
					else
					{
						csvuserimport_mailer_updater($user_refid,$user_id,$user_email,$user_pass,$user_username);
					}
				 
				}
			}	// end of 'for each around arr_rows'
				
			if(count($error_email_ids)> 0)
			{
				$html_update = "<div class='updated' style='color: red'>The following email ids were not inserted:<br>";
				$html_update .= "<ul>";
				foreach ($error_email_ids as $email_id)
				{
					$html_update .= "<li>$email_id</li>";
				}
				$html_update .= "</ul>";
				$html_update .="</div>";
			}
			else
				$html_update = "<div class='updated'>All users appear to be have been imported successfully.</div>";
				
		} // end of 'if arr_rows is array'
		else {
			$html_update = "<div class='updated' style='color: red'>It seems the file was not uploaded correctly.</div>";
		}
	} 	// end of 'if mode is submit'

	?>
<div class="wrap">
	<?php echo $html_update; ?>
	<div id="icon-users" class="icon32">
		<br />
	</div>
	<h2>CSV User Import</h2>
	<p>Please select the CSV file you want to import below.</p>

	<form action="users.php?page=csv-user-import" method="post"
		enctype="multipart/form-data">
		<input type="hidden" name="mode" value="submit"> <input type="file"
			name="csv_file" /> <input type="submit" value="Import" />
	</form>

	<p>The CSV file should be in the following format:</p>

	<p>
		<a href="<?php echo plugin_dir_url(__FILE__).'test_csv.csv';?>"
			target="_blank">View Example</a>
	</p>

	<p>
		<strong>Note:</strong>
	</p>

	<ol>
		<li>Please make sure that each email id and refid entered in the CSV
			is on a single line.</li>
		<li><span style="color: red">Please make sure you back up your
				database before proceeding!.</span>
		</li>
	</ol>

</div>
<?php
}	// end of 'function csvuserimport_page1()'
?>