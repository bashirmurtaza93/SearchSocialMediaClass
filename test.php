<?php 
/* A quick example of how the class is used.

*/
    include 'searchAPI.php';
    
    $newSearch = new search;
    $info = $newUser->getUserInformation('nasa'); 
    $full_name = $info['twitter']['full_name']; 
    $screen_name = $info['twitter']['screen_name'];
    $twitterFollowers = numberAbbreviation($info['twitter']['followers']);
    $twitter_url = $info['twitter']['url'];
    $facebookLikes = numberAbbreviation($info['facebook']['likes']);
    $facebook_url = $info['facebook']['url'];
    $inst_count_followers = $info['instagram']['followers'];
    $instagram_url = $info['instagram']['url'];
    $image_url = $info['image'];
    $total = $info['total'];
    $image_url = $info['image'];
    $bio = $info['instagram']['bio'];
    $website = $info['instagram']['website'];
    $location = $info['twitter']['location'];
    $email = $info['instagram']['email'];
    $bio = preg_replace("/'/", "''", $bio); //if storing information, remove any single quotes and replace with double single quotes.
    $full_name = preg_replace("/'/", "''", $full_name); //if storing information, remove any single quotes and replace with double single quotes.
    $original = preg_replace("/'/", "''", $original);//if storing information, remove any single quotes and replace with double single quotes.
    
    /* You don't have to store the information in variables, like I did. 
       Only easier for me to naviage through. 
  
       */
