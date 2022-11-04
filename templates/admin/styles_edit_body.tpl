{STYLE_MENU}{TPL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!-- 
function showcolor(cbutton,value) 
{ 
	cbutton.style.backgroundColor = value; 
} 
//--> 
</script> 

<h1>{L_THEMES_TITLE}</h1>

<p>{L_THEMES_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_THEME_ACTION}" method="post">
<tr>
  	<th class="thHead" colspan="3">{L_THEME_SETTINGS}</th>
</tr>
<tr>
	<td class="row1"><b>{L_THEME_NAME}:</b></td>
	<td class="row2" colspan="2"><input class="post" type="text" size="25" maxlength="30" name="style_name" value="{THEME_NAME}" /></td>
</tr>
<!-- BEGIN switch_noedit -->
<tr>
	<td class="row1"><b>{L_THEME_VERSION}:</b></td>
	<td class="row2" colspan="2"><input type="hidden" name="style_version" value="{THEME_VERSION}" /><b>{THEME_VERSION}</b></td>
</tr>
<!-- END switch_noedit -->
<!-- BEGIN switch_edit -->
<tr>
	<td class="row1"><b>{L_THEME_VERSION}:</b></td>
	<td class="row2" colspan="2"><input class="post" type="text" size="10" maxlength="6" name="style_version" value="{THEME_VERSION}" /></td>
</tr>
<!-- END switch_edit -->
<tr>
	<td class="row1"><b>{L_TEMPLATE}:</b></td>
	<td class="row2" colspan="2">{S_TEMPLATE_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_STYLESHEET}:</b><br /><span class="gensmall">{L_STYLESHEET_EXPLAIN}</span></td>
	<td class="row2" colspan="2"><input class="post" type="text" size="25" maxlength="100" name="head_stylesheet" value="{HEAD_STYLESHEET}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_IMAGE_CFG}:</b><br /><span class="gensmall">{L_IMAGE_CFG_EXPLAIN}</span></td>
	<td class="row2" colspan="2"><input class="post" type="text" size="25" maxlength="100" name="image_cfg" value="{IMAGE_CFG}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BACKGROUND_IMAGE}:</b></td>
	<td class="row2" colspan="2"><input class="post" type="text" size="50" maxlength="100" name="body_background" value="{BODY_BACKGROUND}"></td>
</tr>
<tr>
	<td class="row1"><b>{L_THEME_HEADER}</b>:</td>
	<td class="row2" colspan="2"><input type="radio" name="theme_header" value="1" {THEME_HEADER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="theme_header" value="0" {THEME_HEADER_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_THEME_FOOTER}</b>:</td>
	<td class="row2" colspan="2"><input type="radio" name="theme_footer" value="1" {THEME_FOOTER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="theme_footer" value="0" {THEME_FOOTER_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_THEME_PUBLIC}</b>:<br /><span class="gensmall">{L_THEME_PUBLIC_EXPLAIN}</span></td>
	<td class="row2" colspan="2"><input type="radio" name="theme_public" value="1" {THEME_PUBLIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="theme_public" value="0" {THEME_PUBLIC_NO} /> {L_NO}</td>
</tr>
<tr>
	<th class="thCornerL" width="40%">{L_THEME_ELEMENT}</th>
	<th class="thTop" width="30%">{L_VALUE}</th>
	<th class="thCornerR" width="30%">{L_SIMPLE_NAME}</th>
</tr>
<tr>
	<td class="row1"><b>{L_FONTCOLOR_ADMIN}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="adminfontcolor" value="{ADMINFONTCOLOR}" onChange="showcolor(this.form.adminfontcolor_color,this.value)" /> <input type="button" disabled="disabled" value="       " id="adminfontcolor_color" style="background-color:{ADMINFONTCOLOR}"></td>
	<td class="row2">&nbsp;</td>		
</tr>
<tr>
	<td class="row1"><b>{L_BOLD_ADMIN}:</b></span></td>
	<td class="row2"><input type="radio" name="adminbold" value="1" {BOLD_ADMIN_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="adminbold" value="0" {BOLD_ADMIN_NO} /> {L_NO}</td>
	<td class="row2">&nbsp;</td>		
</tr>
<tr>
	<td class="row1"><b>{L_FONTCOLOR_SUPERMOD}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="supermodfontcolor" value="{SUPERMODFONTCOLOR}" onChange="showcolor(this.form.supermodfontcolor_color,this.value)" /> <input type="button" disabled="disabled" value="       " id="supermodfontcolor_color" style="background-color:{SUPERMODFONTCOLOR}"></td>
	<td class="row2">&nbsp;</td>		
</tr>
<tr>
	<td class="row1"><b>{L_BOLD_SUPERMOD}:</b></span></td>
	<td class="row2"><input type="radio" name="supermodbold" value="1" {BOLD_SUPERMOD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="supermodbold" value="0" {BOLD_SUPERMOD_NO} /> {L_NO}</td>
	<td class="row2">&nbsp;</td>		
</tr>
<tr>
	<td class="row1"><b>{L_FONTCOLOR_MOD}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="modfontcolor" value="{MODFONTCOLOR}" onChange="showcolor(this.form.modfontcolor_color,this.value)" /> <input type="button" disabled="disabled" value="       " id="modfontcolor_color" style="background-color:{MODFONTCOLOR}"></td>
	<td class="row2">&nbsp;</td>		
</tr>
<tr>
	<td class="row1"><b>{L_BOLD_MOD}:</b></span></td>
	<td class="row2"><input type="radio" name="modbold" value="1" {BOLD_MOD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="modbold" value="0" {BOLD_MOD_NO} /> {L_NO}</td>
	<td class="row2">&nbsp;</td>		
</tr>
<tr>
	<td class="row1"><b>{L_FONTCOLOR_PLAYERS}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="playersfontcolor" value="{PLAYERSFONTCOLOR}" onChange="showcolor(this.form.playersfontcolor_color,this.value)" /> <input type="button" disabled="disabled" value="       " id="playersfontcolor_color" style="background-color:{PLAYERSFONTCOLOR}"></td>
	<td class="row2">&nbsp;</td>		
</tr>
<tr>
	<td class="row1"><b>{L_FONTCOLOR_BOT}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="botfontcolor" value="{BOTFONTCOLOR}" onChange="showcolor(this.form.botfontcolor_color,this.value)" /> <input type="button" disabled="disabled" value="       " id="botfontcolor_color" style="background-color:{BOTFONTCOLOR}"></td>
	<td class="row2">&nbsp;</td>		
</tr>
<tr> 
	<td class="spaceRow" colspan="3" height="1"><img src="../images/spacer.gif" alt="" title="" width="1" height="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_BACKGROUND_COLOR}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="body_bgcolor" value="{BODY_BGCOLOR}"  onChange="showcolor(this.form.body_bg_color_color,this.value)" /> <input type="button" disabled="disabled" value="       " id="body_bg_color_color" style="background-color:{BODY_BGCOLOR}"></td> 
	<td class="row2">&nbsp;</td>
</tr>
<tr>
	<td class="row1"><b>{L_BODY_TEXT_COLOR}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="body_text" value="{BODY_TEXT_COLOR}" onChange="showcolor(this.form.body_text_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="body_text_color" style="background-color:{BODY_TEXT_COLOR}"></td> 
	<td class="row2">&nbsp;</td>
</tr>
<tr>
	<td class="row1"><b>{L_BODY_LINK_COLOR}:</b></td>
	<td class="row2"><input class="post"  type="text" size="10" maxlength="6" name="body_link" value="{BODY_LINK_COLOR}" onChange="showcolor(this.form.body_link_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="body_link_color" style="background-color:{BODY_LINK_COLOR}"></td> 
	<td class="row2">&nbsp;</td>
</tr>
<tr>
	<td class="row1"><b>{L_BODY_VLINK_COLOR}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="body_vlink" value="{BODY_VLINK_COLOR}" onChange="showcolor(this.form.body_vlink_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="body_vlink_color" style="background-color:{BODY_VLINK_COLOR}"></td> 
	<td class="row2">&nbsp;</td>		
</tr>
<tr>
	<td class="row1"><b>{L_BODY_ALINK_COLOR}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="body_alink" value="{BODY_ALINK_COLOR}" onChange="showcolor(this.form.body_alink_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="body_alink_color" style="background-color:{BODY_ALINK_COLOR}"></td> 
	<td class="row2">&nbsp;</td>		
</tr>
<tr>
	<td class="row1"><b>{L_BODY_HLINK_COLOR}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="body_hlink" value="{BODY_HLINK_COLOR}" onChange="showcolor(this.form.body_hlink_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="body_hlink_color" style="background-color:{BODY_HLINK_COLOR}"></td> 
	<td class="row2">&nbsp;</td>		
</tr>
<tr>
	<td class="row1"><b>{L_TR_COLOR1}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="tr_color1" value="{TR_COLOR1}" onChange="showcolor(this.form.tr_color1_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="tr_color1_color" style="background-color:{TR_COLOR1}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="tr_color1_name" value="{TR_COLOR1_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TR_COLOR2}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="tr_color2" value="{TR_COLOR2}" onChange="showcolor(this.form.tr_color2_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="tr_color2_color" style="background-color:{TR_COLOR2}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="tr_color2_name" value="{TR_COLOR2_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TR_COLOR3}:</b></td>
	<td class="row2"><input class="post"  type="text" size="10" maxlength="6" name="tr_color3" value="{TR_COLOR3}" onChange="showcolor(this.form.tr_color3_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="tr_color3_color" style="background-color:{TR_COLOR3}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="tr_color3_name" value="{TR_COLOR3_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TR_CLASS1}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="25" name="tr_class1" value="{TR_CLASS1}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="tr_class1_name" value="{TR_CLASS1_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TR_CLASS2}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="25" name="tr_class2" value="{TR_CLASS2}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="tr_class2_name" value="{TR_CLASS2_NAME}">
</tr>
<tr>
	<td class="row1"><b>{L_TR_CLASS3}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="25" name="tr_class3" value="{TR_CLASS3}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="tr_class3_name" value="{TR_CLASS3_NAME}">
</tr>
<tr>
	<td class="row1"><b>{L_TH_COLOR1}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="th_color1" value="{TH_COLOR1}" onChange="showcolor(this.form.th_color1_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="th_color1_color" style="background-color:{TH_COLOR1}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="th_color1_name" value="{TH_COLOR1_NAME}">
</tr>
<tr>
	<td class="row1"><b>{L_TH_COLOR2}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="th_color2" value="{TH_COLOR2}" onChange="showcolor(this.form.th_color2_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="th_color2_color" style="background-color:{TH_COLOR2}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="th_color2_name" value="{TH_COLOR2_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TH_COLOR3}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="th_color3" value="{TH_COLOR3}" onChange="showcolor(this.form.th_color3_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="th_color3_color" style="background-color:{TH_COLOR3}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="th_color3_name" value="{TH_COLOR3_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TH_CLASS1}:</b></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="100" name="th_class1" value="{TH_CLASS1}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="th_class1_name" value="{TH_CLASS1_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TH_CLASS2}:</b></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="100" name="th_class2" value="{TH_CLASS2}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="th_class2_name" value="{TH_CLASS2_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TH_CLASS3}:</td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="100" name="th_class3" value="{TH_CLASS3}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="th_class3_name" value="{TH_CLASS3_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TD_COLOR1}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="td_color1" value="{TD_COLOR1}" onChange="showcolor(this.form.td_color1_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="td_color1_color" style="background-color:{TD_COLOR1}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="td_color1_name" value="{TD_COLOR1_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TD_COLOR2}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="td_color2" value="{TD_COLOR2}" onChange="showcolor(this.form.td_color2_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="td_color2_color" style="background-color:{TD_COLOR2}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="td_color2_name" value="{TD_COLOR2_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TD_COLOR3}:</b></td>
	<td class="row2"><input class="post"  type="text" size="10" maxlength="6" name="td_color3" value="{TD_COLOR3}" onChange="showcolor(this.form.td_color3_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="td_color3_color" style="background-color:{TD_COLOR3}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="td_color3_name" value="{TD_COLOR3_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TD_CLASS1}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="100" name="td_class1" value="{TD_CLASS1}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="25" name="td_class1_name" value="{TD_CLASS1_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TD_CLASS2}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="100" name="td_class2" value="{TD_CLASS2}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="25" name="td_class2_name" value="{TD_CLASS2_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_TD_CLASS3}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="100" name="td_class3" value="{TD_CLASS3}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="25" name="td_class3_name" value="{TD_CLASS3_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_FONTFACE_1}:</b></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="50" name="fontface1" value="{FONTFACE1}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="fontface1_name" value="{FONTFACE1_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_FONTFACE_2}:</b></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="50" name="fontface2" value="{FONTFACE2}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="fontface2_name" value="{FONTFACE2_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_FONTFACE_3}:</b></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="50" name="fontface3" value="{FONTFACE3}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="fontface3_name" value="{FONTFACE3_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_FONTSIZE_1}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="fontsize1" value="{FONTSIZE1}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="fontsize1_name" value="{FONTSIZE1_NAME}" />	
</tr>
<tr>
	<td class="row1"><b>{L_FONTSIZE_2}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="fontsize2" value="{FONTSIZE2}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="fontsize2_name" value="{FONTSIZE2_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_FONTSIZE_3}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="fontsize3" value="{FONTSIZE3}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="fontsize3_name" value="{FONTSIZE3_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_FONTCOLOR_1}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="fontcolor1" value="{FONTCOLOR1}" onChange="showcolor(this.form.fontcolor1_color,this.value)" /> <input type="button" disabled="disabled" value="       " id="fontcolor1_color" style="background-color:{FONTCOLOR1}"></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="fontcolor1_name" value="{FONTCOLOR1_NAME}" />	
</tr>
<tr>
	<td class="row1"><b>{L_FONTCOLOR_2}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="fontcolor2" value="{FONTCOLOR2}" onChange="showcolor(this.form.fontcolor2_color,this.value)" /> <input type="button" disabled="disabled" value="       " id="fontcolor2_color" style="background-color:{FONTCOLOR2}"></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="fontcolor2_name" value="{FONTCOLOR2_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_FONTCOLOR_3}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="fontcolor3" value="{FONTCOLOR3}" onChange="showcolor(this.form.fontcolor3_color,this.value)" /> <input type="button" disabled="disabled" value="       " id="fontcolor3_color" style="background-color:{FONTCOLOR3}"></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="fontcolor3_name" value="{FONTCOLOR3_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_FONTCOLOR_4}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="fontcolor4" value="{FONTCOLOR4}" onChange="showcolor(this.form.fontcolor4_color,this.value)" /> <input type="button" disabled="disabled" value="       " id="fontcolor4_color" style="background-color:{FONTCOLOR4}"></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="fontcolor4_name" value="{FONTCOLOR4_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_SPAN_CLASS_1}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="25" name="span_class1" value="{SPAN_CLASS1}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="span_class1_name" value="{SPAN_CLASS1_NAME}" />	
</tr>
<tr>
	<td class="row1"><b>{L_SPAN_CLASS_2}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="25" name="span_class2" value="{SPAN_CLASS2}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="span_class2_name" value="{SPAN_CLASS2_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_SPAN_CLASS_3}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="25" name="span_class3" value="{SPAN_CLASS3}" /></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="span_class3_name" value="{SPAN_CLASS3_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_JB_COLOR1}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="jb_color1" value="{JB_COLOR1}" onChange="showcolor(this.form.jb_color1_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="jb_color1_color" style="background-color:{JB_COLOR1}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="jb_color1_name" value="{JB_COLOR1_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_JB_COLOR2}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="jb_color2" value="{JB_COLOR2}" onChange="showcolor(this.form.jb_color2_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="jb_color2_color" style="background-color:{JB_COLOR2}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="jb_color2_name" value="{JB_COLOR2_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_JB_COLOR3}:</b></td>
	<td class="row2"><input class="post"  type="text" size="10" maxlength="6" name="jb_color3" value="{JB_COLOR3}" onChange="showcolor(this.form.jb_color3_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="jb_color3_color" style="background-color:{JB_COLOR3}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="jb_color3_name" value="{JB_COLOR3_NAME}" />
</tr>
<tr> 
	<td class="spaceRow" colspan="3" height="1"><img src="../images/spacer.gif" alt="" title="" width="1" height="1" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_HR_COLOR1}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="hr_color1" value="{HR_COLOR1}" onChange="showcolor(this.form.hr_color1_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="hr_color1_color" style="background-color:{HR_COLOR1}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="hr_color1_name" value="{HR_COLOR1_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_HR_COLOR2}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="hr_color2" value="{HR_COLOR2}" onChange="showcolor(this.form.hr_color2_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="hr_color2_color" style="background-color:{HR_COLOR2}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="hr_color2_name" value="{HR_COLOR2_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_HR_COLOR3}:</b></td>
	<td class="row2"><input class="post"  type="text" size="10" maxlength="6" name="hr_color3" value="{HR_COLOR3}" onChange="showcolor(this.form.hr_color3_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="hr_color3_color" style="background-color:{HR_COLOR3}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="hr_color3_name" value="{HR_COLOR3_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_HR_COLOR4}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="hr_color4" value="{HR_COLOR4}" onChange="showcolor(this.form.hr_color4_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="hr_color4_color" style="background-color:{HR_COLOR4}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="hr_color4_name" value="{HR_COLOR4_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_HR_COLOR5}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="hr_color5" value="{HR_COLOR5}" onChange="showcolor(this.form.hr_color5_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="hr_color5_color" style="background-color:{HR_COLOR5}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="hr_color5_name" value="{HR_COLOR5_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_HR_COLOR6}:</b></td>
	<td class="row2"><input class="post"  type="text" size="10" maxlength="6" name="hr_color6" value="{HR_COLOR6}" onChange="showcolor(this.form.hr_color6_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="hr_color6_color" style="background-color:{HR_COLOR6}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="hr_color6_name" value="{HR_COLOR6_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_HR_COLOR7}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="hr_color7" value="{HR_COLOR7}" onChange="showcolor(this.form.hr_color7_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="hr_color7_color" style="background-color:{HR_COLOR7}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="hr_color7_name" value="{HR_COLOR7_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_HR_COLOR8}:</b></td>
	<td class="row2"><input class="post" type="text" size="10" maxlength="6" name="hr_color8" value="{HR_COLOR8}" onChange="showcolor(this.form.hr_color8_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="hr_color8_color" style="background-color:{HR_COLOR8}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="hr_color8_name" value="{HR_COLOR8_NAME}" />
</tr>
<tr>
	<td class="row1"><b>{L_HR_COLOR9}:</b></td>
	<td class="row2"><input class="post"  type="text" size="10" maxlength="6" name="hr_color9" value="{HR_COLOR9}" onChange="showcolor(this.form.hr_color9_color, this.value)" /> <input type="button" disabled="disabled" value="       " id="hr_color9_color" style="background-color:{HR_COLOR9}"></td> 
	<td class="row2"><input class="post" type="text" size="30" maxlength="100" name="hr_color9_name" value="{HR_COLOR9_NAME}" />
</tr>
<tr>
	<td class="catBottom" colspan="3" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SAVE_SETTINGS}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
