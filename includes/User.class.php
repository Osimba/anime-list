<?php

require_once('Dbh.class.php');

class User extends Dbh {

	/**
	 * Gets all users from DB
	 * 
	 * @access public
	 * @param  none
	 * @return array of user rows
	 */
	public function getAllUsers() {

		$conn = $this->connect();
		$users = array();

		try {
			
			$stmt = $conn->prepare("SELECT id, username, email, user_role, image FROM users");
			
			$stmt->execute();

			$i = 0;


			while($row = $stmt->fetch()) {

				$users[$i]['id'] = $row['id'];
				$users[$i]['username'] = $row['username'];
				$users[$i]['email'] = $row['email'];
				$users[$i]['user_role'] = $row['user_role'];
				$users[$i]['image'] = $row['image'];

				
				$i++;		
			}

			return $users;

		} catch (Exception $e) {

			echo "Error: " . $e->getMessage();
			
		}
	}

	/**
	 * Gets user_id from DB associated with specified username
	 * 
	 * @access public
	 * @param  string
	 * @return int for user id
	 */
	public function getUserId($username) {

		$conn = $this->connect();
		$user = array();

		try {
			
			$stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
			$stmt->bindParam(':username', $username);
			$stmt->execute();

			$row = $stmt->fetch();
	

			return $row['id'];

		} catch (Exception $e) {

			echo "Error: " . $e->getMessage();
			
		}
	}

	/**
	 * Gets user from DB associated with provided user id
	 * 
	 * @access public
	 * @param  none
	 * @return array for user row
	 */
	public function getUserInfo($id) {

		$conn = $this->connect();
		$user = array();

		try {
			
			$stmt = $conn->prepare("SELECT id, username, email, user_role, image FROM users WHERE id = :id");
			$stmt->bindParam(':id', $id);
			$stmt->execute();

			$row = $stmt->fetch();

			$user['id'] = $row['id'];
			$user['username'] = $row['username'];
			$user['email'] = $row['email'];
			$user['user_role'] = $row['user_role'];
			$user['image'] = $row['image'];

				

			return $user;

		} catch (Exception $e) {

			echo "Error: " . $e->getMessage();
			
		}
	}


	/**
	 * Check the user's login credentials
	 * 
	 * @access public
	 * @param  string, string
	 * @return true or false depending on if credentials are correct
	 */
	public function checkUserCredentials($username, $password) {
		$user = array();
		$conn = $this->connect();

		$hash = $this->getUserPassword($username);

		if(!$hash['error']) {

			$auth = password_verify($password, $hash['password']);

		} else {
			$auth = false;
		}

		return $auth;

	}

	/**
	 * Adds token for password reset to DB to verify password change
	 * 
	 * @access public
	 * @param  string, string
	 * @return boolean
	 */
	public function resetRequest($email, $token) {

		$conn = $this->connect();

		try {

			$stmt = $conn->prepare("UPDATE users SET token = :token WHERE email = :email");
				$stmt->bindParam(':token', $token);
				$stmt->bindParam(':email', $email);
				$stmt->execute();

				return $stmt->rowCount() > 0;


			} catch (Exception $e) {

				echo "Error: " . $e->getMessage();
			}

		return false;
	}


	/**
	 * Change user password to newly provided password if email and token are correct
	 * 
	 * @access public
	 * @param  string, string, string
	 * @return boolean
	 */
	public function changePassword($email, $token, $password) {

		$conn = $this->connect();

		try {

			$stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email AND token = :token");
			$stmt->bindParam(':password', $password);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':token', $token);
			$stmt->execute();

			if($stmt->rowCount() > 0) {
				return destroyToken($email);
			}
			
			
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}

		return false;
	}

	/**
	 * Destroys token after password has been reset
	 * 
	 * @access private
	 * @param  string
	 * @return boolean
	 */
	private function destroyToken($email) {

		$conn = $this->connect();

		try {

			$stmt = $conn->prepare("UPDATE users SET token = '' WHERE email = :email");
			$stmt->bindParam(':email', $email);
			$stmt->execute();

			return $stmt->rowCount() > 0;
			
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}

		return false;
	}

	/**
	 * Get's user password from database
	 * 
	 * @access private
	 * @param  string
	 * @return string
	 */
	private function getUserPassword($username) {

		$conn = $this->connect();

		if($this->userExists($username, "")) {		

			try {

				$stmt = $conn->prepare("SELECT password FROM users WHERE username = :username");
				$stmt->bindParam(':username', $username);
				$stmt->execute();

				$row = $stmt->fetch();
				$row['error'] = false;
					
			} catch (Exception $e) {

				echo "Error: " . $e->getMessage();
			}

		} else {

			$row['error'] = true;
		}


		return $row;
		
	}


	/**
	 * Create new user in DB
	 * 
	 * @access public
	 * @param  string, string, string
	 * @return int
	 */
	public function createUser($username, $email, $password) {

		$conn = $this->connect();


		if(!$this->userExists($username, $email)) {

			try {
			
				$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':email', $email);
				$stmt->bindParam(':password', $password);
				
				if ($stmt->execute()) {
					return 101;
				} else {
					return 102;
				}

				

			} catch (Exception $e) {
				echo "Echo: " . $e->getMessage();
			}
		}

		return 103;

		

	}

	/**
	 * Checks if user exists in DB
	 * 
	 * @access private
	 * @param  string, string
	 * @return boolean
	 */
	private function userExists($username, $email) {

		$conn = $this->connect();

		try {
			
			$stmt = $conn->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
			$stmt->bindParam(':username', $username);
			$stmt->bindParam(':email', $email);
			$stmt->execute();

			if($stmt->fetch()) return true;
			else return false;

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * Checks if the user has watched the anime
	 * 
	 * @access public
	 * @param  string, int
	 * @return boolean
	 */
	public function hasWatched($username, $animeId) {

		$conn = $this->connect();

		$userId = $this->getUserId($username);

		try {
			
			$stmt = $conn->prepare("SELECT * FROM watched_anime WHERE user_id = :user_id AND anime_id = :anime_id");
			$stmt->bindParam(":user_id", $userId);
			$stmt->bindParam(":anime_id", $animeId);

			$stmt->execute();
			 
			if($stmt->fetch()) return TRUE;


		} catch (Exception $e) {
			echo "Echo: " . $e->getMessage();
		}

		return FALSE;
	}

	/**
	 * Adds anime to the user's watched list
	 * 
	 * @access public
	 * @param  string, int, double(3,1)
	 * @return boolean
	 */
	public function addToWatched($username, $animeId, $userRating) {

		$conn = $this->connect();

		$userId = $this->getUserId($username);

		try {
			
			$stmt = $conn->prepare("INSERT INTO watched_anime (user_id, anime_id, user_rating) VALUES (:user_id, :anime_id, :user_rating)");
			$stmt->bindParam(":user_id", $userId);
			$stmt->bindParam(":anime_id", $animeId);
			$stmt->bindParam(":user_rating", $userRating);

			if ($stmt->execute()) return TRUE;


		} catch (Exception $e) {
			echo "Echo: " . $e->getMessage();
		}

		return FALSE;
	}

	/**
	 * Adds anime to the user's dream list
	 * 
	 * @access public
	 * @param  string, int
	 * @return boolean
	 */
	public function addToDream($username, $animeId) {

		$conn = $this->connect();

		$userId = $this->getUserId($username);

		try {
			
			$stmt = $conn->prepare("INSERT INTO dream_anime (user_id, anime_id) VALUES (:user_id, :anime_id)");
			$stmt->bindParam(":user_id", $userId);
			$stmt->bindParam(":anime_id", $animeId);

			if ($stmt->execute()) return TRUE;


		} catch (Exception $e) {
			echo "Echo: " . $e->getMessage();
		}

		return FALSE;
	}

	/**
	 * Removes anime from the user's watched list
	 * 
	 * @access public
	 * @param  string, int
	 * @return boolean
	 */
	public function removeFromWatched($username, $animeId) {

		$conn = $this->connect();

		$userId = $this->getUserId($username);

		try {
			
			$stmt = $conn->prepare("DELETE FROM watched_anime WHERE user_id = :user_id AND anime_id = :anime_id");
			$stmt->bindParam(":user_id", $userId);
			$stmt->bindParam(":anime_id", $animeId);

			if ($stmt->execute()) return TRUE;


		} catch (Exception $e) {
			echo "Echo: " . $e->getMessage();
		}

		return FALSE;
	}

	/**
	 * Removes anime from the user's dream list
	 * 
	 * @access public
	 * @param  string, int
	 * @return boolean
	 */
	public function removeFromDream($username, $animeId) {

		$conn = $this->connect();

		$userId = $this->getUserId($username);

		try {
			
			$stmt = $conn->prepare("DELETE FROM dream_anime WHERE user_id = :user_id AND anime_id = :anime_id");
			$stmt->bindParam(":user_id", $userId);
			$stmt->bindParam(":anime_id", $animeId);

			if ($stmt->execute()) return TRUE;


		} catch (Exception $e) {
			echo "Echo: " . $e->getMessage();
		}

		return FALSE;
	}
}