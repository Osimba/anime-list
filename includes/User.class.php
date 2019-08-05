<?php

class User extends Dbh {


	public function checkUserCredentials($username, $password) {
		$user = array();
		$conn = $this->connect();

		$hash = $this->getUser($username);

		if(!$hash['error']) {

			$auth = password_verify($password, $hash['password']);

		} else {
			$auth = false;
		}

		return $auth;

	}

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

	public function changePassword($email, $token, $password) {

		$conn = $this->connect();

		try {

			$stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email AND token = :token");
			$stmt->bindParam(':password', $password);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':token', $token);
			$stmt->execute();

			if($stmt->rowCount() > 0) {
				return destroyToken($email, $token);
			}
			
			
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}

		return false;
	}

	private function destroyToken($email, $token) {

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

	private function getUser($username) {

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


	/* Create */
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
				echo $e->getMessage();
			}
		}

		return 103;

		

	}

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

	
}