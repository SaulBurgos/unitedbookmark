<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	require_once('db_connect.php');
	require_once('utilities.php');

	@ $mysqli = conect_db();
	@ session_start();

	$respond = array();

	if (mysqli_connect_errno()) {
		$respond['status'] = 'error';
		$respond['msg'] = 'imposible conect to DB';
		echo json_encode($respond);	
	}	

	// clean all data
	foreach($_GET as $key=>$value) {
		$_GET[$key] = check_input($value); 
	}
	
	switch ($_GET['action']) {
		
		case "getBookmarkUser":
			@ session_start();
			require_once('classes/bookmarks.php');			
    		require_once('classes/links.php');
    		require_once('classes/tags.php');			
			
			$tag = new Tags($mysqli);			
			$bookmark = new Bookmarks($mysqli);
			header('Content-type: application/json');

			$bookmarks = $bookmark->getBookmarkByUserToGrid($_SESSION['userId']);

			foreach ($bookmarks as $key => $value) {
				$linkQuantity = $bookmark->getNumerLinksUsed($bookmarks[$key]['id']);
				$bookmarks[$key]['links'] = $linkQuantity['data']['linksCount'];
				//collect tags
				$tags = $tag->getTagsByBookmarkId($bookmarks[$key]['id']);	
				$tagsString = '';
				//convert tags to string separate by comma
				for ($i = 0 ; $i < count($tags['data']);$i++) { 
					$tagsString = $tagsString.$tags['data'][$i]['name'];
					
					if ( $i != count($tags['data']) - 1 ) {
						$tagsString = $tagsString.',';
					}
				}
				$bookmarks[$key]['tags'] = $tagsString;
			}
			echo json_encode($bookmarks);
		break;
		case "getLinksByBookmark":
			
			require_once('classes/bookmarks.php');			
    		require_once('classes/links.php');
			
			$bookmark = new Bookmarks($mysqli);
			header('Content-type: application/json');
			echo json_encode($bookmark->getLinksByBookmarkToGrid($_GET['bookmarkId']));
		break;

	}

?>