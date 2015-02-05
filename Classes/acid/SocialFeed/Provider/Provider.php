<?php
namespace acid\SocialFeed\Provider;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

abstract class Provider {
	
	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\NodeTypeManager
	 */
	protected $nodeTypeManager;


	/**
	 * @var mixed[] Settings
	 */
	protected $settings;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface
	 */
	protected $contextFactory;


	function __construct($settings){
		$this->settings = $settings;

	}


	protected function importPost( $properties ){
		$this->context = $this->contextFactory->create(array('workspaceName' => 'live', 'targetDimensions' => array('language' => 'en_US')));
		$rootNode = $this->context->getNode('/sites/JCwebsite/news/main/node-54b66b1e9db85/entries');

		$socialPostTemplate = new \TYPO3\TYPO3CR\Domain\Model\NodeTemplate();
		$socialPostTemplate->setNodeType($this->nodeTypeManager->getNodeType('acid.SocialFeed:Post'));
		// $socialPostTemplate->setHidden($this->settings['autoHide']);


		foreach($properties as $key => $value){
			$socialPostTemplate->setProperty($key, $value);
		}

		$rootNode->createNodeFromTemplate($socialPostTemplate);
		echo sprintf("Imported %s from %s\n", $properties['originalID'], $properties['provider']);
	}



	abstract public function import();
}