<!-- BEGIN ulist_open --><ul><!-- END ulist_open -->
<!-- BEGIN ulist_close --></ul><!-- END ulist_close -->

<!-- BEGIN olist_open --><ol type="{LIST_TYPE}"><!-- END olist_open -->
<!-- BEGIN olist_close --></ol><!-- END olist_close -->

<!-- BEGIN listitem --><li><!-- END listitem -->

<!-- BEGIN quote_open --><div class="quotetitle"><b>{L_QUOTE}:</b></div><div class="quotecontent"><!-- END quote_open -->
<!-- BEGIN quote_close --></div><!-- END quote_close -->

<!-- BEGIN quote_username_open --><div class="quotetitle"><b>{USERNAME} {L_WROTE}:</b></div><div class="quotecontent"><!-- END quote_username_open -->

<!-- BEGIN code_open --><div class="codetitle"><b>{L_CODE}:</b></div><div class="codecontent"><!-- END code_open -->

<!-- BEGIN code_close --></div><!-- END code_close -->

<!-- BEGIN edit_open --><div class="quotetitle"><b>{L_EDIT}:</b></div><div class="quotecontent"><!-- END edit_open -->
<!-- BEGIN edit_close --></div><!-- END edit_close -->

<!-- BEGIN b_open --><span style="font-weight: bold"><!-- END b_open -->
<!-- BEGIN b_close --></span><!-- END b_close -->

<!-- BEGIN u_open --><span style="text-decoration: underline"><!-- END u_open -->
<!-- BEGIN u_close --></span><!-- END u_close -->

<!-- BEGIN i_open --><span style="font-style: italic"><!-- END i_open -->
<!-- BEGIN i_close --></span><!-- END i_close -->

<!-- BEGIN color_open --><span style="color: {COLOR}"><!-- END color_open -->
<!-- BEGIN color_close --></span><!-- END color_close -->

<!-- BEGIN size_open --><span style="font-size: {SIZE}px; line-height: normal"><!-- END size_open -->
<!-- BEGIN size_close --></span><!-- END size_close -->

<!-- BEGIN img --><img src='{URL}'{REDUCED_IMG}{MISSING_IMG} /><!-- END img --> 
<!-- BEGIN left --><img src="{URL}" align="left"{REDUCED_IMG}{MISSING_IMG} /><!-- END left -->
<!-- BEGIN right --><img src="{URL}" align="right"{REDUCED_IMG}{MISSING_IMG} /><!-- END right -->

<!-- BEGIN url --><a href="{URL}" target="_blank" class="postlink">{DESCRIPTION}</a><!-- END url -->

<!-- BEGIN email --><a href="mailto:{EMAIL}">{EMAIL}</a><!-- END email -->

<!-- BEGIN align_open --><div style="text-align:{ALIGN}"><!-- END size_open -->
<!-- BEGIN align_close --></div><!-- END align_close -->

<!-- BEGIN blur_open --><span style="height: 20; filter:blur(add=1,direction=270,strength=10)"><!-- END blur_open -->
<!-- BEGIN blur_close --></span><!-- END blur_close -->  

<!-- BEGIN fade_open --><span style="height: 1; Filter: Alpha(Opacity=100, FinishOpacity=0, Style=1, StartX=0, FinishX=100%)"><!-- END fade_open -->
<!-- BEGIN fade_close --></span><!-- END fade_close -->

<!-- BEGIN flash --><!-- URL's used in the movie--> 
<!-- text used in the movie--> 
<!-- --> 
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width={WIDTH} height={HEIGHT}> 
<param name="AutoStart" value="0" />
<param name="movie" value="{URL}" /> 
<param name="loop" value="{LOOP}" /> 
<param name="quality" value="high" /> 
<param name="scale" value="noborder" /> 
<param name"wmode" value="transparent" /> 
<param name="bgcolor" value="#000000" /> 
<embed src="{URL}" loop={LOOP} quality=high scale=noborder wmode=transparent bgcolor=#000000 WIDTH={WIDTH} HEIGHT={HEIGHT} TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed> 
</object><!-- END flash --> 

<!-- BEGIN cf --><!-- URL's used in the movie--> 
<!-- text used in the movie--> 
<!-- --> 
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"> 
<param name="AutoStart" value="0" />
<param name="movie" value="{URL}" />
<param name="quality" value="high" />
<param name="scale" value="noborder" /> 
<param name="wmode" value="transparent" /> 
<param name="bgcolor" value="#000000" /> 
<embed src="{URL}" quality=high scale=noborder wmode=transparent bgcolor=#000000 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed> 
</object><!-- END cf --> 

<!-- BEGIN flipv_open --><span style="filter: flipv; height:1"><!-- END flipv_open --> 
<!-- BEGIN flipv_close --></span>&nbsp;<!-- END flipv_close -->  

<!-- BEGIN fliph_open --><span style="filter: fliph; height:1"><!-- END fliph_open --> 
<!-- BEGIN fliph_close --></span>&nbsp;<!-- END fliph_close -->

<!-- BEGIN highlight_open --><span style="background-color: {HIGHLIGHTCOLOR}"><!-- END highlight_open -->
<!-- BEGIN highlight_close --></span><!-- END highlight_close -->

<!-- BEGIN video --><div align="center"><embed src="{URL}" autostart="0" width="{WIDTH}" height="{HEIGHT}"></embed></div><!-- END video -->

<!-- BEGIN stream --><object id="wmp" width=300 height=70 classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,0,0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject"> 
<param name="FileName" value="{URL}" /> 
<param name="ShowControls" value="1" /> 
<param name="ShowDisplay" value="0" /> 
<param name="ShowStatusBar" value="1" /> 
<param name="AutoSize" value="1" /> 
<param name="AutoStart" value="0" />
<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/windows95/downloads/contents/wurecommended/s_wufeatured/mediaplayer/default.asp" src="{URL}" name=MediaPlayer2 showcontrols=1 showdisplay=0 showstatusbar=1 autostart=0 autosize=1 visible=1 animationatstart=0 transparentatstart=1 loop=0 height=70 width=300></embed> 
</object><!-- END stream -->

<!-- BEGIN strike_open --><span><strike><!-- END strike_open --> 
<!-- BEGIN strike_close --></strike></span><!-- END strike_close --> 

<!-- BEGIN spoiler_open -->
<table width="90%" cellspacing="1" cellpadding="4" align="center">
  <tr>
	<td><span class="genmed"><b>{L_SPOILER}:</b></span></td>
  </tr>
  <tr>
	<td><span style="font-size: 10px; color: #000000; background-color: #000000">
<!-- END spoiler_open -->
<!-- BEGIN spoiler_close -->
	</td>
  </tr>
</table>
</span><!-- END spoiler_close -->

<!-- BEGIN table_open --><table cellpadding="2" cellspacing="0" class="postbody" border="1"><!-- END table_open -->
<!-- BEGIN table_close --></td></tr></table><!-- END table_close -->
<!-- BEGIN table_color --><table align="top" cellpadding="2" cellspacing="0" class="postbody" bgcolor="{TABCOLOR}" border="1"><!-- END table_color -->
<!-- BEGIN table_size --><table align="top" cellpadding="2" cellspacing="0" style="font-size: {TABSIZE}px" border="1"><!-- END table_size -->
<!-- BEGIN table_cs --><table align="top" cellpadding="2" cellspacing="0" bgcolor="{TABCSCOLOR}" style="font-size: {TABCSSIZE}px" border="1"><!-- END table_cs -->
<!-- BEGIN table_mainrow --></td></tr><tr><td style="font-weight: bold; text-align: center;"><!-- END table_mainrow -->
<!-- BEGIN table_mainrow_color --></td></tr><tr bgcolor="{TABMRCOLOR}"><td style="font-weight: bold; text-align: center;"><!-- END table_mainrow_color -->
<!-- BEGIN table_mainrow_size --></td></tr><tr style="font-size: {TABMRSIZE}px;"><td style="font-weight: bold; text-align: center;"><!-- END table_mainrow_size -->
<!-- BEGIN table_mainrow_cs --></td></tr><tr bgcolor="{TABMRCSCOLOR}" style="font-size: {TABMRCSSIZE}px"><td style="font-weight: bold; text-align: center;"><!-- END table_mainrow_cs -->
<!-- BEGIN table_maincol --></td><td style="font-weight: bold; text-align: center;"><!-- END table_maincol -->
<!-- BEGIN table_maincol_color --></td><td bgcolor="{TABMCCOLOR}" style="font-weight: bold; text-align: center;"><!-- END table_maincol_color -->
<!-- BEGIN table_maincol_size --></td><td style="font-size: {TABMCSIZE}px; font-weight: bold; text-align: center;"><!-- END table_maincol_size -->
<!-- BEGIN table_maincol_cs --></td><td bgcolor="{TABMCCSCOLOR}" style="font-size: {TABMCCSSIZE}px; font-weight: bold; text-align: center;"><!-- END table_maincol_cs -->
<!-- BEGIN table_row --></td></tr><tr><td><!-- END table_row -->
<!-- BEGIN table_row_color --></td></tr><tr bgcolor="{TABRCOLOR}"><td><!-- END table_row_color -->
<!-- BEGIN table_row_size --></td></tr><tr style="font-size: {TABRSIZE}px"><td><!-- END table_row_size -->
<!-- BEGIN table_row_cs --></td></tr><tr bgcolor="{TABRCSCOLOR}" style="font-size: {TABRCSSIZE}px"><td><!-- END table_row_cs -->
<!-- BEGIN table_col --></td><td><!-- END table_col -->
<!-- BEGIN table_col_color --></td><td bgcolor="{TABCCOLOR}"><!-- END table_col_color -->
<!-- BEGIN table_col_size --></td><td style="font-size: {TABCSIZE}px"><!-- END table_col_size -->
<!-- BEGIN table_col_cs --></td><td bgcolor="{TABCCSCOLOR}" style="font-size: {TABCCSSIZE}px"><!-- END table_col_cs -->

<!-- BEGIN scroll_open --><span><marquee><!-- END scroll_open -->
<!-- BEGIN scroll_close --></marquee></span><!-- END scroll_close -->

<!-- BEGIN updown_open --><span><marquee behavior="scroll" direction="up" scrollamount="1" height="60"><!-- END updown_open -->
<!-- BEGIN updown_close --></marquee></span><!-- END updown_close -->

<!-- BEGIN hr --><hr noshade color="#000000" size="1"><!-- END hr -->

<!-- BEGIN font_open --><span style="font-family: {FONT}"><!-- END font_open -->
<!-- BEGIN font_close --></span><!-- END font_close -->

<!-- BEGIN glow_open --><span style="filter: glow(color={GLOWCOLOR}); height:20"><!-- END glow_open -->
<!-- BEGIN glow_close --></span><!-- END glow_close -->

<!-- BEGIN shadow_open --><span style="filter: shadow(color={SHADOWCOLOR}); height:20"><!-- END shadow_open -->
<!-- BEGIN shadow_close --></span><!-- END shadow_close -->

<!-- BEGIN schild --><img src="{URL}" alt="" title="" /><!-- END schild -->

<!-- BEGIN offtopic_open --><span class="genmed" style="color: #006699"><i>Offtopic: <!-- END offtopic_open -->
<!-- BEGIN offtopic_close --></i></span><!-- END offtopic_close -->

<!-- BEGIN wave_open --><span style="height: 20; filter:wave(add=1,direction=270,strength=12)"><!-- END wave_open --> 
<!-- BEGIN wave_close --></span><!-- END wave_close -->  

<!-- BEGIN tab --><pre style="display:inline;">    </pre><!-- END tab -->
	
<!-- BEGIN super_open --><span style="vertical-align: super; font-size:  smaller;"><!-- END super_open -->
<!-- BEGIN super_close --></span><!-- END super_close -->

<!-- BEGIN footnote_open --><br /><br />_____<span style="font-size: 85%;"><!-- END sub_open -->
<!-- BEGIN footnote_close --></span><!-- END sub_close -->

<!-- BEGIN google --><a href="http://www.google.com/search?q={QUERY}" target="_blank">{STRING}</a><!-- END google -->
<!-- BEGIN yahoo --><a href="http://search.yahoo.com/search?p={QUERY}" target="_blank">{STRING}</a><!-- END yahoo -->
<!-- BEGIN search --><a href="search.php?search_keywords={KEYWORD}" />{KEYWORD}</a><!-- END search -->
<!-- BEGIN ebay --><a href="http://search.ebay.com/search/search.dll?from=R40&satitle={QUERY}" target="_blank">{STRING}</a><!-- END ebay -->

<!-- BEGIN border_open --><span style="border-width: 1; border-style: solid; border-color: #910404; padding: 2px;"><!-- END border_open -->
<!-- BEGIN border_close --></span><!-- END border_close -->

<!-- BEGIN youtube -->
<object width="425" height="350">
<param name="movie" value="http://www.youtube.com/v/{YOUTUBEID}" /></param>
<embed src="http://www.youtube.com/v/{YOUTUBEID}" type="application/x-shockwave-flash" width="425" height="350"></embed>
</object>
<br /><a href="http://youtube.com/watch?v={YOUTUBEID}" target="_blank" class="postlink">{YOUTUBELINK}</a><br />
<!-- END youtube -->

<!-- BEGIN googlevid -->
<object width="425" height="350">
<param name="movie" value="http://video.google.com/googleplayer.swf?docId={GVIDEOID}" /></param>
<embed src="http://video.google.com/googleplayer.swf?docId={GVIDEOID}" type="application/x-shockwave-flash" width="425" height="350"></embed>
</object>
<br /><a href="http://video.google.com/videoplay?docid={GVIDEOID}" target="_blank" class="postlink">{GVIDEOLINK}</a><br />
<!-- END googlevid -->

<!-- BEGIN pre_open --><pre><!-- END pre_open -->
<!-- BEGIN pre_close --></pre><!-- END pre_close -->

<!-- BEGIN mod_username_open --></span> 
<table width="90%" cellspacing="1" cellpadding="4" align="center"> 
   <tr> 
      <td class="modtable" rowspan="2" align="middle" valign="center" width="1%"><span class="exclamation" title="{MOD_WARN}">&nbsp;!&nbsp;</span></td>     
      <td><b class="genmed">{USERNAME} {L_WROTE}:</b></td>
   </tr>
   <tr>
      <td class="mod"><!-- END mod_open --> 

<!-- BEGIN mod_open --></span> 
<table width="90%" cellspacing="1" cellpadding="4" align="center"> 
   <tr> 
      <td class="modtable" rowspan="2" align="middle" valign="center" width="1%"><span class="exclamation"  title="{MOD_WARN}">&nbsp;!&nbsp;</span></td>     
      <td class="mod"><!-- END mod_open --> 

<!-- BEGIN mod_close --></td> 
   </tr> 
</table> 
<span class="postbody"><!-- END mod_close -->

<!-- BEGIN mp3_open -->
<object type="application/x-shockwave-flash" data="mods/jcfplayer.swf" width="310" height="30" id="audioplayer1">
<param name="movie" value="mods/jcfplayer.swf" />
<param name="FlashVars" value="soundFile=<!-- END mp3_open -->
<!-- BEGIN mp3_close -->" />
<param name="quality" value="high" />
<param name="menu" value="false" />
<param name="wmode" value="transparent" />
</object>
<!-- END mp3_open -->	

<!-- BEGIN login_request --><div class="quotetitle"><b>{L_TITLE}</b></div><div class="quotecontent">{L_WARNING}<br />{GET_REGISTERED}{ENTER_FORUM}</div><!-- END login_request -->

<!-- BEGIN post_count_request --><div class="quotetitle"><b>{L_TITLE}</b></div><div class="quotecontent">{L_WARNING}</div><!-- END post_count_request -->
