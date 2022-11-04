<table width="100%" cellpadding="2" cellspacing="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
  </tr>
</table>

<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
<tr>
	<th colspan="2" class="thHead">{L_LINK_US}</th>
</tr>
<tr> 
	<td class="row1">{L_LINK_US_EXPLAIN}<br />
	  <table width="100%" cellspacing="1" cellpadding="4" align="center" style="table-layout:fixed;" class="bodyline">
	    <tr> 
		<td class="gensmall"><input type="text" value="{LINK_US_SYNTAX}" class="post" style="width: 100%;" /></td>
	    </tr>
	  </table>
	</td>
	<td class="row1" align="center"><img src="{U_SITE_LOGO}" alt="{SITENAME}" title="{SITENAME}" /></td>
</tr>
</table>
<br />

<!-- BEGIN CAT_PARENT -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead">{L_CATEGORY}</th>
</tr>
<tr>
	<td class="row1"><table cellpadding="2" cellspacing="1" width="100%">
	<!-- BEGIN catcol -->
	<tr>
		<!-- BEGIN no_cat_parent -->
		<td width="{LINK_WIDTH}%"><table cellpadding="2" cellspacing="2" width="100%">
		<tr>
			<td><a href="{CAT_PARENT.catcol.no_cat_parent.U_CAT}"><img src="{CAT_PARENT.catcol.no_cat_parent.CAT_IMAGE}" alt="{CAT_PARENT.catcol.no_cat_parent.CAT_NAME}" title="{CAT_PARENT.catcol.no_cat_parent.CAT_NAME}" align="absmiddle" /></a></td>
			<td width="100%" valign="middle" nowrap="nowrap"><a href="{CAT_PARENT.catcol.no_cat_parent.U_CAT}"  class="cattitle">{CAT_PARENT.catcol.no_cat_parent.CAT_NAME}</a>&nbsp;<span class="gensmall">({CAT_PARENT.catcol.no_cat_parent.FILECAT})</span>
			<!-- BEGIN SUB_CAT -->
			<br />
			<!-- BEGIN GEN_SUB_CAT -->
			<a href="{CAT_PARENT.catcol.no_cat_parent.SUB_CAT.GEN_SUB_CAT.U_SUB_CAT}"  class="genmed">{CAT_PARENT.catcol.no_cat_parent.SUB_CAT.GEN_SUB_CAT.GEN_SUB_CAT}</a><span class="genmed">{CAT_PARENT.catcol.no_cat_parent.SUB_CAT.GEN_SUB_CAT.L_COMMA}</span>
			<!-- END GEN_SUB_CAT -->
			<!-- END SUB_CAT -->
			</td>
		</tr>
		</table></td>
		<!-- END no_cat_parent -->
      		</tr>
	<!-- END catcol -->
	</table></td>
</tr>
</table>
<!-- END CAT_PARENT -->