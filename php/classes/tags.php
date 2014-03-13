<?php

class Tags {
	
	public $mysqli;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
    } 

    public function search ($data) {
    	$query = 'SELECT name FROM bm_tags WHERE name LIKE "'.$data->term.'%" LIMIT 10';

    	if($info = $this->mysqli->query($query)) {
    		$this->PrepareNextQuery();
    		$info = db_to_array ($info);
    		$info = decodeHtmlEntity($info);
    		return $this->SendResults('true',$info,'',$this->mysqli->error);
		} else {
			return $this->SendResults('false','','Error searching tag',$this->mysqli->error);
		}
    }

    public function getTagsByBookmarkId($id) {
    	$query = 'SELECT
				bm_tags.name 
				FROM bm_bookmark_tag 
				LEFT JOIN bm_tags ON bm_tags.id = bm_bookmark_tag.tagid  
				WHERE bookmarkid = '.$id;

		if($info = $this->mysqli->query($query)) {
    		$this->PrepareNextQuery();
    		$info = db_to_array ($info);
    		$info = decodeHtmlEntity($info);
    		return $this->SendResults('ok',$info,'',$this->mysqli->error);
		} else {
			return $this->SendResults('error','','Error searching tag',$this->mysqli->error);
		}
    }

    public function insert ($tags) {
    	$currentDate = date ('y-m-d');
    	foreach ($tags as $key => $value) {
    		$value = trim($value);
    		$value = strtolower($value);
    		$value = (strlen($value) > 25) ? substr($value,0,25) : $value;

			$query = 'INSERT INTO bm_tags (name,created) VALUES ("'.$value.'","'.$currentDate.'")';
			$this->mysqli->query($query);
			$this->PrepareNextQuery();
			//if was inserted
			/*if($this->mysqli->insert_id != 0 ) {
				array_push($idsTagsInserted,$this->mysqli->insert_id);
			}*/
			/*$this->mysqli->error;*/
		}
    }

    public function getIds($tags) {
    	$idsTags = array();
    	foreach ($tags as $key => $value) {
    		$value = trim($value);
    		$value = strtolower($value);

			$query = 'SELECT id FROM bm_tags WHERE name = "'.$value.'"';
			$foundTag = $this->mysqli->query($query);
			$foundTag = db_to_array ($foundTag);
			array_push($idsTags,$foundTag[0]['id']);
			$this->PrepareNextQuery();
    	}
    	return $idsTags;
    }

    public function getRecent() {
    	$query = "SELECT id,name  FROM bm_tags ORDER BY id DESC LIMIT 80";
    	if($info = $this->mysqli->query($query)) {
    		$this->PrepareNextQuery();
    		$info = db_to_array ($info);
    		$info = decodeHtmlEntity($info);
    		return $this->SendResults('ok',$info,'',$this->mysqli->error);
		} else {
			return $this->SendResults('error','','Error getting tags',$this->mysqli->error);
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