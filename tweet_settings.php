<?php
/*
Twando.com Free PHP Twitter Application
http://www.twando.com/
*/

include('inc/include_top.php');
set_time_limit(0); //For large file uploads

//Set return page
$return_url = "tweet_settings.php?id=" . strip_tags($_GET['id']);;
$pass_msg = "";

//Check if logged in
if (mainFuncs::is_logged_in() != true) {
 $page_select = "not_logged_in";
 $header_info['js_scripts'] =  '<script type="text/javascript" src="inc/scripts/anytime.c.js"></script>' . "\n" . '<link rel="stylesheet" type="text/css" href="inc/scripts/anytime.c.css" />' . "\n" .'<script type="text/javascript" src="inc/scripts/jquery.form.js"></script>' . "\n" . '<script type="text/javascript" src="inc/scripts/jquery.form.min.js"></script>' . "\n" . '';

} else {
 $page_select = "tweet_settings";
 $header_info['on_load'] = "ajax_tweet_settings_tab('tab1');";
 $header_info['js_scripts'] =  '<script type="text/javascript" src="inc/scripts/anytime.c.js"></script>' . "\n" . '<link rel="stylesheet" type="text/css" href="inc/scripts/anytime.c.css" />' . "\n" .'<script type="text/javascript" src="inc/scripts/jquery.form.js"></script>' . "\n" . '<script type="text/javascript" src="inc/scripts/jquery.form.min.js"></script>' . "\n" . '';

 //Get data here
 $q1a = $db->get_user_data($_GET['id']);




if (isset($_POST['a'])){
 if ($_POST['a'] == 'csv_upload') {
  //Bulk CSV upload
  $header_info['on_load'] = "ajax_tweet_settings_tab('tab4');";

  if ($_FILES['csv_file']['name']) {

   //Not ideal, but saves reminding user to chmod a directory
   $handle = @fopen($_FILES['csv_file']['tmp_name'],'r');
   $valid_rows = 0;
   while (($data = @fgetcsv($handle, 1000, ",")) !== FALSE) {;
    if (count($data) == 4) {
     $valid_rows ++;
	$imagefile  = curl($data[2]);
   }
   
     
     $db->query("INSERT INTO " . DB_PREFIX . "scheduled_tweets (owner_id, tweet_content, tweet_image, time_to_post, everyday)
    		  VALUES ('" . $db->prep($q1a['id']) . "','" . $db->prep($data[1]) . "','" . $imagefile . "','" . $db->prep($data[0]) . "','" . $db->prep($data[3]) . "')");
    }
  
   @fclose($handle);

   //Check valid rows
   if ($valid_rows == 0) {
  
    $pass_msg = 19;
   } else {
   
    $pass_msg = 20;
   }

  }

 }



}
}
mainFuncs::print_html($page_select);

include('inc/include_bottom.php');
?>
