<?php
	

		$get_user_info = array(
			'function' => "getuserinfo",
			'refno' => "LA01"		
		);
		
		$get_gathering_images = array(
			'function' => "getGatheringImages",
			'refno' => "LA01"		
		);
		
		/******** Call the function that you want to *************/
		echo "<b>UserInfo</b> <br/>";
		print_r(fetch_from_local_db($get_user_info));
		
		echo "<br/><b> Images Info </b><br/>";
		print_r(fetch_from_local_db($get_gathering_images));
		
		
		function fetch_from_local_db($data) {

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://121.243.24.194/apps/PhatakSirNew/PhatakSirDataServices-debug/Interface/interface.php");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$output = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			$return_value = unserialize($output);
			return $return_value;
			
		}

?>
