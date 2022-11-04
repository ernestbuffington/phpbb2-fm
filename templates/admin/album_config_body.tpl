{ALBUM_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_ALBUM_CONFIG}</h1>

<p>{L_ALBUM_CONFIG_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_ALBUM_CONFIG_ACTION}" method="post">

<!-- BEGIN switch_config -->
<tr>
	<th class="thHead" colspan="2">{L_ALBUM_CONFIG}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_MAX_PICS}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="9" size="9" name="max_pics" value="{MAX_PICS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_USER_PICS_LIMIT}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="12" size="5" name="user_pics_limit" value="{USER_PICS_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MOD_PICS_LIMIT}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="12" size="5" name="mod_pics_limit" value="{MOD_PICS_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SLIDEPICS_PER_PAGE}:</b><br /><span class="gensmall">{L_SLIDEPICS_PER_PAGE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="3" size="5" name="slidepics_per_page" value="{SLIDEPICS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GD_VERSION}:</b></td>
	<td class="row2">&nbsp;<input type="radio" {NO_GD} name="gd_version" value="0" /> {L_MANUAL_THUMBNAIL}<br />&nbsp;<input type="radio" {GD_V1} name="gd_version" value="1" /> gd1&nbsp;&nbsp;<input type="radio" {GD_V2} name="gd_version" value="2" /> gd2</td>
</tr>
<tr>
	<td class="row1"><b>{L_HOTLINK_PREVENT}:</b></td>
	<td class="row2"><input type="radio" {HOTLINK_PREVENT_ENABLED} name="hotlink_prevent" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {HOTLINK_PREVENT_DISABLED} name="hotlink_prevent" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HOTLINK_ALLOWED}:</b></td>
	<td class="row2"><input class="post" type="text" size="35" name="hotlink_allowed" value="{HOTLINK_ALLOWED}" /></td>
</tr>
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MAX_FILE_SIZE}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="12" size="12" name="max_file_size" value="{MAX_FILE_SIZE}" /> Bytes</td>
</tr>
<tr>
	<td class="row1"><b>{L_IMAGE_SIZE}:</b><br /><span class="gensmall">{L_IMAGE_SIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="9" size="4" name="max_height" value="{MAX_HEIGHT}" /> x <input class="post" type="text" maxlength="9" size="4" name="max_width" value="{MAX_WIDTH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_JPG_ALLOWED}:</b></td>
	<td class="row2"><input type="radio" {JPG_ENABLED} name="jpg_allowed" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {JPG_DISABLED} name="jpg_allowed" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PNG_ALLOWED}:</b></td>
	<td class="row2"><input type="radio" {PNG_ENABLED} name="png_allowed" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {PNG_DISABLED} name="png_allowed" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_GIF_ALLOWED}:</b></td>
	<td class="row2"><input type="radio" {GIF_ENABLED} name="gif_allowed" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {GIF_DISABLED} name="gif_allowed" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_FEW_POSTS}:</b><br /><span class="gensmall">{L_FEW_POSTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="posts" value="{HOW_MANY_POSTS}" size="5" maxlength="10" /></td>
</tr>
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DISPLAY_LATEST}:</b></td>
	<td class="row2"><input type="radio" {DISPLAY_LATEST_ENABLED} name="disp_late" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {DISPLAY_LATEST_DISABLED} name="disp_late" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISPLAY_HIGHEST}:</b></td>
	<td class="row2"><input type="radio" {DISPLAY_HIGHEST_ENABLED} name="disp_high" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {DISPLAY_HIGHEST_DISABLED} name="disp_high" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISPLAY_RANDOM}:</b></td>
	<td class="row2"><input type="radio" {DISPLAY_RANDOM_ENABLED} name="disp_rand" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {DISPLAY_RANDOM_DISABLED} name="disp_rand" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PIC_ROW}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="5" size="3" name="img_rows" value="{PIC_ROW}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PIC_COL}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="5" size="3" name="img_cols" value="{PIC_COL}" /></td>
</tr>
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PERSONAL_GALLERY}:</b></td>
	<td class="row2"><input type="radio" {PERSONAL_GALLERY_USER} name="personal_gallery" value="{S_USER}" /> {L_REG}&nbsp;&nbsp;<input type="radio" {PERSONAL_GALLERY_PRIVATE} name="personal_gallery" value="{S_PRIVATE}" /> {L_PRIVATE}&nbsp;&nbsp;<input type="radio" {PERSONAL_GALLERY_ADMIN} name="personal_gallery" value="{S_ADMIN}" /> {L_ADMIN}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PERSONAL_GALLERY_LIMIT}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="5" size="5" name="personal_gallery_limit" value="{PERSONAL_GALLERY_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PERSONAL_GALLERY_VIEW}:</b></td>
	<td class="row2"><input type="radio" {PERSONAL_GALLERY_VIEW_ALL} name="personal_gallery_view" value="{S_GUEST}" /> {L_GUEST}&nbsp;&nbsp;<input type="radio" {PERSONAL_GALLERY_VIEW_REG} name="personal_gallery_view" value="{S_USER}" /> {L_REG}&nbsp;&nbsp;<input type="radio" {PERSONAL_GALLERY_VIEW_PRIVATE} name="personal_gallery_view" value="{S_PRIVATE}" /> {L_PRIVATE}</td>
</tr>
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_RATE_SYSTEM}:</b></td>
	<td class="row2"><input type="radio" {RATE_ENABLED} name="rate" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {RATE_DISABLED} name="rate" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_RATE_SCALE}:</b></td>
	<td class="row2"><input class="post" type="text" name="rate_scale" value="{RATE_SCALE}" size="5" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_RATE_TYPE}:</b></td>
	<td class="row2"><select name="rate_type">
		<option {RATE_TYPE_0} value="0">{L_RATE_TYPE_0}</option>
		<option {RATE_TYPE_1} value="1">{L_RATE_TYPE_1}</option>
		<option {RATE_TYPE_2} value="2">{L_RATE_TYPE_2}</option>
	</select></td>
</tr>
<tr>
	<td class="spaceRow" colspan="2"><img src="../images/spacer.gif" alt="" width="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_COMMENT_SYSTEM}:</b></td>
	<td class="row2"><input type="radio" {COMMENT_ENABLED} name="comment" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {COMMENT_DISABLED} name="comment" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PIC_DESC_MAX_LENGTH}:</b></td>
	<td class="row2"><input class="post" type="text" size="6" name="desc_length" value="{PIC_DESC_MAX_LENGTH}" /> Bytes</td>
</tr>
<!-- END switch_config -->

<!-- BEGIN switch_thumb -->
<tr>
	<th class="thHead" colspan="2">{L_THUMBNAIL_SETTINGS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_THUMBNAIL_CACHE}:</b></td>
	<td class="row2"><input type="radio" {THUMBNAIL_CACHE_ENABLED} name="thumbnail_cache" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {THUMBNAIL_CACHE_DISABLED} name="thumbnail_cache" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_THUMBNAIL_SIZE}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="9" size="5" name="thumbnail_size" value="{THUMBNAIL_SIZE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_THUMBNAIL_QUALITY}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="3" size="5" name="thumbnail_quality" value="{THUMBNAIL_QUALITY}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_ROWS_PER_PAGE}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="2" size="5" name="rows_per_page" value="{ROWS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_COLS_PER_PAGE}:</b></td>
	<td class="row2"><input class="post" type="text" maxlength="2" size="5" name="cols_per_page" value="{COLS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DEFAULT_SORT_METHOD}:</b></td>
	<td class="row2"><select name="sort_method">
		<option {SORT_TIME} value='pic_time'>{L_TIME}</option>
		<option {SORT_PIC_TITLE} value='pic_title'>{L_PIC_TITLE}</option>
		<option {SORT_USERNAME} value='username'>{L_USERNAME}</option>
		<option {SORT_VIEW} value='pic_view_count'>{L_VIEW}</option>
		<option {SORT_RATING} value='rating'>{L_RATING}</option>
		<option {SORT_COMMENTS} value='comments'>{L_COMMENTS}</option>
		<option {SORT_NEW_COMMENT} value='new_comment'>{L_NEW_COMMENT}</option>
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
	<td class="row1"><b>{L_FULLPIC_POPUP}:</b></td>
	<td class="row2"><input type="radio" {FULLPIC_POPUP_ENABLED} name="fullpic_popup" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {FULLPIC_POPUP_DISABLED} name="fullpic_popup" value="0" /> {L_NO}</td>
</tr>
<!-- END switch_thumb -->

<!-- BEGIN switch_midthumb -->
<tr>
	<th class="thHead" colspan="2">{L_MID_THUMBNAIL_SETTINGS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_MIDTHUMB_USE}:</b></td>
	<td class="row2"><input type="radio" {MIDTHUMB_ENABLED} name="midthumb_use" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {MIDTHUMB_DISABLED} name="midthumb_use" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MIDTHUMB_CACHE}:</b></td>
	<td class="row2"><input type="radio" {MIDTHUMB_CACHE_ENABLED} name="midthumb_cache" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {MIDTHUMB_CACHE_DISABLED} name="midthumb_cache" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MIDTHUMB_SIZE}:</b><br /><span class="gensmall">{L_IMAGE_SIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="9" size="5" name="midthumb_height" value="{MIDTHUMB_HEIGHT}" /> x <input class="post" type="text" maxlength="9" size="5" name="midthumb_width" value="{MIDTHUMB_WIDTH}" /></td>
</tr>
<!-- END switch_midthumb -->

<!-- BEGIN switch_watermk -->
<tr>
	<th class="thHead" colspan="2">{L_ALBUM_SP_WATERMARK}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_WATERMARK}:</b></td>
	<td class="row2"><input type="radio" {WATERMARK_ENABLED} name="use_watermark" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {WATERMARK_DISABLED} name="use_watermark" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WATERMARK_USERS}:</b><br /><span class="gensmall">{L_WATERMARK_USERS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" {WATERMARK_USERS_ENABLED} name="wut_users" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {WATERMARK_USERS_DISABLED} name="wut_users" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WATERMARK_PLACENT}:</b></td>
	<td class="row2"><table cellpadding="4">
	<tr>
	  	<td><input type="radio" {WATERMAR_PLACEMENT_1} name="disp_watermark_at" value="1" /></td>
	  	<td><input type="radio" {WATERMAR_PLACEMENT_5} name="disp_watermark_at" value="5" /></td>
	  	<td><input type="radio" {WATERMAR_PLACEMENT_2} name="disp_watermark_at" value="2" /></td>
	</tr>
	<tr>
		<td><input type="radio" {WATERMAR_PLACEMENT_8} name="disp_watermark_at" value="8" /></td>
	  	<td><input type="radio" {WATERMAR_PLACEMENT_0} name="disp_watermark_at" value="0" /></td>
	  	<td><input type="radio" {WATERMAR_PLACEMENT_6} name="disp_watermark_at" value="6" /></td>
	</tr>
	<tr>
	  	<td><input type="radio" {WATERMAR_PLACEMENT_4} name="disp_watermark_at" value="4" /></td>
	  	<td><input type="radio" {WATERMAR_PLACEMENT_7} name="disp_watermark_at" value="7" /></td>
	  	<td><input type="radio" {WATERMAR_PLACEMENT_3} name="disp_watermark_at" value="3" /></td>
	</tr>
	</table></td>
</tr>
<!-- END switch_watermk -->

<!-- BEGIN switch_hon -->
<tr>
	<th class="thHead" colspan="2">{L_ALBUM_SP_HOTORNOT}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_HON_USERS}:</b></td>
	<td class="row2"><input type="radio" {HON_USERS_ENABLED} name="hon_rate_users" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {HON_USERS_DISABLED} name="hon_rate_users" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HON_ALREDY_RATED}:</b></td>
	<td class="row2"><input type="radio" {HON_ALREADY_RATED_ENABLED} name="hon_rate_times" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {HON_ALREADY_RATED_DISABLED} name="hon_rate_times" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HON_SEP_RATING}:</b></td>
	<td class="row2"><input type="radio" {HON_SEP_RATING_ENABLED} name="hon_rate_sep" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {HON_SEP_RATING_DISABLED} name="hon_rate_sep" value="0" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HON_WHERE}</b><br /><span class="gensmall">{L_HON_WHERE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" name="hon_rate_where" value="{HON_WHERE}" /></td>
</tr>
<!-- END switch_hon -->

<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>
