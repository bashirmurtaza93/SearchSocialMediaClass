<?php 
/*Version 2 needed for editing 


*/
error_reporting(0);
class search {

     
   public function setInstagram($instagram){
        $this->instagramtoken = $instagram;
    }
    public function getInstagram(){
        return $this->instagramtoken;
    }
   public function setUser($facebook){
        $this->facebookUserToken = $facebook;
   }
   public function getUserToken(){
       return $this->facebookUserToken;
   }
       public function setFacebook($facebook){
        $this->facebooktoken = $facebook;
    }
    public function getFacebook(){
        return $this->facebooktoken;
    }
       public function setTwitter($settings){
        $this->settings = $settings;
    }
    public function getTwitter(){
        return $this->settings;
    }
 
 
 
 
    function getUserInformation($user){
    $user = trim($user);
    $userInformation = array();
    $instagramtoken = $this->getInstagram();
    $settings = $this->getTwitter();
    $facebooktoken = $this->getFacebook();
    require_once('TwitterAPIExchange.php');
    $ta_url='https://api.twitter.com/1.1/users/search.json';
    $getfield = '?q='.$user.'&page=1&count=3';                  // enter your twitter name without the "@" here
    $requestMethod = 'GET';
    $twitter = new TwitterAPIExchange($settings);
    $follow_count=$twitter->setGetfield($getfield)
    ->buildOauth($ta_url, $requestMethod)
    ->performRequest(); 
    $json_twitter = json_decode($follow_count, true);
    $twitter_id = $jsonDecode['data'][0]['id'];
    $image_url = $json_twitter[0]['profile_image_url'];
    $twitterFollowers = @$json_twitter[0]['followers_count'];
    $twitterScreenName = $json_twitter[0]['screen_name'];
    $location = $json_twitter[0]['location'];
    $urlTwitterLink = "https://twitter.com/".$twitterScreenName;
    $twitter_full_name = $json_twitter[0]['name'];
    $userInformation['twitter']['followers'] = $twitterFollowers;
    $userInformation['image'] = $image_url;
    $userInformation['twitter']['url'] = $urlTwitterLink;
    $userInformation['twitter']['screen_name'] = $twitterScreenName;
    $userInformation['twitter']['full_name'] = $twitter_full_name;
    $userInformation['twitter']['location'] = $location;

   $facebookGivenName = $this->facebookSearch($user);
    if($twitterScreenName != NULL){
    $facebookFullName = $this->facebookSearch($twitter_full_name);
    }
    if($twitter_full_name != NULL){
    $facebookScreenName = $this->facebookSearch($twitterScreenName);
    }



     $facebookResult = max(current($facebookScreenName), current($facebookFullName), current($facebookGivenName));
     if(current($facebookGivenName) == $facebookResult){
       $userInformation['facebook']['url'] = 'http://facebook.com/'.key($facebookGivenName);
       $userInformation['facebook']['likes'] = $facebookResult;

    }
    if(current($facebookScreenName) == $facebookResult){
     $userInformation['facebook']['url'] = 'http://facebook.com/'.key($facebookScreenName);
       $userInformation['facebook']['likes'] = $facebookResult;
       
    }
    if(current($facebookFullName) == $facebookResult){
       $userInformation['facebook']['url'] = 'http://facebook.com/'.key($facebookFullName);
       $userInformation['facebook']['likes'] = $facebookResult;
       
    }
    



    if($twitterScreenName != NULL){
    $instagramFullName = $this->instagramSearch($twitter_full_name);
    }
    if($twitter_full_name != NULL){
    $instagramScreenName = $this->instagramSearch($twitterScreenName);
    }
    $instagramGivenName = $this->instagramSearch($user);
    
    $instagramResult = max(current($instagramGivenName), current($instagramScreenName), current($instagramFullName));
    //echo "This is instagram result :".$instagramResult;
       $right = false;
       $somethingArray = array($instagramGivenName, $instagramScreenName, $instagramFullName);
       foreach($somethingArray as $it){
       // var_dump($it);
        echo "<br/>";
   
        if($it['checkCorrect'] == "correct"){
            
            $right = true;
            $something = $it['correct'];
            //echo $something;
            //var_dump($it[$something][$something]);
            $instagramResult = $it[$something][$something];
            $userInformation['instagram']['url'] = 'http://instagram.com/'.$something;
            $userInformation['instagram']['followers'] = $instagramResult;
            $instagramResult = $userInformation['instagram']['followers'];
            $userInformation['instagram']['bio'] = $it[$something]['bio'];
            $userInformation['instagram']['website'] = $it[$something]['website'];
            $userInformation['instagram']['email'] = $it[$something]['email'];
            //var_dump($userInformation);
            // stuff
        }
       }
       
       if($right == false){
       if(current($instagramGivenName) == $instagramResult){
       $userInformation['instagram']['url'] = 'http://instagram.com/'.key($instagramGivenName);
       $userInformation['instagram']['followers'] = $instagramResult;
       $userInformation['instagram']['bio'] = $instagramGivenName['bio'];
       $userInformation['instagram']['website'] = $instagramGivenName['website'];
       $userInformation['instagram']['email'] = $instagramGivenName['email'];
       if($instagramGivenName['image'] != NULL){
        $userInformation['image'] = $instagramGivenName['image'];
       }

    }
    if(current($instagramScreenName) == $instagramResult){
       $userInformation['instagram']['url'] = 'http://instagram.com/'.key($instagramScreenName);
       $userInformation['instagram']['followers'] = $instagramResult;
       $userInformation['instagram']['bio'] = $instagramScreenName['bio'];
       $userInformation['instagram']['website'] = $instagramScreenName['website'];
       $userInformation['instagram']['email'] = $instagramScreenName['email'];
         if($instagramScreenName['image'] != NULL){
        $userInformation['image'] = $instagramScreenName['image'];
       }
       
    }
    if(current($instagramFullName) == $instagramResult){
       $userInformation['instagram']['url'] = 'http://instagram.com/'.key($instagramFullName);
       $userInformation['instagram']['followers'] = $instagramResult;
       $userInformation['instagram']['bio'] = $instagramFullName['bio'];
       $userInformation['instagram']['website'] = $instagramFullName['website'];
       $userInformation['instagram']['email'] = $instagramFullName['email'];
       if($instagramFullName['image'] != NULL){
        $userInformation['image'] = $instagramFullName['image'];
       }
       
    }
    }

    
    $userInformation['total'] = $instagramResult + $facebookResult + $twitterFollowers;
    //var_dump($userInformation);
    
    return $userInformation;
    
    }
    
    function facebookSearch($user){
    $facebooktoken = $this->getFacebook();  
    $facebookArray = array();
    $facebookUserToken = $this->getUserToken();
    // First JSON.
    $user = preg_replace('/\s+/', '+', $user);
    $json_url_p1 = 'https://graph.facebook.com/search?q='.$user.'&type=page&access_token='.$facebooktoken; 
    
    // Second JSON.
    $json_url_p2 = "https://graph.facebook.com/v2.6/".$user."?access_token=".$facebooktoken;
    //third JSON has to use facebookUserToken.
    $json_url_p3 = "https://graph.facebook.com/v2.6/search?q=".$user."&type=user&access_token=".$facebookUserToken;
    // get curl info
    $json_p1 = $this->curl($json_url_p1);
    $json_p2 = $this->curl($json_url_p2);
    $json_p3 = $this->curl($json_url_p3);
    //set JSONp1 information
    $p1_id = $json_p1['data'][0]['id'];
    $p2_id = $json_p2['id'];
    $p3_id = $json_p3['data'][0]['id'];
    // We will check if the ID's are equal, if so we will make one 
    // request and then return the likes. 
    if($p1_id === $p2_id && $p1_id != NULL){
    $json_url_p1_profile = 'https://graph.facebook.com/v2.3/'.$p1_id.'?fields=likes&access_token='.$facebooktoken;
    $json_p1 = $this->curl($json_url_p1_profile);
    $facebookLikes = $json_p1['likes']; 
    $facebookArray[$p1_id] = $facebookLikes;
    return $facebookArray;
    }
    // JSON_p1_Likes
    $json_url_p1_profile = 'https://graph.facebook.com/v2.3/'.$p1_id.'?fields=likes&access_token='.$facebooktoken;
    $json_url_p2_profile = 'https://graph.facebook.com/v2.3/'.$p2_id.'?fields=likes&access_token='.$facebooktoken;
    $json_url_p3_profile = 'https://graph.facebook.com/v2.3/'.$p3_id.'?fields=likes&access_token='.$facebookUserToken;
    //get curl info 
    $json_p1_new = $this->curl($json_url_p1_profile);
    $json_p2_new = $this->curl($json_url_p2_profile);
    $json_p3_new = $this->curl($json_url_p3_profile);
    var_dump($json_p3_new);
    $likes = max($json_p1_new['likes'], $json_p2_new['likes'], $json_p3_new['likes']);
    if($json_p1_new['likes'] === $likes ){
     $facebookArray[$p1_id] = $likes;
     return $facebookArray;
    }
    if($json_p2_new['likes'] === $likes ){
     $facebookArray[$p2_id] = $likes;
     return $facebookArray;
    }
    if($json_p3_new['likes'] === $likes ){
     $facebookArray[$p3_id] = $likes;
     return $facebookArray;
    }
    
     
    

      
        
        
    } // end function 


    function instagramSearch($user){
    $instagramtoken = $this->getInstagram();
    $instagramArray = array();
    $checkUser = preg_replace('/\s+/', '', $user);
    $user = preg_replace('/\s+/', '+', $user);
    $json_url = 'https://api.instagram.com/v1/users/search?q='.$user.'&access_token='.$instagramtoken;

    $json_decode = $this->curl($json_url);
    

    foreach($json_decode['data'] as $info ){
        if($info['username'] == $user || $info['username'] == $checkUser){
        
    
        if($info['username'] == $user){
        $instagramArray['checkCorrect'] = "correct";
        $instagramArray['correct'] = $user;
        
       
 
        $json_url = 'https://api.instagram.com/v1/users/'.$info['id'].'/?access_token='.$instagramtoken;
        $json_decode =  $this->curl($json_url);
        $instagramFollowers = $json_decode['data']['counts']['followed_by'];
        $instagramArray[$user][$json_decode['data']['username']] = $instagramFollowers;
        $instagramArray[$user]['bio'] = $json_decode['data']['bio'];
        $instagramArray[$user]['image'] = $json_decode['data']['profile_picture'];
        $instagramArray[$user]['website'] = $json_decode['data']['website'];
        $bioarr = explode(' ', $instagramArray[$checkUser]['bio']);
        $email ="";
        foreach($bioarr as $it){
        $emailTemp = strpos($it, "@");
        if($emailTemp == TRUE){
        $email = $it;
        break;
                  } // end if
            } // end foreach bio
        $instagramArray[$user]['email'] = $email;
    } // end if
        else {

        $instagramArray['checkCorrect'] = "correct";
        $instagramArray['correct'] = $checkUser;
        
       
 
        $json_url = 'https://api.instagram.com/v1/users/'.$info['id'].'/?access_token='.$instagramtoken;
        $json_decode =  $this->curl($json_url);
        $instagramFollowers = $json_decode['data']['counts']['followed_by'];
        $instagramArray[$checkUser][$json_decode['data']['username']] = $instagramFollowers;
        $instagramArray[$checkUser]['bio'] = $json_decode['data']['bio'];
        $instagramArray[$checkUser]['image'] = $json_decode['data']['profile_picture'];
        $instagramArray[$checkUser]['website'] = $json_decode['data']['website'];
        $bioarr = explode(' ', $instagramArray[$checkUser]['bio']);
        $email ="";
        foreach($bioarr as $it){
        $emailTemp = strpos($it, "@");
        if($emailTemp == TRUE){
        $email = $it;
        break;
        } // end if
        } // end foreach bio
        $instagramArray[$checkUser]['email'] = $email;
        
    }// end else 




        //var_dump($instagramArray);
        return $instagramArray;
        }
        

       

         // end if 

        $json_url = 'https://api.instagram.com/v1/users/'.$json_decode['data'][0]['id'].'/?access_token='.$instagramtoken;
        $json_decode =  $this->curl($json_url);
        $instagramFollowers = $json_decode['data']['counts']['followed_by'];
        $instagramArray[$json_decode['data']['username']] = $instagramFollowers;
        $instagramArray['bio'] = $json_decode['data']['bio'];
        $instagramArray['image'] = $json_decode['data']['profile_picture'];
        $instagramArray['website'] = $json_decode['data']['website'];
        $bioarr = explode(' ', $instagramArray['bio']);
        $email ="";
        foreach($bioarr as $it){
        $emailTemp = strpos($it, "@");
        if($emailTemp == TRUE){
        $email = $it;
        break;
        }
        else continue;
    }
        $instagramArray['email'] = $email;
        return $instagramArray;

    }

    }
    
    
    
    
    
  /* function getUser($id, $getField = '', $extra = ''){
   $instagramtoken = $this->getProperty();
   $url = 'https://api.instagram.com/v1/users/'.$id.''.$getField.'?access_token='.$instagramtoken.''.$extra;
   $jsonDecode = $this->curl($url);
   return $jsonDecode;
       
   }*/
   
   function curl($url){
    $curl_connection = curl_init($url);
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    $json = json_decode(curl_exec($curl_connection), true); 
    return $json;   
       
   } // end function 
   function youtube($user){
   $user = explode('https://www.youtube.com/user/', $user);
   $username = $user[1];
   $key ='AIzaSyAAANUEzxJ9RkLAZOoVxxgP5hrxmrzUOnc';
   $url = 'https://www.googleapis.com/youtube/v3/channels?key='.$key.'&forUsername='.$username.'&part=id';
   $json = $this->curl($url);
   $id = $json['items'][0]['id'];
   $url = 'https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$id.'&key='.$key;
   $json = $this->curl($url);
   $subscriberCount = $json['items'][0]['statistics']['subscriberCount'];
   return $subscriberCount;
   
       
       
   } 
} // end class




/*
How class works 
to get id of user 
ceate a new object and point it to getID("usernamegoeshere");
the return value will be the ID. 
to get a json_decode of a url 
take the object and point it to getUser($getIDObject, '/whatever/goeshere/')
make sure to end 2nd parameter with single quotes. And to put a slash in beginning and end
If extra parameter is needed, make sure to put make it as &count=10
If the user is self just set the ID/ first parameter to self.
$newUser = new search;
$newUser->getUserInformation("chrispaul");
$newUser->getUserInformation("chris paul");
$newUser->getUserInformation("cp3");


$newUser = new search;
$info = $newUser->getUserInformation("chrispaul");
//var_dump($info);
//echo $info['instagram']['url'];

$newUser = new search;
$info = $newUser->getUserInformation("kingjames");
var_dump($info);
$info = $newUser->getUserInformation("lebron james");
var_dump($info);
$info = $newUser->getUserInformation("lebronjames");
var_dump($info);
*/

$newUser = new search;
$info = $newUser->getUserInformation("Lauren Maillian");
var_dump($info);



?>
