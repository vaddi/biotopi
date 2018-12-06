/***********************************************************
                         Pageload-Skript
************************************************************/

pagestart = new Date();

function pageload() {
	current = new Date();
	dtime = current.getTime() - pagestart.getTime();
	loadtime = dtime/1000;
	document.getElementById("pageload").innerHTML = loadtime;
}

$( document ).ready( function() {
	pageload();
});


