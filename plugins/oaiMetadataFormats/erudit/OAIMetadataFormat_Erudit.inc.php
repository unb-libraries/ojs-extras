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
    $article =& $record->getData('article');

    // Get a list of files associated with this article
    $articleId = $article->getId();
    $articleFileDao =& DAORegistry::getDAO('ArticleFileDAO');
    $articleFiles = $articleFileDao->getArticleFilesByArticle($articleId);
    
    // Public PDF and HTML galleys of published articles *should* follow the
    // same naming convention used to name Erudit XML files, i.e.,
    // [journal_id][vol](_[issue](_[part])?)?[article_type_id][article_no].[pdf|html]
    // 
    // E.g., 
    // ageo47art07.html     => AGEO Vol. 47, article 07, HTML galley
    // scl36_1art05.pdf     => SCL Vol. 36, Issue 1, article 05, PDF galley
    // tric30_1_2art03.html => TRIC Vol. 30, Issue 1, part 2, article 03, HTML galley
    
    // Examine files with type = 'public' to find a candidate galley upon
    // which to base the search for the corresponding Erudit XML file. First
    // parenthesized subpattern captures filename excluding extension 
    $fileNameRegExp = "/^([A-Za-z]+\d+_?(\d+)?(_\d+)*[a-z]+\d+)\.(pdf|html)$/";

    $eruditXmlFileName = '';
    foreach ($articleFiles as $articleFile) {
      // We're only interested in public galleys
      if ($articleFile->getType() == 'public') {

        // Galley filenames may have been changed to follow naming convention:
        // filter out any that don't correspond.
        if (preg_match($fileNameRegExp, $articleFile->getOriginalFileName(), $matches)) {
          
          // Seems fine.  Build Erudit XML filename 
          $eruditXmlFileName = $matches[1] . ".xml";
          break;
        }
      }
      
    } // end foreach ($articleFiles as $articleFile)
    
    if (! $eruditXmlFileName) {
      // Ugly but temporarily useful;
      $error = "Can't identify Érudit XML file for article ID $articleId";
      error_log($error);
      return "<error>$error</error>";
    }

    // Find the XML file 
    $eruditXmlFiles = 
      preg_split("{\n}", 
        trim(
          shell_exec('/usr/bin/find /home/journals/etc_journals -wholename ' . escapeshellarg("*/XML/$eruditXmlFileName") . ' -print')
        )
      );
    
    if (! sizeof($eruditXmlFiles)) {
      // Still ugly, still useful
      $error = "Érudit XML file '$eruditXmlFileName' not found for article ID $articleId";
      error_log($error);
      return "<error>$error</error>";
    }
    
    // Read the XML file
    $eruditXml = file_get_contents($eruditXmlFiles[0]);
    
    if (! $eruditXml) {
      // See above.
      $error = "Can't read Érudit XML file '$eruditXmlFileName' for article ID $articleId";
      error_log($error);
      return "<error>$error</error>";
    }
    
    // Extract the <article> element from the Erudit XML
    $eruditArticleXml = preg_replace("{^.*?<article}is", "<article", $eruditXml);

    // Begin the response with URIs for each of the article's galleys
    $response = '';
    foreach ($article->getGalleys() as $galley) {
      $response .= '<self-uri ' . 
                    'xmlns:xlink="http://www.w3.org/1999/xlink" ' . 
                    'content-type="' . htmlspecialchars(Core::cleanVar($galley->getFileType())) . '" ' .
                    'xlink:href="' . htmlspecialchars(Core::cleanVar(Request::url($journal->getPath(), 'article', 'view', array($article->getBestArticleId(), $galley->getId())))) . '" ' .
                    '/>' . "\n";
    }
    
    // Append the article XML
    $response .= $eruditArticleXml;
    
    // That's it.
    return $response;  
	}
}
?>
