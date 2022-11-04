{ALBUM_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_TITLE}</h1>

<p>{L_TITLE_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead" colspan="4">{L_TITLE}</th>
</tr>
<!-- BEGIN no_pics -->
<tr>
	<td class="row1" align="center">{L_NO_PICS_FOUND}</td>
</tr>
<!-- END no_pics -->
<!-- BEGIN catrow -->
<tr>
	<td class="catHead" colspan="4"><b>{catrow.TITLE}</b><br /><span class="gensmall">{catrow.DESC}</span></td>
</tr>
<tr>
	<td class="{catrow.COLOR}" width="{S_COL_WIDTH}" valign="top"><table>
	<tr>
		<!-- BEGIN picrow -->
		<td><center><table>
		<tr>
			<td height="120" valign="top"><a href="{catrow.picrow.U_PIC}" target="_blank"><img src="{catrow.picrow.THUMBNAIL}" alt="{catrow.picrow.DESC}" title="{catrow.picrow.DESC}" vspace="10" /></a></td>
		</tr>
		</table>
		{catrow.picrow.APPROVAL}<br />
		</center>
		<span class="gensmall"> 
		<b>{L_PIC_TITLE}:</b> {catrow.picrow.TITLE}<br />
		<b>{L_POSTER}:</b> {catrow.picrow.POSTER}<br />
		<b>{L_POSTED}:</b> {catrow.picrow.TIME}<br />
		<b>{L_VIEW}:</b> {catrow.picrow.VIEW}<br />
		{catrow.picrow.RATING} 
		{catrow.picrow.COMMENTS} 
		{catrow.picrow.IP}
		{catrow.picrow.EDIT} {catrow.picrow.DELETE} 
		</span></td>
		{catrow.picrow.NEXTROW}
		<!-- END picrow -->
	</tr>
	</table></td>
</tr>
<!-- END catrow -->
<tr>
	<td class="catBottom" colspan="4">&nbsp;</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>
