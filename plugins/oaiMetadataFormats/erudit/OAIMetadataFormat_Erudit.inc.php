<?php

/**
 * @defgroup oai_format_erudit
 */

/**
 * @file classes/oai/format/OAIMetadataFormat_Erudit.inc.php
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class OAIMetadataFormat_Erudit
 * @ingroup oai_format
 * @see OAI
 *
 * @brief OAI metadata format class -- EruditArticle
 */


class OAIMetadataFormat_Erudit extends OAIMetadataFormat {

	/**
	 * @see OAIMetadataFormat#toXml
	 */
	function toXml(&$record, $format = null) {

                $journal =& $record->getData('journal');
                $issue =& $record->getData('issue');
                $section =& $record->getData('section');
                $article =& $record->getData('article');

		$articleId = $article->getId();

                $response = '';
                $articleFileDao =& DAORegistry::getDAO('ArticleFileDAO');
                $articleFiles = $articleFileDao->getArticleFilesByArticle($articleId);
                if (is_array($articleFiles)) {
                        $articleFile = $articleFiles[0];
                        $originalFileName =& $articleFile->getOriginalFileName();
                        $originalXmlFile = preg_replace("{\.[^\.]+$}", ".xml", $originalFileName);
			$originalXmlFile = preg_replace("{^index_}", "", $originalXmlFile);
                        if ($originalXmlFile != '') {
                                $foundFiles = trim(shell_exec("/usr/bin/find /home/journals/etc_journals -name " . escapeshellarg($originalXmlFile) . " -print"));
                                $foundFilesArray = preg_split("{\n}", $foundFiles);     
                                if (sizeof($foundFilesArray) == 1) { // only one file anyway
                                        $response .= file_get_contents($foundFilesArray[0]);
                                } else {
                                        foreach ($foundFilesArray as $foundFile) {
                                                if (preg_match("{/XML/}", $foundFile)) {
                                                        $response .= file_get_contents($foundFile);
                                                        break;
                                                }
                                        }

                                        if ($response == '') { // we found something, but not /XML/ so include something
                                                $response .= file_get_contents($foundFilesArray[0]);
                                        }
                                }
                        } 
                }
                $response = preg_replace("{^.*?<article}is", "<article", $response);
                $galleyTags = '';
                foreach ($article->getGalleys() as $galley) {
                        $galleyTags .= "\t\t\t<self-uri xmlns:xlink=\"http://www.w3.org/1999/xlink\" content-type=\"" . htmlspecialchars(Core::cleanVar($galley->getFileType())) . 
                                        "\" xlink:href=\"" . htmlspecialchars(Core::cleanVar(Request::url($journal->getPath(), 'article', 'view', array($article->getBestArticleId(), $galley->getId())))) . "\" />\n";
                }

                return $galleyTags . $response;
	}
}
?>
