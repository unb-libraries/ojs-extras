<?php

/**
 * @file LLTNavigationPlugin.inc.php
 *
 * Copyright (c) 2003-2007 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.LLTNavigationPlugin
 * @class LLTNavigationPlugin
 *
 * $Id: LLTNavigationPlugin.inc.php,v 1.22 2007/10/30 23:06:05 jnugent Exp $
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class LLTNavigationPlugin extends GenericPlugin {
	/**
	 * Register the plugin, if enabled; note that this plugin
	 * runs under both Journal and Site contexts.
	 * @param $category string
	 * @param $path string
	 * @return boolean
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
				HookRegistry::register('TemplateManager::display', array(&$this, 'callback'));

			}
			return true;
		}
		return true;
	}

	/**
	 * Hook callback function for TemplateManager::display
	 * @param $hookName string
	 * @param $args array
	 * @return boolean
	 */
	function callback($hookName, $args) {

                $templateManager =& $args[0];
                $template =& $args[1];

                $page = Request::getRequestedPage();
                $user = Request::getUser();

                if (!$user || $user->getUsername() != 'administrator' ) {
                    if ($page == 'user' || $page == 'about') {
                        Request::redirectUrl('http://www.lltjournal.ca/index.php/llt');
                    }
                }
                return false;
	}

	/**
	 * Get the symbolic name of this plugin
	 * @return string
	 */
	function getName() {
		return 'LLTNavigationPlugin';
	}

	/**
	 * Get the display name of this plugin
	 * @return string
	 */
	function getDisplayName() {
		return Locale::translate('plugins.generic.LLTNavigation.name');
	}

	/**
	 * Get the description of this plugin
	 * @return string
	 */
	function getDescription() {
		return Locale::translate('plugins.generic.LLTNavigation.description');
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
		return parent::getManagementVerbs($verbs);
	}

	/**
	 * Execute a management verb on this plugin
	 * @param $verb string
	 * @param $args array
	 * @return boolean
	 */
	function manage($verb, $args, &$message) {
		if (!parent::manage($verb, $args, $message)) return false;
		$journal =& Request::getJournal();
	}
}

?>
