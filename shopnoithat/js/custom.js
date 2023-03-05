function popWin(url,win,para) {
    var win = window.open(url,win,para);
    win.focus();
}
function setLocation(n){
    window.location.href=n
}
function setPLocation(url, setFocus){
    if( setFocus ) {
        window.opener.focus();
    }
    window.opener.location.href = url;
}
function setLanguageCode(code, fromCode){
    //TODO: javascript cookies have different domain and path than php cookies
    var href = window.location.href;
    var after = '', dash;
    if (dash = href.match(/\#(.*)$/)) {
        href = href.replace(/\#(.*)$/, '');
        after = dash[0];
    }

    if (href.match(/[?]/)) {
        var re = /([?&]store=)[a-z0-9_]*/;
        if (href.match(re)) {
            href = href.replace(re, '$1'+code);
        } else {
            href += '&store='+code;
        }

        var re = /([?&]from_store=)[a-z0-9_]*/;
        if (href.match(re)) {
            href = href.replace(re, '');
        }
    } else {
        href += '?store='+code;
    }
    if (typeof(fromCode) != 'undefined') {
        href += '&from_store='+fromCode;
    }
    href += after;

    setLocation(href);
}

/**
 * Scrollable
 */
function scrollToElement(id){
    jQuery('body,html').animate({
        scrollTop: jQuery(id).offset().top - 10
    }, 800);
}

/**
 * Check valid an email
 */
function isValidEmail(email) {
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return filter.test(email);
}

/**
 * get clock
 * 
 * @param int type
 */
function getClock(disp){
    var curTime=new Date();
    var nhours=curTime.getHours();
    var nmins=curTime.getMinutes();
    var nsecn=curTime.getSeconds();
    var nday=curTime.getDay();
    var nmonth=curTime.getMonth();
    var ntoday=curTime.getDate();
    var nyear=curTime.getYear();
    var AMorPM=" ";
    if (nhours>=12)AMorPM="";
    else AMorPM="";
    if (nhours>=13)nhours-=12;
    if (nhours==0)nhours=12;
    if (nsecn<10)nsecn="0"+nsecn;
    if (nmins<10)nmins="0"+nmins;
    if (nday==0)nday="Chủ nhật";
    if (nday==1)nday="Thứ hai";
    if (nday==2)nday="Thứ ba";
    if (nday==3)nday="Thứ tư";
    if (nday==4)nday="Thứ năm";
    if (nday==5)nday="Thứ sáu";
    if (nday==6)nday="Thứ bảy";
    nmonth+=1;
    if (nyear<=99)nyear= "19"+nyear;
    if ((nyear>99) && (nyear<2000))nyear+=1900;
    var d;
    var Str0="";
    if (nhours>9) Str0 = "";
    else Str0 = "0";
    
    d= document.getElementById("theClock");
    var ouput = "";
    if(disp == 0){
        ouput = ntoday + "/" + nmonth + "/" + nyear + " " + Str0 + nhours + ":" + nmins + ":" + nsecn;
    }else{
        ouput = " <span>" + Str0 + nhours+" giờ "+nmins+" phút "+nsecn + " giây - " + AMorPM + "</span>" + nday+" - Ngày " + ntoday +" tháng " + nmonth + " năm " + nyear;
    }
    
    d.innerHTML = ouput;
    setTimeout('getClock(1)',1000);
}

/**
 * Parse SID and produces the correct URL
 */
function parseSidUrl(baseUrl, urlExt) {
    var sidPos = baseUrl.indexOf('/?SID=');
    var sid = '';
    urlExt = (urlExt != undefined) ? urlExt : '';

    if(sidPos > -1) {
        sid = '?' + baseUrl.substring(sidPos + 2);
        baseUrl = baseUrl.substring(0, sidPos + 1);
    }

    return baseUrl+urlExt+sid;
}

/**
 * Formats currency using patern
 * format - JSON (pattern, decimal, decimalsDelimeter, groupsDelimeter)
 * showPlus - true (always show '+'or '-'),
 *      false (never show '-' even if number is negative)
 *      null (show '-' if number is negative)
 */

function formatCurrency(price, format, showPlus){
    var precision = isNaN(format.precision = Math.abs(format.precision)) ? 2 : format.precision;
    var requiredPrecision = isNaN(format.requiredPrecision = Math.abs(format.requiredPrecision)) ? 2 : format.requiredPrecision;

    //precision = (precision > requiredPrecision) ? precision : requiredPrecision;
    //for now we don't need this difference so precision is requiredPrecision
    precision = requiredPrecision;

    var integerRequired = isNaN(format.integerRequired = Math.abs(format.integerRequired)) ? 1 : format.integerRequired;

    var decimalSymbol = format.decimalSymbol == undefined ? "," : format.decimalSymbol;
    var groupSymbol = format.groupSymbol == undefined ? "." : format.groupSymbol;
    var groupLength = format.groupLength == undefined ? 3 : format.groupLength;

    var s = '';

    if (showPlus == undefined || showPlus == true) {
        s = price < 0 ? "-" : ( showPlus ? "+" : "");
    } else if (showPlus == false) {
        s = '';
    }

    var i = parseInt(price = Math.abs(+price || 0).toFixed(precision)) + "";
    var pad = (i.length < integerRequired) ? (integerRequired - i.length) : 0;
    while (pad) { i = '0' + i; pad--; }
    j = (j = i.length) > groupLength ? j % groupLength : 0;
    re = new RegExp("(\\d{" + groupLength + "})(?=\\d)", "g");

    /**
     * replace(/-/, 0) is only for fixing Safari bug which appears
     * when Math.abs(0).toFixed() executed on "0" number.
     * Result is "0.-0" :(
     */
    var r = (j ? i.substr(0, j) + groupSymbol : "") + i.substr(j).replace(re, "$1" + groupSymbol) + (precision ? decimalSymbol + Math.abs(price - i).toFixed(precision).replace(/-/, 0).slice(2) : "")
    var pattern = '';
    if (format.pattern.indexOf('{sign}') == -1) {
        pattern = s + format.pattern;
    } else {
        pattern = format.pattern.replace('{sign}', s);
    }

    return pattern.replace('%s', r).replace(/^\s\s*/, '').replace(/\s\s*$/, '');
};

/**
 * Set Bookmark
 */
function setBookmark(url, title){
    if (window.sidebar) // firefox
        window.sidebar.addPanel(title, url, "");
    else if(window.opera && window.print){ // opera
        var elem = document.createElement('a');
        elem.setAttribute('href',url);
        elem.setAttribute('title',title);
        elem.setAttribute('rel','sidebar');
        elem.click();
    }
    else if(document.all)// ie
        window.external.AddFavorite(url, title);
}

jQuery(function($){
    // Read a page's GET URL variables and return them as an associative array.
    $.extend({
        getUrlVars: function(){
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        },
        getUrlVar: function(name){
            return $.getUrlVars()[name];
        }
    });
});