
<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_MODE_ACTION}">
<tr> 
	<td valign="bottom"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	<td align="right" nowrap="nowrap"><span class="genmed">{L_SELECT_IMTYPE_METHOD}:&nbsp;{S_IMTYPE_SELECT}<br />{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></span></td>
</tr>
</table>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th class="thCornerL" nowrap="nowrap">#</th>
	<th class="thTop" nowrap="nowrap">{L_USERNAME}</th>
	<!-- BEGIN switch_single_top -->
	<th class="thCornerR" nowrap="nowrap">{switch_single_top.L_IMTYPE}</th>
	<!-- END switch_single_top -->
	<!-- BEGIN switch_all_top -->
	<th class="thTop" nowrap="nowrap">{switch_all_top.L_IMTYPE_user_yim}</th>
	<th class="thTop" nowrap="nowrap">{switch_all_top.L_IMTYPE_user_aim}</th>
	<th class="thTop" nowrap="nowrap">{switch_all_top.L_IMTYPE_user_msnm}</th>
	<th class="thTop" nowrap="nowrap">{switch_all_top.L_IMTYPE_user_icq}</th>
	<th class="thTop" nowrap="nowrap">{switch_all_top.L_IMTYPE_user_gtalk}</th>
	<th class="thTop" nowrap="nowrap">{switch_all_top.L_IMTYPE_user_xfi}</th>
	<th class="thCornerR" nowrap="nowrap">{switch_all_top.L_IMTYPE_user_skype}</th>
	<!-- END switch_all_top -->
</tr>
<!-- BEGIN memberrow -->
<tr> 
	<td class="{memberrow.ROW_CLASS}" align="center"><span class="gen">&nbsp;{memberrow.ROW_NUMBER}&nbsp;</span></td>
	<td class="{memberrow.ROW_CLASS}" align="center"><span class="gen"><a href="{memberrow.U_VIEWPROFILE}" class="gen">{memberrow.USERNAME}</a></span></td>

	<!-- BEGIN switch_single_list -->
	<td class="{memberrow.ROW_CLASS}" align="center">&nbsp;{memberrow.switch_single_list.IMTYPE_IMG}&nbsp;</td>
	<!-- END switch_single_list -->

	<!-- BEGIN switch_all_list -->
	<td class="{memberrow.ROW_CLASS}" align="center">&nbsp;{memberrow.switch_all_list.IMTYPE_IMG_user_yim}&nbsp;</td>
	<td class="{memberrow.ROW_CLASS}" align="center">&nbsp;{memberrow.switch_all_list.IMTYPE_IMG_user_aim}&nbsp;</td>
	<td class="{memberrow.ROW_CLASS}" align="center">&nbsp;{memberrow.switch_all_list.IMTYPE_IMG_user_msnm}&nbsp;</td>
	<td class="{memberrow.ROW_CLASS}" align="center">&nbsp;{memberrow.switch_all_list.IMTYPE_IMG_user_icq}&nbsp;</td>
	<td class="{memberrow.ROW_CLASS}" align="center">&nbsp;{memberrow.switch_all_list.IMTYPE_IMG_user_gtalk}&nbsp;</td>
	<td class="{memberrow.ROW_CLASS}" align="center">&nbsp;{memberrow.switch_all_list.IMTYPE_IMG_user_xfi}&nbsp;</td>
	<td class="{memberrow.ROW_CLASS}" align="center">&nbsp;<div style="position:relative"><div style="position:relative;">{memberrow.switch_all_list.IMTYPE_IMG_user_skype}</div><div style="position:absolute;left:3px;top:-1px">{memberrow.switch_all_list.IMTYPE_USER_user_skype}</div></div>&nbsp;</td>
	<!-- END switch_all_list -->
</tr>
<!-- END memberrow -->
<tr> 
	<td class="catbottom" colspan="9">&nbsp;</td>
</tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2">
<tr> 
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
</tr>
</form></table>

<table width="100%" cellspacing="2" align="center">
<tr> 
	<td valign="top" align="right"><br />{JUMPBOX}</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Instant Messenger List 1.5.2 &copy; 2002, {COPYRIGHT_YEAR} <a href="http://www.phpbbsmith.com" class="copyright" target="_blank">Thoul</a></div>

