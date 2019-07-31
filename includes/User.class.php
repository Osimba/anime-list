<?php

class User extends Dbh {


	public function checkUserCredentials($username, $password) {
		$user = array();
		$conn = $this->connect();

		try {
			
			$stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
			$stmt->bindParam(':username', $username);
			$stmt->bindParam(':password', $password);
			$stmt->execute();

			if($row = $stmt->fetch()) {
				return true;
			} 

			return false;
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	public function getUser($email) {

		$user = array();
		$conn = $this->connect();

		try {

			$stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
			$stmt->bindParam(':email', $email);
			$stmt->execute();

			while ($row = $stmt->fetch()) {
	
				$user['username'] = $row['username'];
				$user['password'] = $row['password'];
				
				return $user;
			}

			$user['error'] = "User doesn't exist!";
	
			return $user;
			
		} catch (Exception $e) {

			echo "Error: " . $e->getMessage();
		}
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
					return "Successfully created account!";
				} else {
					return "Failed to create user";
				}

				

			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		return "User already exists!";

		

	}

	private function userExists($username, $email) {

		$conn = $this->connect();

		try {
			
			$stmt = $conn->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
			$stmt->bindParam(':username', $username);
			$stmt->bindParam(':email', $email);
			$stmt->execute();
			$stmt->store_result();

			return $stmt->num_rows > 0;

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	
}