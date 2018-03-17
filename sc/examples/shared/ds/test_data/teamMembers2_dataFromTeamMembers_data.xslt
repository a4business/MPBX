<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:template match="List">
    <xsl:copy>
      <xsl:apply-templates select="@*|node()"/>
      <xsl:text>&#xA;</xsl:text>
    </xsl:copy>
  </xsl:template>

  <xsl:template match="teamMember">
    <xsl:text>&#xA;    </xsl:text>
    <teamMember2>
      <xsl:text>&#xA;        </xsl:text>
      <TeamId>
        <xsl:choose>
          <xsl:when test="projectCode = 'New Costing System'">1</xsl:when>
          <xsl:when test="projectCode = 'Warehousing Improvements'">2</xsl:when>
          <xsl:when test="projectCode = 'Evaluate AJAX Frameworks'">3</xsl:when>
          <xsl:when test="projectCode = 'Upgrade Postgres'">4</xsl:when>
          <xsl:when test="projectCode = 'Online Billing'">5</xsl:when>
          <xsl:otherwise>???</xsl:otherwise>
        </xsl:choose>
      </TeamId>
      <xsl:text>&#xA;        </xsl:text>
      <EmployeeId><xsl:value-of select="employeeId"/></EmployeeId>
      <xsl:text>&#xA;    </xsl:text>
    </teamMember2>
  </xsl:template>

  <xsl:template match="text()"/>
</xsl:stylesheet>
