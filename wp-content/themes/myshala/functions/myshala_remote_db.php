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
	
	if ( bp_displayed_user_id()== bp_loggedin_user_id() || is_site_admin())
	
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
		//var_dump($result );
		$html = '<div class="custom-table-wrapper style-2"><table class="profile-fields">';
		$html .= '<tbody>';

		if($result)
		{
			$html .= sprintf('<tr><th colspan=2>Student Table Data</th></tr>');
			$html .= sprintf('<tr><td class="table-label">Reference Number</td><td><p>%s</p></td></tr>',$result[0]->RefNo);
			$html .= sprintf('<tr><td class="table-label">Name</td><td><p>%s %s</p></td></tr>',$result[0]->FName,$result[0]->LName);
			$html .= sprintf('<tr><td class="table-label">Class</td><td><p>%s</p></td></tr>',$result[0]->CClass);
			$html .= sprintf('<tr><td class="table-label">Division</td><td><p>%s</p></td></tr>',$result[0]->CDiv);
			$html .= sprintf('<tr><td class="table-label">Address</td><td><p>%s<br>%s<br>%s</p></td></tr>',$result[0]->Addr1,$result[0]->Addr2,$result[0]->Addr3);

			$html .= sprintf('<tr><th colspan=2>Parent Table Data</th></tr>');
			$html .= sprintf('<tr><td class="table-label">Fathers Name</td><td><p>%s %s %s</p></td></tr>',$result[0]->FthFName,$result[0]->FthMName,$result[0]->FthLName);
			if($result[0]->FthPhone1==0) 
				$result[0]->FthPhone1="N/A";
			$html .= sprintf('<tr><td class="table-label">Fathers Contact Number</td><td><p>%s</p></td></tr>',$result[0]->FthPhone1);
			$html .= sprintf('<tr><td class="table-label">Fathers email Id</td><td><p>%s</p></td></tr>',$result[0]->FthEmail);
			$html .= sprintf('<tr><td class="table-label">Mothers Name</td><td><p>%s %s</p></td></tr>',$result[0]->MthFName,$result[0]->MthLName);
			if($result[0]->MthPhone1==0)
				$result[0]->MthPhone1="N/A";
			$html .= sprintf('<tr><td class="table-label">Mothers Contact Number</td><td><p>%s</p></td></tr>',$result[0]->MthPhone1);
			$html .= sprintf('<tr><td class="table-label">Mothers email Id</td><td><p>%s</p></td></tr>',$result[0]->MthEmail);
			$html .= sprintf('<tr><td class="table-label">Fathers Company Name</td><td><p>%s</p></td></tr>',$result[0]->FthCompanyName);
			
			$father_office_address = "";
			if($result[0]->FthAdd1 != "NULL" && $result[0]->FthAdd1 != "")
				$father_office_address .= $result[0]->FthAdd1."<br>";
			if($result[0]->FthAdd2!="NULL" && $result[0]->FthAdd2 != "")
				$father_office_address .= $result[0]->FthAdd2."<br>";
			if($result[0]->FthAdd3!="NULL" && $result[0]->FthAdd3 != "")
				$father_office_address .= $result[0]->FthAdd3."<br>";
			if($result[0]->FthCity!="-1" && $result[0]->FthCity != "")
				$father_office_address .= $result[0]->FthCity."<br>"; 
			if($result[0]->FthPin!="0" && $result[0]->FthPin != "")
				$father_office_address .= $result[0]->FthPin."<br>";
			 
			$html .= sprintf('<tr><td class="table-label">Fathers office address</td><td><p>%s</p></td></tr>',$father_office_address);
			$html .= sprintf('<tr><td class="table-label">Fathers office Number</td><td><p>%s</p></td></tr>',$result[0]->FthPhone2);
			$html .= sprintf('<tr><td class="table-label">Fathers Profession</td><td><p>%s</p></td></tr>',$result[0]->FthProfMain);
			$html .= sprintf('<tr><td class="table-label">Mothers Company Name</td><td><p>%s</p></td></tr>',$result[0]->MthCompanyName);
			$mothers_office_address = "";
			if($result[0]->MthAdd1 != "NULL" && $result[0]->MthAdd1 != "")
				$mothers_office_address .= $result[0]->MthAdd1."<br>";
			if($result[0]->MthAdd2!="NULL" && $result[0]->MthAdd2 != "")
				$mothers_office_address .= $result[0]->MthAdd2."<br>";
			if($result[0]->MthAdd3!="NULL" && $result[0]->MthAdd3 != "")
				$mothers_office_address .= $result[0]->MthAdd3."<br>";
			if($result[0]->MthCity!="-1" && $result[0]->MthCity != "")
				$mothers_office_address .= $result[0]->MthCity."<br>";
			if($result[0]->MthPin!="0" && $result[0]->MthPin != "")
				$mothers_office_address .= $result[0]->MthPin."<br>";
			$html .= sprintf('<tr><td class="table-label">Mothers office address</td><td><p>%s</p></td></tr>',$mothers_office_address);
			$html .= sprintf('<tr><td class="table-label">Mothers office Number</td><td><p>%s</p></td></tr>',$result[0]->MthPhone2);
			$html .= sprintf('<tr><td class="table-label">Mothers Profession</td><td><p>%s</p></td></tr>',$result[0]->MthProfMain);
		}
		else
		{
			$html .= '<tr><td>No data on record.</td></tr>';
		}
		$html .= '</tbody>';
		$html .= '</table></div>';
		echo $html;
	}
	else
	{
		echo '<h2>No RefNo associated with member.</h2>';
	}

}