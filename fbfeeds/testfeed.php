
<?php 

#print_r($fb_response);

#######
####
#### --> 						https://graph.facebook.com/453833084788437/feed?access_token=EAATMI1OV1ZCgBAPe4zpPURiReQHSgjaYf2DyjQ4wN5YL0qaOi4eQGjYkntAZAnmqGSeomzNjDtGxYUs9JxO7ozWGiO7s3YLKxRaVhWoetVBlZBOptAxQs6waZCj8PtXav8X8SxZBmZCH247HjG2wGWq2Tka8UrAZB6NNoWbg2ZC9DAZDZD&fields=id,message,picture,link,name,description,type,icon,created_time,from,object_id&limit=3
#### Link to the feed - can reference from this link. Contains Page ID, Access Tokenb, Field Names & Max Limit
####
####
#######
#https://graph.facebook.com/453833084788437/feed?access_token=EAATMI1OV1ZCgBAPe4zpPURiReQHSgjaYf2DyjQ4wN5YL0qaOi4eQGjYkntAZAnmqGSeomzNjDtGxYUs9JxO7ozWGiO7s3YLKxRaVhWoetVBlZBOptAxQs6waZCj8PtXav8X8SxZBmZCH247HjG2wGWq2Tka8UrAZB6NNoWbg2ZC9DAZDZD&fields=id,message,picture,link,name,description,type,icon,created_time,from,object_id&limit3
#################################################################
## FACEBOOK -> STATUS UPDATE FEED COMPONENTS
$fb_page_id = "1101570009987194";
$profile_photo_src = "https://graph.facebook.com/{$fb_page_id}/picture?type=square";
$access_token = "EAAbG2JPdFF0BAIslNirIRuzklxNcFugScW7DmEUUVyPlRHhq0FFAou4SqiyiD2LWAYZBO0MphVmV9uyXKsrsoHjGBeTZABrQC4gXiI56pDFtzJrrWNZBAzvMd7gj4iwJQ9eGcZAZCkgm5zxf6tgBQsCL2zr9q0ttlg9EuZCLDGHQZDZD";

$fields = "id,message,picture,link,name,description,type,icon,created_time,from,object_id,attachments";
$limit = 3;

$json_link = "https://graph.facebook.com/{$fb_page_id}/feed?access_token={$access_token}&fields={$fields}&limit=3";
$json = file_get_contents($json_link);

$obj = json_decode($json);
?>

<?php
			 
foreach($obj->data as $feed){
	// user's custom message
	if (isset($feed->message)) {
		if(strlen($feed->message) > 160){
		   $message =  substr($feed->message, 0, 160).'...';
		}
	}
	// picture from the link
	if (isset($feed->picture)) {
	$picture = $feed->picture;
	$picture_url_arr = explode('&url=', $picture);
	$picture_url = urldecode($picture_url_arr[0]);
	}
	#Get the post id for LIKES
	#https://graph.facebook.com/453833084788437_781558242015918/likes?summary=true&access_token=EAAHJv7U9gAYBABib1Q94nyOnc48Pbyw9CVQRIH6lY3JP6eHZBon1fFYO8wGkLWog0I074YMV3ItI5zjMShpUy5ICj52ZAwzPZAq6MLcUkQIxaJEtmcUhzjJUDMzl7Ecox89q4FrXK1xlOtWp8hd71IJ1MaHMBWIMQdMlHGFpxPtPsllNYB6MI0U8SR5uUZAigFBmUlgkBgZDZD
	$post_id = $feed->id;
	$likes_link = "https://graph.facebook.com/{$post_id}/likes?summary=true&access_token={$access_token}";
	$likes_json = file_get_contents($likes_link);
	$likes = json_decode($likes_json);
	#print_r($likes);
	#Get the post id for LIKES
	#https://graph.facebook.com/453833084788437_780627955442280/comments?summary=true&access_token=EAAHJv7U9gAYBABib1Q94nyOnc48Pbyw9CVQRIH6lY3JP6eHZBon1fFYO8wGkLWog0I074YMV3ItI5zjMShpUy5ICj52ZAwzPZAq6MLcUkQIxaJEtmcUhzjJUDMzl7Ecox89q4FrXK1xlOtWp8hd71IJ1MaHMBWIMQdMlHGFpxPtPsllNYB6MI0U8SR5uUZAigFBmUlgkBgZDZD
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
	$picture = $feed->picture;
	// useful for photo
	?>
		
	<div class="col-sm-4 col-md-4 col-xs-12">
		<div class="post-thumb">
			<a href="https://www.facebook.com/<?php echo $page_id;?>" target="_blank"><img class="" src="<?php echo $feed->picture;
											?>" alt="" style="margin: 0 auto;width: 50%;"></a> 
			<div class="post-meta">
			<?php if (isset($picture)) {?>
				<span style="color: #13212E;"><i class="fa fa-comments-o"></i> <?php echo $comments->summary->total_count; ?> Comments</span>
				<span style="color: #13212E;"><i class="fa fa-heart"></i> <?php echo $likes->summary->total_count; ?> Likes</span> 
			<?php }?>
			</div>
		</div>
		<div class="entry-header">
			<h3><a href="https://www.facebook.com/<?php echo $page_id;?>" target="_blank"><?php if (isset($feed->name)) { echo $feed->name;} else { echo $feed->from->name;} ?></a></h3>
			<span class="date">Posted <?php echo $ago_value;?></span>
			<span class="category">as a <strong><?php echo $feed->type; ?></strong></span>
		</div>
		
		<div class="entry-content">
			<a href="https://www.facebook.com/<?php echo $post_id;?>" target="_blank"><p><?php if (isset($feed->message)) { echo $message; } else { echo "No Message Content";}?></p></a>
		</div>
	</div>   
	<!-- /Status -->	
<?php 
$message = "";
$object_id = "";
?>
<?php }?>