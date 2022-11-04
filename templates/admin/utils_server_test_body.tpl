{UTILS_MENU}{LOG_MENU}{DB_MENU}{LANG_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
var win = null;

function NewWindow(mypage, myname, w, h, pos, infocus)
{
	if (pos == 'random')
	{
		myleft=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;mytop=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;
	}
	if (pos == 'center')
	{
		myleft=(screen.width)?(screen.width-w)/2:100;mytop=(screen.height)?(screen.height-h)/2:100;
	}
	else if ((pos != 'center' && pos != 'random') || pos == null)
	{
		myleft=0;mytop=20
	}
	settings = "width=" + w + ",height=" + h + ",top=" + mytop + ",left=" + myleft + ",scrollbars=no,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no";win=window.open(mypage,myname,settings);
	win.focus();
}
//-->
</script>
<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<!-- BEGIN default -->
<table class="forumline" cellspacing="1" cellpadding="4" width="100%" align="center">
<tr>
	<th class="thHead">&nbsp;{default.L_PAGE_TITLE}&nbsp;</th>
</tr>
<tr>
	<td class="row1" align="center" height="50">{default.U_START}</td>
</tr>
</table>
<!-- END default -->

<!-- BEGIN result1 -->
<table class="forumline" cellspacing="1" cellpadding="4" width="100%" align="center">
<tr>
	<th class="thHead">&nbsp;{default.L_PAGE_TITLE}&nbsp;</th>
</tr>
<tr>
	<td class="row1" align="center" height="50">{default.U_START}</td>
</tr>
</table>
<!-- END result1 -->

<!-- BEGIN result2 -->
<table class="forumline" cellspacing="1" cellpadding="4" width="100%" align="center">
<tr>
	<th class="thHead">&nbsp;{default.L_PAGE_TITLE}&nbsp;</th>
</tr>
<tr>
	<td class="row1" align="center" height="50">{default.U_START}</td>
</tr>
</table>
<!-- END result2 -->

<!-- BEGIN results -->
<table class="forumline" cellspacing="1" cellpadding="4" width="100%" align="center">
<tr>
	<th class="thHead" colspan="2">&nbsp;{results.L_PAGE_TITLE}&nbsp;</th>
</tr>
<tr>
	<td class="row1" width="38%" rowspan="2"><b>{results.L_PAGE_TITLE} #1:</b></td>
	<td class="row2">{results.1}</td>
</tr>
<tr>
	<td class="row2">{results.2}</td>
</tr>
<tr>
	<td class="row1"><b>{results.L_PAGE_TITLE} #2:</b></td>
	<td class="row2">{results.3}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2">&nbsp;</td>
</tr>
</table>
<!-- END results -->
<br />
<div align="center" class="copyright">Admin Server Test 1.5.0 &copy; 2002, {COPYRIGHT_YEAR} Dimitri Seitz</div>