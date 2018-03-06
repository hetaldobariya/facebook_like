<script src="jquery-1.12.4.js"></script>
<script type="text/javascript">
$(document).ready(function() 
{		
	$('.like').on("click",function()
	{
		var ID = $(this).attr("id");
		var sid = ID.split("like");
		var New_ID = sid[1];
		var REL = $(this).attr("rel");
		var URL='message_like_ajax.php';
		var dataString = 'message_id=' + New_ID +'&rel='+ REL;
		
		$.ajax({
			type: "POST",
			url: URL,
			data: dataString,
			cache: false,
			success: function(html){

				if(REL == 'Like')
				{
					$("#youlike"+New_ID).slideDown('slow').prepend("<span id='you"+New_ID+"'><a href='#'>You</a> like this.</span>.");
					$("#likes"+New_ID).prepend("<span id='you"+New_ID+"'><a href='#'>You</a>, </span>");
					$('#'+ID).html('Unlike').attr('rel', 'Unlike').attr('title', 'Unlike');
				}
				else
				{
					$("#youlike"+New_ID).slideUp('slow');
					$("#you"+New_ID).remove();
					$('#'+ID).attr('rel', 'Like').attr('title', 'Like').html('Like');
				}
			}
		});
	});
});
</script>



<?php
$message_id = 1;
$user_id = 2;
include 'db.php';
 
$q = mysqli_query($db,"SELECT * FROM message
INNER JOIN users_facebook
ON users_facebook.user_id = message.user_id ");
while ($r = mysqli_fetch_array($q))
{ 

?>
	<div class="stbody">
	<div class="stimg"><img src="userprofile.jpg"/></div>
	<div class="sttext">

	<b><?php echo $r['username']; ?></b>:<?php echo $r['message']; ?>
	<div class="sttime">48 seconds ago</div>
	<div>
<?php
		$query = mysqli_query($db,"SELECT U.username, U.user_id, M.message_id, M.message, M.like_count FROM users_facebook U, message M WHERE U.user_id=M.user_id and U.user_id='$user_id'") or die ("query failed".mysqli_error());
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC))
		{
			$message_id = $row['message_id'];
			$message = $row['message'];
			$username = $row['username'];
			$like_count = $row['like_count'];
			$user_id = $row['user_id'];
			
			$q = mysqli_query($db,"SELECT like_id FROM message_likes WHERE user_id = '$user_id' and message_id='$message_id' ");
			// echo "<pre>";
			// print_r($q);
			if(mysqli_num_rows($q) == 0)
			{
				
				echo '<a href="#" class="like" id="like'.$message_id.'" title="Unlike" rel="Unlike">Unlike</a>';
			} 
			else 
			{
				
				echo '<a href="#" class="like" id="like'.$message_id.'" title="Like" rel="Like">Like</a>';
			} 
?>
</div>

<?php 

			if($like_count>0) 
			{
				$query=mysqli_query($db,"SELECT U.username,U.user_id FROM message_likes M, users_facebook U WHERE U.user_id=M.user_id AND M.message_id ='$message_id' LIMIT 3");
?>
		<div class='likeUsers' id="likes<?php echo $message_id ?>">
<?php
				$new_like_count = $like_count-3;
			
				while($row = mysqli_fetch_array($query))
				{
					$like_user_id = $row['user_id'];
					$likeusername = $row['username'];

					if($like_user_id == $user_id)
					{
						echo '<span id="you'.$message_id.'"><a href="'.$likeusername.'">You</a></span>';
					}
					else
					{
					echo '<a href="'.$likeusername.'">'.$likeusername.' </a>';
					} 
				}
				echo 'and '.$new_like_count.' other friends like this';
?>
		</div>
<?php 
			}
			else 
			{
				echo '<div class="likeUsers" id="elikes'.$message_id.'"></div>';
			} 
?>
		</div>
	</div>
<?php 
		} // end of while
}// end of while
?>