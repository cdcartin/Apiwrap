<?PHP
/**
 * API Wrapper
 *
 * A simple wrapper for the Facebook PHP SDK that returns the details for a specific public page
 *
 * Please make sure to place your own Facebook App credentials into the $app_id & $app_secret properties
 *
 * This package requires the Facebook SDK v5 located here:
 *
 * https://developers.facebook.com/docs/reference/php
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2015 - 2016, Daniel Cartin - Fastbook
 * Copyright (c) Facebook - Facebook SDK
 * 
 * @package	Apiwrap
 * @author	Daniel Cartin
 * @license	http://opensource.org/licenses/MIT	MIT License
 */
class Apiwrap {
	/**
	* Holds Facebook SDK connection
	* @var object
	*/
	
	public static $fb = null;

	/**
	* Page name
	* @var string
	*/
	public $name;

	/**
	* URL to page profile picture
	* @var string
	*/
	public $profile_pic;

	/**
	* Ten most recent posts
	* @var array
	*/
	public $feed;

	/**
	* Credentials - You must use your own credentials generated here:
	*
	* https://developers.facebook.com/apps
	*
	* @var $app_id string
	* @var $app_secret string
	*/
	
	private $app_id = '<your own id>';
	private $app_secret = '<your own secret>';


	/**
	*	Class Constructor
	*	
	*	Loads and authenticates through the Facebook SDK
	*	and sets up needed properties
	*
	*	@todo support app id and secret as parameters
	*	@param id string Facebook page identifier	
	*	@return void
	*/
	public function __construct($id){
		
		// Load Facebook SDK
		require_once('autoload.php');		

		// create facebook connection, if not already existing
		if(!self::$fb){
			// Get the session
			try {

					self::$fb = new Facebook\Facebook([
					'app_id' => $this->app_id,
					'app_secret' => $this->app_secret,
					'default_graph_version' => 'v2.3',
					'default_access_token' => $this->app_id.'|'.$this->app_secret
					]);

				} catch(Facebook\Exceptions\FacebookResponseException $e) {
				 
					// When Graph returns an error
					echo 'Graph returned an error: ' . $e->getMessage();
					exit;

				} catch(Facebook\Exceptions\FacebookSDKException $e) {
				  
					// When validation fails or other local issues
					echo 'Facebook SDK returned an error: ' . $e->getMessage();
					exit;

				}
		}
		
		try{
			/**
			*	Set needed properties
			*/

			// Name of the page
			$this->name = $this->get_page_details($id);

			// Profile picture
			$this->profile_pic = $this->get_profile_pic($id);

			// Feed (hard coded to 10)
			$this->feed = $this->get_feed($id);


		}catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		}
							
	}

	/**
	 *	get_page_details
	 *	
	 *	Queries the Facebook API and retrieves an object representing the page
	 *	parses the name out of the object and returns it a string
	 *
	 *	@param id string Facebook page identifier
	 *	@return string
	 */
	private function get_page_details($id){		
		$response = self::$fb->get('/'.$id);					
		return $response->getDecodedBody()['name'];
	}

	/**
	 *	get_profile_pic
	 *	
	 *	Queries the Facebook API and retrieves an object representing the profile picture
	 *	parses the location out of the object and returns it a string
	 *
	 *	@param id string Facebook page identifier
	 *	@return string
	 */
	private function get_profile_pic($id){
		$response = self::$fb->get('/'.$id.'/picture?type=large');
		return $response->getHeaders()['Location'];		
	}

	/**
	 *	get_feed
	 *	
	 *	Queries the Facebook API and retrieves an object representing the profile picture
	 *	parses the location out of the object and returns it a string
	 *
	 *	@todo Support dynamic number of posts
	 *	@param id string Facebook page identifier
	 *	@return string
	 */
	private function get_feed($id){
		$feed = array();
		$response = self::$fb->get('/'.$id.'/feed?limit=10');
		$posts = $response->getDecodedBody();		
		foreach($posts["data"] as $post){

			// Create a DateTime object from the returned timestamp			
			$stamp = new DateTime($post['created_time']);
			// Format DateTime object			
			$temp['stamp'] = $stamp->format("F j \a\\t g:ia");

			//Convert special characters to their HTML Entity equivalent
			$temp['message'] = htmlentities($post['message']);//mb_convert_encoding($post['message'], "HTML-ENTITIES");

			// Save the url of the 
			$temp['thumb'] = "https://graph.facebook.com/{$id}/picture?type=small";
			$feed[] = $temp;						
		};

		return $feed;

	}	
  
}


?>