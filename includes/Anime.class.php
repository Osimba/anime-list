<?php

require_once('Dbh.class.php');

class Anime extends Dbh {

	/**
	 * Gets all anime from DB
	 * 
	 * @access public
	 * @param  
	 * @return array of anime rows
	 */
	public function getAllAnime() {

		$conn = $this->connect();
		$animeRow = array();

		//Pull data from database
		try {

			$stmt = $conn->prepare("SELECT * FROM anime");
			$stmt->execute();

			$i = 0;


			while($row = $stmt->fetch()) {

				$animeRow[$i]['id'] = $row['id'];
				$animeRow[$i]['title'] = $row['title'];
				$animeRow[$i]['genre'] = $row['genre'];
				$animeRow[$i]['rating'] = $row['rating'];
				$animeRow[$i]['episodes'] = $row['episodes'];
				$animeRow[$i]['image'] = $row['image'];
				$animeRow[$i]['summary'] = $row['summary'];
				
				$i++;		
			}

			return $animeRow;
			
			
		} catch (Exception $e) {
			echo "Unable to get data from database: " . $e->getMessage();
		}
	}

	/**
	 * Gets specific anime from DB associated with provided anime id
	 * 
	 * @access public
	 * @param  int
	 * @return array for anime row 
	 */
	public function getAnime($id) {

		$conn = $this->connect();
		$anime = array();

		//Pull data from database
		try {

			$stmt = $conn->prepare("SELECT * FROM anime WHERE id = :id");
			$stmt->bindParam(':id', $id);
			$stmt->execute();


			$row = $stmt->fetch();

				$anime['id'] = $row['id'];
				$anime['title'] = $row['title'];
				$anime['genre'] = $row['genre'];
				$anime['rating'] = $row['rating'];
				$anime['episodes'] = $row['episodes'];
				$anime['image'] = $row['image'];
				$anime['summary'] = $row['summary'];
					
		

			return $anime;
			
			
		} catch (Exception $e) {
			echo "Unable to get data from database: " . $e->getMessage();
		}

	}

	


	/**
	 * Add new anime to DB
	 * 
	 * @access public
	 * @param  string, string, int, int, strong
	 * @return null
	 */
	public function addAnime($title, $genre, $rating, $episodes, $image) {

		$conn = $this->connect();


		try {

			$stmt = $conn->prepare("INSERT INTO anime (title, genre, rating, episodes, image, summary) VALUES (:title, :genre, :rating, :episodes, :image, :summary)");
			$stmt->bindParam(':title', $title);
			$stmt->bindParam(':genre', $genre);
			$stmt->bindParam(':rating', $rating);
			$stmt->bindParam(':episodes', $episodes);
			$stmt->bindParam(':image', $image);
			$stmt->bindParam(':summary', $summary);
			$stmt->execute();

			echo "Successfully added " . $title . " to the database";
		
		} catch (Exception $e) {
			echo "Failed to insert row: " . $e->getMessage();
		}
	}

	

}