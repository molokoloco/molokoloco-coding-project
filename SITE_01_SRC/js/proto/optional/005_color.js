/* Copyright (c) 2006 Yahoo! Inc. All rights reserved. */

var YAHOO = function() { return {util: {}} } ();

YAHOO.util.Color = new function() {
  
  // Adapted from http://www.easyrgb.com/math.html
  // hsv values = 0 - 1
  // rgb values 0 - 255
  this.hsv2rgb = function (h, s, v) {
    var r, g, b;
    if ( s == 0 ) {
      r = v * 255;
      g = v * 255;
      b = v * 255;
    } else {

      // h must be < 1
      var var_h = h * 6;
      if ( var_h == 6 ) {
        var_h = 0;
      }

      //Or ... var_i = floor( var_h )
      var var_i = Math.floor( var_h );
      var var_1 = v * ( 1 - s );
      var var_2 = v * ( 1 - s * ( var_h - var_i ) );
      var var_3 = v * ( 1 - s * ( 1 - ( var_h - var_i ) ) );

      if ( var_i == 0 ) { 
        var_r = v; 
        var_g = var_3; 
        var_b = var_1;
      } else if ( var_i == 1 ) { 
        var_r = var_2;
        var_g = v;
        var_b = var_1;
      } else if ( var_i == 2 ) {
        var_r = var_1;
        var_g = v;
        var_b = var_3
      } else if ( var_i == 3 ) {
        var_r = var_1;
        var_g = var_2;
        var_b = v;
      } else if ( var_i == 4 ) {
        var_r = var_3;
        var_g = var_1;
        var_b = v;
      } else { 
        var_r = v;
        var_g = var_1;
        var_b = var_2
      }

      r = var_r * 255          //rgb results = 0 ÷ 255
      g = var_g * 255
      b = var_b * 255

      }
    return [Math.round(r), Math.round(g), Math.round(b)];
  };

  // added by Matthias Platzer AT knallgrau.at 
  this.rgb2hsv = function (r, g, b) {
      var r = ( r / 255 );                   //RGB values = 0 ÷ 255
      var g = ( g / 255 );
      var b = ( b / 255 );

      var min = Math.min( r, g, b );    //Min. value of RGB
      var max = Math.max( r, g, b );    //Max. value of RGB
      deltaMax = max - min;             //Delta RGB value

      var v = max;
      var s, h;
      var deltaRed, deltaGreen, deltaBlue;

      if ( deltaMax == 0 )                     //This is a gray, no chroma...
      {
         h = 0;                               //HSV results = 0 ÷ 1
         s = 0;
      }
      else                                    //Chromatic data...
      {
         s = deltaMax / max;

         deltaRed = ( ( ( max - r ) / 6 ) + ( deltaMax / 2 ) ) / deltaMax;
         deltaGreen = ( ( ( max - g ) / 6 ) + ( deltaMax / 2 ) ) / deltaMax;
         deltaBlue = ( ( ( max - b ) / 6 ) + ( deltaMax / 2 ) ) / deltaMax;

         if      ( r == max ) h = deltaBlue - deltaGreen;
         else if ( g == max ) h = ( 1 / 3 ) + deltaRed - deltaBlue;
         else if ( b == max ) h = ( 2 / 3 ) + deltaGreen - deltaRed;

         if ( h < 0 ) h += 1;
         if ( h > 1 ) h -= 1;
      }

      return [h, s, v];
  }

  this.rgb2hex = function (r,g,b) {
    return this.toHex(r) + this.toHex(g) + this.toHex(b);
  };

  this.hexchars = "0123456789ABCDEF";

  this.toHex = function(n) {
    n = n || 0;
    n = parseInt(n, 10);
    if (isNaN(n)) n = 0;
    n = Math.round(Math.min(Math.max(0, n), 255));

    return this.hexchars.charAt((n - n % 16) / 16) + this.hexchars.charAt(n % 16);
  };

  this.toDec = function(hexchar) {
    return this.hexchars.indexOf(hexchar.toUpperCase());
  };

  this.hex2rgb = function(str) { 
    var rgb = [];
    rgb[0] = (this.toDec(str.substr(0, 1)) * 16) + 
            this.toDec(str.substr(1, 1));
    rgb[1] = (this.toDec(str.substr(2, 1)) * 16) + 
            this.toDec(str.substr(3, 1));
    rgb[2] = (this.toDec(str.substr(4, 1)) * 16) + 
            this.toDec(str.substr(5, 1));
    // gLogger.debug("hex2rgb: " + str + ", " + rgb.toString());
    return rgb;
  };

  this.isValidRGB = function(a) { 
    if ((!a[0] && a[0] !=0) || isNaN(a[0]) || a[0] < 0 || a[0] > 255) return false;
    if ((!a[1] && a[1] !=0) || isNaN(a[1]) || a[1] < 0 || a[1] > 255) return false;
    if ((!a[2] && a[2] !=0) || isNaN(a[2]) || a[2] < 0 || a[2] > 255) return false;

    return true;
  };
}


/* 
	colorPicker for script.aculo.us, version 0.9
	REQUIRES prototype.js, yahoo.color.js and script.aculo.us
	written by Matthias Platzer AT knallgrau.at
	for a detailled documentation go to http://www.knallgrau.at/code/colorpicker 
*/

if(!Control) var Control = {};
Control.colorPickers = [];
Control.ColorPicker = Class.create();
Control.ColorPicker.activeColorPicker;
Control.ColorPicker.CONTROL;
/**
 * ColorPicker Control allows you to open a little inline popUp HSV color chooser.
 * This control is bound to an input field, that holds a hex value.
 */
Control.ColorPicker.prototype = {
  initialize : function(field, options) {
    var colorPicker = this;
    Control.colorPickers.push(colorPicker);
    this.field = $(field);
    this.fieldName = this.field.name || this.field.id;
    this.options = Object.extend({
       IMAGE_BASE : "Plugins/ScriptAculous/colorimages/"
    }, options || {});
    this.swatch = $(this.options.swatch) || this.field;
    this.rgb = {};
    this.hsv = {};
    this.isOpen = false;

    // create control (popUp) if not already existing
    // all colorPickers on a page share the same control (popUp)
    if (!Control.ColorPicker.CONTROL) {
      Control.ColorPicker.CONTROL = {};
      if (!$("colorpicker")) {
        var control = Builder.node('div', {id: 'colorpicker'});
        control.innerHTML = 
          '<div id="colorpicker-div">' + (
            // apply png fix for ie 5.5 and 6.0
            (/MSIE ((6)|(5\.5))/gi.test(navigator.userAgent) && /windows/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent)) ?
              '<img id="colorpicker-bg" src="' + this.options.IMAGE_BASE + 'blank.gif" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + this.options.IMAGE_BASE + 'pickerbg.png\', sizingMethod=\'scale\')" alt="">' :
              '<img id="colorpicker-bg" src="' + this.options.IMAGE_BASE + 'pickerbg.png" alt="">'
             ) +
          '<div id="colorpicker-bg-overlay" style="z-index: 1002;"></div>' +
          '<div id="colorpicker-selector"><img src="' + this.options.IMAGE_BASE + 'select.gif" width="11" height="11" alt="" /></div></div>' +
          '<div id="colorpicker-hue-container"><img src="' + this.options.IMAGE_BASE + 'hue.png" id="colorpicker-hue-bg-img"><div id="colorpicker-hue-slider"><div id="colorpicker-hue-thumb"><img src="' + this.options.IMAGE_BASE + 'hline.png"></div></div></div>' + 
          '<div id="colorpicker-footer"><span id="colorpicker-value">#<input type="text" onclick="this.select()" id="colorpicker-value-input" name="colorpicker-value" value="" style="height:16px;"></input></span><button id="colorpicker-okbutton">OK</button></div>'
        document.body.appendChild(control);
      }
      Control.ColorPicker.CONTROL = {
        popUp : $("colorpicker"),
        pickerArea : $('colorpicker-div'),
        selector : $('colorpicker-selector'),
        okButton : $("colorpicker-okbutton"),
        value : $("colorpicker-value"),
        input : $("colorpicker-value-input"),
        picker : new Draggable($('colorpicker-selector'), {
          snap: function(x, y) {
            return [
              Math.min(Math.max(x, 0), Control.ColorPicker.activeColorPicker.control.pickerArea.offsetWidth), 
              Math.min(Math.max(y, 0), Control.ColorPicker.activeColorPicker.control.pickerArea.offsetHeight)
            ];
          },
          zindex: 1009,
          change: function(draggable) {
            var pos = draggable.currentDelta();
            Control.ColorPicker.activeColorPicker.update(pos[0], pos[1]);
          }
        }),
        hueSlider: new Control.Slider('colorpicker-hue-thumb', 'colorpicker-hue-slider', {
          axis: 'vertical',
          onChange: function(v) {
            Control.ColorPicker.activeColorPicker.updateHue(v);
          }
        })
      };
      Element.hide($("colorpicker"));
    }
    this.control = Control.ColorPicker.CONTROL;

    // bind event listener to properties, so we can use them savely with Event[observe|stopObserving]
    this.toggleOnClickListener = this.toggle.bindAsEventListener(this);
    this.updateOnChangeListener = this.updateFromFieldValue.bindAsEventListener(this);
    this.closeOnClickOkListener = this.close.bindAsEventListener(this);
    this.updateOnClickPickerListener = this.updateSelector.bindAsEventListener(this);

    Event.observe(this.swatch, "click", this.toggleOnClickListener);
    Event.observe(this.field, "change", this.updateOnChangeListener);
    Event.observe(this.control.input, "change", this.updateOnChangeListener);

    this.updateSwatch();
  },
  toggle : function(event) {
    this[(this.isOpen) ? "close" : "open"](event);
    Event.stop(event);    
  },
  open : function(event) {
    Control.colorPickers.each(function(colorPicker) {
      colorPicker.close();
    });
    Control.ColorPicker.activeColorPicker = this;
    this.isOpen = true;
    Element.show(this.control.popUp);
    if (this.options.getPopUpPosition) {
       var pos = this.options.getPopUpPosition.bind(this)(event);
    } else {
      var pos = Position.cumulativeOffset(this.swatch || this.field);
      pos[0] = (pos[0] + (this.swatch || this.field).offsetWidth + 10);
    }
    this.control.popUp.style.left = (pos[0]) + "px";
    this.control.popUp.style.top = (pos[1]) + "px";
    this.updateFromFieldValue();
    Event.observe(this.control.okButton, "click", this.closeOnClickOkListener);
    Event.observe(this.control.pickerArea, "mousedown", this.updateOnClickPickerListener);
    if (this.options.onOpen) this.options.onOpen.bind(this)(event);
  },
  close : function(event) {
    if (Control.ColorPicker.activeColorPicker == this) Control.ColorPicker.activeColorPicker = null;
    this.isOpen = false;
    Element.hide(this.control.popUp);
    Event.stopObserving(this.control.okButton, "click", this.closeOnClickOkListener);
    Event.stopObserving(this.control.pickerArea, "mousedown", this.updateOnClickPickerListener);
    if (this.options.onClose) this.options.onClose.bind(this)();
  },
  updateHue : function(v) {
    var h = (this.control.pickerArea.offsetHeight - v * 100) / this.control.pickerArea.offsetHeight;
    if (h == 1) h = 0;
    var rgb = YAHOO.util.Color.hsv2rgb( h, 1, 1 );
    if (!YAHOO.util.Color.isValidRGB(rgb)) return;
    this.control.pickerArea.style.backgroundColor = "rgb(" + rgb[0] + ", " + rgb[1] + ", " + rgb[2] + ")";
    this.update();
  },
  updateFromFieldValue : function(event) {
    if (!this.isOpen) return;
    var field = (event && Event.findElement(event, "input")) || this.field;
    var rgb = YAHOO.util.Color.hex2rgb( field.value );
    if (!YAHOO.util.Color.isValidRGB(rgb)) return;
    var hsv = YAHOO.util.Color.rgb2hsv( rgb[0], rgb[1], rgb[2] );
    this.control.selector.style.left = Math.round(hsv[1] * this.control.pickerArea.offsetWidth) + "px";
    this.control.selector.style.top = Math.round((1 - hsv[2]) * this.control.pickerArea.offsetWidth) + "px";
    this.control.hueSlider.setValue((1 - hsv[0]));
  },
  updateSelector : function(event) {
    var xPos = Event.pointerX(event);
    var yPos = Event.pointerY(event);
    var pos = Position.cumulativeOffset($("colorpicker-bg"));
    this.control.selector.style.left = (xPos - pos[0] - 6) + "px";
    this.control.selector.style.top = (yPos - pos[1] - 6) + "px";
    this.update((xPos - pos[0]), (yPos - pos[1]));
    this.control.picker.initDrag(event);
  },
  updateSwatch : function() { // ????
    var rgb = YAHOO.util.Color.hex2rgb( this.field.value );
    //if (!YAHOO.util.Color.isValidRGB(rgb)) return;
    this.swatch.style.backgroundColor = this.field.value; //"rgb(" + rgb[0] + ", " + rgb[1] + ", " + rgb[2] + ")";
    var hsv = YAHOO.util.Color.rgb2hsv( rgb[0], rgb[1], rgb[2] );
    this.swatch.style.color = (hsv[2] > 0.65) ? "#000000" : "#FFFFFF";
  },
  update : function(x, y) {
    if (!x) x = this.control.picker.currentDelta()[0];
    if (!y) y = this.control.picker.currentDelta()[1];

    var h = (this.control.pickerArea.offsetHeight - this.control.hueSlider.value * 100) / this.control.pickerArea.offsetHeight;
    if (h == 1) { h = 0; };
    this.hsv = {
      hue: 1 - this.control.hueSlider.value,
      saturation: x / this.control.pickerArea.offsetWidth,
      brightness: (this.control.pickerArea.offsetHeight - y) / this.control.pickerArea.offsetHeight
    };
    var rgb = YAHOO.util.Color.hsv2rgb( this.hsv.hue, this.hsv.saturation, this.hsv.brightness );
    this.rgb = {
      red: rgb[0],
      green: rgb[1],
      blue: rgb[2]
    };
	var myColor = YAHOO.util.Color.rgb2hex(rgb[0], rgb[1], rgb[2]);
    this.field.value = '#'+myColor;
    this.control.input.value = myColor;
    this.updateSwatch();
    if (this.options.onUpdate) this.options.onUpdate.bind(this)(this.field.value);
  }
}