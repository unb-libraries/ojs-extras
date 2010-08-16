<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
      xmlns:xs="http://www.w3.org/2001/XMLSchema"
      xmlns:html="http://www.w3.org/1999/xhtml"
      exclude-result-prefixes="xs"
      version="1.0">
	
	<xsl:output 
		method="html" 
		encoding="UTF-8"
		doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
		doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"
	/>

	<xsl:template match="/">
		<html>
			<head>
				<title>Submission Ids</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			</head>
			<body>
				<xsl:apply-templates select="descendant::table"/>
			</body>
		</html>
		
	</xsl:template>
	
	<xsl:template match="table">
		<p>Delete these articles?</p>
		<xsl:apply-templates select="descendant::tr[@valign='top']"/>
		<p>Fire this from the command line:</p>
		<p style="font-family: courier;">php tools/deleteSubmissions.php <xsl:apply-templates select="descendant::tr[@valign='top']/child::td[position()=1]"/></p>
	</xsl:template>
	
	<xsl:template match="tr">
		<xsl:apply-templates select="child::td[position()=1]"/><xsl:text> </xsl:text>
		<xsl:apply-templates select="child::td[position()=3]"/><xsl:text> </xsl:text>
		<xsl:apply-templates select="child::td[position()=4]"/><xsl:text> </xsl:text>
		<xsl:apply-templates select="child::td[position()=5]"/><xsl:text> </xsl:text><br/>
	</xsl:template>
	
	<xsl:template match="td"><xsl:value-of select="normalize-space(.)"/><xsl:text> </xsl:text></xsl:template>
	
	<xsl:template match="a"><xsl:value-of select="normalize-space(.)"/></xsl:template>
	
</xsl:stylesheet>
