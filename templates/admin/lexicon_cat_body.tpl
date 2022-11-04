{LEXICON_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" type="text/javascript">
<!--
function checkForm() { 

   formErrors = false;    
   if (document.post.categorie.value.length < 2) { 
      formErrors = "{L_EMPTY_CATEGORIE}\r"; 
   } 
   if (formErrors) { 
      alert(formErrors); 
      return false; 
   } else { 
      return true; 
   } 
}
//-->
</script>

<h1>{CATEGORIE_ADMINISTRATION}</h1>

<p>{CATEGORIE_ADMIN_EXPLAIN}</p>

<!-- BEGIN switch_lexicon_cat_overview -->
<table width="100%" cellpadding="2" cellspacing="2" align="center"><form method="post" action="{S_ACTION}" onsubmit="return checkForm(this)" name="post">
<tr> 
	<td align="right"><span class="genmed">{L_ADD_CATEGORIE}: <input type="text" name="categorie" size="35" maxlength="80" class="post" /> <input type="submit" name="B1" class="liteoption" value="{L_CREATE}" /></span></td>
</tr> 
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr> 
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_CATEGORIE}&nbsp;</th> 
	<th class="thTop" nowrap="nowrap">&nbsp;{L_CATEGORIE_LANG}&nbsp;</th> 
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th> 
</tr>
<!-- BEGIN cat_row -->
<tr> 
	<td class="{switch_lexicon_cat_overview.cat_row.ROW_CLASS}" align="left">{switch_lexicon_cat_overview.cat_row.CATEGORIE}</td> 
	<td class="{switch_lexicon_cat_overview.cat_row.ROW_CLASS}" align="center">{switch_lexicon_cat_overview.cat_row.CATEGORIE_LANG}</td> 
	<td class="{switch_lexicon_cat_overview.cat_row.ROW_CLASS}" align="right" nowrap="nowrap">{switch_lexicon_cat_overview.cat_row.S_EDIT} {switch_lexicon_cat_overview.cat_row.S_DELETE}</td> 
</tr>
<!-- END cat_row -->
</table>
<!-- END switch_lexicon_cat_overview -->

<!-- BEGIN switch_cat_edit -->
<style type="text/css">
<!--
button {
	background-color : #FAFAFA;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	text-decoration: none;
	height: 17px;
	border: thin solid;
	border-color: #CCCCCC #333333 #333333 #CCCCCC;
}
button.mainoption {font-weight : bold;}
button.liteoption {font-weight : normal;}
-->
</style>

<table cellpadding="4" cellspacing="1" align="center" class="forumline"><form method="post" action="{S_ACTION}" onsubmit="return checkForm(this)" name="post">
<tr> 
	<th colspan="2" class="thHead">&nbsp;{CATEGORIE_ADMINISTRATION}&nbsp;</th> 
</tr>
<tr> 
	<td class="row1" width="38%"><b>{L_CATEGORIE}:</b></td> 
	<td class="row2"><input type="text" name="categorie" size="35" maxlength="80" class="post" value="{CATEGORIE}" /></td> 
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" class="mainoption" value="{L_SUBMIT}" />&nbsp;&nbsp;<button type="button" class="liteoption" onclick="window.location.href='{S_CANCEL}'">{L_CANCEL}</button></td>
</tr> 
</form></table>
<!-- END switch_cat_edit -->
<br />
<div align="center" class="copyright">Lexicon v2 &copy; 2005, {COPYRIGHT_YEAR} <a href="http://www.amigalink.de" target="_blank" class="copyright">AmigaLink</a></div>


