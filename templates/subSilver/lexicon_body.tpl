<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav" valign="bottom"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> {LEXICON_NAV}</td>
	<td align="right">{LEXICON_CAT_SELECTOR}</td>
</tr>
</form></table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center">
<tr>
	<th class="thHead" colspan="2">{LEXICON_TITLE}</th>
</tr>
<tr>
	<td class="row1" colspan="2">{LEXICON_DESCRIPTION}</td>
</tr>
<tr>
	<td class="catLeft"><span class="gensmall">{FIRST_LETTER_NAVIGATION}</span></td>
	<form action="{U_LEXICON}" method="post" name="search" />
	<td class="catRight" align="right"><input type="text" name="lexicon_searchword" size="20" maxlength="40" class="post" />&nbsp;<input type="submit" name="search" value="{L_SEARCH}" class="liteoption">&nbsp;</td>
	</form>
</tr>
<!-- BEGIN lexicon_row -->
<tr>
	<td class="{lexicon_row.ROW_CLASS}" colspan="2"><a name="{lexicon_row.ANCHOR}"></a><span class="cattitle">{lexicon_row.KEYWORD}</span> <i>{lexicon_row.CATEGORIE}</i><br />{lexicon_row.EXPLANATION}<br /></td>
</tr>
<tr> 
	<td class="spaceRow" colspan="2" height="1"><img src="images/spacer.gif" alt="" width="1" height="1" /></td>
</tr>
<!-- END lexicon_row -->
<tr>
	<td class="catBottom" align="right" colspan="2"><b class="gensmall">{KEYWORD_COUNT}</b></td>
</tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
  </tr> 
</table> 
<br />
<div align="center" class="copyright">Lexicon v2 &copy; 2005 <a href="http://www.amigalink.de" target="_blank" class="copyright">AmigaLink</a></div>


