<?php
namespace acid\SocialFeed\Provider;

use \Facebook\FacebookSession;
use \Facebook\FacebookRequest;
use \Facebook\GraphUser;
use \Facebook\GraphLocation;
use \Facebook\FacebookRequestException;


use TYPO3\Flow\Annotations as Flow;

class FacebookProvider extends Provider {

	/**
	 * @var \TYPO3\TYPO3CR\Domain\Service\Context
	 */
	protected $context;



	function __construct($settings){
		parent::__construct($settings);

		FacebookSession::setDefaultApplication($this->settings['appId'], $this->settings['appSecret']);
		$this->session = new FacebookSession($this->settings['accessToken']);
	}



	public function import(){
		
		foreach ($this->settings['users'] as $user) {
			$querystring = sprintf('/%s/posts', $user);

			try {
				$posts = (new FacebookRequest(
					$this->session, 'GET', $querystring
				))->execute()->getGraphObject()->asArray();

				foreach ($posts['data'] as $post) {
					if(isset($post->message)){
						$post_array = $this->buildPost($post);
						if($post_array != null){
							$this->importPost($post_array);
						}
					}	
				}

			} catch(FacebookRequestException $e){
				echo ( $e->getMessage());
			}
		}
	}



	private function buildPost($fbPost){

		$post =  array(
			'provider'		=> 'facebook',
			'originalID' 	=> $fbPost->id,
			'author'		=> $fbPost->from->name,
			'story'			=> $fbPost->message,
			'link'			=> $fbPost->link,
			'date'			=> new \DateTime($fbPost->created_time)
		);

		switch($fbPost->type){
			case "status":
				return null;
				break;

			case "photo":
				$post = $this->fetchPicture($fbPost->object_id, $post);
				break;

			case "video":
				if(isset($fbPost->object_id)){
					$post = $this->fetchVideo($fbPost->object_id, $post);
				} else {
					$post = $this->fetchLink($fbPost->link, $post);
				}
				break;
		}
		return $post;
	}

	private function fetchPicture($pId, $post){
		try {
			$fbResult = (new FacebookRequest(
				$this->session, 'GET', '/'.$pId
			))->execute()->getGraphObject();

			foreach($fbResult->getProperty('images')->asArray() as $image){
				if($image->height <= 600 && $image->height >= 480){
					$post['assetType'] = 'photo';
					$post['assetImage'] = $image->source;
				}

			}

		} catch(FacebookRequestException $e){
			echo ( $e->getMessage());
		}
		return $post;
	}


	private function fetchVideo($vId, $post){
		try {
			$fbResult = (new FacebookRequest(
				$this->session, 'GET', '/'.$vId
			))->execute()->getGraphObject();

			foreach($fbResult->getProperty('format')->asArray() as $image){
				if($image->height <= 720 && $image->height >= 400){
					$post['assetType'] = 'fb_video';
					$post['assetImage'] = $image->picture;
					$post['asset'] = $vId;
					break;
				}
			}

		} catch(FacebookRequestException $e){
			echo ( $e->getMessage());
		}
		return $post;
	}

	private function fetchLink($link, $post){
		
		// Check if link leads to youtube
		if(strpos($link, 'youtube.com/watch')){
			$videoId = explode("watch?v=", $link);
			if(count($videoId > 1)){
				$videoId = $videoId[count($videoId) -1];

				$post['assetType'] = 'yt_video';
				$post['assetImage'] = 'http://img.youtube.com/vi/'.$videoId.'/hqdefault.jpg';
				$post['asset'] = $videoId;
			}
		}

		return $post;
	}
}

