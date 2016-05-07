# instagramAPIClass
## Class for searching Facebook, Twitter, and Instagram
**Updated: 05/07/16**
Now Supports Facebook and Twitter.

### Requirements:
This class uses the twitter API class that user, J7mbo has created. 
https://github.com/J7mbo/twitter-api-php

###About:
The class starts by pulling the users twitter information. After taking that information
it takes the user name and full name of the user you originally looked up
and goes through facebook to find the person. Afterwards using the facebook and twitter
information, we now look for the instagram infromation.

###**An example of how the class can be used shows as follows:**
```
$newSearch = new search;
$getInformation = $newSearch("nasa"); // The name of the user you want to search goes inside the quotes.
var_dump($getInformation); // view the results that the class has returned. 


```

**Noted Problems**
Still some results are incorrect with facebook.
Since sometimes the user's name is actually their username
the API does not pull it.
Working on a version that doesn't require the Twitter API class via J7mbo.
