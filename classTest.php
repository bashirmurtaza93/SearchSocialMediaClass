<?php 

class instagram {
    
    public $instagramtoken = " Token Goes here";
    
   public function setProperty($instagram){
        $this->instagramtoken = $instagram;
    }
    public function getProperty(){
        return $this->instagramtoken;
    }
 
    function getID($user){
    $instagramtoken = $this->getProperty();
    $url = "https://api.instagram.com/v1/users/search?q=".$user."&access_token=".$instagramtoken;
    $jsonDecode = $this->curl($url);
    $id = $jsonDecode['data'][0]['id'];
    return $id;
    }
    
   function getUser($id, $getField){
   $instagramtoken = $this->getProperty();
   $url = 'https://api.instagram.com/v1/users/'.$id.''.$getField.'?access_token='.$instagramtoken;
   $jsonDecode = $this->curl($url);
   return $jsonDecode;
       
   }
   
   function curl($url){
    $curl_connection = curl_init($url);
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    $json = json_decode(curl_exec($curl_connection), true); 
    return $json;   
       
   } // end function 
} // end class







$newUser = new instagram;
$newID = $newUser->getID("blobdoesnotexist");
$json = $newUser->getUser($newID, '/media/recent/');
var_dump($json);



?>
