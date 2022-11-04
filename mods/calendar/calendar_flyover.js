var agt = navigator.userAgent.toLowerCase();
var originalFirstChild;
function createTitle(which, string, x, y) {
    // record the original first child (protection when deleting)
    if (typeof(originalFirstChild) == 'undefined') {
        originalFirstChild = document.body.firstChild;
    }

    x = document.all ? (event.clientX + document.body.scrollLeft) : x;
    y = document.all ? (event.clientY + document.body.scrollTop) : y;
    element = document.createElement('div');
    element.style.position = 'absolute';
    element.style.zIndex = 1000;
    element.style.visibility = 'hidden';
    if (document.all) {
        element.style.width = '200px';
        excessWidth = 50;
        excessHeight = 20;
    }
    else {
        excessWidth = 0; 
        excessHeight = 20;
    }
    element.innerHTML = '<div class="bodyline" style="padding: 2px; text-align: left;"><span class="gen">' + string + '</span></div>';
    renderedElement = document.body.insertBefore(element, document.body.firstChild);
    renderedWidth = renderedElement.offsetWidth;
    renderedHeight = renderedElement.offsetHeight;
    // fix overflowing off the right side of the screen
    overFlowX = x + renderedWidth + excessWidth - document.body.offsetWidth;
    x = overFlowX > 0 ? x - overFlowX : x;
    // fix overflowing off the bottom of the screen
    overFlowY = y + renderedHeight + excessHeight - window.innerHeight - window.pageYOffset;
    y = overFlowY > 0 ? y - overFlowY : y;
    renderedElement.style.top = (y + 15) + 'px';
    renderedElement.style.left = x + 'px';
    // windows versions of mozilla are like too fast here...we have to slow it down
    if (agt.indexOf('gecko') != -1 && agt.indexOf('win') != -1) {
        setTimeout("renderedElement.style.visibility = 'visible'", 1);
    }
    else {
        renderedElement.style.visibility = 'visible';
    }
}
function destroyTitle() {
    // make sure we don't delete the actual page contents (javascript can get out of alignment)
    if (document.body.firstChild != originalFirstChild) {
        document.body.removeChild(document.body.firstChild);
    }
}
