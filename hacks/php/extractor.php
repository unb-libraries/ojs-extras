<?php
	// URI identifies a page listing of articles published by an OJS-hosted journal
	$source = $_POST['source'];
	
	if (! $source) {
		exit('Source not specified...');
	}
	
	$sourceDoc = new DOMDocument();
	if (! $sourceDoc->loadHTML($source)) {
		exit('Can\'t parse source HTML');
	}
	$styleDoc = new DomDocument();
	if (! $styleDoc->load('http://etcdev.hil.unb.ca/ojs2.3-devel/utils/xsl/submissions.xsl')) {
		exit("Can't read style $styleUri");
	}

	$proc = new XSLTProcessor();
	$proc->importStylesheet($styleDoc);
	echo $proc->transformToXML($sourceDoc);

?>