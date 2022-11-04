/** 
*
* @package includes
* @version $Id: AJAXed_func.js,v 2.0.8 2006/03/08 16:46:19 matt Exp $
* @copyright (c) 2006 *=Matt=*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

var aA, aB, aC, aD 						  = null;
var cA, cB, cC 							  = null;
var dA 								  = null;
var eA 								  = null;
var iA, iB 							  = null;
var mA, mB 							  = null;
var pA 								  = null;
var pB 							   = new Array();
var tA, tB, tC, tD, tE, tF 					  = null;
var uA 								  = null;
var xA, xB, xC, xD, xE, xF, xG, xH, xI, xJ, xM, xN, xO 	  = null;

ae(document, "mousemove", xy);
ae(document, "keydown", ul);
ae(document, "click", ca);

function aa(a, b)
{
	if(document.getElementById)
	{
		document.getElementById(a).innerHTML = b;
	}
	else if(document.layers)
	{
		document.layers[a].document.open();
		document.layers[a].document.write(b);
		document.layers[a].document.close();
	}
	else
	{
		return false;
	}
	return false;
}

function ab(a, b, c)
{
	if (xM < ad(a).offsetWidth)
	{
		if(cc() == "ie")
		{
			ad(a).style.left = page['scrollLeft'] + page['clientX'] - ad(a).offsetWidth;
		}
		else
		{
			ad(a).style.left = window.pageXOffset + page['clientX'] - ad(a).offsetWidth;
		}
	}
	else
	{
		if(cc() == "ie")
		{
			ad(a).style.left = page['scrollLeft'] + page['clientX'] - b;
		}
		else
		{
			ad(a).style.left = window.pageXOffset + page['clientX'] - b;
		}
	}

	if (xN < ad(a).offsetHeight)
	{
		if(cc() == "ie")
		{
			ad(a).style.top = page['scrollTop'] + page['clientY'] - ad(a).offsetHeight;
		}
		else
		{
			ad(a).style.top = window.pageYOffset + page['clientY'] - ad(a).offsetHeight;
		}
	}
	else
	{
		if(cc() == "ie")
		{
			ad(a).style.top = page['scrollTop'] + page['clientY'] - c;
		}
		else
		{
			ad(a).style.top = window.pageYOffset + page['clientY'] - c;
		}
	}
}

function ac(a)
{
	var b = "";
	if(document.getElementsByName)
	{
		b = document.getElementsByName(a)[0];
	}
	else if(document.all)
	{
		b = document.all[a];
	}
	else if(document.layers)
	{
		b = document.layers[a];
	}
	else
	{
		return false;
	}
	return b;
	delete(b);
}

function ad(a)
{
	var b = "";
	if(document.getElementById)
	{
		b = document.getElementById(a);
	}
	else if(document.all)
	{
		b = document.all[a];
	}
	else if(document.layers)
	{
		b = document.layers[a];
	}
	else
	{
		return false;
	}
	return b;
	delete(b);
}

function ae(a, b, c, d)
{
 	if(a.addEventListener)
	{
		a.addEventListener(b, c, d);
		return true;
	}
	else if(a.attachEvent)
	{
		var e = a.attachEvent("on" + b, c);
		return e;
	}
	else
	{
		alert("Handler could not be added");
	}
}

function ag(a)
{
	if(typeof(a) == "string")
	{
		a = a.replace(/&/g,"&amp");
		a = a.replace(/=/g,"&#61;");
		a = a.replace(/\+/g,"&#43;");
		a = a.replace(/"/g, '&quot;');
	}
	return a;
}

function ca(a)
{
	if (ad(aD))
	{
		if(cA)
		{
			cA = false;
		}
		else
		{
			if(cc() == "ie")
			{
				b = window.event.srcElement;
			}
			else
			{
				b = a.target;
			}
			if( b != ad(aD) )
			{
				aa(aD,' ');

				if(config['AJAXed_User_Power'])
				{
					config['AJAXed_User_Power'] = false;
					config['AJAXed_User_Typed'] = null;
				}

				if(!aB)
				{
					ad(aD).style.top = '0px';
					ad(aD).style.left = '0px';
					pB[config['AJAXed_Post_ID'] + '_menu_1'] = false;
					pB[config['AJAXed_Post_ID'] + '_menu_2'] = false;
				}
			}
		}
	}
}

function cc()
{
	var cC = navigator.userAgent.toLowerCase();
	if(cC.indexOf("msie") != -1)
	{
		if(cC.indexOf("opera") != -1)
		{
			return "opera";
		}
		else
		{
			return "ie";
		}
	}
	else if(cC.indexOf("netscape") != -1)
	{
		return "netscape";
	}
	else if (cC.indexOf("firefox") != -1)
	{
		return "firefox";
	}
	else if (cC.indexOf("mozilla/5.0") != -1)
	{
		return "mozilla";
	}
	else if (cC.indexOf("\/") != -1)
	{
		if (cC.substr(0,cC.indexOf('\/')) != 'mozilla')
		{
			return navigator.userAgent.substr(0,cC.indexOf('\/'));
		}
		else
		{
			return "netscape";
		}
	}
	else if (cC.indexOf(' ') != -1)
	{
		return navigator.userAgent.substr(0,cC.indexOf(' '));
	}
	else
	{
		return navigator.userAgent;
	}
}

function dc(a, b)
{
	if(dA)
	{
		if(b)
		{
			aa('topic',a);
		}
		else
		{
			ad('topic_' + aB).style.display = 'none';
			mf();
		}
		dA = false;
	}
	if(config['AJAXed_Is_Loading'])
	{
		ih();
	}
}

function dt(a, b)
{
	ib();
	if(confirm(lang['AJAXed_Delete_Confirm']))
	{
		if(config['AJAXed_Is_Loading'])
		{
			aB = a;
			dA = true;
			x_delete_topic(a, b, dc, b, 2);
		}
	}
	else
	{
		if(config['AJAXed_Is_Loading'])
		{
			ih();
		}
	}
}

function ea(a, b)
{
	ib();
	if(!b)
	{
		var c = ad(a + '_message').value;
		if(ad(a + '_addUpdate').checked)
		{
			config['addUpdate'] = "true";
		}
		else
		{
			config['addUpdate'] = "false";
		}

		x_edit_post_msg(a, c, config['addUpdate'], ee, a, 2);
		pB[a + '_editor'] = null;
		delete(c);
	}
	else
	{
		aa('p_' + a + '_message', pB[a + '_text']);
		if(config['AJAXed_Is_Loading'])
		{
			ih();
		}
	}
}

function eb(a)
{
	if(pB[a + '_editor'])
	{
		aa('p_' + a + '_message', pB[a + '_editor']);
		ta('p_' + a + '_message');
	}
	else
	{
		ib();
		pB[a + '_check'] = true;
		pB[a + '_text'] = ad('p_' + a + '_message').innerHTML;
		x_get_editor(a, ee, a, 2);
	}
}

function ec(a, b)
{
	if(parseInt(ad(b + '_message').style.height))
	{
		var d = parseInt(ad(b + '_message').style.height);
	}
	else
	{
		var d = '200';
	}

	var e = parseInt(d) + parseInt(a);
	if ( e > 0 )
	{
		ad(b + '_message').style.height = e + 'px';
	}
	delete(d);
	delete(e);
}

function ee(a, b)
{
	if(pB[b + '_check'])
	{
		pB[b + '_editor'] = a;
	}
	else
	{
		delete(pB[b + '_editor'])
	}

	ta('p_' + b + '_message');
	aa('p_' + b + '_message',a);

	if(config['addUpdate'] == "true")
	{
		ef(b);
	}
	if(config['AJAXed_Is_Loading'])
	{
		ih();
	}
	pB[b + '_check'] = false;
}

function ef(a)
{
	eA = true;
	x_edit_post_msg_update(a, eg, a, 2);
}

function eg(a, b)
{
	if(eA)
	{
		aa('p_' + b + '_edited', a);
		eA = false;
	}
}

function ia()
{
	if(config['AJAXed_Is_Loading'])
	{
		alert(lang['AJAXed_Timed_Out']);
		ih();
		clearTimeout(iB);
	}
}

function ib()
{
	if(!config['AJAXed_Is_Loading'])
	{
		iA = setInterval("ip()", "50"); // Half second interval to change the loading bar move.
		config['AJAXed_Is_Loading'] = true;
		if(ad('loading').innerHTML != lang['AJAXed_Loading'])
		{
			aa('loading', lang['AJAXed_Loading']);
		}
		else
		{
			ad('loading').style.display = 'block';
		}
		iB = setTimeout("ia()", "15000"); // A 15 second time out for ajax
	}
}

function ih()
{
	if(config['AJAXed_Is_Loading'])
	{
		ad('loading').style.display = 'none';
		config['AJAXed_Is_Loading'] = false;
	}
	clearInterval(iA);
	clearTimeout(iB);
}

function ip()
{
	var id = (cc() == 'ie') ? document.body.scrollLeft : pageXOffset;
	var ig = (cc() == 'ie') ? document.body.scrollTop : pageYOffset;
	var ih = (cc() == 'ie') ? document.body.clientWidth : window.innerWidth-20;
	if (cc() == 'ie' || document.getElementById)
	{
		ad('loading').style.left = parseInt(id) + parseInt(ih) - '60';
		ad('loading').style.top = parseInt(ig);
	}
	else if (document.layers)
	{
		ad('loading').left = id + ih - '60';
		ad('loading').top = ig + window_height;
	}
}

function ka(a, b)
{
	if(I_ADD_POLLS)
	{
		if(a.tBodies[0].rows[b])
		{
			var count = b;
			for(var i=b; i < a.tBodies[0].rows.length; i++)
			{
				var tempVal = a.tBodies[0].rows[i].myRow.one.value.split(' ');
				count++;
			}
		}
	}
}

function kb(a)
{
	if(I_ADD_POLLS)
	{
		for(var i=0; i < a.length; i++)
		{
			var rIndex = a[i].sectionRowIndex;
			a[i].parentNode.deleteRow(rIndex);
			config['AJAXed_Poll_count']--;
		}
	}
}

function kc(a)
{
	if(I_ADD_POLLS)
	{
		var b = a.parentNode.parentNode;
		var c = b.parentNode.parentNode;
		var d = b.sectionRowIndex;
		var e = new Array(b);
		kb(e);
		delete(b);
		delete(c);
		delete(d);
		delete(e);
	}
}

function kd(a)
{
	this.one = a;
}

function ke(a)
{
	if(I_ADD_POLLS)
	{
		if( ( parseInt(config['AJAXed_Poll_count']) + parseInt( config['AJAXed_Poll_counted'] ) ) >= config['AJAXed_Poll_Max'] )
		{
			alert(lang['AJAXed_Poll_Many']);
			return false;
		}
		else
		{
			var c = parseInt(ad('posting_body').tBodies['new_polls'].rows.length) + 1;
			if( ac('poll_option_text[' + c + ']') )
			{
				var c = parseInt(c) + parseInt(ad('posting_body').tBodies['all_polls'].rows.length) + 1;
			}
			var d = ad('posting_body').tBodies['new_polls'].rows.length;
			if(!a)
			{
				a = d;
			}
			var b = ad('posting_body').tBodies['new_polls'].insertRow(a);
			var e = b.insertCell(0);
			e.className = 'row1';
			e.innerHTML = '<span class="gen"><b>' + lang['AJAXed_Poll_Option'] + '</b></span>';
			var f = b.insertCell(1);
			f.className = 'row2';
			f.innerHTML = poll(c);
			ac('add_poll_option_text').value = '';
			ac('add_poll_option_text').focus();
			b.myRow = new kd(f);
			config['AJAXed_Poll_count']++;
			delete(b); delete(c); delete(d); delete(e); delete(f);
		}
	}
}

function kf(a)
{
	if(document.post.add_poll_option_text.value)
	{
		ke(a);
	}
}

function la(a)
{
	x_lock_topic(a, lb);
}

function lb(a)
{
	aa('ttop', a);
	aa('bottom', a);
}

function lc(a, b)
{
	if(b)
	{
		la(aB);
		ad('topic_lock').src = a;
	}
	else
	{
		ad('t_' + aB).src = a;
	}
	if(config['AJAXed_Is_Loading'])
	{
		ih();
	}
}

function lt(a, b)
{
	ib();
	aB = a;
	x_lock_unlock_topic(a, b, lc, b, 2);
}

function mb(a, b)
{
	if(I_MOVE)
	{
		ib();
		aD = 'misc';
		d = aD;
		e = false;
		mA = true;
		if(b)
		{
			mB = true;
			var f = '1';
		}
		else
		{
			mB = false;
			var f = '0';
		}
		x_move_build(a, f, ud);
	}
}

function mc()
{
	if(I_MOVE)
	{
		d = 'misc'; e = false;
		ud(' ');
	}
}

function md(a)
{
	if(I_MOVE)
	{
		x_move(a, me);
		delete(e);
	}
}

function me(a)
{
	if(I_MOVE)
	{
		aa('ttop_nav', a);
		aa('bottom_nav', a);
		if(config['AJAXed_Is_Loading'])
		{
			ih();
		}
	}
}

function mf()
{
	var Matt = 0;
	for (var GrU = 0; GrU < ad('view_forum').rows.length; GrU++)
	{
		if (ad('view_forum').rows[GrU].style.display != "none")
		{
			Matt++;
		}
	}
	if(Matt == "2")
	{
		ad('No_topics').style.display = '';
	}
	delete(Matt);
}

function mt(a, b)
{
	if(I_MOVE)
	{
		ib();
		x_move_topic(a, ad(a + '_new_forum').value, ad(a + '_leave_shadow').checked, xa);
		if(!b)
		{
			if(!ad(a + '_leave_shadow').checked)
			{
				ad('topic_' + a).style.display = 'none';
				mf();
			}
			else
			{
				aa('topic_' + a + '_type', lang['AJAXed_Topic_Move']);
				aa('topic_' + a + '_mod', ' ');
			}
			if(config['AJAXed_Is_Loading'])
			{
				ih();
			}
		}
		else
		{
			md(a);
		}
	}
}

function oc(a, b)
{
	if(!ad(a))
	{
		return true;
	}
	else if(ad(a).style)
	{
		ad(a).style.display = ( ad(a).style.display != "none" ) ? "none" : "inline";
	}
	else
	{
		ad(a).visibility = "show";
	}

	if(!ad(b))
	{
		return true;
	}
	else if(ad(b).style)
	{
		ad(b).style.display = ( ad(b).style.display != "none" ) ? "none" : "inline";
	}
	else
	{
		ad(b).visibility = "show";
	}
}

function pa(a)
{
	if(!pA)
	{
		return pi(a, 1);
	}
	else
	{
		return pi(a, 2);
	}
}

function pc(a)
{
	ta('preview_box');
	aa('preview_box', a);
	if(config['AJAXed_Is_Loading'])
	{
		ih();
	}
}

function pd(a, b)
{
	ib();
	if(confirm(lang['AJAXed_Delete_Post']))
	{
		if(config['AJAXed_Is_Loading'])
		{
			x_post_delete(a, b, pw, b, 2);
		}
	}
	else
	{
		if(config['AJAXed_Is_Loading'])
		{
			ih();
		}
	}
}

function pi(a, b)
{
	pB[a + '_ip'] = '1';
	pB[a + '_menu_2'] = true;
	if(!b)
	{
		pA = 0;
		c = 0;
		d = 'misc';
		e = false;
	}
	else
	{
		if(b == '1')
		{
			mA = '1';
			pA = '1';
			c = '1';
			d = 'ip';
			e = false;
		}
		else
		{ 
			mA = 0;
			pA = 0;
			c = '2';
			d = 'ip';
			e = false;
		}
	}
	x_post_ip(a, c, ud);
}

function pm(a, b)
{
	ib();
	e = 'preview';
	var c = ac('username').value;
	var h = (ac('disable_bbcode').checked) ? '1' : '0';
	var f = (ac('disable_smilies').checked) ? '1' : '0';

	if(ac('attach_sig'))
	{
		var g = (ac('attach_sig').checked) ? '1' : '0';
	}
	else
	{
		var g = '0';
	}

	x_preview_pm(a, b, c, h, f, g, ud);
	delete(c);
	delete(d);
	delete(f);
	delete(g);
}

function pp(a, b)
{
	ib();
	e = 'preview';
	var h = (ac('disable_bbcode').checked) ? '1' : '0';
	var f = (ac('disable_smilies').checked) ? '1' : '0';

	if(ac('attach_sig'))
	{
		var g = (ac('attach_sig').checked) ? '1' : '0';
	}
	else
	{
		var g = '0';
	}

	x_preview_post(a, b, h, f, g, ud);
	delete(h);
	delete(f);
	delete(g);
}

function pw(a, b)
{
	if(a!='yes')
	{
		aa('topic', a);
	}
	else
	{
		ad('postrow_' + b,' ').style.display = 'none';
		ad('postrow_' + b + '_second',' ').style.display = 'none';
		ad('postrow_' + b + '_third',' ').style.display = 'none';
	}
	if(config['AJAXed_Is_Loading'])
	{
		ih();
	}
}

function pz(a, b)
{
	ib();
	config['Post_ID'] = b;
	e = 'previewPost';
	x_previewPost(a, b, ud);
}

function sc(a, b)
{
	if(config['AJAXed_Post_Title'])
	{
		aa('p_' + b + '_subject', a);
		if(!b)
		{
			aa('topic_title', a);
			document.title = lang['AJAXed_Topic_Title'] + a;
		}
		if(config['AJAXed_Is_Loading'])
		{
			ih();
		}
	}
}

function ss(a, b)
{
	if(config['AJAXed_Post_Title'])
	{
		ib();
		x_edit_post_subject(a, b, ad(a + '_subject').value, sc, b, 2);
	}
}

function ta(a)
{
	if(!a)
	{
		return false;
	}
	try
	{
		var b = document.getElementById(a);
	}
	catch(error)
	{
		var b;
	}
	if(b)
	{
		var c = tb(b);
		if(c)
		{
			scroll(0, c - 30);
		}
	}
	delete(b);
	delete(c);
}

function tb(a)
{
	var b = a.offsetTop;
	while((a = a.offsetParent) != null)
	{
		b += a.offsetTop;
	}
	return b;
	delete(b);
}

function tc()
{
	if(config['AJAXed_Poll_Menu'])
	{
		ib();
		aD = 'misc';
		d = 'misc';
		e = 'poll_menu';
		if(config['AJAXed_Poll_Viewed'] == "Matt")
		{
			var a = '1';
		}
		else
		{
			var a = '0';
		}
		x_poll_menu(a, ud);
		ab(d, 10, 10);
		delete(a);
	}
}

function td()
{
	if(config['AJAXed_Poll_Title'])
	{
		ib();
		x_poll_title(ad('poll_titled').value, tf);
	}
}

function te()
{
	aa('misc', ' ');
}

function tf(a)
{
	if(config['AJAXed_Poll_Title'])
	{
		aa('poll_title', a);
	}
	if(config['AJAXed_Is_Loading'])
	{
		ih();
	}
}

function tg()
{
	if(config['AJAXed_Poll_Options'])
	{
		ib();
		e = false;
		tC = true;
		d = 'poll_option';
		config['AJAXed_Poll_count'] = "0";
		x_poll_options(ud);
	}
}

function th(a)
{
	tD = a;
}

function ti(a)
{
	ib();
	e = false;
	d = 'poll_display';
	if(config['AJAXed_Poll_Viewed'] == null)
	{
		if(config['AJAXed_Poll_View'])
		{
			config['AJAXed_Poll_Viewed'] = 'gru';
		}
		else
		{
			config['AJAXed_Poll_Viewed'] = 'Matt';
		}
	}
	if(a)
	{
		if( config['AJAXed_Poll_Viewed'] == "Matt" )
		{
			var c = '0';
		}
		else
		{
			var c = '1';
		}
	}
	else
	{
		if( config['AJAXed_Poll_Viewed'] == "Matt" )
		{
			config['AJAXed_Poll_Viewed'] = 'gru';
			var c = '1';
		}
		else
		{
			config['AJAXed_Poll_Viewed'] = 'Matt';
			var c = '0';
		}
	}
	x_poll_view(c, ud);
	delete(c);
}

function tj(a)
{
	if(config['AJAXed_Poll_Options'])
	{
		var b = ad('poll_option_text[' + a + ']').value;
		if(b)
		{
			ib();
			e = 'poll_update';
			x_poll_option_update('0', a, b, ud);
			delete(b);
		}
	}
}

function tk(a)
{
	if(config['AJAXed_Poll_Options'])
	{
		ib();
		if(confirm(lang['AJAXed_Poll_Confirm']))
		{
			e = 'poll_update';
			x_poll_option_update('1', a, '', ud);
		}
		else
		{
			if(config['AJAXed_Is_Loading'])
			{
				ih();
			}
			return false;
		}
	}
}

function tl()
{
	ib();
	var b = '';
	var c =	document.voting;
	for (i = 0; i < c.vote_id.length; i++)
	{
		if (c.vote_id[i].checked)
		{
			b = c.vote_id[i].value;
		}
	}
	if(!b)
	{
		alert(lang['AJAXed_Poll_Select']);
		if(config['AJAXed_Is_Loading'])
		{
			ih();
		}
	}
	else
	{
		e = false;
		d = 'poll_display';
		x_poll_vote(b, ud);
	}
	delete(b);
	delete(c);
}

function ua()
{
	if(config['AJAXed_Username_Check'])
	{
		if(ac('username').value)
		{
			ib();
			x_check_username(ac('username').value, ue);
		}
		else
		{
			ad('AJAXed_username').src = image['AJAXed_Wrong'];
		}
	}
}

function ub(a, b)
{
	if(config['AJAXed_User_List'])
	{
		if(config['AJAXed_User_Power'])
		{
			if(config['AJAXed_KeyUp'] == 37)
			{
				return false;
			}
			else if(config['AJAXed_KeyUp'] == 38)
			{
				return false;
			}
			else if(config['AJAXed_KeyUp'] == 39)
			{
				return false;
			}
			else if(config['AJAXed_KeyUp'] == 40)
			{
				return false;
			}
		}

		if (uA > 0)
		{
			clearTimeout(uA);
		}

		if(!b)
		{
			config['AJAXed_User_mode'] = 'username';
		}
		else
		{
			config['AJAXed_User_mode'] = 'username';
		}
		uA = setTimeout("ui('" + a + "')", "500");
	}
}

function uc(a)
{
	if(config['AJAXed_User_List'])
	{
		d = 'user_list';
		e = false;
		var username = a.replace(/&039;/g,"'");
		ac(config['AJAXed_User_mode']).value = username;
		delete(username);
		ud(' ');
		config['AJAXed_User_Power'] = false;
	}
}

function ud(a)
{
	if(e)
	{
		if(e == 'post_menu')
		{
			pB[a + '_menu_1'] = true;
			pB[config['AJAXed_Post_ID'] + '_post_menu'] = a;
		}
		else if(e == 'poll_update')
		{
			if(config['AJAXed_Is_Loading'])
			{
				ih();
			}
			return false;
		}
		else if(e == 'preview')
		{
			d = 'preview_box';
			ta(d);
		}
		else if(e == 'previewPost')
		{
			d = config['Post_ID'] + '_preview';
			ta(d);
		}
		aa(d,a);
	}
	else
	{
		aa(d, a);
		if(mA)
		{
			if(mB)
			{
				ab(d, 10, 10);
				delete(mB);
			}
			else
			{
				ab(d, 150, 50);
			}
			delete(mA);
		}
		ad(d).style.visibility = "visible";
	}
	delete(d);
	delete(e);
	if(config['AJAXed_Is_Loading'])
	{
		ih();
	}
}

function ue(a)
{
	if(config['AJAXed_Username_Check'])
	{
		if(ad('AJAXed_username'))
		{
			var b = a.charAt(0);
			var c = a.substring(1);
			if(b == "C")
			{
				ad('AJAXed_username').src = image['AJAXed_Correct'];
			}
			else
			{
				ad('AJAXed_username').src = image['AJAXed_Wrong'];
			}
			ad('AJAXed_username').alt = c;
			ad('AJAXed_username').title = c;
			delete(b); delete(c);
		}
		if(config['AJAXed_Is_Loading'])
		{
			ih();
		}
	}
}
function uf(a)
{
	if(!a)
	{
		cA = true;
	}
	else
	{
		cA = false;
	}
}

function ug(a, b)
{
	ib();
	aD = 'misc';
	d = 'misc';
	if(pB[a + '_ip'])
	{
		pB[a + '_ip'] = null;
	} 
	if(pB[a + '_post_menu'])
	{
		aa(d, pB[a + '_post_menu']);
		if(config['AJAXed_Is_Loading'])
		{
			ih();
		}
	}
	else
	{
		e = 'post_menu';
		config['AJAXed_Post_ID'] = a;
		x_post_menu(a, b, ud);
	}
	if(!pB[a + '_menu_1'])
	{
		if(!pB[a + '_menu_2'])
		{
			ab(d, 10, 10);
		}
	}
}

function uh()
{
	ib();
	e = false;
	d = 'watch_topic';
	x_watch_topic(ud);
}

function ui(a)
{
	if(config['AJAXed_User_List'])
	{
		aB = true;
		aD = 'user_list';
		e = false;

		config['AJAXed_User_Typed'] = ac(config['AJAXed_User_mode']).value;

		if(ac(config['AJAXed_User_mode']).style.width)
		{
			f = ac(config['AJAXed_User_mode']).style.width;
		}
		else
		{
			f = '136';
		}
		if(ac(config['AJAXed_User_mode']).value)
		{
			config['AJAXed_User_Power'] = true;
			config['AJAXed_User_Selected'] = null;
			d = 'user_list';
			x_build_user_list(a, f, ud);
		}
		else
		{
			config['AJAXed_User_Power'] = false;
			config['AJAXed_User_Typed'] = null;
			aa('user_list', ' ');
			delete(d); delete(f);
		}
	}
}

function uj()
{
	if(config['AJAXed_Password_Check'])
	{
		var a = ac('new_password').value;
		var b = ac('password_confirm').value;
	
		if( (a != '') && (b != '') )
		{
			var c = false;
			if(a == b)
			{
				c = true;
			}
			if(ad('AJAXed_password'))
			{
				if(c)
				{
					ad('AJAXed_password').src = image['AJAXed_Correct'];
					ad('AJAXed_password').alt = lang['AJAXed_Check_True'];
					ad('AJAXed_password').title = lang['AJAXed_Check_True'];
				}
				else
				{
					ad('AJAXed_password').src = image['AJAXed_Wrong'];
					ad('AJAXed_password').alt = lang['AJAXed_Check_False'];
					ad('AJAXed_password').title = lang['AJAXed_Check_False'];
				}
			}
		}
	}
}

function uk(a)
{
	var c = config['AJAXed_User_Selected'];
	if(ad(a))
	{
		ad(a).style.backgroundColor = config['AJAXed_Class_2'];
		if(config['AJAXed_User_Selected'])
		{
			if(a != c)
			{
				ad(c).style.backgroundColor = config['AJAXed_Class_1'];
			}
		}
		config['AJAXed_User_Selected'] = a;
	}
}

function ul(a)
{
	if(config['AJAXed_User_List'])
	{
		um();
		config['AJAXed_KeyUp'] = a.charCode ? a.charCode : a.keyCode
		if(config['AJAXed_User_Power'])
		{

			if(config['AJAXed_User_Selected'])
			{
				var b = config['AJAXed_User_Selected'].replace("userlist_","");
			}

			if(config['AJAXed_KeyUp'] == 37)
			{
				d = 'user_list';
				e = false;
				ud(' ');
				ac(config['AJAXed_User_mode']).value = config['AJAXed_User_Typed'];
				return false;
			}
			else if(config['AJAXed_KeyUp'] == 38)
			{
				if(config['AJAXed_User_Selected'])
				{
					b = parseInt(b) - 1;
					un('user_list_inner', '1', '10');

					if(!ad("userlist_" + b))
					{
						b = config['AJAXed_User_Max'];
						un('user_list_inner', '2', '0');
					}
				}
				else
				{
					b = "0";
				}
			}
			else if(config['AJAXed_KeyUp'] == 39)
			{
				d = 'user_list';
				e = false;
				ud(' ');
				if(config['AJAXed_User_Power'])
				{
					config['AJAXed_User_Typed'] = null;
				}
			}
			else if(config['AJAXed_KeyUp'] == 40)
			{
				if(config['AJAXed_User_Selected'])
				{
					b = parseInt(b) + 1;
					un('user_list_inner', '3', '10');
	
					if(!ad("userlist_" + b))
					{
						b = "0";
						un('user_list_inner', '4', '0');
					}
				}
				else
				{
					b = "0";
				}
			}
			if(ad('userlist_' + b + '_name'))
			{
				var username = ad('userlist_' + b + '_name').value.replace(/&039;/g,"'");
				ac(config['AJAXed_User_mode']).value = username;
				ta("userlist_" + b + "_name");
				uk("userlist_" + b);
				delete(username); delete(b);
			}
		}
	}
}

function um()
{
	if(ad("The_List_AJAXed"))
	{
		var User = ad("The_List_AJAXed");
		var List = User.rows;
		config['AJAXed_User_Max'] = parseInt(List.length) - 1;
		delete(User); delete(List);
	}
}

function un(a, b, c)
{
	if(ad(a))
	{
		if(b == "1")
		{
			ad(a).scrollTop = parseInt(ad(a).scrollTop) - parseInt(c);
		}
		else if(b == "2")
		{
			ad(a).scrollTop = parseInt(ad(a).scrollHeight);
		}
		else if(b == "3")
		{
			ad(a).scrollTop = parseInt(ad(a).scrollTop) + parseInt(c);
		}
		else if(b == "4")
		{
			ad(a).scrollTop = parseInt(c);
		}
		else
		{
			return false;
		}
	}
}

function xa(a)
{
	return false;
}

function xb(a)
{
	if(!xJ)
	{
		document.onmousedown = xa;
		xC = a; 
		xC.style.zIndex = 100;
		document.onmousemove = xc;
		document.onmouseup = xd;
		xD = xA;
		xE = xB;
		xF = xC.offsetLeft;
		xG = xC.offsetTop;
	}
}

function xc(a)
{
	if(xC)
	{
		xH = xF + (xA - xD);
		xI = xG + (xB - xE);

		if( xI > 0 )
		{
			if ( page['scrollHeight'] <= xI + xC.offsetHeight )
			{
				xI = page['scrollHeight'] - xC.offsetHeight;
			}
		}
		else
		{
			xI = '0';
		}

		if( xH > 0 )
		{
			if ( page['scrollWidth'] <= xH + xC.offsetWidth )
			{
				xH = page['scrollWidth'] - xC.offsetWidth;
			}
		}
		else
		{
			xH = '0';
		}
		xC.style.left = xH + 'px';
		xC.style.top  = xI + 'px';
	}
	xy(a);
}

function xd()
{
	if(xC)
	{
		xC.style.zIndex = 0;
		xC.style.left = xH + 'px';
		xC.style.top  = xI + 'px';
		xC = null;
	}
	document.onmouseup = null;
	document.onmousedown = null;
}

function xe(a)
{
	if(a)
	{
		xJ = true;
	}
	else
	{
		xJ = false;
	}
}

function xy(a)
{
	page['scrollWidth'] 		= document.body.scrollWidth;
	page['scrollHeight'] 		= document.body.scrollHeight;
	page['clientWidth']			= document.body.clientWidth;
	page['clientHeight']		= document.body.clientHeight;

	if(cc() == "ie")
	{
		page['scrollLeft'] 		= (document.documentElement && document.documentElement.scrollLeft) ? document.documentElement.scrollLeft : document.body.scrollLeft;
		page['scrollTop'] 		= (document.documentElement && document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.body.scrollTop;
		page['clientX'] 		= event.clientX;
		page['clientY'] 		= event.clientY;
		xA = page['clientX'] + page['scrollLeft'];
		xB = page['clientY'] + page['scrollTop'];
	}
	else
	{
		page['scrollLeft'] 		= pageXOffset;
		page['scrollTop'] 		= pageYOffset;
		page['clientX'] 		= a.clientX;
		page['clientY'] 		= a.clientY;
		xA = a.pageX;
		xB = a.pageY;
	} 
	if(xA < 0)
	{
		xA = '0';
	}
	if(xB < 0)
	{
		xB = '0';
	}
	xM = page['clientWidth'] - page['clientX'];
	xN = page['clientHeight'] - page['clientY'];
}
