<?php
namespace acid\SocialFeed\Command;


/*                                                                        *
 * This script belongs to the TYPO3 Flow package "acid.SocialFeed".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class SocialfeedCommandController extends \TYPO3\Flow\Cli\CommandController {

    /**
     * @var array
     */
    protected $settings;

    /**
     * Inject the settings
     *
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings) {
            $this->settings = $settings;
    }


	/**
	 * Import Posts from specified Social Media Accounts
	 *
	 * The comment of this command method is also used for TYPO3 Flow's help screens. The first line should give a very short
	 * summary about what the command does. Then, after an empty line, you should explain in more detail what the command
	 * does. You might also give some usage example.
	 *
	 * It is important to document the parameters with param tags, because that information will also appear in the help
	 * screen.
	 *
	 * @param string $requiredArgument This argument is required
	 * @param string $optionalArgument This argument is optional
	 * @return void
	 */
	public function importCommand($wipeDB = NULL) {

		// wipe DB
		if($wipeDB != null){
			$this->context = $this->contextFactory->create(array('workspaceName' => 'live', 'targetDimensions' => array('language' => 'en_US')));
			foreach ($this->context->getNode('/sites/JCwebsite/news/main/node-54b66b1e9db85/entries')->getChildNodes('acid.SocialFeed:Post') as $node) {
				$node->setRemoved(TRUE);
				echo sprintf("removed node %s\n", $node->getName());
			}

		}
		
		foreach ($this->settings['enabledProvider'] as $provider) {
			if(isset($this->settings['Provider'][$provider])){

				$class = "\acid\SocialFeed\Provider\\".$provider.'Provider';
				$object = new $class($this->settings['Provider'][$provider]);
				$object->import();

			} else {
				$this->outputLine('<em>Error:</em> %s has no further configuration in Settings.yaml', array($provider));
			}
		}

		$this->outputLine('You called the example command and passed "%s" as the first argument.', array('test' ));
	}

}