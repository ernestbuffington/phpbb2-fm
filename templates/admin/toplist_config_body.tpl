{TOPLIST_MENU}
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
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">

<!-- BEGIN switch_config -->
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_TOPLIST_IMGE_DIS}:</b><br /><span class="gensmall">{L_TOPLIST_IMGE_DIS_EXPLAIN}</span></td>
      	<td class="row2"><input type="text" size="5" maxlength="3" name="toplist_imge_dis" value="{TOPLIST_IMGE_DIS}" class="post" /></td>
</tr>
<tr>
      	<td class="row1"><b>{L_HIN_ACTIVATION}:</b><br /><span class="gensmall">{L_HIN_ACTIVATION_EXPLAIN}</span></td>
   	<td class="row2"><input type="radio" name="toplist_hin_activation" value="1" {HINACTEN} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="toplist_hin_activation" value="0" {HINACTDI} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_TOPLIST_TOPLIST_TOP10}:</b><br /><span class="gensmall">{L_TOPLIST_TOPLIST_TOP10_EXPLAIN}</span></td>
   	<td class="row2"><input type="radio" name="toplist_toplist_top10" value="1" {TOP10EN} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="toplist_toplist_top10" value="0" {TOP10DI} /> {L_NO}</td>
</tr>
<!-- END switch_config -->

<!-- BEGIN switch_button -->
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_BUTTON_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_BUTTON_1}:</b></td>
	<td class="row2"><input type="text" maxlength="255" size="35" name="toplist_button_1" value="{BUTTON_1}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BUTTON_1_L}:</b></td>
	<td class="row2"><input type="text" maxlength="255" size="35" name="toplist_button_1_l" value="{BUTTON_1_L}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BUTTON_2}:</b></td>
	<td class="row2"><input type="text" maxlength="255" size="35" name="toplist_button_2" value="{BUTTON_2}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BUTTON_2_L}:</b></td>
	<td class="row2"><input type="text" maxlength="255" size="35" name="toplist_button_2_l" value="{BUTTON_2_L}" class="post" /></td>
</tr>   
<!-- END switch_button -->

<!-- BEGIN switch_hits -->
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_HITS_IN}:</b></td>
   	<td class="row2"><input type="radio" name="toplist_view_hin_hits" value="1" {INHITSEN} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="toplist_view_hin_hits" value="0" {INHITSDI} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HITS_OUT}:</b></td>
   	<td class="row2"><input type="radio" name="toplist_view_out_hits" value="1" {OUTHITSEN} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="toplist_view_out_hits" value="0" {OUTHITSDI} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_HITS_IMG}:</b></td>
   	<td class="row2"><input type="radio" name="toplist_view_img_hits" value="1" {IMGHITSEN} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="toplist_view_img_hits" value="0" {IMGHITSDI} /> {L_NO}</td>
</tr>
<!-- END switch_hits -->

<!-- BEGIN switch_image -->
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_DIMENSIONS_EXPL_WIDTH}:</b></td>
	<td class="row2"><input type="text" size="5" maxlength="3" name="dimension_width" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DIMENSIONS_EXPL_HEIGHT}:</b></td>
	<td class="row2"><input type="text" size="5" maxlength="3" name="dimension_heigth" class="post" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DIMENSIONS_EXPL_MULTIPLE}:</b></td>
	<td class="row2"><select name="toplist_dimensions[]" multiple="multiple" size="5">{DIMENSIONS}</select></td>
</tr>
<!-- END switch_image -->

<!-- BEGIN switch_interval -->
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_PRUNE_INTERVAL_HIN}:</b></td>
	<td class="row2"><select size="1" name="toplist_prune_hin_hits_interval">
		<option {PRUNE_HIN_0_SELECT} value="0">{L_NEVER}</option>
		<option {PRUNE_HIN_3600_SELECT} value="3600">1 {L_HOURS}</option>
		<option {PRUNE_HIN_7200_SELECT} value="7200">2 {L_HOURS}</option>
		<option {PRUNE_HIN_21600_SELECT} value="21600">6 {L_HOURS}</option>
		<option {PRUNE_HIN_43200_SELECT} value="43200">12 {L_HOURS}</option>
		<option {PRUNE_HIN_86400_SELECT} value="86400">{L_1_DAY}</option>
		<option {PRUNE_HIN_172800_SELECT} value="172800">2 {L_DAYS}</option>
		<option {PRUNE_HIN_259200_SELECT} value="259200">3 {L_DAYS}</option>
		<option {PRUNE_HIN_604800_SELECT} value="604800">{L_1_WK}</option>
		<option {PRUNE_HIN_1209600_SELECT} value="1209600">{L_2_WKS}</option>
		<option {PRUNE_HIN_2592000_SELECT} value="2592000">{L_1_MTH}</option>
		<option {PRUNE_HIN_5184000_SELECT} value="5184000">2 {L_MONTHS}</option>
		<option {PRUNE_HIN_15552000_SELECT} value="15552000">{L_6_MTHS}</option>
		<option {PRUNE_HIN_31536000_SELECT} value="31536000">{L_1_YR}</option>
		<option {PRUNE_HIN_63072000_SELECT} value="63072000">2 {L_YEARS}</option>
		<option {PRUNE_HIN_157680000_SELECT} value="157680000">5 {L_YEARS}</option>
		<option {PRUNE_HIN_315360000_SELECT} value="315360000">10 {L_YEARS}</option>
		<option {PRUNE_HIN_630720000_SELECT} value="630720000">20 {L_YEARS}</option>
		<option {PRUNE_HIN_157680000_SELECT} value="1576800000">50 {L_YEARS}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_PRUNE_INTERVAL_OUT}</b></td>
      	<td class="row2"><select size="1" name="toplist_prune_out_hits_interval">
		<option {PRUNE_OUT_0_SELECT} value="0">{L_NEVER}</option>
		<option {PRUNE_OUT_3600_SELECT} value="3600">1 {L_HOURS}</option>
		<option {PRUNE_OUT_7200_SELECT} value="7200">2 {L_HOURS}</option>
		<option {PRUNE_OUT_21600_SELECT} value="21600">6 {L_HOURS}</option>
		<option {PRUNE_OUT_43200_SELECT} value="43200">12 {L_HOURS}</option>
		<option {PRUNE_OUT_86400_SELECT} value="86400">{L_1_DAY}</option>
		<option {PRUNE_OUT_172800_SELECT} value="172800">2 {L_DAYS}</option>
		<option {PRUNE_OUT_259200_SELECT} value="259200">3 {L_DAYS}</option>
		<option {PRUNE_OUT_604800_SELECT} value="604800">{L_1_WK}</option>
		<option {PRUNE_OUT_1209600_SELECT} value="1209600">{L_2_WKS}</option>
		<option {PRUNE_OUT_2592000_SELECT} value="2592000">{L_1_MTH}</option>
		<option {PRUNE_OUT_5184000_SELECT} value="5184000">2 {L_MONTHS}</option>
		<option {PRUNE_OUT_15552000_SELECT} value="15552000">{L_6_MTHS}</option>
		<option {PRUNE_OUT_31536000_SELECT} value="31536000">{L_1_YR}</option>
		<option {PRUNE_OUT_63072000_SELECT} value="63072000">2 {L_YEARS}</option>
		<option {PRUNE_OUT_157680000_SELECT} value="157680000">5 {L_YEARS}</option>
		<option {PRUNE_OUT_315360000_SELECT} value="315360000">10 {L_YEARS}</option>
		<option {PRUNE_OUT_630720000_SELECT} value="630720000">20 {L_YEARS}</option>
		<option {PRUNE_OUT_157680000_SELECT} value="1576800000">50 {L_YEARS}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_PRUNE_INTERVAL_IMG}:</b></td>
	<td class="row2"><select size="1" name="toplist_prune_img_hits_interval">
		<option {PRUNE_IMG_0_SELECT} value="0">{L_NEVER}</option>
		<option {PRUNE_IMG_3600_SELECT} value="3600">1 {L_HOURS}</option>
		<option {PRUNE_IMG_7200_SELECT} value="7200">2 {L_HOURS}</option>
		<option {PRUNE_IMG_21600_SELECT} value="21600">6 {L_HOURS}</option>
		<option {PRUNE_IMG_43200_SELECT} value="43200">12 {L_HOURS}</option>
		<option {PRUNE_IMG_86400_SELECT} value="86400">{L_1_DAY}</option>
		<option {PRUNE_IMG_172800_SELECT} value="172800">2 {L_DAYS}</option>
		<option {PRUNE_IMG_259200_SELECT} value="259200">3 {L_DAYS}</option>
		<option {PRUNE_IMG_604800_SELECT} value="604800">{L_1_WK}</option>
		<option {PRUNE_IMG_1209600_SELECT} value="1209600">{L_2_WKS}</option>
		<option {PRUNE_IMG_2592000_SELECT} value="2592000">{L_1_MTH}</option>
		<option {PRUNE_IMG_5184000_SELECT} value="5184000">2 {L_MONTHS}</option>
		<option {PRUNE_IMG_15552000_SELECT} value="15552000">{L_6_MTHS}</option>
		<option {PRUNE_IMG_31536000_SELECT} value="31536000">{L_1_YR}</option>
		<option {PRUNE_IMG_63072000_SELECT} value="63072000">2 {L_YEARS}</option>
		<option {PRUNE_IMG_157680000_SELECT} value="157680000">5 {L_YEARS}</option>
		<option {PRUNE_IMG_315360000_SELECT} value="315360000">10 {L_YEARS}</option>
		<option {PRUNE_IMG_630720000_SELECT} value="630720000">20 {L_YEARS}</option>
		<option {PRUNE_IMG_157680000_SELECT} value="1576800000">50 {L_YEARS}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_ANTI_FLOOD_INTERVAL_HIN}:</b></td>
	<td class="row2"><select size="1" name="toplist_anti_flood_hin_hits_interval">
		<option {ANTI_FLOOD_HIN_0_SELECT} value="0">{L_NEVER}</option>
		<option {ANTI_FLOOD_HIN_3600_SELECT} value="3600">1 {L_HOURS}</option>
		<option {ANTI_FLOOD_HIN_7200_SELECT} value="7200">2 {L_HOURS}</option>
		<option {ANTI_FLOOD_HIN_21600_SELECT} value="21600">6 {L_HOURS}</option>
		<option {ANTI_FLOOD_HIN_43200_SELECT} value="43200">12 {L_HOURS}</option>
		<option {ANTI_FLOOD_HIN_86400_SELECT} value="86400">{L_1_DAY}</option>
		<option {ANTI_FLOOD_HIN_172800_SELECT} value="172800">2 {L_DAYS}</option>
		<option {ANTI_FLOOD_HIN_259200_SELECT} value="259200">3 {L_DAYS}</option>
		<option {ANTI_FLOOD_HIN_604800_SELECT} value="604800">{L_1_WK}</option>
		<option {ANTI_FLOOD_HIN_1209600_SELECT} value="1209600">{L_2_WKS}</option>
		<option {ANTI_FLOOD_HIN_2592000_SELECT} value="2592000">{L_1_MTH}</option>
		<option {ANTI_FLOOD_HIN_5184000_SELECT} value="5184000">2 {L_MONTHS}</option>
		<option {ANTI_FLOOD_HIN_15552000_SELECT} value="15552000">{L_6_MTHS}</option>
		<option {ANTI_FLOOD_HIN_31536000_SELECT} value="31536000">{L_1_YR}</option>
		<option {ANTI_FLOOD_HIN_63072000_SELECT} value="63072000">2 {L_YEARS}</option>
		<option {ANTI_FLOOD_HIN_157680000_SELECT} value="157680000">5 {L_YEARS}</option>
		<option {ANTI_FLOOD_HIN_315360000_SELECT} value="315360000">10 {L_YEARS}</option>
		<option {ANTI_FLOOD_HIN_630720000_SELECT} value="630720000">20 {L_YEARS}</option>
		<option {ANTI_FLOOD_HIN_157680000_SELECT} value="1576800000">50 {L_YEARS}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_ANTI_FLOOD_INTERVAL_OUT}:</b></td>
	<td class="row2"><select size="1" name="toplist_anti_flood_out_hits_interval">
		<option {ANTI_FLOOD_OUT_0_SELECT} value="0">{L_NEVER}</option>
		<option {ANTI_FLOOD_OUT_3600_SELECT} value="3600">1 {L_HOURS}</option>
		<option {ANTI_FLOOD_OUT_7200_SELECT} value="7200">2 {L_HOURS}</option>
		<option {ANTI_FLOOD_OUT_21600_SELECT} value="21600">6 {L_HOURS}</option>
		<option {ANTI_FLOOD_OUT_43200_SELECT} value="43200">12 {L_HOURS}</option>
		<option {ANTI_FLOOD_OUT_86400_SELECT} value="86400">{L_1_DAY}</option>
		<option {ANTI_FLOOD_OUT_172800_SELECT} value="172800">2 {L_DAYS}</option>
		<option {ANTI_FLOOD_OUT_259200_SELECT} value="259200">3 {L_DAYS}</option>
		<option {ANTI_FLOOD_OUT_604800_SELECT} value="604800">{L_1_WK}</option>
		<option {ANTI_FLOOD_OUT_1209600_SELECT} value="1209600">{L_2_WKS}</option>
		<option {ANTI_FLOOD_OUT_2592000_SELECT} value="2592000">{L_1_MTH}</option>
		<option {ANTI_FLOOD_OUT_5184000_SELECT} value="5184000">2 {L_MONTHS}</option>
		<option {ANTI_FLOOD_OUT_15552000_SELECT} value="15552000">{L_6_MTHS}</option>
		<option {ANTI_FLOOD_OUT_31536000_SELECT} value="31536000">{L_1_YR}</option>
		<option {ANTI_FLOOD_OUT_63072000_SELECT} value="63072000">2 {L_YEARS}</option>
		<option {ANTI_FLOOD_OUT_157680000_SELECT} value="157680000">5 {L_YEARS}</option>
		<option {ANTI_FLOOD_OUT_315360000_SELECT} value="315360000">10 {L_YEARS}</option>
		<option {ANTI_FLOOD_OUT_630720000_SELECT} value="630720000">20 {L_YEARS}</option>
		<option {ANTI_FLOOD_OUT_157680000_SELECT} value="1576800000">50 {L_YEARS}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><b>{L_ANTI_FLOOD_INTERVAL_IMG}:</b></td>
	<td class="row2"><select size="1" name="toplist_anti_flood_img_hits_interval">
		<option {ANTI_FLOOD_IMG_0_SELECT} value="0">{L_NEVER}</option>
		<option {ANTI_FLOOD_IMG_3600_SELECT} value="3600">1 {L_HOURS}</option>
		<option {ANTI_FLOOD_IMG_7200_SELECT} value="7200">2 {L_HOURS}</option>
		<option {ANTI_FLOOD_IMG_21600_SELECT} value="21600">6 {L_HOURS}</option>
		<option {ANTI_FLOOD_IMG_43200_SELECT} value="43200">12 {L_HOURS}</option>
		<option {ANTI_FLOOD_IMG_86400_SELECT} value="86400">{L_1_DAY}</option>
		<option {ANTI_FLOOD_IMG_172800_SELECT} value="172800">2 {L_DAYS}</option>
		<option {ANTI_FLOOD_IMG_259200_SELECT} value="259200">3 {L_DAYS}</option>
		<option {ANTI_FLOOD_IMG_604800_SELECT} value="604800">{L_1_WK}</option>
		<option {ANTI_FLOOD_IMG_1209600_SELECT} value="1209600">{L_2_WKS}</option>
		<option {ANTI_FLOOD_IMG_2592000_SELECT} value="2592000">{L_1_MTH}</option>
		<option {ANTI_FLOOD_IMG_5184000_SELECT} value="5184000">2 {L_MONTHS}</option>
		<option {ANTI_FLOOD_IMG_15552000_SELECT} value="15552000">{L_6_MTHS}</option>
		<option {ANTI_FLOOD_IMG_31536000_SELECT} value="31536000">{L_1_YR}</option>
		<option {ANTI_FLOOD_IMG_63072000_SELECT} value="63072000">2 {L_YEARS}</option>
		<option {ANTI_FLOOD_IMG_157680000_SELECT} value="157680000">5 {L_YEARS}</option>
		<option {ANTI_FLOOD_IMG_315360000_SELECT} value="315360000">10 {L_YEARS}</option>
		<option {ANTI_FLOOD_IMG_630720000_SELECT} value="630720000">20 {L_YEARS}</option>
		<option {ANTI_FLOOD_IMG_157680000_SELECT} value="1576800000">50 {L_YEARS}</option>
	</select></td>
</tr>
<!-- END switch_interval -->

<!-- BEGIN switch_rank -->
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
 	<td class="row2" colspan="2"><span class="gensmall">{L_COUNT_HITS_EX}</span></td>
</tr>
<tr>
      <td class="row1" width="50%"><b>{L_COUNT_HITS_IN}:</b></td>
      <td class="row2"><input type="checkbox" name="toplist_count_hin_hits" {COUNT_INHITS} /></td>
</tr>
<tr>
      <td class="row1"><b>{L_COUNT_HITS_OUT}:</b></td>
      <td class="row2"><input type="checkbox" name="toplist_count_out_hits" {COUNT_OUTHITS} /></td>
</tr>
<tr>
      <td class="row1"><b>{L_COUNT_HITS_IMG}:</b></td>
      <td class="row2"><input type="checkbox" name="toplist_count_img_hits" {COUNT_IMGHITS} /></td>
</tr>
<!-- END switch_rank -->

<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Toplist 1.3.8 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.wyrihaximus.net" target="_blank" class="copyright">WyriHaximus</a></div>

