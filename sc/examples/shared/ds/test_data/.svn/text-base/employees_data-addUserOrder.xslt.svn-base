<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:template match="@*|node()">
    <xsl:copy>
      <xsl:apply-templates select="@*|node()"/>
    </xsl:copy>
  </xsl:template>

  <xsl:template match="List">
    <List>
      <xsl:for-each select="employee">
        <xsl:text>&#x0A;&#x09;</xsl:text>
        <xsl:copy>
          <xsl:text>&#x0A;&#x09;&#x09;</xsl:text>
          <userOrder><xsl:value-of select="position()"/></userOrder>

          <xsl:apply-templates select="@*|node()"/>
        </xsl:copy>
      </xsl:for-each>
      <xsl:text>&#x0A;</xsl:text>
    </List>
  </xsl:template>
</xsl:stylesheet>
