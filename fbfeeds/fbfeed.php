
<?php 

## FACEBOOK -> STATUS UPDATE FEED COMPONENTS
$fb_page_id = "1101570009987194";
$profile_photo_src = "https://graph.facebook.com/{$fb_page_id}/picture?type=square";
$access_token = "EAAbG2JPdFF0BAIslNirIRuzklxNcFugScW7DmEUUVyPlRHhq0FFAou4SqiyiD2LWAYZBO0MphVmV9uyXKsrsoHjGBeTZABrQC4gXiI56pDFtzJrrWNZBAzvMd7gj4iwJQ9eGcZAZCkgm5zxf6tgBQsCL2zr9q0ttlg9EuZCLDGHQZDZD";

$fields = "id,message,picture,link,name,description,type,icon,created_time,from,object_id";
$limit = 3;

$json_link = "https://graph.facebook.com/{$fb_page_id}/feed?access_token={$access_token}&fields={$fields}&limit=3";
$json = file_get_contents($json_link);

$obj = json_decode($json);
?>

<?php
$z=1;			 
foreach($obj->data as $feed){
	// picture from the link
	if (isset($feed->picture)) {
	$picture = $feed->picture;
	$picture_url_arr = explode('&url=', $picture);
	$picture_url = urldecode($picture_url_arr[0]);
	}
	
	#Get the post id for LIKES
	$post_id = $feed->id;
	$likes_link = "https://graph.facebook.com/{$post_id}/likes?summary=true&access_token={$access_token}";
	$likes_json = file_get_contents($likes_link);
	$likes = json_decode($likes_json);
	#print_r($likes);
	#Get the post id for Comments
	
	$post_id = $feed->id;
	$comments_link = "https://graph.facebook.com/{$post_id}/comments?summary=true&access_token={$access_token}";
	$comments_json = file_get_contents($comments_link);
	$comments = json_decode($comments_json);
	#print_r($likes);
	
	// link posted
	if (isset($feed->link)) {
	$link = $feed->link;
	}
	// name or title of the link posted
	#$name = $feed->name;
	$description = "";
	#$description = $feed->description;
	#$type = $feed->type;
 
	// when it was posted
	$created_time = $feed->created_time;
	$converted_date_time = date( 'Y-m-d H:i:s', strtotime($created_time));
	$ago_value = time_elapsed_string($converted_date_time);
 
	// from
	$page_name = $feed->from->name;
	$page_id = $feed->from->id;
	if (isset($feed->object_id)) {
		$object_id = $feed->object_id;
	} else {
		$object_id = $feed->from->id;
	}
	
	// useful for photo
	
	
	// user's custom message
	$message="No message content, <a href='https://www.facebook.com/{$post_id}' target='_blank'>click here</a> to view the post";
	if (isset($feed->message)) {
		if(strlen($feed->message) > 300){
		   $message =  substr($feed->message, 0, 300).'...';
		}
		else {
			$message = $feed->message;
		}
	}
	?>
	<div class="col-sm-4 col-md-4 col-xs-12" style="padding-top: 20px; padding-bottom: 10px; background-color: white;">
		<div class="post-thumb" style="">
			<a href="https://www.facebook.com/<?php echo $page_id;?>" target="_blank"><img class="img-responsive" src="<?php 
											echo "https://graph.facebook.com/" .$object_id. "/picture"; 
											if ($object_id === $feed->from->id) {echo "?type=large";}
											?>" alt="" style="height: 300px; margin: 0 auto;"></a> 
			<div class="post-meta">
			<?php if (isset($picture)) {?>
				<span style="color: white;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;"><i class="fa fa-comments-o"></i> <?php echo $comments->summary->total_count; ?> Comments</span><br>
				<span style="color: white;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;"><i class="fa fa-heart"></i> <?php echo $likes->summary->total_count; ?> Likes</span> 
			<?php }?>
			</div>
		</div>
		<div class="entry-header text-center" style="height: 30px;color: black">
			<span class="date">Posted <?php echo $ago_value;?></span>
			<span class="category">as a <strong style="color:#8E205D;"><?php echo $feed->type; ?></strong></span>
		</div>
		
		<div class="entry-content text-center" style="color: black; max-height: 230px;">
			<p><?php if (isset($feed->message)) { echo $message; } else { echo "No Message Content";}?></p>
		</div>
		<div class="text-center" style="height: 50px;">
            <button type="submit" class="btn-submit">Read More <i class="fa fa-facebook" style="color: blue;text-size:20px;" aria-hidden="true"></i></button>
		</div>
	</div>   
	<!-- /Status -->	
<?php $message = "";?>
<?php }?>