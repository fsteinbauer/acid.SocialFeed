<?php
namespace acid\SocialFeed\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "acid.SocialFeed".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Post {

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @var string
	 */
	protected $provider;

	/**
	 * @var \DateTime
	 */
	protected $datetime;

	/**
	 * @var string
	 */
	protected $image;


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param string $content
	 * @return void
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getProvider() {
		return $this->provider;
	}

	/**
	 * @param string $provider
	 * @return void
	 */
	public function setProvider($provider) {
		$this->provider = $provider;
	}

	/**
	 * @return \DateTime
	 */
	public function getDatetime() {
		return $this->datetime;
	}

	/**
	 * @param \DateTime $datetime
	 * @return void
	 */
	public function setDatetime($datetime) {
		$this->datetime = $datetime;
	}

	/**
	 * @return string
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @param string $image
	 * @return void
	 */
	public function setImage($image) {
		$this->image = $image;
	}

}