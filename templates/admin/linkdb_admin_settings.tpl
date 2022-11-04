{LINKDB_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_SETTINGSTITLE}</h1>

<p>{L_SETTINGSEXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_SETTINGS_ACTION}" method="post">
  <tr>
	<th colspan="2" class="thHead">{L_SETTINGSTITLE}</th>
  </tr> 
  <tr>
	<td class="row1" width="50%"><b>{L_LOCK_SUBMIT_SITE}:</b></td>
	<td class="row2"><input type="radio" name="lock_submit_site" value="1" {LOCK_SUBMIT_SITE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="lock_submit_site" value="0" {LOCK_SUBMIT_SITE_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_CAT_COL}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="2" size="5" name="cat_col" value="{CAT_COL}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_SPLIT_LINKS}:</b></td>
	<td class="row2"><input type="radio" name="split_links" value="1" {SPLIT_LINKS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="split_links" value="0" {SPLIT_LINKS_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_SITE_LOGO}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="100" size="35" name="site_logo" value="{SITE_LOGO}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_SITE_URL}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="100" size="35" name="site_url" value="{SITE_URL}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_WIDTH}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="5" size="5" name="width" value="{WIDTH}" /></td>
  </tr>
	<tr>
	<td class="row1"><b>{L_HEIGHT}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="2" size="5" name="height" value="{HEIGHT}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_SETTINGS_LINK_PAGE}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="9" size="5" name="settings_link_page" value="{SETTINGS_LINK_PAGE}" /></td>
  </tr>

  <tr>
	<td class="row1"><b>{L_DEFAULT_SORT_METHOD}:</b></td>
	<td class="row2"><select name="sort_method">
		<option {SORT_NAME} value='link_name'>{L_NAME}</option>
		<option {SORT_TIME} value='link_time'>{L_DATE}</option>
		<option {SORT_RATING} value='link_longdesc'>{L_LINK_SITE_DESC}</option>
		<option {SORT_DOWNLOADS} value='link_hits'>{L_DOWNLOADS}</option>
	</select></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_DEFAULT_SORT_ORDER}:</b></td>
	<td class="row2"><select name="sort_order">
		<option {SORT_ASC} value='ASC'>{L_ASC}</option>
		<option {SORT_DESC} value='DESC'>{L_DESC}</option>
	</select></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_DISPLAY_INTERVAL}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="9" size="5" name="display_interval" value="{INTERVAL}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_DISPLAY_LOGO_NUM}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="9" size="5" name="display_logo_num" value="{LOGO_NUM}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_ALLOW_GUEST_SUBMIT_SITE}:</b></td>
	<td class="row2"><input type="radio" name="allow_guest_submit_site" value="1" {ALLOW_GUEST_SUBMIT_SITE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_guest_submit_site" value="0" {ALLOW_GUEST_SUBMIT_SITE_NO} /> {L_NO}</td>
  </tr>
  <tr>
 	<td class="row1"><b>{L_URL_VALIDATION}:</b></td>
 	<td class="row2"><input type="radio" name="url_validation" value="1" {URL_VALIDATION_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_guest_submit_site" value="0" {URL_VALIDATION_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_NEED_VALIDATION}</b></td>
	<td class="row2"><input type="radio" name="need_validation" value="1" {NEED_VALIDATE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="need_validation" value="0" {NEED_VALIDATE_NO} /> {L_NO}</td>
  </tr>
  <tr>
 	<td class="row1"><b>{L_URL_VALIDATION_SETTING}:</b><br /><span class="gensmall">{L_URL_VALIDATION_SETTING_EXPLAIN}</span></td>
 	<td class="row2"><table width="100%" cellpadding="3" cellspacing="1">
	<!-- BEGIN validation_switch_row -->
	{validation_switch_row.ROW_START}
		<td class="row1">{validation_switch_row.NAME}({validation_switch_row.DEFAULT_SETTING}):</td>
		<td><select name="{validation_switch_row.SWITCH_NAME}">
			<option {validation_switch_row.MANDATORY} value='{validation_switch_row.ID}+'>{validation_switch_row.L_URL_VALIDATION_MANDATORY}</option>
			<option {validation_switch_row.ALLOWED} value='{validation_switch_row.ID}-'>{validation_switch_row.L_URL_VALIDATION_NOT_ALLOWED}</option>
			<option {validation_switch_row.OPTIONAL} value='{validation_switch_row.ID}?'>{validation_switch_row.L_URL_VALIDATION_OPTIONAL}</option>
			<option {validation_switch_row.DEFAULT} value='{validation_switch_row.ID}' >{validation_switch_row.L_URL_VALIDATION_DEFAULT}</option>
		</select></td>
	{validation_switch_row.ROW_END}
	<!-- END validation_switch_row -->
	<tr>
		<td colspan="4" class="row1">{L_LEGEND}:&nbsp;(+)-{L_URL_VALIDATION_MANDATORY},&nbsp;&nbsp;(-){L_URL_VALIDATION_NOT_ALLOWED},&nbsp;&nbsp;(?)-{L_URL_VALIDATION_OPTIONAL}</td>
	</tr>
	</table></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_ALLOW_NO_LOGO}:</b></td>
	<td class="row2"><input type="radio" name="allow_no_logo" value="1" {ALLOW_NO_LOGO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_no_logo" value="0" {ALLOW_NO_LOGO_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_DISPLAY_LINKS_LOGO}:</b></td>
	<td class="row2"><input type="radio" name="display_links_logo" value="1" {DISLAY_LINKS_LOGO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="display_links_logo" value="0" {DISLAY_LINKS_LOGO_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_ALLOW_EDIT_LINK}:</b></td>
	<td class="row2"><input type="radio" name="allow_edit_link" value="1" {ALLOW_EDIT_LINK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_edit_link" value="0" {ALLOW_EDIT_LINK_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_ALLOW_DELETE_LINK}:</b></td>
	<td class="row2"><input type="radio" name="allow_delete_link" value="1" {ALLOW_DELETE_LINK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_delete_link" value="0" {ALLOW_DELETE_LINK_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_LINK_EMAIL_NOTIFY}:</b></td>
	<td class="row2"><input type="radio" name="email_notify" value="1" {EMAIL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="email_notify" value="0" {EMAIL_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_LINK_PM_NOTIFY}:</b></td>
	<td class="row2"><input type="radio" name="pm_notify" value="1" {PM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="pm_notify" value="0" {PM_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_LINK_ALLOW_RATE}:</b></td>
	<td class="row2"><input type="radio" name="allow_vote" value="1" {RATE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_vote" value="0" {RATE_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_LINK_ALLOW_COMMENT}:</b></td>
	<td class="row2"><input type="radio" name="allow_comment" value="1" {COMMENT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_comment" value="0" {COMMENT_NO} /> {L_NO}</td>
  </tr>
  <tr>
	<td class="row1"><b>{L_MAX_CHAR}:</b><br /><span class="gensmall">{L_MAX_CHAR_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="6" name="max_comment_chars" value="{MAX_CHAR}" /></td>
  </tr>
  <tr>
	<td align="center" class="catBottom" colspan="2"><input class="mainoption" type="submit" value="{L_SUBMIT}" name="submit" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
  </tr>
</form></table>	
