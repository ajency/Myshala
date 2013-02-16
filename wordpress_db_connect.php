<?php

/**
 * Open the wordpress site folder and locate the wp-config.php and use the connection constants.
 * Define the global constants
 */
if(!defined('WP_REMOTE_DB_HOST'))
	define('WP_REMOTE_DB_HOST', 'localhost');

if(!defined('WP_REMOTE_DB_USER'))
	define('WP_REMOTE_DB_USER', 'root');

if(!defined('WP_REMOTE_DB_PASSWORD'))
	define('WP_REMOTE_DB_PASSWORD','');

if(!defined('WP_REMOTE_DB_NAME'))
	define('WP_REMOTE_DB_NAME', 'myshala');

/**
 * Function to check if data is serialized.
 * @param string $str
 * @return boolean
 */
function is_serialized($str) {
	return ($str == serialize(false) || @unserialize($str) !== false);
}

/**
 * Function to talk to the wordpress db in myshala
 * Connects to remote db with access provided by constants like
 * WP_REMOTE_DB_HOST
 * WP_REMOTE_DB_USER
 * WP_REMOTE_DB_PASSWORD &
 * WP_REMOTE_DB_NAME
 * @param string $query
 * @return boolean|multitype:The output of the query or false if error
 */
function msh_wp_db_connection($query)
{
	$host = (defined('WP_REMOTE_DB_HOST'))? WP_REMOTE_DB_HOST : '';
	$user = (defined('WP_REMOTE_DB_USER'))? WP_REMOTE_DB_USER	: '';
	$pass = (defined('WP_REMOTE_DB_PASSWORD'))? WP_REMOTE_DB_PASSWORD	: '';
	$dbnm = (defined('WP_REMOTE_DB_NAME'))? WP_REMOTE_DB_NAME	: '';

	$myshala_query_result = array();

	$myshala_con = @new mysqli($host,$user,$pass,$dbnm);

	//Check if there was an error in connection
	if ($myshala_con->connect_errno) {

		//Return false on connection error
		return false;
	}
	else
	{
		if(!$result = $myshala_con->query($query,MYSQLI_USE_RESULT))
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

	//Return the result as an array of objects.
	return $myshala_query_result;
}

/**
 * Example usage of the above function
 * 
 */
function example_get_users_from_wordpress()
{
	$the_table_name = 'wp_posts';
	$the_query		= sprintf("SELECT * FROM %s",$the_table_name);
	$the_result		= msh_wp_db_connection($the_query);
	if($the_result)
	{
		foreach($the_result as $result)
		{
			/**
			 * NOTE:use @unserialize() for coloms that have serialized data.
			 * Eg. if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
               		return @unserialize( $original );
	       		 return $original;
			 */
			echo $result->post_title.'<br>';
		}
	}
	else 
	{
		echo 'Some error occured.';
	}
}

/**
 * Function to get the images selected by a user.
*/
function myshal_get_usermeta($user_id,$meta_key){

	$the_query		= sprintf("SELECT * FROM `wp_usermeta` WHERE `user_id` = %d AND `meta_key` = '%s'",$user_id,$meta_key);
	$the_result		= msh_wp_db_connection($the_query);
	
	if($the_result)
	{
		if(is_serialized($the_result[0]->meta_value))
		{
			$meta_value = @unserialize( $the_result[0]->meta_value );
		}
		else
		{
			$meta_value = $the_result[0]->meta_value;
		}
		return $meta_value;
	}
	
	return false;
}

$user_photos = myshal_get_usermeta(19397,'photos_picked');
var_dump($user_photos);