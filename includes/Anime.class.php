<?php
require_once('Dbh.class.php');

class Anime extends Dbh {

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

	public function getComments($id) {

		$conn = $this->connect();
		$comments = array();

		//Pull data from database
		try {

			$stmt = $conn->prepare("SELECT * FROM comments WHERE anime_id = :id ORDER BY comment_num DESC");
			$stmt->bindParam(':id', $id);
			$stmt->execute();

			$i = 0;


			while($row = $stmt->fetch()) {

				$comments[$i]['comment_num'] = $row['comment_num'];
				$comments[$i]['user_id'] = $row['user_id'];
				$comments[$i]['anime_id'] = $row['anime_id'];
				$comments[$i]['time_stamp'] = $row['time_stamp'];
				$comments[$i]['comment'] = $row['comment'];

				$i++;					
			}

			return $comments;
			
			
		} catch (Exception $e) {
			echo "Unable to get data from database: " . $e->getMessage();
		}
	}


	/* Create */
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

	public function addComment($user_id, $anime_id, $time_stamp, $comment) {

		$conn = $this->connect();

		try {
			
			$stmt = $conn->prepare("INSERT INTO comments (user_id, anime_id, time_stamp, comment) VALUES (:user_id, :anime_id, :time_stamp, :comment)");
			$stmt->bindParam(':user_id', $user_id);
			$stmt->bindParam(':anime_id', $anime_id);
			$stmt->bindParam(':time_stamp', $time_stamp);
			$stmt->bindParam(':comment', $comment);
			$stmt->execute();

			$newCommentNum = $conn->lastInsertId();

			return $this->getComment($newCommentNum);

		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	private function getComment($comment_num) {

		$conn = $this->connect();

		try {

			$stmt = $conn->prepare("SELECT * FROM comments WHERE comment_num = :comment_num");
			$stmt->bindParam(':comment_num', $comment_num);
			$stmt->execute();

			$row = $stmt->fetch();

			$comment['comment_num'] = $row['comment_num'];
			$comment['user_id'] = $row['user_id'];
			$comment['anime_id'] = $row['anime_id'];
			$comment['time_stamp'] = $row['time_stamp'];
			$comment['comment'] = $row['comment'];

			return $comment;
			
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}

		return null;
	}
}