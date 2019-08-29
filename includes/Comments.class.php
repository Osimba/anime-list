<?php 
	
require_once('Dbh.class.php');

/*
    Comments Class
    Handle all things related to comments - Creating new comments, retreiving comments, deleting comments
*/




class Comments extends Dbh
{

	/**
	 * Gets all comments from DB associated with provided anime
	 * 
	 * @access public
	 * @param  int
	 * @return array of comment rows from specified anime
	 */
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

	/**
	 * Get comment from DB associated with provided comment number
	 * 
	 * @access private
	 * @param  int
	 * @return array for comment row
	 */
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

	//Create
	/**
	 * Add new comment to DB
	 * 
	 * @access public
	 * @param  int, int, string, string
	 * @return returns comment number for inserted comment
	 */
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
}

?>