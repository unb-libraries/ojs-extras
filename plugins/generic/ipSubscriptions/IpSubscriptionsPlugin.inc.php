<?php

/**
 * @file IpSubscriptionsPlugin.inc.php
 *
 * Copyright (c) 2003-2007 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.ipSubscriptions
 * @class IpSubscriptionsPlugin
 *
 * $Id: IpSubscriptionsPlugin.inc.php,v 1.22 2007/10/30 23:06:05 asmecher Exp $
 */

import('classes.plugins.GenericPlugin');

class IpSubscriptionsPlugin extends GenericPlugin {
	/**
	 * Register the plugin, if enabled; note that this plugin
	 * runs under both Journal and Site contexts.
	 * @param $category string
	 * @param $path string
	 * @return boolean
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			$this->addLocaleData();
			if ($this->getEnabled()) {

                                $this->import('IpSubscriptionsDAO');
                                $ipDao = &new IpSubscriptionsDAO();
                                $returner = &DAORegistry::registerDAO('IpSubscriptionsDAO', $ipDao);

				HookRegistry::register('IssueAction::subscriptionRequired', array(&$this, 'callback'));

			}
			return true;
		}
		return false;
	}

	/**
	 * Hook callback function for IssueAction::subscriptionRequired
	 * @param $hookName string
	 * @param $args array
	 * @return boolean
	 */
	function callback($hookName, $args) {
		$journal =& $args[0];
		$issue   =& $args[1];
		$result  =& $args[2];

		if ($this->isSubscriberIp($journal, $issue))  { 
                    $result = false; // CRKN IP and past moving wall - subscription not required
                    return true;
                }

                return false;
	}

	function isSubscriberIp(&$journal, &$issue) {

                $ipDao = &DAORegistry::getDAO('IpSubscriptionsDAO');
                return $ipDao->isCRKNSubscriber(Request::getRemoteAddr());
	}

	/**
	 * Get the symbolic name of this plugin
	 * @return string
	 */
	function getName() {
		return 'IpSubscriptionsPlugin';
	}

	/**
	 * Get the display name of this plugin
	 * @return string
	 */
	function getDisplayName() {
		return Locale::translate('plugins.generic.ipSubscriptions.name');
	}

	/**
	 * Get the description of this plugin
	 * @return string
	 */
	function getDescription() {
		return Locale::translate('plugins.generic.ipSubscriptions.description');
	}

	/**
	 * Check whether or not this plugin is enabled
	 * @return boolean
	 */
	function getEnabled() {
		$journal =& Request::getJournal();
		$journalId = $journal?$journal->getJournalId():0;
		return $this->getSetting($journalId, 'enabled');
	}

	/**
	 * Get a list of available management verbs for this plugin
	 * @return array
	 */
	function getManagementVerbs() {
		return array(array(
			($this->getEnabled()?'disable':'enable'),
			Locale::translate($this->getEnabled()?'manager.plugins.disable':'manager.plugins.enable')
		));
	}

	/**
	 * Execute a management verb on this plugin
	 * @param $verb string
	 * @param $args array
	 * @return boolean
	 */
	function manage($verb, $args) {
		$journal =& Request::getJournal();
		$journalId = $journal?$journal->getJournalId():0;
		switch ($verb) {
			case 'enable':
				$this->updateSetting($journalId, 'enabled', true);
				break;
			case 'disable':
				$this->updateSetting($journalId, 'enabled', false);
				break;
		}
		return false;
	}
}

?>
