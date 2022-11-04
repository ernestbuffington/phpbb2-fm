<html dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>{SITENAME} :: {PAGE_TITLE}</title>
<style type="text/css">
<!--
body {
    font-family:	Verdana,serif;
    font-size:		10pt;
}

td {
	font-family:	Verdana,serif;
	font-size:		10pt;
	line-height:	150%;
}

.code, .quote {
    font-size:	smaller;
	border:		black solid 1px;
}

.forum {
	font-family:	Arial,Helvetica,sans-serif;
    font-weight:	bold;
    font-size:		18pt;
}

.topic {
	font-family:	Arial,Helvetica,sans-serif;
    font-size:		14pt;
	font-weight:	bold;
}

.gensmall {
	font-size: 8pt;
}

.copyright {
	font-size: 7pt;
}

hr {
	color:			#888888;
	height:			3px;
	border-style:	solid;
}

hr.sep	{
	color:			#AAAAAA;
	height:			1px;
	border-style:	dashed;
}
img, .forumline img { border: 0; }
-->
</style>
</head>
<body>
<table width="85%" cellspacing="3" cellpadding="0" align="center">
  <tr>
	<td><span class="Forum"><div align="center">{SITENAME}</div></span><br />
	<span class="Topic">{TOPIC_TITLE}</span></td>
  </tr>
</table>
<hr width="85%" />
<!-- BEGIN postrow -->
<table width="85%" cellspacing="3" cellpadding="0" align="center">
	<tr>
		<td width="10%" nowrap="nowrap">{L_AUTHOR}:&nbsp;</td>
		<td><b>{postrow.POSTER_NAME}</b> [ {postrow.POST_DATE} ]</td>
	</tr>
	<tr>
		<td nowrap="nowrap">{L_POST_SUBJECT}:&nbsp;</td>
		<td><b>{postrow.POST_SUBJECT}</b></td>
	</tr>
	<tr>
		<td colspan="2"><hr class="sep" />{postrow.MESSAGE}</td>
	</tr>
</table>
<hr width="85%" />
<!-- END postrow -->
</body>
</html>