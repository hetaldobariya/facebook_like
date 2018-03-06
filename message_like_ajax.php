 <?php
include 'db.php';
if(isset($_POST['message_id']) && isSet($_POST['rel']))
{
	$message_id = mysqli_real_escape_string($db,$_POST['message_id']);
	$rel = mysqli_real_escape_string($db,$_POST['rel']);
	$user_id = 2; // User login session id
	if($rel=='Like')
	{
		//---Like----
		$q = mysqli_query($db,"SELECT like_id FROM message_likes WHERE user_id='$user_id' and message_id='$message_id' ");

		if(mysqli_num_rows($q) == 0)
		{
			$query=mysqli_query($db,"INSERT INTO message_likes (message_id,user_id) VALUES('$message_id','$user_id')");
			$q=mysqli_query($db,"UPDATE message SET like_count=like_count+1 WHERE message_id='$message_id'") ;
			$g=mysqli_query($db,"SELECT like_count FROM message WHERE message_id='$message_id'");
			$d=mysqli_fetch_array($g,MYSQLI_ASSOC);
			echo $d['like_count'];
		}
	}
	else
	{
		//---Unlike----
		$q = mysqli_query($db,"SELECT like_id FROM message_likes WHERE user_id='$user_id' and message_id='$message_id' ");
		if(mysqli_num_rows($q)>0)
		{
			$query=mysqli_query($db,"DELETE FROM message_likes WHERE message_id='$message_id' and user_id='$user_id'");
			$q=mysqli_query($db,"UPDATE message SET like_count=like_count-1 WHERE message_id='$message_id'");
			$g=mysqli_query($db,"SELECT like_count FROM message WHERE message_id='$message_id'");
			$d=mysqli_fetch_array($g,MYSQLI_ASSOC);
			echo $d['like_count'];
		}
	}
}
?>