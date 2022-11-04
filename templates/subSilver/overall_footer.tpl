	<a name="bottom"></a>
	<br />
	<table width="100%" cellspacing="2" cellpadding="2">
	  <tr>
	  	<form name="change">
		<td nowrap="nowrap" width="25%" class="gensmall">
		<!-- BEGIN switch_transition_toggle -->
		<a href="{switch_transition_toggle.TRANSITION_TOGGLE}" class="gensmall">{switch_transition_toggle.L_TRANSITION_TOGGLE}</a><br /><br />
		<!-- END switch_transition_toggle -->
		<!-- BEGIN switch_style_select --> 
		{L_STYLE}:
		<select name="style">
		<!-- BEGIN style_select --> 
		{switch_style_select.style_select.options} 
		<!-- END style_select -->
		</select> &nbsp;<input class="liteoption" type="button" value="{L_GO}" onClick="location=document.change.style.options[document.change.style.selectedIndex].value">
		<!-- END switch_style_select --> 
		</td>
		</form>
		<td width="50%" align="center" class="copyright">{ADMIN_LINK}</td>
		<form action="{U_SEARCHBOX}" method="post">
		<td nowrap="nowrap" width="25%" align="right" class="gensmall">
		<!-- BEGIN switch_search_footer -->
		{L_SEARCH}: <input class="post" type="text" size="15" name="search_keywords" value="" style="border-color: #000000;"> <input class="liteoption" type="submit" value="{L_GO}">
		<input type="hidden" name="search_author" value="">
		<input type="hidden" name="search_forum" value="-1">
		<input type="hidden" name="search_author" value="">
 		<input type="hidden" name="search_time" value="0">
		<input type="hidden" name="search_cat" value="-1">
		<input type="hidden" name="sort_by" value="0">
		<input type="hidden" name="show_results" value="topics">
		<input type="hidden" name="return_chars" value="-1">
		<!-- END switch_search_footer --> 
		</td>
		</form>
	  </tr>
	</table>
	<table width="100%" cellspacing="2" cellpadding="2">
	  <tr>
	    <td align="center">{BANNER_7_IMG}</td>
	    <td align="center">{BANNER_8_IMG}</td>
	    <td align="center">{BANNER_9_IMG}</td>
	  </tr>
	  <tr>
	    <td align="center">{BANNER_10_IMG}</td>
	    <td align="center">{BANNER_11_IMG}</td>
	    <td align="center">{BANNER_12_IMG}</td>
	  </tr>
	</table>
<div align="center" class="copyright">{CUSTOM_OVERALL_FOOTER}{BOARD_SIG}<br />&nbsp;{SITEMAP_LINK}&nbsp;<br />Powered by <a href="http://www.phpbb.com/" target="_phpbb" class="copyright" title="phpBB">phpBB</a> &copy; 2001, {COPYRIGHT_YEAR} phpBB Group{TRANSLATION_INFO}<br />{L_ALL_CONTENT} &copy; {SITENAME} {L_ORIG_AUTHOR}<a href="{LOGO_URL}"><img src="{AUTO_BACKUP}" width="0" height="0" alt="" title="" /></a><br />{SERVER_LOAD}</div></td>
</tr>
</table>
{L_CUSTOM_FOOTER}
<style type="text/css">
<!--
body { background-color: {T_BODY_BGCOLOR}; }
-->
</style>