{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{HEADER}</h1>

<p>{HEADER_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>{IMG_NEW_TEMPL}</td>
</tr>
</table>

<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline">	
<tr>
	<th class="thCornerL" nowrap="nowrap" width="50%">{THIS_TEMPL_NAME}</th>
	<th class="thCornerR" nowrap="nowrap" width="50%">{SELECTION}</th>
</tr>
<!-- BEGIN selections -->
<tr>
	<td class="catLeft"><span class="name"><a name="{selections.ANCHOR}"></a></span> <a href="{selections.EXPAND_URL}" class="cattitle">{selections.TEMPL_NAME}</td>
	<td class="catRight" align="center"><span class="gen">{selections.EDIT} {selections.ADD_IMAGE} {selections.DROP_IMAGE}</span></td>
</tr>
<!-- BEGIN expansion -->
<tr> 
	<td class="{selections.expansion.ROW_CLASS}" align="center">{selections.expansion.TEMPL_NAME}</td>
	<td class="{selections.expansion.ROW_CLASS}" align="center">{selections.expansion.SELECTION}</td>
</tr>
<!-- END expansion -->
<!-- END selections -->
</table>
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>


