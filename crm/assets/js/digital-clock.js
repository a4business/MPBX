
// Fonts from :  https://www.keshikan.net/fonts-e.html

var daylist = [ l("Пн"), l("Вт"), l("Ср"), l("Чт"), l("Пн"), l("Сб"), l("Вс") ];

function genTimerStrings(tm, num){

	var i;
	var ret = tm.toString(10);
	var left = ret.length;

	if( left < num){
		for(i=0; i<( num - left ); i++ ){
			ret = String(0) + ret;
		}
	}
	return ret;
}

function updateTimer(){
	var ret;
	var date = new Date();
	var tm_year, tm_mon, tm_date, tm_hour, tm_min, tm_sec, tm_msec,tm_day;
	var colon;

	tm_year = date.getFullYear();
	tm_mon = date.getMonth()+1;
	tm_date = date.getDate();
	tm_day = date.getDay();
	tm_hour = date.getHours();
	tm_min = date.getMinutes();
	tm_sec = date.getSeconds();
	tm_msec = date.getMilliseconds();

	tm_mon = genTimerStrings(tm_mon, 2);
	tm_date = genTimerStrings(tm_date, 2);
	tm_hour = genTimerStrings(tm_hour, 2);
	tm_min = genTimerStrings(tm_min, 2);
	tm_sec = genTimerStrings(tm_sec, 2);
	tm_day = daylist[tm_day];

	if( tm_msec > 499 ){
		colon = ' ';
	}else{
		colon = ':';
	}

	document.getElementById("DSEGClock").innerHTML = tm_hour + colon + tm_min ; //+  colon + "<span style=\"font-size:30px;\">"  + tm_sec + "</span>";
	document.getElementById("DSEGClock-Year").innerHTML = "<span class=\"D7MI\">" + tm_date + "-" + tm_mon + "-" + tm_year + ' ' + "</span><span class=\"D14MI\">" + tm_day  +  "." + "</span>";

	setTimeout("updateTimer()", 500 - date.getMilliseconds()%500 );

}

