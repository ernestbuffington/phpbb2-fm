<script language="JavaScript" type="text/javascript">
<!-- 
	function mpFoto(img)
	{
		foto1= new Image(); 
		foto1.src=(img); 
		mpControl(img); 
	}

	function mpControl(img)
	{ 
		if((foto1.width!=0)&&(foto1.height!=0))
		{ 
			viewFoto(img);
		}
		else
		{ 
			mpFunc="mpControl('"+img+"')"; 
			intervallo=setTimeout(mpFunc,20); 
		} 
	} 

	function viewFoto(img)
	{ 
		largh=foto1.width+20; 
		altez=foto1.height+20; 
		string="width="+largh+",height="+altez; 
		finestra=window.open(img,"",string); 
	}
	
	function MM_jumpMenu(targ,selObj,restore)
	{ 
		//v3.0
		eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
		if (restore)
		{
			selObj.selectedIndex=0;
		}
	}

    function delete_file(theURL) 
	{
       if (confirm('Are you sure you want to delete this file??')) 
	   {
          window.location.href=theURL;
       }
       else
	   {
          alert ('No Action has been taken.');
       } 
    }
		
//--> 
</script>

<table width="100%" height="99%" cellpadding="2" cellspacing="0">
  <tr>
	<td align="center" width="100%" height="100%" valign="top">

<table align="center" cellpadding="2" cellspacing="2">
  <tr>	
	<td align="center">
	<!-- IF IS_AUTH_SEARCH -->		
	&nbsp;<a href="{U_PASEARCH}"><img src="{SEARCH_IMG}" alt="{L_SEARCH}" title="{L_SEARCH}" /></a>&nbsp;
	<!-- ENDIF -->
	<!-- IF IS_AUTH_STATS -->	
	&nbsp;<a href="{U_PASTATS}"><img src="{STATS_IMG}" alt="{L_STATS}" title="{L_STATS}" /></a>&nbsp;
	<!-- ENDIF -->
	<!-- IF IS_AUTH_TOPLIST -->	
	&nbsp;<a href="{U_TOPLIST}"><img src="{TOPLIST_IMG}" alt="{L_TOPLIST}" title="{L_TOPLIST}" /></a>&nbsp;
	<!-- ENDIF -->
	<!-- IF IS_AUTH_UPLOAD -->
	&nbsp;<a href="{U_UPLOAD}"><img src="{UPLOAD_IMG}" alt="{L_UPLOAD}" title="{L_UPLOAD}" /></a>&nbsp;
	<!-- ENDIF -->	
	<!-- IF IS_AUTH_VIEWALL -->	
	&nbsp;<a href="{U_VIEW_ALL}"><img src="{VIEW_ALL_IMG}" alt="{L_VIEW_ALL}" title="{L_VIEW_ALL}" /></a>&nbsp;	
	<!-- ENDIF -->	
	<!-- IF IS_AUTH_MCP -->	
	&nbsp;<a href="{U_MCP}" class="nav">{MCP_LINK}</a>&nbsp;	
	<!-- ENDIF -->	
	</td>
</tr>
</table>
