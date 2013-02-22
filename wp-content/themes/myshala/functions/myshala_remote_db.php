<?php
/**
 * Function to talk to the remote db in myshala
 * Connects to remote db with access provided by constants like
 * MYSHALA_REMOTE_DB_HOST
 * MYSHALA_REMOTE_DB_USER
 * MYSHALA_REMOTE_DB_PASSWORD & 
 * MYSHALA_REMOTE_DB_NAME provided in wp-config.php
 * @param string $query
 * @return boolean|multitype:The output of the query or false if error
 */
function msh_remote_db_connection($query)
{
	$host = (defined('MYSHALA_REMOTE_DB_HOST'))? MYSHALA_REMOTE_DB_HOST : 'localhost';
	$user = (defined('MYSHALA_REMOTE_DB_USER'))? MYSHALA_REMOTE_DB_USER	: 'root';
	$pass = (defined('MYSHALA_REMOTE_DB_PASSWORD'))? MYSHALA_REMOTE_DB_PASSWORD	: '';
	$dbnm = (defined('MYSHALA_REMOTE_DB_NAME'))? MYSHALA_REMOTE_DB_NAME	: 'myshala_external';

	$myshala_query_result = array();

	$myshala_con = @new mysqli($host,$user,$pass,$dbnm);
	
	//Check if there was an error in connection
	if ($myshala_con->connect_errno) {
		
		//Return false on connection error
		return false;
	}
	else
	{
		$myshala_query = $query;

		if(!$result = $myshala_con->query($myshala_query,MYSQLI_USE_RESULT))
		{
			//Return false on query error
			return false; 
		}
		else
		{
			$num_rows = 0;
			while ( $row = $result->fetch_object() ) {
				$myshala_query_result[$num_rows] = $row;
				$num_rows++; //Can be used in the future for getting row returned.
			}		
		}
		//Free the resources.
		$result->close();
	}
	
	//Close the db connection.
	$myshala_con->close();
	
	//Return the result/
	return $myshala_query_result;
}

///////////////////////////Creation of profile tab/////////////////////////
/**
 * Following few functions are used to create a new profile tab 
 * Follow wordpress/buddypress conventions to add function to hooks
 */
add_action( 'bp_setup_nav', 'msh_remote_info_profile_tab' );

function msh_remote_info_profile_tab()
{
	$args = array(
			'name' 						=> __('Student Info', 'buddypress'), //The tab title
			'slug' 						=> 'msh-remote-info',				 //The unique page slug
			'position' 					=> 100,								 //Position amongst the porfile tabs	
			'show_for_displayed_user' 	=> true,							 //Show it while viewing other user profiles
			'screen_function' 			=> 'msh_remote_info_tab',			 //The function to output the html code	
			'item_css_id' 				=> 'msh-remote-info'				 //Unique CSS id for the tab	
	);
	bp_core_new_nav_item($args);
}

/**
 * The screen_function
 */
function msh_remote_info_tab () {
	
	//msh_remote_into_tab_title -> the function to output the title in the screen
	add_action( 'bp_template_title', 	'msh_remote_into_tab_title' );
	//msh_remote_into_tab_content -> the function to output the html contents of the tab
	add_action( 'bp_template_content', 	'msh_remote_into_tab_content' );
	//include this file for backward compatibility.
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}
/**
 * The title function.
 */
function msh_remote_into_tab_title() {
	echo 'Student Info';
}
/**
 * The tab html content function.
 */
function msh_remote_into_tab_content() {

	global $wpdb;
	$refid = get_user_meta(bp_displayed_user_id(),'msh_remote_refid',true);
	if($refid)
	{
		//Query to fetch the remote user data
		//$remote_query = $wpdb->prepare("SELECT * FROM `studentinfo` INNER JOIN `parentinfo` ON studentinfo.RefNo = parentinfo.RefNo WHERE studentinfo.RefNo = %d",$refid);
		//$result = msh_remote_db_connection($remote_query);
		
		$get_gathering_images = array(
				'function' => "getuserinfo",
				'refno' => $refid
		);
		$result = fetch_from_local_db($get_gathering_images);
		$html = '<table class="profile-fields">';
		$html .= '<tbody>';

		if($result)
		{
			$html .= sprintf('<tr><th class="label">Student Table Data</th></tr>');
			$html .= sprintf('<tr><td class="label">Name</td><td class="data"><p>%s %s</p></td></tr>',$result[0]->FName,$result[0]->LName);
			$html .= sprintf('<tr><td class="label">Address</td><td class="data"><p>%s<br>%s<br>%s</p></td></tr>',$result[0]->Addr1,$result[0]->Addr2,$result[0]->Addr3);

			$html .= sprintf('<tr><th class="label">Parent Table Data</th></tr>');
			$html .= sprintf('<tr><td class="label">Fathers Name</td><td class="data"><p>%s %s %s</p></td></tr>',$result[0]->FthFName,$result[0]->FthMName,$result[0]->FthLName);
			$html .= sprintf('<tr><td class="label">Mothers Name</td><td class="data"><p>%s %s</p></td></tr>',$result[0]->MthFName,$result[0]->MthLName);
			$html .= sprintf('<tr><td class="label">Fathers Company Name</td><td class="data"><p>%s</p></td></tr>',$result[0]->FthCompanyName);
			$html .= sprintf('<tr><td class="label">Fathers Profession</td><td class="data"><p>%s</p></td></tr>',$result[0]->FthProfMain);
			$html .= sprintf('<tr><td class="label">Mothers Profession</td><td class="data"><p>%s</p></td></tr>',$result[0]->MthProfMain);
		}
		else
		{
			$html .= '<tr><td>No data on record.</td></tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		echo $html;
	}
	else
	{
		echo '<h2>No RefNo associated with member.</h2>';
	}

}