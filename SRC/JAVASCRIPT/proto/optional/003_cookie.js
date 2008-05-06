/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco for Borntobeweb.fr... [BETA TESTING FOR EVER] ........... (o_O)  /////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

 * BloxPress2
 * ----------------------
 * Copyright (C) 2006, Kjell Bublitz
 * www.bloxpress.org
 *
 * @type: Constructor
 * @fileoverview Bloxpress document.cookie Manager
 * @author Kjell Bublitz kb@bloxpress.org
 * @version 1.4
 
var layoutCookie = new cookieManager('bp2preset'),
layoutCookie.cookieValue = this.template;
layoutCookie.update();
 
*/
var cookieManager = Class.create();
cookieManager.prototype = {
    initialize: function(cookieName, cookieValue, cookieVerbose, cookieLife) {
        this.cookieName = cookieName;
        this.cookieValue = cookieValue

        if (cookieVerbose == null) {
            this.cookieVerbose = true;
        } else {
            this.cookieVerbose = cookieVerbose;
        }

        if (cookieLife == null) {
            this.cookieLife = keepCookieDays;
        } else {
            this.cookieLife = cookieLife;
        }
    },
    set: function() {
        var today = new Date();
        var expire = new Date();
        expire.setTime(today.getTime() + 3600000 * 24 * this.cookieLife);
        document.cookie = this.cookieName + "=" + this.cookieValue + ";path=/;expires=" + expire.toGMTString();
    },
    remove: function() {
        var expire = new Date();
        var currentVal = this.read();
        expire.setTime(expire.getTime() - 1);
        document.cookie = this.cookieName + "=" + currentVal + ";path=/;expires=" + expire.toGMTString();
    },
    read: function() {
        var allCookies = document.cookie;
        if (allCookies.indexOf(this.cookieName) != -1) {
            var cookiePos = allCookies.indexOf(this.cookieName);
            var snippet = allCookies.substring(cookiePos, allCookies.length);
            var snippetVal = snippet.split('=')[1];
            var cookieVal = snippetVal.split(';')[0];
            return cookieVal;
        } else {
            return null;
        }
    },
    update: function() {
        this.remove();
        this.set();
        if(this.cookieVerbose) {
            this.createNotifier('notifyLayer', 'cookienotice', updateMessage, updateMessageLeft, updateMessageTop);
            Element.show('notifyLayer');
            setTimeout("Element.hide('notifyLayer')", 500);
        }
    },
    createNotifier: function(elementID, elementClass, elementContent, elementLeft, elementTop) {
        var notifyTop, notifyLeft;
        notifyTop = elementTop;
        notifyLeft = elementLeft;
        if (document.getElementById(elementID) == null) { // dont duplicate
            if (notifyTop == null) {
                notifyTop = '0px';
            }
            if (notifyLeft == null) {
                notifyLeft = '0px';
            }
            var notifyStyle = 'display:none;position:fixed;z-index:1000;top:' + notifyTop + ';left:' + notifyLeft;
            var notifyElement = Builder.node('div', {id:elementID, className:elementClass, style:notifyStyle}, [elementContent]);
            document.body.appendChild(notifyElement);
            return true;
        }
        return false;
    },
    cookieValue: '',
    cookieName: '',
    cookieLife: ''
}