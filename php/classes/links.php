<?php

class Links {
	
	public $mysqli;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
    }

    public function insert($data) {
    	$currentDate = date ('y-m-d');
    	$data->link = filter_var($data->link, FILTER_SANITIZE_URL);
    	
    	$query = 'INSERT INTO bm_links (link,userid,created) 
    			  VALUES ("'.$data->link.'",'.$data->userId.',"'.$currentDate.'")';
		if($this->mysqli->query($query)) {			
			$this->PrepareNextQuery();
			return $this->SendResults('ok',array('linkId'=>$this->mysqli->insert_id),'link Saved',$this->mysqli->error);
		} else 	{
			return $this->SendResults('error','','Error saving link.',$this->mysqli->error);
		}    
    } 

    public function getLikesByLinkIdAndBookmarkId($linkId,$bookmarkId) {
    	$query =   'SELECT COUNT(bm_bookmark_link_vote.useful) AS like_ 
					FROM bm_bookmark_link_vote 
					WHERE bm_bookmark_link_vote.bookmarkid = '.$bookmarkId.'   
					AND bm_bookmark_link_vote.linkid = '.$linkId.' 
					AND bm_bookmark_link_vote.useful = 1 ';

		$info = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$info = db_to_array ($info);
		return $info[0]['like_'];	
    }

    public function getNoLikesByLinkIdAndBookmarkId($linkId,$bookmarkId) {
    	$query =   'SELECT COUNT(bm_bookmark_link_vote.useful) AS nolike 
					FROM bm_bookmark_link_vote 
					WHERE bm_bookmark_link_vote.bookmarkid = '.$bookmarkId.'   
					AND bm_bookmark_link_vote.linkid = '.$linkId.' 
					AND bm_bookmark_link_vote.useful = 0 ';

		$info = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$info = db_to_array ($info);
		return $info[0]['nolike'];	
    }

    public function userAlreadyVote($data) {
    	$query = 'SELECT useful FROM bm_bookmark_link_vote 
				  WHERE userid = '.$data->userId.' AND linkid = '.$data->linkId;

		$info = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$info = db_to_array ($info);

		if (count($info) > 0 ) {
			return true;
		} else {
			return false;
		}
    }

    public function setVote($data) {
    	$query = 'INSERT INTO bm_bookmark_link_vote (userid,linkid,bookmarkid,useful) 
				  VALUES ('.$data->userId.','.$data->linkId.','.$data->bookmarkId.','.$data->vote.')';
		if($this->mysqli->query($query)) {			
			$this->PrepareNextQuery();
			return $this->SendResults('ok','','link vote',$this->mysqli->error);
		} else 	{
			return $this->SendResults('error','','Error voting link.',$this->mysqli->error);
		}		  
    }

    public function report($data) {

    	$currentDate = date ('y-m-d');
    	$query = 'INSERT INTO bm_report_link (bookmarkid,linkid,userid,comment,created) 
    			VALUES ('.$data->bookmarkid.','.$data->linkid.','.$data->userId.',"'.$data->comment.'","'.$currentDate.'")';
    	$this->mysqli->query($query);
		$this->PrepareNextQuery();
    }

    public function isUsed($data) {
    	$query = 'SELECT 
				bm_links.id as linkId,
				bm_links.link,
				bm_links.userid, 
				bm_users.name ,
				bm_bookmark_link.bookmarkid as bookmarkId
				FROM bm_links 
				LEFT JOIN bm_bookmark_link ON bm_bookmark_link.linkid = bm_links.id  
				LEFT JOIN bm_users ON bm_users.id = bm_links.userid 
				WHERE bm_links.link = "'.$data->link.'"';

		$info = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$info = db_to_array ($info);
		$info = decodeHtmlEntity($info);
    	
    	if(count($info) > 0 ) {
    		return $this->SendResults('true',$info[0],'',$this->mysqli->error);
    	} else {
    		return $this->SendResults('false','','Link is not in used',$this->mysqli->error);
    	}
    }

    public function updateCountUsed($data) {
    	$query = 'SELECT reused FROM bm_links WHERE id = '.$data->linkId;
    	$info = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$info = db_to_array ($info);
		$newCount = intval($info[0]['reused']) + 1;
		
		$queryUpdate = 'UPDATE bm_links 
						SET reused = '.$newCount.' WHERE id = '.$data->linkId;
		$this->mysqli->query($queryUpdate);
		$this->PrepareNextQuery();
    }

    public function getTitle($data) {

    	$html = $this->getContentFromUrl($data->link);
    	//treat utf-8 coding
		$searchPage = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8"); 
    	
    	//parsing begins here:
		$doc = new DOMDocument();
		@$doc->loadHTML($searchPage);

		$nodes = $doc->getElementsByTagName('title');
		/*$linkNodes = $doc->getElementsByTagName('link');*/

		$titleLink = '';

		if($nodes->length > 0) {
			//get and display what you need:
			$titleLink = $nodes->item(0)->nodeValue;
		} else {
			$titleLink = $this->getDomain($data->link);
		}

		$titleLink = strtolower($titleLink);
    	$titleLink = ucfirst($titleLink);

    	$titleData = array(
    		'linkTitle' =>htmlentities($titleLink, ENT_QUOTES,'UTF-8'),
    		'favicon' =>  ''/*$this->searchFaviconUrl($linkNodes);*/
    	);

		return $this->SendResults('true',$titleData,'',$this->mysqli->error); 
    }

    public function getDomain ($url) {

	    $host = @parse_url($url, PHP_URL_HOST);
	 
	    // If the URL can't be parsed, use regex to extra the domain
	    if (!$host) {
	        /*$host = $url;*/
	        // get host name from URL
			preg_match("/^(http:\/\/)?([^\/]+)/i",$url, $matches);
			$host = $matches[2];
			// get last two segments of host name
			preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
			return $matches[0];
	    }	 
	    // The "www." prefix isn't really needed if you're just using
	    // this to display the domain to the user
	    if (substr($host, 0, 4) == "www.") {
	        $host = substr($host, 4);
	    }
	 	
	    // You might also want to limit the length if screen space is limited
	    if (strlen($host) > 70) {
	        $host = substr($host, 0, 65) . '...';
	    }	 
	    return $host;
	}


    public function getContentFromUrl($url) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    $data = curl_exec($ch);
	    curl_close($ch);
	    return $data;
	}

	public function searchFaviconUrl($links) {
		$url = '';
	 	for($i=0;$i < $links->length;$i++ )
        {
            $link = $links->item($i);
            if( $link->getAttribute('rel') == 'icon' || 
            	$link->getAttribute('rel') == "Shortcut Icon" || 
            	$link->getAttribute('rel') == "shortcut icon")
            {
                $url = $link->getAttribute('href');
            }
        }
        return $url;
	}
	
	public function SendResults ($status,$data,$msg,$error) {
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