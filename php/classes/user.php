<?php

class User {
	
	public $mysqli;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
    } 

    public function insert($data) {
    	$currentDate = date ('y-m-d');
    	$pass = hash('ripemd256', $data->pass1);	
    	$query = 'INSERT INTO bm_users (name ,password ,email ,registered)
					 VALUES ( "'.$data->name.'","'.$pass.'","'.$data->email.'","'.$currentDate.'")';

		if($this->mysqli->query($query)) {			
			$this->PrepareNextQuery();
			return $this->SendResults('true','','',$this->mysqli->error);
		} else 	{
			return $this->SendResults('false','','Error creating user',$this->mysqli->error);
		}
    }

    public function getUserByEmail($email) {
    	$query = 'SELECT * FROM  bm_users WHERE email =  "'.$email.'"';
    	if($info = $this->mysqli->query($query)) {
    		$this->PrepareNextQuery();
    		$info = db_to_array ($info);
    		$info = decodeHtmlEntity($info);
    		return $this->SendResults('true',$info,'',$this->mysqli->error);
		} else {
			return $this->SendResults('false','','Error getting user',$this->mysqli->error);
		}
    }

    public function createSession($data) {
    	@ session_start();
		$_SESSION['userId'] = $data['id'];
		$_SESSION['userName'] = $data['name'];;
		$_SESSION['level'] = $data['level'];
    }
	
	public function isEmailAvailable($data){	
		$query = 'SELECT email FROM  bm_users WHERE email =  "'.$data->email.'"';
		$info = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$info = db_to_array ($info);	
		
		if (count($info) > 0 ) {
			return false;
		} else {
			return true;
		}
	}

	public function isThereSession() {
		@ session_start();
		if (!isset($_SESSION['userId'])){ 
			return $this->SendResults('error','','There is not a session','');
		} else {
			return $this->SendResults('ok',$_SESSION,'','');
		}
	}

	public function login($data) {

		if(!$data->email == '' || $data->pass == '') {
			$data->email = filter_var($data->email, FILTER_SANITIZE_STRING); 
			$data->pass = filter_var($data->pass, FILTER_SANITIZE_STRING);
			$data->pass = hash('ripemd256',$data->pass);

			$query = 'SELECT * FROM  bm_users 
					WHERE email =  "'.$data->email.'" AND password = "'.$data->pass.'"';

			$info = $this->mysqli->query($query);
			$this->PrepareNextQuery();
			$info = db_to_array ($info);

			if (count($info) > 0 ) {
				$this->createSession($info[0]);
				return $this->SendResults('ok','','Login successful','');	
			} else {
				return $this->SendResults('error','','Login credentials are incorrect','');	
			}
		} else {
			return $this->SendResults('error','','Please write all your data','');
		}
	}

	public function logOut() {
		if (isset($_SESSION['userId']))	{

			$oldUser = $_SESSION['userId'];//save value to check if user was logged
			unset($_SESSION['userId']);		
			unset($_SESSION['userName']);
			unset($_SESSION['level']); 
			$destroyed = session_destroy(); 

			if (!empty($oldUser)) {
				if($destroyed) {
					//user logged was disconnected
					return $this->SendResults('ok','','','');
				} else {
					return $this->SendResults('error','','We could not end this session','');
				}

			} else {
				//user is was not logged but he could access to this page
				return $this->SendResults('ok','','','');
			}

		} else {
			return $this->SendResults('ok','','','');
		}	
	}

	public function isNameAvailable($data){	
		$query = 'SELECT name FROM  bm_users WHERE name =  "'.$data->name.'"';
		$info = $this->mysqli->query($query);
		$this->PrepareNextQuery();
		$info = db_to_array ($info);	
		
		if (count($info) > 0 ) {
			return false;
		} else {
			return true;
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