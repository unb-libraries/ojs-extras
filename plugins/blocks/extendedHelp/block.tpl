{**
 * block.tpl
 *
 * Copyright (c) 2003-2008 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site sidebar menu -- ETC's custom Help block, with additional links
 *
 * $Id: block.tpl 7 2008-10-16 17:46:56Z jwhitney $
 *}
<div class="block" id="sidebarJournalHelp">
	<span class="blockTitle">{translate key="navigation.journalHelp"}</span>
	<ul>
		<li><a href="javascript:openHelp('{if $helpTopicId}{get_help_id key="$helpTopicId" url="true"}{else}{url page="help"}{/if}')">{translate key="plugins.block.extendedHelp.contextSensitive"}</a></li>
		<li><a href="/static_content/site/OJS-quickref.pdf">{translate key="plugins.block.extendedHelp.quickReference"}</a> (pdf)</li>
		<li><a href="http://pkp.sfu.ca/ojs_documentation" target="blank">{translate key="plugins.block.extendedHelp.helpDocumentation"}</a> @ SFU</li>
		<li><a href="http://pkp.sfu.ca/support/forum" target="blank">{translate key="plugins.block.extendedHelp.supportForum"}</a> @ SFU</li>
	</ul>
</div>
