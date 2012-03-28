<?php
/**
 * @file ExtendedHelpBlockPlugin.inc.php
 *
 * @class ExtendedHelpBlockPlugin
 *
 * @brief ETC Help block plugin
 */

import('lib.pkp.classes.plugins.BlockPlugin');

class ExtendedHelpBlockPlugin extends BlockPlugin {
  /**
   * Determine whether the plugin is enabled. Overrides parent so that
   * the plugin will be displayed during install.
   */
  function getEnabled() {
    if (!Config::getVar('general', 'installed')) return true;
    return parent::getEnabled();
  }

  /**
   * Install default settings on system install.
   * @return string
   */
  function getInstallSitePluginSettingsFile() {
    return $this->getPluginPath() . '/settings.xml';
  }
  
  /**
   * Install default settings on journal creation.
   * @return string
   */
  function getContextSpecificPluginSettingsFile() {
    return $this->getPluginPath() . '/settings.xml';
  }
  
  /**
   * Get the block context. Overrides parent so that the plugin will be
   * displayed during install.
   * @return int
   */
  function getBlockContext() {
    if (!Config::getVar('general', 'installed')) return BLOCK_CONTEXT_RIGHT_SIDEBAR;
    return parent::getBlockContext();
  }
  
  /**
   * Determine the plugin sequence. Overrides parent so that
   * the plugin will be displayed during install.
   */
  function getSeq() {
    if (!Config::getVar('general', 'installed')) return 0;
    return parent::getSeq();
  }

  /**
   * Get the display name of this plugin.
   * @return String
   */
  function getDisplayName() {
    return __('plugins.block.extendedHelp.displayName');
  }

  /**
   * Get a description of the plugin.
   */
  function getDescription() {
    return __('plugins.block.extendedHelp.description');
  }
}

?>
