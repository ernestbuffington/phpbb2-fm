// toggle.js
// (c) 2002 Jerrett Taylor (www.nullcreations.net / www.liquidpulse.net)
/////////////////////////////////////////////////////////////////////////////////

OriginalHeight = new Array();
function SlideOut(object,dest,direc) {
    // get height and remove the 'px'
	h = document.getElementById(object).style.height;
    strip=/[px]/gi; h = h.replace(strip,''); h = parseInt(h);
    // set sizes and see if we are finished
    if (direc == 1) { h+=20; keepgoing = (dest >= h) ? true : false; } else { h-=20; keepgoing = (dest < h) ? true : false; }
    if (keepgoing) {
		// not done yet, timeout so we can keep going
		document.getElementById(object).style.height = h + 'px';
		setTimeout("SlideOut('" + object + "'," + dest + "," + direc + ");",60);
	} else {
		// either hide it or return to original size
		if (direc == 0) { document.getElementById(object).style.display = 'none'; } else { document.getElementById(object).style.height = OriginalHeight[object]+2+'px'; }
	}
}

function ToggleBox(object) { 
    // set original height if it's not already set 
    if (!OriginalHeight[object]) { 
		if (document.getElementById(object).clientHeight == 0) { 
			// if our element is hidden we need to prepare it.. show it, grab the height, shrink it to 0, and hide it again!
			document.getElementById(object).style.display = 'block';
			OriginalHeight[object] = document.getElementById(object).clientHeight;
			document.getElementById(object).style.height = 0; document.getElementById(object).style.display = 'none';
		} else {
			OriginalHeight[object] = document.getElementById(object).clientHeight;
		}
	} 
	// expand or contract 
	if (document.getElementById(object).style.display == 'block') {
		document.getElementById(object).style.height = OriginalHeight[object];
		SlideOut(object,5,0);
	} else {
		document.getElementById(object).style.display = 'block';
		SlideOut(object,OriginalHeight[object],1);
	} 
}