<?php

	//error_reporting(E_ALL);/*all error*/
	/*error_reporting(0);*//*deactived all error*/
	error_reporting(E_ERROR);/*deactive warning only error*/
	ini_set('display_errors', '1');
	
	require_once('db_connect.php');
	require_once('utilities.php');
	/*require_once('underscore.php');*/

	@ $mysqli = conect_db();
	@ session_start();

	$respond = array();

	if (mysqli_connect_errno()) {
		$respond['status'] = 'error';
		$respond['msg'] = 'imposible conect to DB';
		echo json_encode($respond);	
	}


	//becasue angular use json configuracion to send data
	$data = file_get_contents("php://input");
	$objData = json_decode($data);	

	// clean all data
	foreach ($objData->parameters as $key => $value) {
		$objData->parameters->$key = check_input($value);
	}

	switch ($objData->action) {
		/*****************************/
		/******register and user******/
		case "register":
			require_once('classes/user.php');
			$user = new User($mysqli);
			$emailAvailable = $user->isEmailAvailable($objData->parameters);
			$nameAvailable = $user->isNameAvailable($objData->parameters);

			if($emailAvailable == false) {
				echo json_encode(SendResults('error','','Email already exist.',''));
				return;
			} 

			if($nameAvailable == false) {
				echo json_encode(SendResults('error','','User name already exist.',''));
				return;
			} 

			$currentUser = $user->insert($objData->parameters);
			if($currentUser['status'] == 'true') {
				$lastUser = $user->getUserByEmail($objData->parameters->email);
				$user->createSession($lastUser['data'][0]);

				echo json_encode(SendResults('ok','','User Registered',''));

			} else {
				echo json_encode(SendResults('error','',$currentUser['msg'],''));
				return;	
			}		
		break;
		case "getSession":			
			require_once('classes/user.php');
			require_once('classes/bookmarks.php');

			$user = new User($mysqli);
			$bookmark = new Bookmarks($mysqli);
			$session = $user->isThereSession();

			if($session['status'] == 'ok') {
				$linkNewsQty = $bookmark->getQtyBookmarkWithNewLinks($session['data']);
				$session['data']['qtyBookmarkWithNewLinks'] = $linkNewsQty;				
			}
			echo json_encode($session);
        break;
        case "login":			
			require_once('classes/user.php');
			$user = new User($mysqli);
			echo json_encode($user->login($objData->parameters));
        break;
        case "logout":			
			require_once('classes/user.php');
			$user = new User($mysqli);
			echo json_encode($user->logOut());
        break;
        /*****************************/
        /*********bookmark*******/
        case "saveBookmark":

        	try {

        		require_once('classes/bookmarks.php');			
        		require_once('classes/tags.php');
        		require_once('classes/links.php');

        		$tag = new Tags($mysqli);
				$bookmark = new Bookmarks($mysqli);
				$link = new Links($mysqli);

				if(!isset($_SESSION['userId'])){
					echo json_encode(SendResults('error','','Your session has ended on server, please login again.',''));
					return;
				}

				//we get here the id becasue is more secure
				$objData->parameters->userId = $_SESSION['userId']; 

				$bookmarkDuplicate = $bookmark->isDuplicate($objData->parameters);
				if($bookmarkDuplicate['status']) {
					echo json_encode(SendResults('error','','This bookmark is identical to another, please change title or tags',''));
					return;
				} 

				$bookmarkInfo = $bookmark->insert($objData->parameters);
				$tag->insert($objData->parameters->tags);
				$tagIds = $tag->getIds($objData->parameters->tags);
				$bookmark->insertTags($tagIds,$bookmarkInfo['data']['bookmarkId']);
				$linkUsed = $link->isUsed($objData->parameters);
				
				if($linkUsed['status'] == 'false') {
					$linkSaved = $link->insert($objData->parameters);
					$objData->parameters->linkId = $linkSaved['data']['linkId'];
				} else {
					$objData->parameters->linkId = $linkUsed['data']['linkId'];
				}

				$linkInfo = $link->getTitle($objData->parameters);
				$objData->parameters->linkTitle = $linkInfo['data']['linkTitle'];
				$objData->parameters->bookmarkId = $bookmarkInfo['data']['bookmarkId'];
				$link->updateCountUsed($objData->parameters);
				echo json_encode($bookmark->insertLink($objData->parameters));
			    
			} catch (Exception $e) {
				echo json_encode(SendResults('error','','Error trying save bookmark, please try again.',$e));
			}

        break;
        case "getRecentBookmarks":
        	require_once('classes/bookmarks.php');
        	require_once('classes/tags.php');			
			
			$tag = new Tags($mysqli);
			$bookmark = new Bookmarks($mysqli);
			$infoBookmark = $bookmark->getRecent(10);			

			if($infoBookmark['status'] == 'ok') {
				foreach ($infoBookmark['data'] as $key => $value) {					
					$bookmarkTag = $tag->getTagsByBookmarkId($infoBookmark['data'][$key]['id']);
					if($bookmarkTag['status'] == 'ok'){
						$infoBookmark['data'][$key]['tags'] = $bookmarkTag['data'];
					} else {
						$infoBookmark['data'][$key]['tags'] = array();
					}
				}
			}
			echo json_encode($infoBookmark);
        break;
        case "getMoreBookmarks":
        	require_once('classes/bookmarks.php');
        	require_once('classes/tags.php');			
			
			$tag = new Tags($mysqli);
			$bookmark = new Bookmarks($mysqli);
			$infoBookmark = $bookmark->getMore($objData->parameters);

			if($infoBookmark['status'] == 'ok') {
				foreach ($infoBookmark['data'] as $key => $value) {
					$bookmarkTag = $tag->getTagsByBookmarkId($infoBookmark['data'][$key]['id']);
					if($bookmarkTag['status'] == 'ok'){
						$infoBookmark['data'][$key]['tags'] = $bookmarkTag['data'];
					} else {
						$infoBookmark['data'][$key]['tags'] = array();
					}
				}
			}
			echo json_encode($infoBookmark);
        break;
        case "getBookmarkById":
        	require_once('classes/bookmarks.php');			
    		require_once('classes/tags.php');
    		require_once('classes/links.php');

    		$tag = new Tags($mysqli);
			$bookmark = new Bookmarks($mysqli);
			$link = new Links($mysqli);	

			$bookmarkInfo = $bookmark->getById($objData->parameters);

			if($bookmarkInfo['status'] == 'ok') {
				
				$bookmarkLinksInfo = $bookmark->getLinks($objData->parameters);

				foreach ($bookmarkLinksInfo['data'] as $key => $value) {
					//get like by linkId and bookmarkId
					$likesLink = $link->getLikesByLinkIdAndBookmarkId(
						$bookmarkLinksInfo['data'][$key]['id'],
						$bookmarkInfo['data']['id']
					);

					//get nolike by linkId and bookmarkId
					$nolikesLink = $link->getNoLikesByLinkIdAndBookmarkId(
						$bookmarkLinksInfo['data'][$key]['id'],
						$bookmarkInfo['data']['id']
					);
					//assign values to current link
					$bookmarkLinksInfo['data'][$key]['likes'] = $likesLink;
					$bookmarkLinksInfo['data'][$key]['noLikes'] = $nolikesLink;
				}

				/*check if user logged is watching this bookmark*/
				if(isset($_SESSION['userId'])) {
					$isWatch =  $bookmark->isAlreadyWatch($_SESSION['userId'],$bookmarkInfo['data']['id']);					
					$bookmarkInfo['data']['alreadyWatch'] =  $isWatch;
				} else {
					$bookmarkInfo['data']['alreadyWatch'] =  'false';
				}

				$bookmarkTagsInfo = $bookmark->getTags($objData->parameters);
				$bookmarkInfo['data']['links'] = $bookmarkLinksInfo['data'];				
				$bookmarkInfo['data']['tags'] = $bookmarkTagsInfo['data'];
			}
			echo json_encode($bookmarkInfo);
        break;
        case "addLinkTobookmark":
        	require_once('classes/bookmarks.php');			
    		require_once('classes/links.php');

			$bookmark = new Bookmarks($mysqli);
			$link = new Links($mysqli);	

			if(!isset($_SESSION['userId'])){
				echo json_encode(SendResults('error','','Your session has ended on server, please login again.',''));
				return;
			}

			$linkExist = $bookmark->isLinkExist($objData->parameters);

			if (!$linkExist['status']) {
				
				//we get here the id becasue is more secure
				$objData->parameters->userId = $_SESSION['userId'];
				
				$linkUsed = $link->isUsed($objData->parameters);
				
				if($linkUsed['status'] == 'false') {
					$linkSaved = $link->insert($objData->parameters);
					$objData->parameters->linkId = $linkSaved['data']['linkId'];
				} else {
					$objData->parameters->linkId = $linkUsed['data']['linkId'];
				}

				$linkInfo = $link->getTitle($objData->parameters);
				$objData->parameters->linkTitle = $linkInfo['data']['linkTitle'];
				$link->updateCountUsed($objData->parameters);
				$linkInserted = $bookmark->insertLink($objData->parameters);
				//to know if a bookmark has new link
				$bookmark->updateFlagNewLinks($objData->parameters);
				echo json_encode($linkInserted);

			} else {
				echo json_encode($linkExist);	
			}
        break;
        case "searchBookmarkByTitle":
        	require_once('classes/bookmarks.php');
        	require_once('classes/tags.php');

        	$tag = new Tags($mysqli);
			$bookmark = new Bookmarks($mysqli);

			$bookmarkInfo = $bookmark->searchByTitle($objData->parameters);

			if (count($bookmarkInfo['data']) > 0 ) {
				
				foreach ($bookmarkInfo['data'] as $key => $value) {				
					$linksQuantity = $bookmark->getNumerLinksUsed($bookmarkInfo['data'][$key]['id']);
					$bookmarkInfo['data'][$key]['linksCount'] = $linksQuantity['data']['linksCount'];

					$bookmarkTag = $tag->getTagsByBookmarkId($bookmarkInfo['data'][$key]['id']);
					if($bookmarkTag['status'] == 'ok'){
						$bookmarkInfo['data'][$key]['tags'] = $bookmarkTag['data'];
					} else {
						$bookmarkInfo['data'][$key]['tags'] = array();
					}
				}
				echo json_encode($bookmarkInfo);	
			} else 	{
				echo json_encode(SendResults('ok','','result no found.',''));
			}
						
        break;
        case "searchBookmarkByTitleViewMore":
        	require_once('classes/bookmarks.php');
        	require_once('classes/tags.php');

        	$tag = new Tags($mysqli);
			$bookmark = new Bookmarks($mysqli);
			$bookmarkInfo = $bookmark->searchByTitleGetMore($objData->parameters);

			if (count($bookmarkInfo['data']) > 0 ) {
				
				foreach ($bookmarkInfo['data'] as $key => $value) {				
					$linksQuantity = $bookmark->getNumerLinksUsed($bookmarkInfo['data'][$key]['id']);
					$bookmarkInfo['data'][$key]['linksCount'] = $linksQuantity['data']['linksCount'];

					$bookmarkTag = $tag->getTagsByBookmarkId($bookmarkInfo['data'][$key]['id']);
					if($bookmarkTag['status'] == 'ok'){
						$bookmarkInfo['data'][$key]['tags'] = $bookmarkTag['data'];
					} else {
						$bookmarkInfo['data'][$key]['tags'] = array();
					}
				}
				echo json_encode($bookmarkInfo);	
			} else 	{
				echo json_encode(SendResults('ok','','result no found.',''));
			}
						
        break;
        case "searchBookmarkByTitleTags":
        	require_once('classes/bookmarks.php');
        	require_once('classes/tags.php');

        	$tag = new Tags($mysqli);
			$bookmark = new Bookmarks($mysqli);
			$bookmarkInfo = $bookmark->searchByTitleTags($objData->parameters);


			if (count($bookmarkInfo['data']) > 0 ) {
				
				foreach ($bookmarkInfo['data'] as $key => $value) {				
					$linksQuantity = $bookmark->getNumerLinksUsed($bookmarkInfo['data'][$key]['id']);
					$bookmarkInfo['data'][$key]['linksCount'] = $linksQuantity['data']['linksCount'];

					$bookmarkTag = $tag->getTagsByBookmarkId($bookmarkInfo['data'][$key]['id']);
					if($bookmarkTag['status'] == 'ok'){
						$bookmarkInfo['data'][$key]['tags'] = $bookmarkTag['data'];
					} else {
						$bookmarkInfo['data'][$key]['tags'] = array();
					}
				}
				echo json_encode($bookmarkInfo);	
			} else 	{
				echo json_encode(SendResults('ok','','result no found.',''));
			}
			
        break;
        case "searchBookmarkByTitleTagsViewMore":
        	require_once('classes/bookmarks.php');
        	require_once('classes/tags.php');

        	$tag = new Tags($mysqli);
			$bookmark = new Bookmarks($mysqli);
			$bookmarkInfo = $bookmark->searchByTitleTagsGetMore($objData->parameters);

			if (count($bookmarkInfo['data']) > 0 ) {
				
				foreach ($bookmarkInfo['data'] as $key => $value) {				
					$linksQuantity = $bookmark->getNumerLinksUsed($bookmarkInfo['data'][$key]['id']);
					$bookmarkInfo['data'][$key]['linksCount'] = $linksQuantity['data']['linksCount'];

					$bookmarkTag = $tag->getTagsByBookmarkId($bookmarkInfo['data'][$key]['id']);
					if($bookmarkTag['status'] == 'ok'){
						$bookmarkInfo['data'][$key]['tags'] = $bookmarkTag['data'];
					} else {
						$bookmarkInfo['data'][$key]['tags'] = array();
					}
				}
				echo json_encode($bookmarkInfo);	
			} else 	{
				echo json_encode(SendResults('ok','','result no found.',''));
			}
						
        break;
        case "deleteLinkFromBookmark":
        	require_once('classes/bookmarks.php');
			$bookmark = new Bookmarks($mysqli);
			$bookmark->deleteLink($objData->parameters);
			echo json_encode($bookmark->deleteVoteLink($objData->parameters));
        break;
        case "updateBookmark":
        	require_once('classes/bookmarks.php');
        	require_once('classes/tags.php');			
			
			$tag = new Tags($mysqli);
			$bookmark = new Bookmarks($mysqli);

			if(!isset($_SESSION['userId'])){
				echo json_encode(SendResults('error','','Your session has ended on server, please login again.',''));
				return;
			}

			$bookmarkUpdate = $bookmark->update($objData->parameters);

			if($bookmarkUpdate['status']) {
				$bookmark->deleteTags($objData->parameters);
				$tag->insert($objData->parameters->newTags);
				$tagIds = $tag->getIds($objData->parameters->newTags);
				$bookmark->insertTags($tagIds,$objData->parameters->bookmarkId);
				$bookmarkUpdate['status'] = 'ok';
				echo json_encode($bookmarkUpdate);
			} else {
				$bookmarkUpdate['status'] = 'error';
				echo json_encode($bookmarkUpdate);
			}
        break;

        case "resetNotifications":
        	require_once('classes/bookmarks.php');

			try {
			    $bookmark = new Bookmarks($mysqli);
				echo json_encode($bookmark->resetNotifications($objData->parameters->bookmarkIds));
			} catch (Exception $e) {
				echo json_encode(SendResults('error','','Error updating notifications',''));
			}

        break;
        case "deleteBookmark":
        	require_once('classes/bookmarks.php');

        	if(!isset($_SESSION['userId'])){
				echo json_encode(SendResults('error','','Your session has ended on server, please login again.',''));
				return;
			}

			try {
			    $bookmark = new Bookmarks($mysqli);
				echo json_encode($bookmark->delete($objData->parameters));
			} catch (Exception $e) {
				echo json_encode(SendResults('error','','Error deleting bookmark',$e));
			}
        break;
        /*****************************/
        /*******tags******/
        case "searchTags":
        	require_once('classes/tags.php');			
			$tag = new Tags($mysqli);
			
			$infoTag = $tag->search($objData->parameters);
			
			if($infoTag['status'] == 'true') {
				$tagsArray = array();
				foreach ($infoTag['data'] as $key => $value) {
				   array_push($tagsArray,$value['name']);
				}
				//we need to send an array of string
				echo json_encode($tagsArray);
			} else {
				/*send array empty to the directive*/
				echo json_encode(array());	
			}
        break;
        case "getRecentTags":
        	require_once('classes/tags.php');			
			$tag = new Tags($mysqli);
			echo json_encode($tag->getRecent());
        break;
        /*****************************/
        /********links**********/
        case "checkLinkInUse":
        	require_once('classes/links.php');
        	$link = new Links($mysqli);
			echo json_encode($link->isUsed($objData->parameters));		
        break;
        case "voteLink":
        	require_once('classes/links.php');
        	$link = new Links($mysqli);
			
			//we get here the id becasue is more secure
			$objData->parameters->userId = $_SESSION['userId']; 
			$linkVoted = $link->userAlreadyVote($objData->parameters);
			if(!$linkVoted) {
				$link->setVote($objData->parameters);
			} else {
				echo json_encode(SendResults('ok','','You already vote in this link',''));
			}
        break;
        case "reportLink":
        	require_once('classes/links.php');
        	$link = new Links($mysqli);
        	@ session_start();
			//we get here the id becasue is more secure
			$objData->parameters->userId = $_SESSION['userId'];
        	$link->report($objData->parameters);		
        break;
        /*****************************/
        /********notifications**********/
        case "getNotifications":
        	require_once('classes/bookmarks.php');			
			$bookmark = new Bookmarks($mysqli);

			$objData->parameters->userId = $_SESSION['userId'];
			
			$bookmarksWatch = $bookmark->getWatchLinks($objData->parameters);
			$bookmarksNotSeen = $bookmark->getLinksNotSeen($objData->parameters);

			/*check duplicate bookmark on notify*/
			foreach ($bookmarksWatch['data'] as $key => $element) {
				foreach ($bookmarksNotSeen['data'] as $key2 => $element2) {
					if($element2['id'] == $element['id'] ) {
						/*remove the watch because it has more priority your bookmarks than the watch bookmarks*/
						unset($bookmarksWatch[$key]);
					}
				}
			}

			$newArray = array_merge($bookmarksWatch['data'],$bookmarksNotSeen['data']);

			if(count($newArray) > 0 ) {
				echo json_encode(SendResults('ok',$newArray,'',''));
			} else {
				echo json_encode(SendResults('ok',array(),'There are not notifications',''));
			}
			
        break;
        case "watchBookmark":
        	require_once('classes/bookmarks.php');			
			$bookmark = new Bookmarks($mysqli);
			//we get here the id becasue is more secure
			$objData->parameters->userId = $_SESSION['userId'];

			if($objData->parameters->watching) {
				echo json_encode($bookmark->setWatch($objData->parameters));
			} else {
				echo json_encode($bookmark->deleteWatch($objData->parameters));
			}
			
        break;
	}

	/*public function to send respond*/
	function SendResults ($status,$data,$msg,$error){
		$result =  array ();
		$result['status'] = $status;
		$result['msg'] = $msg;
		$result['data'] = $data;
		$result['error'] = $error;
		return $result;
	}

?>