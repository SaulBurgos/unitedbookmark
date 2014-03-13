<?php

class Bookmarks {
	
	public $mysqli;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
    } 

    public function insert($data) {
    	$currentDate = date ('y-m-d');

    	$data->title = strtolower($data->title);
    	$data->title = ucfirst($data->title);
    	
    	$query = 'INSERT INTO bm_bookmark (title ,description ,userid ,created)
					 VALUES ( "'.$data->title.'","'.$data->description.'",'.$data->userId.',"'.$currentDate.'")';

		if($this->mysqli->query($query)) {			
			$this->PrepareNextQuery();
			return $this->SendResults('ok',array('bookmarkId'=>$this->mysqli->insert_id),'Bookmark Saved',$this->mysqli->error);
		} else 	{
			return $this->SendResults('error','','Error saving bookmark.',$this->mysqli->error);
		}
    }

    public function insertTags($tagsId,$bookmarkId) {
    	foreach ($tagsId as $key => $value) {
    		$query = 'INSERT INTO bm_bookmark_tag (bookmarkid,tagid) VALUES ('.$bookmarkId.','.$value.')';
			$this->mysqli->query($query);
			$this->PrepareNextQuery();
    	}
    }

    public function insertLink($data) {
    	$currentDate = date ('y-m-d');
    	/*$data->linkFavicon = filter_var($data->linkFavicon, FILTER_SANITIZE_URL);*/

    	$query = 'INSERT INTO bm_bookmark_link (linkid,comment,userid,bookmarkid,title,created,seen) 
    			  VALUES ('.$data->linkId.',"'.$data->linkComment.'",'.$data->userId.
    			  	','.$data->bookmarkId.',"'.trim($data->linkTitle).'","'.$currentDate.
    			  	'","no")';

    	if($this->mysqli->query($query)) {			
			$this->PrepareNextQuery();
			return $this->SendResults('ok','','Link to bookmark Saved',$this->mysqli->error);
		} else 	{
			return $this->SendResults('error','','Error saving link to bookmark.',$this->mysqli->error);
		}	
    }

    public function updateFlagNewLinks($data) {
    	
    	$query = 'UPDATE bm_bookmark
					SET 
					hasnewlinks = "yes" 
					WHERE id = '.$data->bookmarkId;
		$this->mysqli->query($query);
		$this->PrepareNextQuery();
    }

    public function isDuplicate($data) {
    	$query = 'SELECT title,id FROM bm_bookmark 
				  WHERE title = "'.$data->title.'"';

		$info = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$info = db_to_array ($info);

		//if bookmark exist so we need to check if the tags are the same
		if (count($info) > 0 ) {
			$object = new stdClass();
   			$object->id = $info[0]['id'];
			$tags = $this->getTags($object);
			$countTagsFound = 0;

			foreach ($data->tags as $key1 => $value1) {				
				foreach ($tags['data'] as $key2 => $value2) {
					if($tags['data'][$key2]['name'] == $data->tags[$key1]) {
						$countTagsFound = $countTagsFound + 1;
					} 
				}
			}

			if($countTagsFound == count($tags['data'])) {
				return $this->SendResults(true,'','bookmark duplicate',$this->mysqli->error);
			} else {
				return $this->SendResults(false,'','bookmark does not exist',$this->mysqli->error);
			} 

		} else {
			return $this->SendResults(false,'','bookmark does not exist',$this->mysqli->error);
		}
    }

    public function isLinkExist($data) {
    	$query = 'SELECT bm_bookmark_link.bookmarkid 
				   FROM bm_bookmark_link 
				   INNER JOIN bm_links ON 
				   bm_links.id = bm_bookmark_link.linkid 
				   WHERE bm_links.link = "'.$data->link.'" 
				   AND bm_bookmark_link.bookmarkid = '.$data->bookmarkId;

		$info = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$info = db_to_array ($info);

		if (count($info) > 0 ) {
			return $this->SendResults(true,'','link already exist in this bookmark, please add another.',$this->mysqli->error);	
		} else {
			return $this->SendResults(false,'','This link does not exist in this bookmark',$this->mysqli->error);
		}
    }

    public function getById($data) {
    	$query = 'SELECT 
				bm_bookmark.id,
				bm_bookmark.title,
				bm_users.name ,
				bm_bookmark.description,
				bm_bookmark.created
				FROM bm_bookmark 
				LEFT JOIN bm_users ON bm_bookmark.userid = bm_users.id 
				WHERE bm_bookmark.id = '.$data->id;

		if($info = $this->mysqli->query($query)) {
    		$this->PrepareNextQuery();
    		$info = db_to_array ($info);
    		$info = decodeHtmlEntity($info);
    		return $this->SendResults('ok',$info[0],'',$this->mysqli->error);
		} else {
			return $this->SendResults('error','','Error getting bookmark info',$this->mysqli->error);
		}		
    }

    public function getLinks($data) {
    	$query = 'SELECT 
					bm_bookmark_link.linkid as id,
					bm_bookmark_link.comment,
					bm_bookmark_link.title ,
					bm_bookmark_link.created ,
					bm_users.name as userName ,
					bm_users.id as userId,
					bm_links.link
					FROM bm_bookmark_link 
					LEFT JOIN bm_links ON bm_links.id = bm_bookmark_link.linkid
					LEFT JOIN bm_users ON bm_bookmark_link.userid = bm_users.id 
					WHERE bookmarkid = '.$data->id. ' 
					ORDER BY bm_bookmark_link.created DESC' ;

		if($info = $this->mysqli->query($query)) {
    		$this->PrepareNextQuery();
    		$info = db_to_array ($info);
    		$info = decodeHtmlEntity($info);
    		return $this->SendResults('ok',$info,'',$this->mysqli->error);
		} else {
			return $this->SendResults('error','','Error getting bookmark links',$this->mysqli->error);
		}	
    }

    public function getTags($data) {
    	$query = 'SELECT  
				bm_tags.id,
				bm_tags.name
				FROM bm_bookmark_tag 
				LEFT JOIN bm_tags ON bm_tags.id = bm_bookmark_tag.tagid 
				WHERE bm_bookmark_tag.bookmarkid = '.$data->id;
					
		if($info = $this->mysqli->query($query)) {
    		$this->PrepareNextQuery();
    		$info = db_to_array ($info);
    		$info = decodeHtmlEntity($info);
    		return $this->SendResults('ok',$info,'',$this->mysqli->error);
		} else {
			return $this->SendResults('error','','Error getting bookmark tags',$this->mysqli->error);
		}	
    }

    public function getRecent($qty) {
    	$query = "SELECT 
				bm_bookmark.id,
				bm_bookmark.title,
				bm_users.name ,
				bm_bookmark.created,
				COUNT(bm_bookmark_link.id) as linksCount
				FROM bm_bookmark 
				LEFT JOIN bm_users ON bm_bookmark.userid = bm_users.id   
				LEFT JOIN bm_bookmark_link 
				ON bm_bookmark_link.bookmarkid = bm_bookmark.id GROUP BY bm_bookmark.id  
				ORDER BY bm_bookmark.id DESC LIMIT ".$qty;

		if($info = $this->mysqli->query($query)) {
    		$this->PrepareNextQuery();
    		$info = db_to_array ($info);
    		$info = decodeHtmlEntity($info);
    		return $this->SendResults('ok',$info,'',$this->mysqli->error);
		} else {
			return $this->SendResults('error','','Error getting tags',$this->mysqli->error);
		}
    }

    public function getAll() {
    	$query = "SELECT 
				bm_bookmark.id,
				bm_bookmark.title,
				bm_users.name ,
				bm_bookmark.created,
				COUNT(bm_bookmark_link.id) as linksCount
				FROM bm_bookmark 
				LEFT JOIN bm_users ON bm_bookmark.userid = bm_users.id   
				LEFT JOIN bm_bookmark_link 
				ON bm_bookmark_link.bookmarkid = bm_bookmark.id GROUP BY bm_bookmark.id  
				ORDER BY bm_bookmark.id";

		if($info = $this->mysqli->query($query)) {
    		$this->PrepareNextQuery();
    		$info = db_to_array ($info);
    		$info = decodeHtmlEntity($info);
    		return $this->SendResults('ok',$info,'',$this->mysqli->error);
		} else {
			return $this->SendResults('error','','Error getting tags',$this->mysqli->error);
		}	
    }

    public function getMore($data) {
    	$query = 'SELECT 
					bm_bookmark.id,
					bm_bookmark.title,
					bm_users.name ,
					bm_bookmark.created,
					COUNT(bm_bookmark_link.id) as linksCount 
					FROM bm_bookmark 
					LEFT JOIN bm_users ON bm_bookmark.userid = bm_users.id   
					LEFT JOIN bm_bookmark_link 
					ON bm_bookmark_link.bookmarkid = bm_bookmark.id 
					WHERE bm_bookmark.id < '.$data->id.'  
					GROUP BY bm_bookmark.id  
					ORDER BY bm_bookmark.id DESC LIMIT 10';

		if($info = $this->mysqli->query($query)) {
    		$this->PrepareNextQuery();
    		$info = db_to_array ($info);
    		$info = decodeHtmlEntity($info);
    		return $this->SendResults('ok',$info,'',$this->mysqli->error);
		} else {
			return $this->SendResults('error','','Error getting tags',$this->mysqli->error);
		}
    }
	

    public function searchByTitle($data) {
    	$query = 'SELECT 
					bm_bookmark.id,
					bm_bookmark.title,
					bm_users.name ,
					bm_bookmark.created
					FROM bm_bookmark 
					LEFT JOIN bm_users ON bm_bookmark.userid = bm_users.id 
					WHERE MATCH(bm_bookmark.title) AGAINST ("'.$data->term.'") 
					ORDER BY bm_bookmark.id DESC LIMIT 10';

		$bookmarks = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$bookmarks = db_to_array ($bookmarks);
		$bookmarks = decodeHtmlEntity($bookmarks);

		if (count($bookmarks) > 0 ) {
			return $this->SendResults('ok',$bookmarks,'',$this->mysqli->error);	
		} 
		else
		{
			return $this->SendResults('ok',array(),'No results found',$this->mysqli->error);	
		}
    }

    public function searchByTitleGetMore($data) {
    	$query = 'SELECT 
					bm_bookmark.id,
					bm_bookmark.title,
					bm_users.name ,
					bm_bookmark.created
					FROM bm_bookmark 
					LEFT JOIN bm_users ON bm_bookmark.userid = bm_users.id 
					WHERE MATCH(bm_bookmark.title) AGAINST ("'.$data->term.'") 
					AND bm_bookmark.id < '.$data->lastId.'
					ORDER BY bm_bookmark.id DESC LIMIT 10';


		$bookmarks = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$bookmarks = db_to_array ($bookmarks);
		$bookmarks = decodeHtmlEntity($bookmarks);

		if (count($bookmarks) > 0 ) {
			return $this->SendResults('ok',$bookmarks,'',$this->mysqli->error);	
		} 
		else
		{
			return $this->SendResults('ok',array(),'No results found',$this->mysqli->error);	
		}
    }

    public function searchByTitleTags($data) {
    	$query = 'SELECT 
					DISTINCT b.id,
					b.title,
					bm_users.name ,
					b.created 
					FROM bm_bookmark AS b 
					INNER JOIN bm_bookmark_tag AS bt ON b.id=bt.bookmarkid 
					INNER JOIN bm_tags AS t ON bt.tagid = t.id 
					LEFT JOIN bm_users ON b.userid = bm_users.id 
					WHERE MATCH (b.title) 
					AGAINST ("'.$data->term.'") 
					AND MATCH (t.name) 
					AGAINST ("'.$data->tags.'") 
					ORDER BY b.id DESC LIMIT 10';

		$bookmarks = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$bookmarks = db_to_array ($bookmarks);
		$bookmarks = decodeHtmlEntity($bookmarks);

		if (count($bookmarks) > 0 ) {
			return $this->SendResults('ok',$bookmarks,'',$this->mysqli->error);	
		} 
		else
		{
			return $this->SendResults('ok',array(),'No results found',$this->mysqli->error);	
		}
    }

    public function searchByTitleTagsGetMore($data) {
    	$query = 'SELECT 
					DISTINCT b.id,
					b.title,
					bm_users.name ,
					b.created 
					FROM bm_bookmark AS b 
					INNER JOIN bm_bookmark_tag AS bt ON b.id=bt.bookmarkid 
					INNER JOIN bm_tags AS t ON bt.tagid = t.id 
					LEFT JOIN bm_users ON b.userid = bm_users.id 
					WHERE MATCH (b.title) 
					AGAINST ("'.$data->term.'") 
					AND MATCH (t.name) 
					AGAINST ("'.$data->tags.'") 
					AND b.id < '.$data->lastId.' 
					ORDER BY b.id DESC LIMIT 10';

		$bookmarks = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$bookmarks = db_to_array ($bookmarks);
		$bookmarks = decodeHtmlEntity($bookmarks);

		if (count($bookmarks) > 0 ) {
			return $this->SendResults('ok',$bookmarks,'',$this->mysqli->error);	
		} 
		else
		{
			return $this->SendResults('ok',array(),'No results found',$this->mysqli->error);	
		}
    }

    public function getBookmarkByUserToGrid($user) {
    	$query = 'SELECT 
    				bm_bookmark.id,
					bm_bookmark.title,
					bm_bookmark.description,
					bm_bookmark.created
					FROM bm_bookmark 
					WHERE bm_bookmark.userid = '.$user;

		$bookmarks = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$bookmarks = db_to_array ($bookmarks);
		$bookmarks = decodeHtmlEntity($bookmarks);
		
		return $bookmarks;
    }

    public function getLinksByBookmarkToGrid($bookmarkId) {

    	$query = 'SELECT 
					bm_bookmark_link.linkid,
					bm_bookmark_link.title,
					bm_links.link,
					bm_bookmark_link.created,
					bm_users.name
					FROM 
					bm_bookmark_link 
					LEFT JOIN bm_users ON bm_users.id = bm_bookmark_link.userid
					LEFT JOIN bm_links ON bm_links.id = bm_bookmark_link.linkid
					WHERE bm_bookmark_link.bookmarkid = '.$bookmarkId;

		$links = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$links = db_to_array ($links);
		$links = decodeHtmlEntity($links);
		return $links;
    }

    public function deleteTags($data) {
    	$query = 'DELETE FROM bm_bookmark_tag 
					WHERE bookmarkid = '.$data->bookmarkId;
		$this->mysqli->query($query);
		$this->PrepareNextQuery();
    }

    public function delete($data) {
		$queryBookmark = 'DELETE FROM bm_bookmark 
						  WHERE id = '.$data->bookmarkId;

		$queryBookmarkLink = 'DELETE FROM bm_bookmark_link 
				              WHERE bookmarkid = '.$data->bookmarkId;

		$queryBookmarkVote = 'DELETE FROM bm_bookmark_link_vote 
							  WHERE bookmarkid = '.$data->bookmarkId;

		$queryBookmarkTag = 'DELETE FROM bm_bookmark_tag 
							  WHERE bookmarkid = '.$data->bookmarkId; 

		$this->mysqli->query($queryBookmark);
		$this->PrepareNextQuery();
		$this->mysqli->query($queryBookmarkLink);
		$this->PrepareNextQuery();
		$this->mysqli->query($queryBookmarkVote);
		$this->PrepareNextQuery();
		$this->mysqli->query($queryBookmarkTag);
		$this->PrepareNextQuery();

		return $this->SendResults('ok','','',$this->mysqli->error);
    }

    public function update($data) {

    	$data->newTitle = strtolower($data->newTitle);
    	$data->newTitle = ucfirst($data->newTitle);
    	
    	$query = 'UPDATE bm_bookmark
					SET 
					title = "'.$data->newTitle.'" , 
					description = "'.$data->newDescription.'"   
					WHERE id = '.$data->bookmarkId;

		if($this->mysqli->query($query)) {
    		$this->PrepareNextQuery();
    		return $this->SendResults(true,'','',$this->mysqli->error);
		} else {
			return $this->SendResults(false,'','Error updating bookmark',$this->mysqli->error);
		}
    } 

    public function getNumerLinksUsed($id) {
    	$query = 'SELECT count(bm_bookmark_link.id) as countLinks FROM bm_bookmark_link 
				LEFT JOIN bm_links on bm_bookmark_link.linkid = bm_links.id 
				WHERE bm_bookmark_link.bookmarkid = '.$id;
		$info = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$info = db_to_array ($info);
		return $this->SendResults('ok',array('linksCount'=>$info[0]['countLinks']),'',$this->mysqli->error);
    }

    public function deleteLink($data) {
    	$query = 'DELETE FROM bm_bookmark_link 
					WHERE linkid = '.$data->linkId.' AND bookmarkid = '.$data->bookmarkId;

		if($this->mysqli->query($query)) {			
			$this->PrepareNextQuery();
			return $this->SendResults('true','','',$this->mysqli->error);
		} else 	{
			return $this->SendResults('false','','Error deleting link',$this->mysqli->error);
		}
    }

    public function deleteVoteLink($data) {
    	$query = 'DELETE FROM bm_bookmark_link_vote 
					WHERE linkid = '.$data->linkId.' AND bookmarkid = '.$data->bookmarkId;

		if($this->mysqli->query($query)) {			
			$this->PrepareNextQuery();
			return $this->SendResults('true','','',$this->mysqli->error);
		} else 	{
			return $this->SendResults('false','','Error deleting link',$this->mysqli->error);
		}
    }

    public function getQtyBookmarkWithNewLinks($data) {
    	$query = 'SELECT 
					COUNT(id) as number
					FROM bm_bookmark 
					WHERE userid = '.$data['userId'].' AND hasnewlinks = "yes"';

		$info = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$info = db_to_array ($info);

		//get newlink on watch bookmarks
		$queryWatch = 'SELECT bookmarkid,date_lastcheck 
						FROM bm_watch_bookmark 
						WHERE userid = '.$data['userId'];

		$bookmarksWatch = $this->mysqli->query($queryWatch);
		$this->PrepareNextQuery();
		$bookmarksWatch = db_to_array ($bookmarksWatch);

		if (count($bookmarksWatch) > 0) {
			foreach ($bookmarksWatch as $key => $element) {	
				
				$query2 = 'SELECT  COUNT(id) as qty FROM bm_bookmark_link 
						WHERE bookmarkid = '.$element['bookmarkid'].' 
						AND created > "'.$element['date_lastcheck'].'"';
				
				$infoLinkAdded = $this->mysqli->query($query2);
				$this->PrepareNextQuery();
				$infoLinkAdded = db_to_array ($infoLinkAdded);
				$info[0]['number'] =  $info[0]['number'] + $infoLinkAdded[0]['qty'];
			}
		}

		if($info[0]['number'] > 0) {
			return $info[0]['number'];
		} else {
			return '';
		}

    }

    public function resetNotifications($data) {
    	$currentDate = date ('y-m-d');
    	foreach ($data as $key => $value)
		{
			$queryBookmark = 'UPDATE bm_bookmark 
					SET hasnewlinks = "no" 
					WHERE id = '.$data[$key];

			$queryBookmarkLink = 'UPDATE bm_bookmark_link 
								  SET seen = "yes"  
								  WHERE bookmarkid = '.$data[$key];

			$queryWatchBookmarks = 'UPDATE bm_watch_bookmark 
									SET date_lastcheck = "'.$currentDate.'"  
									WHERE bookmarkid = '.$data[$key];
			
			$this->mysqli->query($queryBookmark);
			$this->PrepareNextQuery();
			$this->mysqli->query($queryBookmarkLink);
			$this->PrepareNextQuery();
			$this->mysqli->query($queryWatchBookmarks);
			$this->PrepareNextQuery();			
		}
		return $this->SendResults('ok','','',$this->mysqli->error);
    }

    public function getWatchLinks($data) {
		$query = 'SELECT 
					bm_watch_bookmark.bookmarkid as id,
					bm_watch_bookmark.date_lastcheck,
					bm_bookmark.title 
					FROM bm_watch_bookmark
					LEFT JOIN bm_bookmark ON bm_bookmark.id = bm_watch_bookmark.bookmarkid
					WHERE bm_watch_bookmark.userid = '.$data->userId;

		$bookmarks = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$bookmarks = db_to_array ($bookmarks);
		$bookmarks = decodeHtmlEntity($bookmarks);  

		if (count($bookmarks) > 0 ) {			
			foreach ($bookmarks as $key => $value)
			{
				$queryLink = 'SELECT 
							bm_bookmark_link.title,
							bm_links.link,
							bm_bookmark_link.created, 
							bm_users.name  
							FROM 
							bm_bookmark_link 
							LEFT JOIN bm_links ON bm_links.id = bm_bookmark_link.linkid 
							LEFT JOIN bm_users ON bm_bookmark_link.userid = bm_users.id 
							WHERE bm_bookmark_link.bookmarkid = '.$bookmarks[$key]['id'].' 
							AND bm_bookmark_link.created > "'.$bookmarks[$key]['date_lastcheck'].'"';

				$links = $this->mysqli->query($queryLink);
				$this->PrepareNextQuery();
				$links = db_to_array ($links);

				if(count($links) > 0) {
					$links = decodeHtmlEntity($links);
					$bookmarks[$key]['links'] = $links;
				} else {
					// if dont have links remove from array
					unset($bookmarks[$key]);
				}

			}
			return $this->SendResults('ok',$bookmarks,'',$this->mysqli->error);
		} 
		else 
		{
			return $this->SendResults('ok',array(),'There are not notifications',$this->mysqli->error);	
		}

    }

    public function getLinksNotSeen($data) {

    	$query = 'SELECT 
					id,title
					FROM bm_bookmark 
					WHERE userid = '.$data->userId.' AND hasnewlinks = "yes"';

		$bookmarks = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$bookmarks = db_to_array ($bookmarks);
		$bookmarks = decodeHtmlEntity($bookmarks);

		if (count($bookmarks) > 0 ) {			
			foreach ($bookmarks as $key => $value)
			{
				$queryLink = 'SELECT 
							bm_bookmark_link.title,
							bm_links.link,
							bm_bookmark_link.created, 
							bm_users.name  
							FROM 
							bm_bookmark_link 
							LEFT JOIN bm_links ON bm_links.id = bm_bookmark_link.linkid 
							LEFT JOIN bm_users ON bm_bookmark_link.userid = bm_users.id 
							WHERE bm_bookmark_link.bookmarkid = '.$bookmarks[$key]['id'].' 
							AND bm_bookmark_link.seen = "no"';

				$links = $this->mysqli->query($queryLink);
				$this->PrepareNextQuery();
				$links = db_to_array ($links);
				$links = decodeHtmlEntity($links);
				$bookmarks[$key]['links'] = $links;
			}
			return $this->SendResults('ok',$bookmarks,'',$this->mysqli->error);
		} 
		else 
		{
			return $this->SendResults('ok',array(),'There are not notifications',$this->mysqli->error);	
		}
    }

    public function setWatch($data) {
    	$currentDate = date ('y-m-d');
    	$query = 'INSERT INTO bm_watch_bookmark 
    			(bookmarkid,userid,date_lastcheck) VALUES 
    			('.$data->bookmarkId.','.$data->userId.',"'.$currentDate.'")';

    	if($this->mysqli->query($query)) {			
			$this->PrepareNextQuery();
			return $this->SendResults('true','','',$this->mysqli->error);
		} else 	{
			return $this->SendResults('false','','Error watching bookmark',$this->mysqli->error);
		}    	
    }

    public function deleteWatch($data) {
    	$query = 'DELETE FROM bm_watch_bookmark 
					WHERE bookmarkid = '.$data->bookmarkId.' AND userid = '.$data->userId;

    	if($this->mysqli->query($query)) {			
			$this->PrepareNextQuery();
			return $this->SendResults('true','','',$this->mysqli->error);
		} else 	{
			return $this->SendResults('false','','Error deleting bookmark',$this->mysqli->error);
		}    	
    }

    public function isAlreadyWatch($userId,$bookmarkid) {
    	$query = 'SELECT id FROM bm_watch_bookmark 
    			WHERE  bookmarkid = '.$bookmarkid.' AND userid = '.$userId;

    	$watching = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$watching = db_to_array ($watching);

		if (count($watching) > 0 ) {
			return 'true';
		} 
		else
		{
			return 'false';
		}    	
    }

	public function SendResults ($status,$data,$msg,$error){
		$result =  array ();
		$result['status'] = $status;
		$result['msg'] = $msg;
		$result['data'] = $data;
		$result['error'] = $error;
		return $result;
	}
	
	public function PrepareNextQuery() {
		if ($this->mysqli->more_results()) {
			$this->mysqli->next_result();
		}
	}
}	
?>