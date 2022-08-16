/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

import './bootstrap';

// https://stackoverflow.com/a/24103596
function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

$(document).ready(function() {
    if (getCookie('dark-mode') == 'true') {
        darkmode.setDarkMode(true);
        $('#night-mode').attr('checked', true);
    }

    $('#night-mode').on('change', function() {
        darkmode.toggleDarkMode();

        if ($(this).prop('checked') == true) {
            setCookie('dark-mode', true, 99999999);
        }
        else {
            eraseCookie('dark-mode');
        }
    });
});
