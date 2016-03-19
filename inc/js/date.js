// Simple Date & Time
// 
// Usage: 
// add a Element with class clock. For example:
// <span class="clock"></span>

function showClock() {

	Now = new Date();

	var MonatTag = Now.getDate();
	var WochenTag = Now.getDay();
	//// Short Weekday
	var Tag = new Array("So","Mo","Di","Mi","Do","Fr","Sa");
	//// Long Weekday
	//var Tag = new Array("Sontag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag");
	var MonthNum = Now.getMonth();
	//// Short Monthname
	var Monat = new Array("Jan","Feb","M&auml;r","Apr","Mai","Jun","Jul","Aug","Sep","Okt","Nov","Dez"); 
	//// Long Monthname
	//var Monat = new Array("Januar","Februar","M&auml;rz","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"); 
	//// Numeric Month
	//var Monat = new Array("1","2","3","4","5","6","7","8","9","10","11","12"); 
	var Jahr = Now.getYear();
	if (Jahr<2000) Jahr=Jahr+1900;

	var Stunde  = Now.getHours();
	var Minute  = Now.getMinutes();
	var Sekunde = Now.getSeconds();

	// Render Date & Time
	$( '.clock' ).html(
		// Dayname
		Tag[ WochenTag ] + " " +
	
		// Date MM.DD.YYYY 
		( ( MonatTag <= 9 ) ? "0" + MonatTag : MonatTag ) + "." +
		( ( Monat[ MonthNum ] <= 9 ) ? "0" + Monat[ MonthNum ] : Monat[ MonthNum ] ) + "." +
		Jahr + " " +
	
		// Time HH:MM:SS (check n > 9 = 0n)
		//  ((Sekunde % 2 == 0) ? "." : "·")
		Stunde + ":" + 
		( ( Minute <= 9 ) ? "0" + Minute : Minute ) + ":" + 
		( ( Sekunde <= 9 ) ? "0" + Sekunde : Sekunde )
	);
	
} // END function

$( document ).ready( function() {
	showClock(); setInterval('showClock()', 1000);
});


