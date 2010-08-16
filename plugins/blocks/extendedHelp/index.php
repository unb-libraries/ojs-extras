<?php

/**
 * @defgroup plugins_blocks_extendedHelp
 */
 
/**
 * @file plugins/blocks/extendedHelp/index.php
 *
 * Copyright (c) 2003-2008 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_blocks_extendedHelp
 * @brief Wrapper for ETC's custom Help block plugin, with additional links
 *
 */

// $Id: index.php 7 2008-10-16 17:46:56Z jwhitney $

require_once('ExtendedHelpBlockPlugin.inc.php');

return new ExtendedHelpBlockPlugin();

?> 
