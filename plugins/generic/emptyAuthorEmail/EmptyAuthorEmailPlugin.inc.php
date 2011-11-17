<?php

import('lib.pkp.classes.plugins.GenericPlugin');

class EmptyAuthorEmailPlugin extends GenericPlugin {

	/**
	 * Called as a plugin is registered to the registry
	 * @param $category String Name of category plugin was registered to
	 * @return boolean True iff plugin initialized successfully; if false,
	 *      the plugin will not be registered.
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			HookRegistry::register('metadataform::validate', array(&$this, 'callbackSaveMetadata'));
			return true;
		} else {
			return false;
		}
	}

	function getDisplayName() {
		return Locale::translate('plugins.generic.emptyAuthorEmail.displayName');
	}

	function getDescription() {
		return Locale::translate('plugins.generic.emptyAuthorEmail.description');
	}

	/**
	 * Set the page's breadcrumbs, given the plugin's tree of items
	 * to append.
	 * @param $subclass boolean
	 */
	function setBreadcrumbs($isSubclass = false) {
		$templateMgr =& TemplateManager::getManager();
		$pageCrumbs = array(
			array(
				Request::url(null, 'user'),
					'navigation.user'
				),
			array(
				Request::url(null, 'manager'),
					'user.role.manager'
				)
			);
		if ($isSubclass) $pageCrumbs[] = array(
			Request::url(null, 'manager', 'plugins'),
				'manager.plugins'
			);

		$templateMgr->assign('pageHierarchy', $pageCrumbs);
	}

	/**
	 * Display verbs for the management interface.
	 */
	function getManagementVerbs() {
		$verbs = array();
		return parent::getManagementVerbs($verbs);
	}

	/*
	 * this hook interecepts the Action::saveMetadata hook called by saveMetadata in classes/submission/common/Action.inc.php.
	 * $params contains one argument - the Article object being saved.
	 * The callback removes the author email constraint if it detects it in the list of Form errors.
	 */

	function callbackSaveMetadata($hookName, $params) {

		$metadataForm =& $params[0];
		if (isset($metadataForm->errorFields['authors[0][email]'])) {
			$errorIndex = 0;
			foreach ($metadataForm->_errors as $field => $formError) {

				if (preg_match("{authors.*email}", $formError->field)){
					unset($metadataForm->_errors[$errorIndex]);
				}
				$errorIndex ++;
			}
		}
		return false;
	}
}
?>
