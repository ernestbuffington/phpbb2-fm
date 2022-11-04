{INLINE_AD_MENU}{BANNER_MENU}{FORUM_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_AD_AFTER_POST}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="3" size="5" name="ad_after_post" value="{AD_AFTER_POST}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AD_EVERY_POST}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="3" size="5" name="ad_every_post" value="{AD_EVERY_POST}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AD_STYLE}:</b></td>
	<td class="row2">&nbsp;<input type="radio" name="ad_old_style" value="0" {AD_NEW_STYLE} /> {L_AD_NEW_STYLE}<br />&nbsp;<input type="radio" name="ad_old_style" value="1" {AD_OLD_STYLE} /> {L_AD_OLD_STYLE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AD_DISPLAY}:</b></td>
	<td class="row2"><input type="radio" name="ad_who" value="1" {AD_ALL} /> {L_AD_ALL}&nbsp;&nbsp;<input type="radio" name="ad_who" value="0" {AD_REG} /> {L_AD_REG}&nbsp;&nbsp;<input type="radio" name="ad_who" value="-1" {AD_GUEST} /> {L_AD_GUEST}</td>
</tr>
<tr>
	<td class="row1"><b>{L_AD_EXCLUDE}:</b><br /><span class="gensmall">{L_AD_EXCLUDE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="20" name="ad_no_groups" value="{AD_NO_GROUPS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AD_FORUMS}:</b><br /><span class="gensmall">{L_AD_FORUMS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="20" name="ad_forums" value="{AD_FORUMS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_AD_POST_THRESHOLD}:</b><br /><span class="gensmall">{L_AD_POST_THRESHOLD_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="5" size="5" name="ad_post_threshold" value="{AD_POST_THRESHOLD}" /></td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Inline Banner Ad 1.0.3 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.geocator.us" target="_blank" class="copyright">geocator</a></div>
