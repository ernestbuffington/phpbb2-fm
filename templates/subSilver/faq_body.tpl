<script language="JavaScript" type="text/javascript"> 
<!--
// by Mechakoopa Revolutiøn
function exall() {
	for (i = 0; i < {TOTAL_NUMBER_OF_FAQ_ITEMS}; i++) {
		var caramel = document.getElementById('faqitem'+i);
		caramel.style.display = '';
	}
}
function coall() {
	for (i = 0; i < {TOTAL_NUMBER_OF_FAQ_ITEMS}; i++) {
		var caramel = document.getElementById('faqitem'+i);
		caramel.style.display = 'none';
	}
}

function ec(i) {
	var caramel = document.getElementById('faqitem'+i);
	if (caramel.style.display == 'none') {
		caramel.style.display = '';
	} else {
		caramel.style.display = 'none';
	}
}
//--> 
</script>
<script language="Javascript" src="templates/js/faq.js"></script>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td align="left" class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	<form name="form1" onSubmit="search(document.form1, frametosearch); return false">
	<td align="right"><input type="text" class="post" value="{L_SEARCH_KEYWORDS}" name="findthis" size="25" title="{L_SEARCH_KEYWORDS_FAQ}" onfocus="this.value=''"> &nbsp;<input type="submit" value="{L_SEARCH}" ACCESSKEY="s" class="liteoption" /></td>
	</form>
</tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center">
<tr>
	<th class="thHead">{L_FAQ_TITLE}</th>
</tr>
	<tr>
		<td class="row1" align="center"><span class="nav"><a href="javascript:;" onClick="exall();" class="nav">{L_EXPAND_ALL}</a> | <a href="javascript:;" onClick="coall();" class="nav">{L_COLLAPSE_ALL}</a></span></td>
	</tr>
</table>
<br />

<!-- BEGIN faq_block -->
<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center">
<tr> 
	<td class="catHead" align="center"><span class="cattitle">{faq_block.BLOCK_TITLE}</span></td>
</tr>
<!-- BEGIN faq_row -->  
<tr> 
	<td class="{faq_block.faq_row.ROW_CLASS}" align="left" valign="top" onClick="ec({faq_block.faq_row.U_FAQ_ID});" style="cursor: pointer;" onMouseOver="this.style.backgroundColor='{HOVER_COLOR}';" onMouseOut="this.style.backgroundColor='';"><span class="postbody"><b>{faq_block.faq_row.FAQ_QUESTION}</b></span><span class="postbody" id="faqitem{faq_block.faq_row.U_FAQ_ID}" style="display: none; "><br />{faq_block.faq_row.FAQ_ANSWER}<br /></span></td>
</tr>
<!-- END faq_row -->
<tr>
	<td class="spaceRow" height="1"><a class="postlink" href="#top"><img src="{ICON_UP}" alt="{L_BACK_TO_TOP}" title="{L_BACK_TO_TOP}" /></a></td>
</tr>
</table>
<br />
<!-- END faq_block -->

<table class="forumline" width="100%" cellspacing="1" cellpadding="4" align="center"> 
<tr> 
	<td class="catHead" colspan="3" align="center"><span class="cattitle"><a name="RankFAQ">{L_RANKFAQ_BLOCK_TITLE}</a></span></td> 
</tr> 
<tr> 
	<td class="row2" align="center"><span class="postbody"><b>{L_RANKFAQ_TITLE}</b></span></td> 
	<td class="row2" align="center"><span class="postbody"><b>{L_RANKFAQ_MIN}</b></span></td> 
	<td class="row2" align="center"><span class="postbody"><b>{L_RANKFAQ_IMAGE}</b></span></td> 
</tr> 
<!-- BEGIN RankFAQ --> 
<tr> 
	<td class="row1" align="center"><span class="postbody">{RankFAQ.RANKFAQ_TITLE}</span></td> 
	<td class="row1" align="center"><span class="postbody">{RankFAQ.RANKFAQ_MIN}</span></td> 
	<td class="row1" align="center">{RankFAQ.RANKFAQ_IMAGE}</td> 
</tr> 
<!-- END RankFAQ --> 
<tr>
	<td class="spacerow" colspan="3" height="1"><a class="postlink" href="#top"><img src="{ICON_UP}" alt="{L_BACK_TO_TOP}" title="{L_BACK_TO_TOP}" /></a></td>
</tr>
</table>
<br />

<table width="100%" cellspacing="2" align="center">
<tr>
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td> 
</tr>
</table>
