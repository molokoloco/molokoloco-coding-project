THIS IS FUCKING FLASH BORDEL ! I KNOW............


# FLASH, FLEX, AIR et AS3 #

```

// ------------------------------------------------------------------------- //
// FlashDevelop
/*
	* Escape: Close the completion list and method call-tip (or press Ctrl key to hide them).
	* F1: when you see "..." in a (yellow) tip, you can press F1 to see a more detailed tip.
	* F4: go to declaration of element at cursor location.
	* Shift+F4: jump back after F4 or code generation.
	* Ctrl+Space: contextual completion list - also, press Tab to expand snippets like 'for', 'while', etc.
	* Ctrl+Shift+Space: method call-tip (the yellow window with current method's signature)
	* Ctrl+Alt+Space: list all project classes (as after ':' or keywords like "new")
	* Ctrl+Shift+1: contextual code generation
*/
// ------------------------------------------------------------------------- //

// Add Class Path to get DATAPROVIDER
// C:\Program Files\Adobe\Adobe Flash CS3\fr\Configuration\Component Source\ActionScript 3.0\User Interface\

// Ajout du fichier de commande : Create FlashDevelop Project.jsfl
// C:\Documents and Settings\mediabox\Local Settings\Application Data\Adobe\Flash CS3\fr\Configuration\Commands

// ------------------------------------------------------------------------- //
(loader.content as Bitmap).smoothing = true;

var thisMc:MovieClip = getChildByName(elementArr) as MovieClip;

mainStage.filters = [new BlurFilter(8)];

if (_visualProgress && contains(_visualProgress)) removeChild(_visualProgress);

const CANT_CHANGE:uint = 3; // Contante positive

dispatchEvent(new Event(Event.COMPLETE));

private function _getInstanceFromString(pClassName:String):DisplayObject
{
try {
var classobj:Class = getDefinitionByName(pClassName) as Class;
return new classobj() as DisplayObject;
}
catch (err:Error) {
trace('la class "' + pClassName + '" n\'existe pas');
}
return null;
}

<mx:TextInput width="90%" height="49" id="locationBar" text="http://www.google.com" backgroundImage="@Embed('icons/nav_nav_c2.png')"/>

// use weak references so when the listOwner changes, we garbage collect old listeners
listOwner.addEventListener(FlexEvent.VALUE_COMMIT, updatePosition, false, 0, true);


var frameColor:uint = getStyle("frameColor");
image.setStyle("verticalAlign", "middle");

stage.displayState = StageDisplayState.FULL_SCREEN;
stage.scaleMode = StageScaleMode.NO_BORDER; // EXACT_FIT // SHOW_ALL // NO_BORDER // NO_SCALE

// ------------------------------------------------------------------------- //

private function main():void {
	if (stage) addedToStage();
	else addEventListener(Event.ADDED_TO_STAGE, addedToStage);
}
private function addedToStage(e:Event = null):void {
	/* ici on commence */
}

// ------------------------------------------------------------------------- //
itemsId[key] = new openID_mc();
addChild(itemsId[key]);
itemsId[key].stockID = key;
itemsId[key].mouseChildren = false;
itemsId[key].addEventListener(MouseEvent.CLICK, itemClicked);

function itemClicked(e:MouseEvent):void {
	itemSelected = e.currentTarget.stockID;
	idSetSelected();
}


// ------------------------------------------------------------------------- //
// YOYO TWEEN

// Create a new Tween object which will fade the text field's alpha property.
var fadeTween:Tween = new Tween(myTextField, "alpha", Strong.easeIn, 1, 0, 2, true);
fadeTween.addEventListener(TweenEvent.MOTION_FINISH, motionFinishHandler);

/* Handler for the fade tween. When the tween dispatches the motionFinish 
   event, this function gets called and reverses the direction of the tween. */
function motionFinishHandler(event:TweenEvent):void {
    Tween(event.currentTarget).yoyo();
}

// ------------------------------------------------------------------------- //

private function loadImages():void {
	var context:LoaderContext = new LoaderContext();
	context.checkPolicyFile = true;
	for (var key:String in IMAGES) {
		var image:URLRequest = new URLRequest(IMAGES[key]['src']);
		var conteneurImage:Loader = new Loader();
		conteneurImage.addEventListener(Event.COMPLETE, addImageHandler); 
		conteneurImage.contentLoaderInfo.addEventListener(Event.COMPLETE, loadImagesHandler);
		conteneurImage.load(image, context);
	}
}

private function loadImagesHandler(event:Event):void {
	var loader:Loader = Loader(event.target.loader);
	var image:Bitmap = Bitmap(loader.content);
	image.smoothing = true;
	addChild(image);
}

// ------------------------------------------------------------------------- //

import flash.net.URLRequest;
import flash.display.Loader;
import flash.events.Event;
import flash.events.ProgressEvent;

function startLoad() {
	var mLoader:Loader = new Loader();
	var mRequest:URLRequest = new URLRequest('./CoverFlow/ImageAdvanceCarousel.swf');
	mLoader.contentLoaderInfo.addEventListener(Event.COMPLETE, onCompleteHandler);
	//mLoader.contentLoaderInfo.addEventListener(ProgressEvent.PROGRESS, onProgressHandler);
	mLoader.load(mRequest);
}
function onCompleteHandler(loadEvent:Event) {
	addChild(loadEvent.currentTarget.content);
}
function onProgressHandler(mProgress:ProgressEvent) {
	var percent:Number = mProgress.bytesLoaded/mProgress.bytesTotal;
}

// ------------------------------------------------------------------------- //

package {
    import flash.display.Sprite;
    public class Array_filter extends Sprite {
        public function Array_filter() {
            var employees:Array = new Array();
            employees.push({name:"Employee 1", manager:false});
            employees.push({name:"Employee 2", manager:true});
            employees.push({name:"Employee 3", manager:false});
            trace("Employees:");
            employees.forEach(traceEmployee);
            
            var managers:Array = employees.filter(isManager);
            trace("Managers:");
            managers.forEach(traceEmployee);
        }
        private function isManager(element:*, index:int, arr:Array):Boolean {
            return (element.manager == true);
        }
        private function traceEmployee(element:*, index:int, arr:Array):void {
            trace("\t" + element.name + ((element.manager) ? " (manager)" : ""));
        }
    }
}

// ------------------------------------------------------------------------- //

// var titre:TextField = createText(articleText.titre, {size:14, width:bmp.width, height:20, alias:AntiAliasType.ADVANCED});
private function createText(textText:String, obj:Object=null):TextField {
	
	var Arial:Font = new Arial14(); // From librairie
	
	var txtFmt:TextFormat = new TextFormat();
	txtFmt.font = Arial.fontName;
	txtFmt.color = 0xFFFFFF;
	txtFmt.size = obj.size || 14;
	txtFmt.leading = 4;
	txtFmt.leftMargin = 2;
	txtFmt.rightMargin = 2;
	
	var _console:TextField = new TextField();
	_console.width = obj.width || 200;
	_console.height = obj.height || 30;
	_console.embedFonts = true;
	_console.antiAliasType = (obj.alias ? obj.alias : AntiAliasType.NORMAL);
	_console.wordWrap = true; 
	_console.multiline = true;
	_console.selectable = false;
	_console.border = false;
	_console.defaultTextFormat = txtFmt;
	_console.background = true;
	_console.backgroundColor = 0x000000;
	_console.htmlText = textText;
	_console.autoSize = TextFieldAutoSize.LEFT;
	
	return _console;
}

// ------------------------------------------------------------------------- //

var url:String = 'stb.php?action=validClient&openid='+escape(itemsId[itemSelected].id_txt.text);
var chargeur:URLLoader = new URLLoader();
var adresseFichier:URLRequest = new URLRequest(url);

function chargementTermine(evt:Event) {
	var xmlData:XML = new XML((evt.target as URLLoader).data);
	if (xmlData != null && xmlData.client[0] != null) {
		clientId = int(xmlData.client[0].id.toString());
		nettoyer();
	}
	else showAlert("Veuillez verifier votre code PIN");
}
chargeur.addEventListener(Event.COMPLETE, chargementTermine);
chargeur.load(adresseFichier);


// ------------------------------------------------------------------------- //

var monXML:XML = new XML ("<balise><test>BlaBla</test></balise>");

trace(monXML.test); // affiche "BlaBla"

var xml:XML = <root><balise name="Test" text="Je suis un attributs" /></root>;
trace( xml.balise.@name ); // trace Test

var xml:XML = <root><balise name="Test" text="Je suis un attributs" /></root>;
trace( xml.balise.@["name"] ); // trace Test

var xml:XML = <root><balise name="Test" text="Je suis un attributs" /></root>;
trace(xml.balise.attribute("name") ); // trace Test

var xml:XML = <root>
<balise name="balise1" />
<balise name="balise2"/>
<balise name="balise3"/>
</root>;

for(var i:String in xml..balise) {
	trace(xml.balise[i].@name);
}

trace(xml..balise.@name);

for each(var bal:XML in xml..balise) { // Soit la variable bal, car le nom "balise" est déja utilisé ici
	trace(bal.@name);
}

var balise:XMLList = xml..balise.(@name=="balise2");
trace(balise.toXMLString());

var xml:XML = <root><balise name="Test" text="Je suis un attribut" /></root>;
var nom:String = "noeuds (dynamique cette fois)";
var monNoeud:XML = <balise name={nom} text="Je suis un attribut" />;
xml.appendChild(monNoeud);

// ------ //

var urlRequest:URLRequest = new URLRequest("xml/contacts.xml");
var urlLoader:URLLoader = new URLLoader(urlRequest);
urlLoader.addEventListener(Event.COMPLETE, xmlCharge);

function xmlCharge(e:Event):void {
	//trace(e.target.data);
	var xml:XML = new XML(e.target.data);
	
	// Accès au nom du contact dont l'ID est "2"
	trace( xml.contact.(@id=="2").nom );
	
	// Récupération des contacts qui ont le prénom "Prenom3"
	trace( xml.contact.(prenom=="Prenom3"));
	
	// Idem avec une variable contenant le prénom
	var prenomRecherche:String = "Prenom3";
	trace( xml.contact.(prenom==prenomRecherche));
	
	// Idem avec une variable contenant le contact
	var contactRecherche:String = "contact";
	trace( xml[contactRecherche].(prenom=="Prenom3"));
	
	//var contactRecherche:String = "contact";
	 var recherche:String = "prenom";
	 trace( xml[contactRecherche].(elements(recherche)=="Prenom3"));
	
	// Récupération des noms de contacts qui
	// ont le prénom "Prenom3"
	 var contactRecherche:String = "contact";
	 trace( xml.contact.(prenom=="Prenom3").nom );
	 
	 test(xml);
}

function test(xml:XML):void {
	var searchResult:XMLList = xml.contact.(hasOwnProperty("prenom") && prenom=="Prenom3");
	for each(var element:XML in searchResult) {
		trace(element.nom, element.prenom, element.telephone);
	}
}


// ------------------------------------------------------------------------- //
// Auto-Closing for Dev

<mx:WindowedApplication xmlns:mx="http://www.adobe.com/2006/mxml" xmlns="*" 
	deactivate="application_deactivate(event);">
	
private function application_deactivate(e:Event):void {
    stage.nativeWindow.close();
}

// ------------------------------------------------------------------------- //

import flash.filters.DropShadowFilter;

var shadow:DropShadowFilter = new DropShadowFilter(10, 45, 0x2C4677, 0.36, 8, 8, 1, 4, false, false, false);
//shadow.distance = 10;
image.filters = [shadow];

// ------------------------------------------------------------------------- //

import flash.utils.*;
import flash.events.*;
import flash.ui.Keyboard;

function startListenKey():void {
	addEventListener(KeyboardEvent.KEY_DOWN, listenKeyBoard, false, 0, true);
}

function listenKeyBoard(event:KeyboardEvent):void {
	if (_currentCarousel == null) return;
	if (event.keyCode == Keyboard.ENTER) makeAction();
	else if (event.keyCode == Keyboard.LEFT) _currentCarousel.moveLeft();
	else if (event.keyCode == Keyboard.RIGHT) _currentCarousel.moveRight();
}

// ------------------------------------------------------------------------- //

import mx.collections.ArrayCollection;
import mx.rpc.events.ResultEvent;
import mx.rpc.http.HTTPService;
import mx.utils.ArrayUtil;

private var service:HTTPService;

private function loadRelated():void
{
	service = new HTTPService();
	service.url = 'http://www.labconvergent.net/infos.xml';
	service.addEventListener(ResultEvent.RESULT, resultHandler);
	service.send();
}

private function resultHandler(event:ResultEvent):void
{
	var result:ArrayCollection = event.result.rubriques is ArrayCollection ? event.result.rubriques as ArrayCollection : new ArrayCollection(ArrayUtil.toArray(event.result.rubriques));
	for (var i:int=0; i < result[0].rubrique.length; i++)
	{
		var titre:String = result[0].rubrique[i].titre;
		var url:String = result[0].rubrique[i].url;
		
		trace(titre+' : '+url);
	}
}



// ------------------------------------------------------------------------- //

function eventoMator(e:*) { // NO ERROR WHATEVER HAPPEN
	trace(e.toString());
}

var chargeur:URLLoader = new URLLoader();
var adresseFichier:URLRequest = new URLRequest(thisUrl);

chargeur.addEventListener(Event.COMPLETE, eventoMator);
chargeur.addEventListener(Event.OPEN, eventoMator);
chargeur.addEventListener(ProgressEvent.PROGRESS, eventoMator);
chargeur.addEventListener(SecurityErrorEvent.SECURITY_ERROR, eventoMator);
chargeur.addEventListener(HTTPStatusEvent.HTTP_STATUS, eventoMator);
chargeur.addEventListener(IOErrorEvent.IO_ERROR, eventoMator);

chargeur.load(adresseFichier);

// ------------------------------------------------------------------------- //

import flash.html.HTMLLoader;
import flash.net.URLRequest;
import flash.events.HTMLUncaughtScriptExceptionEvent;
import flash.events.InvokeEvent;

private var html:HTMLLoader;
private var urlReq:URLRequest;

html = new HTMLLoader();
html.addEventListener(Event.COMPLETE, onHtmlLoadComplete);
html.addEventListener(HTMLUncaughtScriptExceptionEvent.UNCAUGHT_SCRIPT_EXCEPTION, onScriptException);
html.textEncodingFallback = "UTF-8";
html.paintsDefaultBackground = false;

var holder:UIComponent = new UIComponent();
holder.addChild(html);
addChild(holder);
urlReq = new URLRequest(locationBar.text);
html.load(urlReq);

// ------------------------------------------------------------------------- //
import flash.net.navigateToURL;
import flash.net.URLRequest;
import flash.net.URLVariables;

var url:String = "http://www.adobe.com";
var variables:URLVariables = new URLVariables();
variables.exampleSessionId = new Date().getTime();
variables.exampleUserLabel = "Your Name";
var request:URLRequest = new URLRequest(url);
request.data = variables;
try { navigateToURL(request); }
catch (e:Error) {}

// ------------------------------------------------------------------------- //

import mx.events.FlexEvent;

stage.addEventListener(KeyboardEvent.KEY_DOWN, listenKeyBoard, false, 0, true);

private function listenKeyBoard(event:KeyboardEvent):void
{
	//trace('listenKeyBoard() : '+event.keyCode);
	if (event.keyCode == Keyboard.LEFT) showPrev();
	else if (event.keyCode == Keyboard.RIGHT) showNext();
	else if (event.keyCode == Keyboard.ENTER) loadUrl();
}

// ------------------------------------------------------------------------- //

<mx:Script>
<![CDATA[
	private function showLinks(event:Event):void  {
		var dom:JavaScriptObject = event.currentTarget.javaScriptDocument;
		var links:Object = dom.getElementsByTagName("a");
		for(var i:Number = 0; i < links.length; i++) {
			trace(links[i].getAttribute("href"));
		}
	}
]]>
</mx:Script>
<mx:HTML id="html" x="10" y="10" width="824" height="497" complete="showLinks(event);"/>  

// ------------------------------------------------------------------------- //

var myDate:Date = new Date();
trace(DateField.dateToString(myDate, 'DD/MM/YYYY'));

var today:Date = new Date();
var halloween:String = "31/10/2007";

var todayString:String = DateField.dateToString(today, "DD/MM/YYYY");
todayLabel.text = todayString;

var halloweenDate:Date = DateField.stringToDate(halloween, "DD/MM/YYYY");
halloweenLabel.text = halloweenDate.toDateString();

// ------------------------------------------------------------------------- //

private function handleSlideChange():void {
scroll.maxScrollPosition = slider.value; 
scroll.invalidateDisplayList();
}

// addchild
var myComponent:UIComponent=new UIComponent();
myComponent.addChild(loader);
addChild(myComponent);
//instead of..
rawChildren.addChild(loader);
// argument "rest" (...)
function toto2(...param):void {
trace(param, param is Array);
trace(getQualifiedClassName(param));
trace(describeType(param)); // XML decrivant le type de donnée
}
toto2(2, 'toto', new MovieClip());

// Ratio 0--1
var frame:int = 1 + pRatio * (totalFrames - 1);


new String(maString.getBytes("ISO-8859-1"), "UTF-8")

// ------------------------------------------------------------------------- //

[Bindable(event="nameChanged")]
private var personName:String;

private function updateInfo():void {
    dispatchEvent(new Event("nameChanged"));
}

// ------------------------------------------------------------------------- //

package mlklc 
{
	/*
		import mlklc.MyEvent;
		
		myFavoriteManager.addEventListener(Config.ADDLINK, iListen);
		function iListen(event:MyEvent):void {
			var message:String = (event.message != undefined ? event.message : '');
		}
		
		// ...
		
		var thisEvent:MyEvent = new MyEvent(Config.ADDLINK);
		thisEvent.message = 'Test';
		dispatchEvent(thisEvent);
	*/
	
	import flash.events.Event;
	
	public class MyEvent extends Event
	{
		public var message:*;
		
		public function MyEvent(type:String) {
			super(type);
        }
	}
}
// ------------------------------------------------------------------------- //

// FROM SCRIPT // (code to access AS from JS)

    var myFile = new window.runtime.flash.filesystem.File();
    // app:/images
    var urlReq = new URLRequest("http://www.adobe.com/");


    html.runtimeApplicationDomain = ApplicationDomain.currentDomain;
    var customClassObject = new window.runtime.CustomClass();

// FROM AIR

    function helloFromJS(message:String):void {
        trace("JavaScript says:", message);
    }
    html.load(urlReq);

    function loaded(e:Event):void{
        html.window.foo = foo;
        html.window.helloFromJS = helloFromJS;
    }
    
    html.window.document.getElementById('airDrivedLink').onclick = closePopWindow;
    
 

// ------------------------------------------------------------------------- //

package com.pfp.rsscube.vos { 
 [Bindable] 
  public class RssItem { 
   public var title:String; 
   public var date:String;
   public var link:String; 
   public var author:String;
  }
}

// ------------------------------------------------------------------------- //
// VARIABLES ET REFERENCE
// Valeurs : Number, int, uint, String, Boolean : Types simples (primitifs)
var a1:String = 'toto';
var a2:String = a1; // Copie

// Références : Array, ....
var b1:Array = new Array('toto');
var b2:Array = b1; // Passage de référence
var b3:Array = b1.slice(); // Clonage...


// ------------------------------------------------------------------------- //
// EVENEMENTS
// 3 objets : cible / distributeur / ecouteur

function maFctEcouteur(e:MouseEvent):void {
trace('You click me');
}
clip_mc.addEventListener(MouseEvent.CLICK, maFctEcouteur);


// ------------------------------------------------------------------------- //
// DRAW and CHILD

var shape:Shape = new Shape();
shape.graphics.beginFill(0xCC33FF, 0.5);
shape.graphics.lineStyle(2);
shape.graphics.drawCircle(0, 0, 133);
shape.graphics.endFill();
shape.x = shape.y = 200;
this.addChild(shape);

trace(this.numChildren, getChildAt(0));

// ------------------------------------------------------------------------- //
// (this)

graphics.lineStyle(1, 0x000000, 1);
graphics.beginFill(0xCCCCCC);
graphics.drawRoundRect(0, 0, 300, 200, 10);
graphics.endFill();

// ------------------------------------------------------------------------- //

// Copier un tableau
var copy:Array = sourceArray.concat();

public function get contactsList():Array {
return _contactsList.slice(); // SLICE >> RETOURNE UNE COPIE DU TABLEAU !!!
}

// déploiement d'une application Adobe Air sur Internet
// >Badger

// Destroy vars
var a:Object = {foo:a};
delete(a);

// Remote debugging
// > Flash Debug Player.

// creates a Timer
var ticker:Timer = new Timer(1000); // once per second
ticker.addEventListener(TimerEvent.TIMER, onTick);
ticker.start();
public function onTick(event:TimerEvent):void {}

// ------------------------------------------------------------------------- //


// DOMAIN POLICY
/*

<cross-domain-policy>
<!-- Place top level domain name -->
<allow-access-from domain="yourdomain.com" secure="false"/>
<allow-access-from domain="yourdomain.com" to-ports="80,443"/>
<allow-http-request-headers-from domain="yourdomain.com" headers="*" />
<!-- use if you need access from subdomains. testing/www/staging.domain.com -->
<allow-access-from domain="*.yourdomain.com" secure="false" />
<allow-access-from domain="*.yourdomain.com" to-ports="80,443" />
<allow-http-request-headers-from domain="*.yourdomain.com" headers="*" />
</cross-domain-policy>

<cross-domain-policy>
<site-control permitted-cross-domain-policies="all">
<allow-access-from domain="*.yourdomain.com">
</allow-access-from>
<allow-http-request-headers-from domain="*.yourdomain.com" headers="*">
</allow-http-request-headers-from>
</site-control>
</cross-domain-policy>

*/

// ------------------------------------------------------------------------- //

// ROUNDED MASK FOR IMAGE
private var roundedMask:Sprite;
private function init():void {
roundedMask = new Sprite();
canvas.rawChildren.addChild(roundedMask);
}
private function image_resize(evt:ResizeEvent):void {
var w:Number = evt.currentTarget.width;
var h:Number = evt.currentTarget.height;
var cornerRadius:uint = 60;
roundedMask.graphics.clear();
roundedMask.graphics.beginFill(0xFF0000);
roundedMask.graphics.drawRoundRect(0, 0, w, h, cornerRadius, cornerRadius);
roundedMask.graphics.endFill();
image.mask = roundedMask;
}

// ------------------------------------------------------------------------- //

addChild(child:DisplayObject):DisplayObject
Adds a child DisplayObject after the end of this child list. IChildList
addChildAt(child:DisplayObject, index:int):DisplayObject
Adds a child DisplayObject to this child list at the index specified. IChildList
contains(child:DisplayObject):Boolean
Determines if a DisplayObject is in this child list, or is a descendant of an child in this child list. IChildList
getChildAt(index:int):DisplayObject
Gets the child DisplayObject at the specified index in this child list. IChildList
getChildByName(name:String):DisplayObject
Gets the child DisplayObject with the specified name in this child list. IChildList
getChildIndex(child:DisplayObject):int
Gets the index of a specific child in this child list. IChildList
getObjectsUnderPoint(point:Point):Array
Returns an array of DisplayObjects that lie under the specified point and are in this child list. IChildList
removeChild(child:DisplayObject):DisplayObject
Removes the specified child DisplayObject from this child list. IChildList
removeChildAt(index:int):DisplayObject
Removes the child DisplayObject at the specified index from this child list. IChildList
setChildIndex(child:DisplayObject, newIndex:int):void
Changes the index of a particular child in this child list.


// ------------------------------------------------------------------------- //

<mx:WindowedApplication
Properties
alwaysInFront="false"
autoExit="true"
dockIconMenu="null"
maxHeight="10000"
maxWidth="10000"
menu="null"
minHeight="100"
minWidth="100"
showGripper="true"
showStatusBar="true"
showTitleBar="true"
status=""
statusBarFactory="mx.core.ClassFactory"
systemTrayIconMenu="null"
title=""
titleBarFactory="mx.core.ClassFactory"
titleIcon="null"

Styles
buttonAlignment="auto"
buttonPadding="2"
closeButtonSkin="mx.skins.halo.windowCloseButtonSkin"
gripperPadding="3"
gripperStyleName="gripperStyle"
headerHeight="undefined"
maximizeButtonSkin="mx.skins.halo.WindowMaximizeButtonSkin"
minimizeButtonSkin="mx.skins.halo.WindowMinimizeButtonSkin"
restoreButtonSkin="mx.skins.halo.WindowRestoreButtonSkin"
showFlexChrome="true"
statusBarBackgroundColor="0xC0C0C0"
statusBarBackgroundSkin="mx.skins.halo.StatusBarBackgroundSkin"
statusTextStyleName="undefined"
titleAlignment="auto"
titleBarBackgroundSkin="mx.skins.halo.ApplicationTitleBarBackgroundSkin"
titleBarButtonPadding="5"
titleBarColors="[ 0x000000, 0x000000 ]"
titleTextStyleName="undefined"

Effects
closeEffect="No default"
minimizeEffect="No default"
unminimizeEffect="No default"

Events
applicationActivate="No default"
applicationDeactivate="No default"
closing="No default"
displayStateChange="No default"
displayStateChanging="No default"
invoke="No default"
moving="No default"
networkChange="No default"
resizing="No default"
windowComplete="No default"
windowMove="No default"
windowResize="No default"
/>

// ------------------------------------------------------------------------- //

mainStage.setStyle("backgroundImage", "icons/bg.gif");
mainStage.setStyle("backgroundAttachment", "fixed");


  <mx:Style>
     .myFontStyle {
        fontSize: 15;
        color: #9933FF;
     }
  </mx:Style>
 
  <mx:Script><![CDATA[
     public function changeStyles(e:Event):void {
        StyleManager.getStyleDeclaration('.myFontStyle').setStyle('color',0x3399CC);
     }
  ]]></mx:Script>
 
  <mx:Button id="myButton" label="Click Here" styleName="myFontStyle" click="changeStyles(event)"/>


// ------------------------------------------------------------------------- //
// Flash TWEEN in FLEX

// FlashDevelop -> Project set properties
// add ClassPaths
//../../../../Program Files/Adobe Flash CS3/fr/Configuration/ActionScript 3.0/Class

import fl.transitions.easing.Regular;
import fl.transitions.Tween;

private var myTween:Tween;

myTween = new Tween(this, 'alpha', Regular.easeOut, 0, 1, 0.5, true);
//myTween.addEventListener(TweenEvent.MOTION_FINISH, buttonAnimationComplete);

// ------------------------------------------------------------------------- //

// initialize="Font.registerFont(myriad_font);">

import flash.text.Font;
            
[Embed("assets/MyriadWebPro.ttf", fontName="MyMyriad")]
public var myriad_font:Class;


<mx:Label text="Nokia 9930" fontFamily="MyMyriad" fontSize="14" visible="{cb1.selected}" hideEffect="{fadeOut}" showEffect="{fadeIn}"/>

<mx:HBox id="mainStage" styleName="mainStage" width="98%" fontSize="11" paddingTop="0" paddingLeft="0" paddingRight="0" paddingBottom="0" horizontalGap="5" backgroundImage="@Embed('icons/bg.gif')" backgroundAttachment="scroll">

<mx:Image id="closeButton" source="@Embed(source='icons/av_close.png')" width="50" height="49" click="html.htmlLoader.historyForward()" buttonMode="true"/>


<mx:HBox borderStyle="solid" horizontalGap="0">


// ------------------------------------------------------------------------- //
/*
    * color
    * fontFamily
    * fontSize
    * fontStyle
    * fontWeight
    * paddingBottom
    * paddingLeft
    * paddingRight
    * paddingTop
    * textAlign
    * textDecoration
    * textIndent
*/

// styles/runtime/assets/ComplexStyles.css

Application {
backgroundImage: "greenBackground.gif";
theme-color: #9DBAEB;
}

Button {
fontFamily: Tahoma;
color: #000000;
fontSize: 11;
fontWeight: normal;
text-roll-over-color: #000000;
upSkin: Embed(source="orb_up_skin.gif");
overSkin: Embed(source="orb_over_skin.gif");
downSkin: Embed(source="orb_down_skin.gif");
}

.noMargins {
margin-right: 0;
margin-left: 0;
margin-top: 0;
margin-bottom: 0;
horizontal-gap: 0;
vertical-gap: 0;
}

ApplicationControlBar {
borderStyle: "solid";
cornerRadius: 10;
backgroundColor: #FF9933;
alpha: 1;
dottedMap: Embed(source="beige_dotted_map.png");
}


<mx:TextInput id="locationBar" styleName="locationBar" text="http://www.google.com" width="585" height="39" backgroundImage="@Embed(source='icons/nav_nav_c2.png')"/>

.locationBar {
    backgroundColor:'';
    borderStyle:none;
    fontSize:18px;
    color:#FF6600;
    paddingTop:6px;
    paddingLeft:6px;
    /*
    backgroundImage: Embed("icons/nav_nav_c2.png");
    background-repeat:no-repeat;
    backgroundAttachment: fixed;
    backgroundSize:'100%';
    backgroundAlpha:1;
    */
}

// ------------------------------------------------------------------------- //

// URLLOADER

private function loadSomething():void {

var paramsUserCheckConnexion:URLVariables = new URLVariables();
var urlUserCheckConnexion:URLRequest = new URLRequest(siteRoot+'index.php');
var loadUserCheckConnexion:URLLoader = new URLLoader();

paramsUserCheckConnexion.ACTION = 'check_login';
//paramsUserCheckConnexion.openid_url = openid; // openid en STOCK // Don't know it for the moment
paramsUserCheckConnexion.frob = frob;

urlUserCheckConnexion.data = paramsUserCheckConnexion;
urlUserCheckConnexion.method = URLRequestMethod.POST;
//urlUserCheckConnexion.contentType = 'text/xml';

//var loadUserCheckConnexion:URLLoader = new URLLoader();
loadUserCheckConnexion.addEventListener(Event.COMPLETE, completeUserCheckConnexion);
loadUserCheckConnexion.addEventListener(IOErrorEvent.IO_ERROR, errorHandler);

try { loadUserCheckConnexion.load(urlUserCheckConnexion); }
catch (error:ArgumentError) { debug.db("An ArgumentError has occurred."); debug.db(error); }
catch (error:SecurityError) { debug.db("A SecurityError has occurred."); debug.db(error); }

private function completeUserCheckConnexion(evt:Event):void {
debug.db('completeUserCheckConnexion()');
loadUserCheckConnexion.removeEventListener(Event.COMPLETE, completeUserCheckConnexion);
var responseData:String = loadUserCheckConnexion.data;
debug.db('responseData = '+responseData);
if (responseData != '0' && responseData != '') {
openid = responseData; // Get and stock user validated open ID
openidChecked = true;
//UT.show(repBox);
dock();
}
else {
//openid = '';
openidChecked = false;
callDistantPage();
}
}
}

// ------------------------------------------------------------------------- //

import mx.managers.CursorManagerPriority;
import mx.managers.CursorManager;

[Embed("/cursors/cc.png")]
private var customCursor:Class;

private function showCursor():void{
	CursorManager.setCursor(customCursor, CursorManagerPriority.HIGH, 3, 2);
}
private function removeCursor():void {
	CursorManager.removeAllCursors();
}

// ------------------------------------------------------------------------- //

var doc:Object = html.htmlLoader.window.document;
parsedSource.text = html.htmlLoader.window.document.documentElement.innerHTML;
var scriptElement:Object = doc.createElement("script");
scriptElement.setAttribute("type", "text/javascript");
scriptElement.text = "function getTypeof(obj) {return typeof obj};";
doc.body.appendChild(scriptElement);

// ------------------------------------------------------------------------- //

// AMFPHP SERVICES
import flash.net.*;
import flash.display.Bitmap;
import flash.events.FileListEvent;
import flash.events.NetStatusEvent;
import flash.events.SecurityErrorEvent;

private function checkUserAuthentification():void {
debug.db('checkUserAuthentification()');

gateway = new NetConnection();
gateway.addEventListener(NetStatusEvent.NET_STATUS, errorHandler);
gateway.addEventListener(SecurityErrorEvent.SECURITY_ERROR, securityHandler);
gateway.connect(gatewayUrl);
gateway.call('UserManager.checkId', new Responder(onAmfResult, onAmfFault), frob);

// Handle a successful AMF call. This method is defined by the responder.
function onAmfResult(result:*):void {
debug.db('onAmfResult()');

gateway.removeEventListener(NetStatusEvent.NET_STATUS, errorHandler);
gateway.removeEventListener(SecurityErrorEvent.SECURITY_ERROR, securityHandler);

var responseData:String = result as String;
if (responseData != '0' && responseData != '') {
openid = responseData; // Get and stock user validated open ID
openidChecked = true;
dock();
}
else {
openidChecked = false;
callDistantPage();
}
}

// Handle an unsuccessfull AMF call. This is method is dedined by the responder.
function onAmfFault(fault:Object):void {
debug.db('onAmfFault()');
debug.db('PAS OK AMFphp > '+ "code:\n" + fault.fault.faultCode + "\n\nMessage:\n" + fault.fault.faultString + "\n\nDetail:\n" + fault.fault.faultDetail);
}
}

// ------------------------------------------------------------------------- //


// ASPECT RATIO

function appliquerContrainte(loader:Loader, contrainte:DisplayObject):void
{

// Rapport L/H de l'élément chargé puis du cadre de contrainte
var ratioLI:Number = loader.contentLoaderInfo.width / loader.contentLoaderInfo.height;
var ratioContrainte:Number = contrainte.width / contrainte.height;

// Dimensions
if (ratioLI > ratioContrainte)
{
loader.width = contrainte.width;
loader.scaleY = loader.scaleX;
}
else
{
loader.height = contrainte.height;
loader.scaleX = loader.scaleY;
}

// Position
loader.x = contrainte.x + (contrainte.width - loader.width) / 2;
loader.y = contrainte.y + (contrainte.height - loader.height) / 2;

}
 


// LOAD, SCALE AND WRITE IMAGE

var loader:Loader = Loader(event.target.loader);
var myBitmap:Bitmap = Bitmap(loader.content);
var duplicate:Bitmap = new Bitmap(myBitmap.bitmapData.clone());

var bitmap:BitmapData = new BitmapData(150, 150, false, 0x000000);
var nScaleX:Number = 150/duplicate.width;
var nScaleY:Number = 150/duplicate.height;

var matrix:Matrix = new Matrix();
matrix.scale(nScaleX, nScaleY);

bitmap.draw(duplicate,matrix, null, null, null, true);

var jpg:JPEGEncoder = new JPEGEncoder();
var ba:ByteArray = jpg.encode(bitmap);
var newImage:File = new File();
newImage.nativePath='C:\\MyRep\\CG\\Vignettes\\'+fileName+'.jpg';
var fileStream:FileStream = new FileStream();
fileStream.open(newImage, FileMode.UPDATE);
fileStream.writeBytes(ba);
resetForm();


// ------------------------------------------------------------------------- //
/*
    * File.applicationStorageDirectory—a storage directory unique to each installed AIR application
    * File.applicationDirectory—the read-only directory where the application is installed (along with any installed assets)
    * File.desktopDirectory—the user's desktop directory
    * File.documentsDirectory—the user's documents directory
    * File.userDirectory—the user directory
*/


// ------------------------------------------------------------------------- //

<?php

$errors = array();
$data = "";
$success = "false";

function return_result($success,$errors,$data) {
echo("<?xml version="1.0" encoding="utf-8"?>");
?>
<results>
<success><?=$success;?></success>
<?=$data;?>
<?=echo_errors($errors);?>
</results>
<?
}

function echo_errors($errors) {

for($i=0;$i<count($errors);$i++) {
?>
<error><?=$errors[$i];?></error>
<?
}

}

switch($_REQUEST['action']) {

    case "upload":

    $file_temp = $_FILES['file']['tmp_name'];
    $file_name = $_FILES['file']['name'];

    $file_path = $_SERVER['DOCUMENT_ROOT']."/personnalisation/logos_clients/";

    //checks for duplicate files
    if(!file_exists($file_path."/".$file_name)) {

         //complete upload
         $filestatus = move_uploaded_file($file_temp,$file_path."/".$file_name);

         if(!$filestatus) {
         $success = "false";
         array_push($errors,"Upload failed. Please try again.");
         }

    }
    else {
    $success = "false";
    array_push($errors,"File already exists on server.");
    }

    break;

    default:
    $success = "false";
    array_push($errors,"No action was requested.");

}

return_result($success,$errors,$data);

?>


// ------------------------------------------------------------------------- //
var loader:Loader = Loader(event.target.loader); var myBitmap:Bitmap = Bitmap(loader.content); var duplicate:Bitmap = new Bitmap(myBitmap.bitmapData.clone()); var bitmap:BitmapData = new BitmapData(150, 150, false, 0x000000); var nScaleX:Number = 150/duplicate.width; var nScaleY:Number = 150/duplicate.height; var matrix:Matrix = new Matrix(); matrix.scale(nScaleX, nScaleY); bitmap.draw(duplicate,matrix, null, null, null, true); var jpg:JPEGEncoder = new JPEGEncoder(); var ba:ByteArray = jpg.encode(bitmap); var newImage:File = new File(); newImage.nativePath='D:\\myRepertoire\\Vignettes\\'+__file.name; var fileStream:FileStream = new FileStream(); fileStream.open(newImage, FileMode.UPDATE); fileStream.writeBytes(ba);


// Scale multiple time to avoid scalling more than 50% and keep good smooth version

public static function resampleBitmapData (bmp:BitmapData, ratio:Number):BitmapData {
	if (ratio >= 1) {
	return (BitmapManager.resizeBitmapData(bmp, ratio));
	}
	else {
	var bmpData:BitmapData = bmp.clone();
	var appliedRatio:Number = 1;
	
	do {
	if (ratio < 0.5 * appliedRatio) {
	bmpData = BitmapManager.resizeBitmapData(bmpData, 0.5);
	appliedRatio = 0.5 * appliedRatio;
	}
	else {
	bmpData = BitmapManager.resizeBitmapData(bmpData, ratio / appliedRatio);
	appliedRatio = ratio;
	}
	} while (appliedRatio != ratio);
	
	return (bmpData);
	}
}

```

http://www.yazo.net/

```

// ------------ ROOT LOADING ----------------------------------- //

function chargementEnProgress(evt:Event) {
    var pctLoaded:Number = Math.floor(root.loaderInfo.bytesLoaded / root.loaderInfo.bytesTotal * 100);
    if (!isNaN(pctLoaded)) {
        pBar_mc.bar_mc._xscale = pctLoaded;
        pBar_mc.label_txt.text = pctLoaded.toString() + "%";
        if (pctLoaded >= 100) {
           root.removeEventListener(Event.ENTER_FRAME,chargementEnProgress);
           play();
        }
    }
};
//root.loaderInfo.addEventListener(Event.COMPLETE, chargementEnProgress);
root.addEventListener(Event.ENTER_FRAME,chargementEnProgress);

// ------------ GET VARS FROM EMBED ----------------------------------- //
occurence.text = this.stage.loaderInfo.parameters.var


// ------------ GET URL ----------------------------------- //

function getUrl(btn_mc:Object, url:String):void {
    btn_mc.buttonMode = true;
    btn_mc.addEventListener(MouseEvent.MOUSE_DOWN, function(evt:MouseEvent):void {
        var urlReq:URLRequest = new URLRequest(url);
        navigateToURL(urlReq, "_blank");
    });
}



function btn1(evt:MouseEvent) {
    var btn1_url:URLRequest = new URLRequest("http://www.tourismebelgique.com/");
    navigateToURL(btn1_url, "_blank");
}
partenaires_mc.btn1_btn.buttonMode = true;
partenaires_mc.btn1_btn.addEventListener(MouseEvent.MOUSE_DOWN, btn1);



// ------------ GET URL SCROLL TEXT ----------------------------------- //

import fl.controls.UIScrollBar;

var url:String = "http://www.helpexamples.com/flash/text/lorem.txt";

var dynText_txt:TexdynText_txtield = new TexdynText_txtield();
dynText_txt.x = 10;
dynText_txt.y = 10;
dynText_txt.width = 500;
dynText_txt.height = 380;
dynText_txt.wordWrap = true;
addChild(dynText_txt);

var myScrollBar:UIScrollBar = new UIScrollBar();
myScrollBar.move(dynText_txt.x + dynText_txt.width, dynText_txt.y);
myScrollBar.height = dynText_txt.height;
myScrollBar.scrollTarget = dynText_txt;
addChild(myScrollBar);

var uLdr:URLLoader = new URLLoader();
uLdr.addEventListener(Event.COMPLETE, completeHandler);
uLdr.load(new URLRequest(url));

function completeHandler(event:Event):void {
    dynText_txt.text = URLLoader(event.target).data;
    myScrollBar.update();
}


// SEND FORM ----------------------------------- //
var enveloppe:URLLoader = new URLLoader;
var adresseMail:URLRequest = new URLRequest("http://www.yazo.net/racine/swfdusite/envoyer_un_message.php");

btEnvoyer.buttonMode = true;
btEnvoyer.addEventListener(MouseEvent.MOUSE_DOWN,envoyerMail);

function envoyerMail(evt:MouseEvent) {
    var variablesLocales:URLVariables = new URLVariables();
        variablesLocales.nom = nomExpediteur.text.toUpperCase();
    variablesLocales.prenom = prenomExpediteur.text;
    variablesLocales.adressemail = adressemailExpediteur.text;
    variablesLocales.messagemail = messageExpediteur.text;
    adresseMail.data = variablesLocales;
    enveloppe.load(adresseMail);
    messageExpediteur.text = "Message envoyé"
}


// ------------ ANIMATOR ----------------------------------- //

var moteur:Timer = new Timer(10, 500);

moteur.addEventListener(TimerEvent.TIMER,avancer);
moteur.start();

function avancer(evt:TimerEvent) {
    oiseau_d_mc.x+=0.5;
}
function finMouvement(evt:TimerEvent) {
    oiseau_d_mc.y-=10;
}
moteur.addEventListener(TimerEvent.TIMER_COMPLETE,finMouvement);

function arreterAvancer(evt:MouseEvent) {
    moteur.stop();
}
oiseau_d_mc.addEventListener(MouseEvent.CLICK,arreterAvancer);



// ------------ SET INTERVAL ----------------------------------- //
var intervalId:uint;
intervalId = setTimeout(call_TweenPencartesMilieu, 3000);
function call_TweenPencartesMilieu() {
    clearInterval(intervalId);
}


// ------------ CURSOR ----------------------------------- //
var cursor:Sprite = new Sprite();
cursor.graphics.beginFill(0x000000);
cursor.graphics.drawCircle(0,0,20);
cursor.graphics.endFill();
addChild(cursor);

stage.addEventListener(MouseEvent.MOUSE_MOVE,redrawCursor);
Mouse.hide();

function redrawCursor(event:MouseEvent):void
{
    cursor.x = event.stageX;
    cursor.y = event.stageY;
}



// ------------ COOKIEE ----------------------------------- //

var coursier:SharedObject = SharedObject.getLocal("memoPositions");
if (coursier.data.nom!= undefined) {
    nomClient.text = coursier.data.nom;
}
btMemoriser.addEventListener(MouseEvent.MOUSE_DOWN,enregistrerNom);
function enregistrerNom(evt:MouseEvent) {
    coursier.data.nom = nomClient.text;
    coursier.flush();
}


// ------------ FILTERS ----------------------------------- //

import flash.filters.DropShadowFilter;

// BevelFilter, BlurFilter, DropShadowFilter, GlowFilter, GradientBevelFilter, GradientGlowFilter
var ombrePortee:DropShadowFilter = new DropShadowFilter();
ombrePortee.quality = BitmapFilterQuality.HIGH;
ombrePortee.strength = 5;
ombrePortee.blurX = 5;
ombrePortee.blurY = 5;
ombrePortee.distance = 5;
ombrePortee.angle = 30;
ombrePortee.alpha=0.1;

var serieDeFiltres:Array = new Array();
serieDeFiltres.push(ombrePortee);

titre.filters = serieDeFiltres;

// ------------ BLENDS ----------------------------------- //

// Add, alpha, darken, difference, erase, hardlight, invert, layer, lighten, multiply, normal, overlay, screen, subtract
function btEnfonce (evt:MouseEvent) { titre.blendMode = BlendMode.ADD; }
function btRelache (evt:MouseEvent) { titre.blendMode = BlendMode.NORMAL }
titre.addEventListener(MouseEvent.MOUSE_DOWN,btEnfonce)
titre.addEventListener(MouseEvent.MOUSE_UP,btRelache)



// ------------ TEXTE INPUTS ----------------------------------- //

import fl.controls.TextInput;
import fl.controls.Button;

var usernameField:TextInput = new TextInput();
usernameField.move(10,10);
usernameField.width = 200;
addChild(usernameField)

var submitButton:Button = new Button();
submitButton.label = "Submit";
submitButton.move(10,80);
addChild(submitButton);

var passwordField:TextInput = new TextInput();
passwordField.move(10,40);
passwordField.width = 200;
passwordField.displayAsPassword = true;
addChild(passwordField);

// Since we have created the form items in a different order than
// users expect for tabbing, manually set the tab index.
usernameField.tabIndex = 1;
passwordField.tabIndex = 2;
submitButton.tabIndex = 3;

usernameField.setFocus();



// ------------ TWEEN ----------------------------------- //

import fl.transitions.Tween;
import fl.transitions.easing.*;
import fl.transitions.TweenEvent;

var box_tween:Object = new Tween(_text, "y", Strong.easeOut, 0, -25, .8, true); //Strong.easeIn
box_tween.addEventListener(TweenEvent.MOTION_FINISH, motionFinish);
            
function motionFinish(e:TweenEvent):void {
      _text.text = xmlList[imgPos].titre[0].toString();
      box_tween = new Tween(_text, "y", Strong.easeIn, -25, 0, 1, true);
};


// ------------ CLASS TWEEN EXTENDED ----------------------------------- //

var mouvement1:Tween = new Tween(balle1, "x", Regular.easeOut, balle1.x, 200, 5, true);
   
    - balle1 : Occurrence concernée par "l'animation".
    - Regular.easeOut : Effet dans le mouvement. // Back, Strong, Elastic, Bounce et None || easeIn et easeInOut
    - balle1.x : Valeur de départ de l'animation.
    - 200 : Valleur d'arrivée de l'animation.
    - 5 : Valeur exprimée en secondes qui définie la durée de l'animation.
    - true : Paramètre précisant que l'avant dernier paramètre (5 dans notre exemple) correspond à des secondes et non des images.

stage.addEventListener(MouseEvent.MOUSE_DOWN,deplacerBalle2);
function deplacerBalle2(evt:MouseEvent) {
    var mouvement2:Tween;
    mouvement2 = new Tween(balle2, "x", Regular.easeOut, balle2.x, mouseX, 2, true);
    new Tween(balle2, "y", Regular.easeOut, balle2.y, mouseY, 2, true);
}

var zoomX:Tween = new Tween(diams_mc, "scaleX", Elastic.easeOut, 0.2, 1, 2, true);
var zoomY:Tween = new Tween(diams_mc, "scaleY", Bounce.easeOut, 0.2, 1, 1, true);

var diams_tween:Tween = new Tween(diams_mc, "scaleX", Elastic.easeOut, 0.2, 1.2, 2, true);
diams_tween.onMotionFinished = function(){  trace("diams_tween.onMotionFinished"); }

function onFinishDiams_mc():void {
   trace("parameters: " + parameter1_num + ", and " + parameter2_mc);
}

var myTransitionManager:TransitionManager = new TransitionManager(diams_mc);
myTransitionManager.startTransition({type:Zoom, direction:Transition.IN, duration:1, easing:Bounce.easeOut});

// ------------ TWEEN LITE ----------------------------------- //

// TweenLite.to/from(target:Object, duration:Number, variables:Object);
// Putting quotes around values will make the tween relative to the current value

    * alpha: The alpha (opacity level) that the target object should finish at (or begin at if you're using TweenLite.from()). For example, if the target.alpha is 1 when this script is called, and you specify this parameter to be 0.5, it'll transition from 1 to 0.5.
    * x: To change a MovieClip's x position, just set this to the value you'd like the MovieClip to end up at (or begin at if you're using TweenLite.from()).
    
    * delay: The number of seconds you'd like to delay before the tween begins
    * ease: fl.motion.easing.Elastic.easeOut. The Default is Regular.easeOut.
    * easeParams: An array of extra parameter values to feed the easing equation. This can be useful when you use an equation like Elastic and want to control extra parameters like the amplitude and period
    * autoAlpha: Same as changing the "alpha" property but with the additional feature of toggling the "visible" property to false if the alpha ends at 0. It will also toggle visible to true before the tween starts if the value of autoAlpha is greater than zero.
    * volume: To change a MovieClip's volume, just set this to the value you'd like the MovieClip to end up at (or begin at if you're using TweenLite.from()).
    * tint: To change a MovieClip's color, set this to the hex value of the color you'd like the MovieClip to end up at(or begin at if you're using TweenLite.from()). An example hex value would be 0xFF0000. If you'd like to remove the color from a MovieClip, just pass null as the value of tint
    * frame: Use this to tween a MovieClip to a particular frame.
    * onStart: If you'd like to call a function as soon as the tween begins
    * onStartParams: An array of parameters to pass the onStart function
    * onUpdate: If you'd like to call a function every time the property values are updated
    * onUpdateParams: An array of parameters to pass the onUpdate function (this is optional)
    * onComplete: If you'd like to call a function when the tween has finished, use this.
    * onCompleteParams: An array of parameters to pass the onComplete function (this is optional)
    * overwrite: If you do NOT want the tween to automatically overwrite any other tweens that are affecting the same target

TweenLite.to(clip_mc, 5, {
    alpha:0.5,
    x:120,
    ease:Back.easeOut, // Back, Strong, Elastic, Bounce, None || easeOut, easeIn, easeInOut
    delay:2,
    onComplete:onFinishTween,
    onCompleteParams:[5, clip_mc]
});

function onFinishTween(parameter1_num:Number, parameter2_mc:MovieClip):void {
   trace("parameters: " + parameter1_num + ", and " + parameter2_mc);
}
for (i = 1; i <= 7; i++) {
    mc = this["word"+i+"_mc"];
    TweenLite.from(mc, 1, {y:"-140", autoAlpha:0, ease:Elastic.easeOut, delay:i * 0.15});
}



// ------------ TWEEN FILTER LITE ----------------------------------- //
/*
TweenFilterLite.to(target:DisplayObject, duration:Number, variables:Object);

    * Description: Tweens the target's properties from whatever they are at the time you call the method to whatever you define in the variables parameter.
    * Parameters:
         1. target: Target DisplayObject whose properties we're tweening
         2. duration: Duration (in seconds) of the tween
         3. variables: An object containing the end values of all the properties you'd like to have tweened (or if you're using the TweenFilterLite.from() method, these variables would define the BEGINNING values). Putting quotes around values will make the tween relative to the current value. For example, x:"-20" will tween x to whatever it currently is minus 20 whereas x:-20 will tween x to exactly -20. Here are some examples of properties you might include:
                o blurX
                o blurY
                o color: An example for red would be 0xFF0000. Several filters use this property, like DropShadow and Glow
                o colorize: Only used with a type:"Color" tween to colorize an entire MovieClip.
                o amount: Only used to control the amount of colorization.

            Special Properties:
                o type : String - REQUIRED. A string that indicates what type of filter you're tweening.
               
                Possible values are: "Color" (for all image effects like colorize, brightness, contrast, saturation, and threshold), "Blur", "Glow", "DropShadow", or "Bevel"
               
               
                o delay : Number - Number of seconds to delay before the tween begins. This can be very useful when sequencing tweens.
                o ease : Function - You can specify a function to use for the easing with this variable. For example, fl.motion.easing.Elastic.easeOut. The Default is Regular.easeOut.
                o easeParams : Array - An array of extra parameter values to feed the easing equation. This can be useful when you use an equation like Elastic and want to control extra parameters like the amplitude and period. Most easing equations, however, don't require extra parameters so you won't need to pass in any easeParams.
                o autoAlpha : Number - Same as changing the "alpha" property but with the additional feature of toggling the "visible" property to false if the alpha ends at 0. It will also toggle visible to true before the tween starts if the value of autoAlpha is greater than zero.
                o volume : Number - To change a MovieClip's volume, just set this to the value you'd like the MovieClip to end up at (or begin at if you're using TweenFilterLite.from()).
                o tint : Number - To change a MovieClip's color, set this to the hex value of the color you'd like the MovieClip to end up at(or begin at if you're using TweenLite.from()). An example hex value would be 0xFF0000. If you'd like to remove the color from a MovieClip, just pass null as the value of tint. Before version 5.8, tint was called mcColor (which is now deprecated and will likely be removed at a later date although it still works)
                o frame : Number - Use this to tween a MovieClip to a particular frame.
                o onStart : Function - If you'd like to call a function as soon as the tween begins, pass in a reference to it here. This can be useful when there's a delay and you want something to happen just as the tween begins.
                o onStartParams : Array - An array of parameters to pass the onStart function.
                o onUpdate : Function - If you'd like to call a function every time the property values are updated (on every frame during the time the tween is active), pass a reference to it here.
                o onUpdateParams : Array - An array of parameters to pass the onUpdate function (this is optional)
                o onComplete : Function - If you'd like to call a function when the tween has finished, use this.
                o onCompleteParams : Array - An array of parameters to pass the onComplete function (this is optional)
                o overwrite : Boolean - If you do NOT want the tween to automatically overwrite any other tweens that are affecting the same target, make sure this value is false.



TweenFilterLite.from(target:DisplayObject, duration:Number, variables:Object);

    * Description: Exactly the same as TweenFilterLite.to(), but instead of tweening the properties from where they're at currently to whatever you define, this tweens them the opposite way - from where you define TO where ever they are now (when the method is called). This is handy for when things are set up on the stage the way the should end up and you just want to tween them to where they are.
    * Parameters: Same as TweenFilterLite.to(). (see above)


TweenLite.delayedCall(delay:Number, onComplete:Function, onCompleteParams:Array);

    * Description: Provides an easy way to call any function after a specified number of seconds. Any number of parameters can be passed to that function when it's called too.
    * Parameters:
         1. delay: Number of seconds before the function should be called.
         2. onComplete: The function to call
         3. onCompleteParams [optional] An array of parameters to pass the onComplete function when it's called.


TweenFilterLite.killTweensOf(target:Object, complete:Boolean);

    * Description: Provides an easy way to kill all tweens of a particular Object/MovieClip. You can optionally force it to immediately complete (which will also call the onComplete function if you defined one)
    * Parameters:
         1. target: Any/All tweens of this Object/MovieClip will be killed.
         2. complete: If true, the tweens for this object will immediately complete (go to the ending values and call the onComplete function if you defined one).


TweenFilterLite.killDelayedCallsTo(function:Function);

    * Description: Provides an easy way to kill all delayed calls to a particular function (ones that were instantiated using the TweenFilterLite.delayedCall() method).
    * Parameters:
         1. function: Any/All delayed calls to this function will be killed.


EXAMPLES

As a simple example, you could tween the blur of clip_mc from where it's at now to 20 over the course of 1.5 seconds by:

   1. import gs.TweenFilterLite;
   2. TweenFilterLite.to(clip_mc, 1.5, {type:"Blur", blurX:20, blurY:20});

import fl.transitions.easing.*;
import gs.TweenLite;
import gs.TweenFilterLite;


TweenFilterLite.to(video_mc, 1.6, {
    type:'DropShadow',
    alpha:0.5,
    angle:313,
    blurX:10,
    blurY:10,
    distance:-5,
    strength:1,
    quality:3,
    ease:Strong.easeOut,
    overwrite:false,
    delay:0
});


If you want to get more advanced and tween the clip_mc MovieClip over 5 seconds, changing the saturation to 0, delay starting the whole tween by 2 seconds, and then call a function named "onFinishTween" when it has completed and pass in a few arguments to that function (a value of 5 and a reference to the clip_mc), you'd do so like:

   1.
      import gs.TweenFilterLite;
   2.
      import fl.motion.easing.Back;
   3.
      TweenFilterLite.to(clip_mc, 5, {type:"Color", brightness:1, delay:2, onComplete:onFinishTween, onCompleteParams:[5, clip_mc]});
   4.
      function onFinishTween(argument1_num:Number, argument2_mc:MovieClip):void {
   5.
          trace("The tween has finished! argument1_num = " + argument1_num + ", and argument2_mc = " + argument2_mc);
   6.
      }

If you have a MovieClip on the stage that already has the properties you'd like to end at, and you'd like to start with a colorized version (red: 0xFF0000) and tween to the current properties, you could:

   1. import gs.TweenFilterLite;
   2. TweenFilterLite.from(clip_mc, 5, {type:"color", colorize:0xFF0000});

FAQ

   1. Can I set up a sequence of tweens so that they occur one after the other?
      Of course! Just use the delay property and make sure you set the overwrite property to false (otherwise tweens of the same object will always overwrite each other to avoid conflicts). Here's an example where we colorize a MovieClip red over the course of 2 seconds, and then blur it over the course of 1 second:
         1. import gs.TweenFilterLite;
         2. TweenFilterLite.to(clip_mc, 2, {type:"color", colorize:0xFF0000, amount:1});
         3. TweenFilterLite.to(clip_mc, 1, {type:"blur", blurX:20, blurY:20, delay:2, overwrite:false});

*/


// -------------- getChildByName ---------------------------//


// =============== SOLUTION =============== //
var clipStock:Array = [];
private function creerClip(i:int):Void
{
    var mc:MonClip = new MonClip();
    clipStock[i] = mc;
    addChild(mc);
}
var mc1:MonClip = clipStock[2];
mc1.label = "Mon texte";



/*
Mise en situation :

    * on dispose d’un clip que créé graphiquement
    * on crée la liaison en l’exportant pour Action Script (ce qui génère une classe que l’on nommera “maClasse”, extention de la classe MovieClip)
    * on souhaite créer dynamiquement des instances de maClasse ET créer dynamiquement une instance d’objet TextField à l’intérieur de ces premières

Fonction qui génère ce que l’on veut dans la mise en situation:

function creerClip(i:int){
    var monClip:maClasse= new maClasse();// Création du clip
    monClip.name = 'monClip'+i;// nomination du clip dynamique, on aura des noms de la forme monClip1, monClip2...
    addChild(monClip);// on ajoute le MovieClip à liste d'affichage
    var monTexte:TextField = new TextField(); // Création du texte dans le clip
    monTexte.name = 'monTexte'+i;// nomination du clip dynamique, on aura des noms de la forme monTexte1, monTexte...
    monClip.addChild(monTexte);// on ajoute le TexteField à la liste 'affichage et on l'associe auxinstances demonClip
}
creerClip(1);// crée une instance monClip de la classe maClasse dont le name vaut monClip1 ET crée une instance monTexte de la classe TextField dont le name vaut monTexte1

Comment faire

Par la suite il est préférable d’avoir une classe avec un constructeur plutôt qu’une fonction mais c’est pour l’exemple. Bref, le décors étant planté, ce que l’on veux c’est accéder à la propriété monText1.txt afin de la modifier. Logiquement on devrait faire :

monClip1.monText1.text = "texte que l'on veut";

Mais cela ne marche pas, pour y parvenir, il faut utiliser la méthode getChildByName(), voici des exemples d’utilisation pour bien comprendre comment cela marche :

    * getChildByName(monClip1) renvoie un objet de type maClasse
    * getChildByName(monClip1).name renvoie une chaine de caractères “monClip1?
    * getChildByName(monClip1).numChildren renvoie une erreur à la place d’indiquer le nombre d’enfants (qui devrait être d’1, il s’agit de monText1 associé à monClip1 via monClip.addChild(monTexte))

Pour obtenir effectivement numChildren, il ne faut pas oublier de faire ce qu’on appelle du cast d’objet, il faut écrire :
MovieClip(getChildByName(monClip1)).numChildren;

Par extension, pour obtenir la propriété txt monClip1.monText1.text, il faudra utiliser la syntaxe suivante :
TextField(MovieClip(getChildByName(monClip1)).getChildByName(monText1)).text = "texte que l'on veut";

Et plus généralement pour obtenir une propriété / méthode d’une instance de classe crée dynamiquement :
Accès à la propriété d’une instance de la classe nomDeLaClasse…
nomDeLaClasse(getChildByName(propriété name de l'instance de nomDeLaClasse)).propriété;
Accès à la propriété d’une instance de la classe nomDeLaClasse2 enfante d’une instance de la classe nomDeLaClasse1…
nomDeLaClasse2(nomDeLaClasse1(getChildByName(propriété name de l'instance de nomDeLaClasse1)).getChildByName(propriété name de l'instance de nomDeLaClasse2)).propriété de la classe nomDeLaClasse2;

*/

// -------------------------------- //


pencarte_milieu_mc.addEventListener(
    MouseEvent.MOUSE_OVER,
    function(evt:MouseEvent):void {
        cursor_mc.gotoAndStop(2);
    }
);
pencarte_milieu_mc.addEventListener(
    MouseEvent.MOUSE_OUT,
    function(evt:MouseEvent):void {
        cursor_mc.gotoAndStop(1);
    }
);


var cursor:Sprite = new Sprite();

cursor.graphics.beginFill(0x000000);
cursor.graphics.drawCircle(0,0,20);
cursor.graphics.endFill();
addChild(cursor);
cursor_mc.addChild(cursor_mc);


stage.addEventListener(MouseEvent.MOUSE_MOVE, mouseMoveHandler);
cursor_mc.visible = false;
function mouseMoveHandler(event:MouseEvent):void {
    Mouse.hide();
    cursor_mc.x = event.localX;
    cursor_mc.y = event.localY;
    event.updateAfterEvent();
    cursor_mc.visible = true;
}


// -------------- DISPATCH EVENTS !!!!!!!!!!!! ----------------------------- //

/*
Action Script
voir codecopier dans le presse papierimprimer?

   1. 
   2. import mx.events.EventDispatcher;
   3. class Chargement extends EventDispatcher
   4. 

import mx.events.EventDispatcher;
class Chargement extends EventDispatcher

tu rajoute dans la classe chargement

Action Script
voir codecopier dans le presse papierimprimer?

   1. 
   2. public function traitementXML() {
   3. .....//ton code
   4. this.dispatchEvent({type:"onLoad",target:this})
   5. }
   6. 

public function traitementXML() {
.....//ton code
this.dispatchEvent({type:"onLoad",target:this})
}


et dans la classe placement

Action Script
voir codecopier dans le presse papierimprimer?

   1. 
   2.  class Placement { 
   3. 
   4. private var _listener:Object
   5. function Placement(sceneBase:MovieClip) { 
   6.    this._listener = new Object()
   7.    this._listener.onLoad = Delegate.create(this, _onLoadXML)
   8.    scene = sceneBase;          
   9.    liste = scene.attachMovie("cible_mc", "liste_mc", scene.getNextHighestDepth());          
  10.    chargement = new Chargement(); 
  11.    chargement.addEventListener(this._listener)
  12. }
  13. function _onLoadXML(){
  14.    tableauFiche = chargement.tableauFiche;          
  15.    trace (tableauFiche[0][0]); 
  16. }
  17. 


 class Placement {

private var _listener:Object
function Placement(sceneBase:MovieClip) {
   this._listener = new Object()
   this._listener.onLoad = Delegate.create(this, _onLoadXML)
   scene = sceneBase;         
   liste = scene.attachMovie("cible_mc", "liste_mc", scene.getNextHighestDepth());         
   chargement = new Chargement();
   chargement.addEventListener(this._listener)
}
function _onLoadXML(){
   tableauFiche = chargement.tableauFiche;         
   trace (tableauFiche[0][0]);
}


// Action Script


   1. import Fiche;
   2. import Chargement;
   3. import mx.utils.Delegate; 
   4. 
   5. class Placement
   6. {
   7.     private var scene:MovieClip;
   8.     private var liste:MovieClip;
   9. 
  10.     private var chargement:Chargement;
  11.     
  12.     private var tableauFiche:Array;
  13.     
  14.     function Placement(sceneBase:MovieClip)
  15.     {
  16.         scene = sceneBase;
  17.         
  18.         liste = scene.attachMovie("cible_mc", "liste_mc", scene.getNextHighestDepth());
  19.         
  20.         chargement = new Chargement();  
  21.         chargement.addEventListener("chargementComplet", Delegate.create(this, fichierCharge));
  22.     }
  23.     
  24.     private function fichierCharge()
  25.     { 
  26.         tableauFiche = chargement.tableauFiche;
  27.         placementScene();
  28.     } 
  29.     
  30.     public function placementScene() : Void
  31.     {
  32.         liste._x = 5;
  33.         liste._y = 10;
  34.         
  35.         placementFiche();
  36.     }
  37.     
  38.     private function placementFiche() : Void
  39.     {
  40.         var tailleTableau:Number;
  41.         tailleTableau = tableauFiche.length;
  42.         
  43.         var positionX:Number = -176;
  44.         var positionY:Number = -76;
  45.         
  46.         var niveau:Number;
  47.         niveau = scene.getNextHighestDepth() + tailleTableau;
  48.         
  49.         var i:Number;
  50.         var identifiant:Number;
  51.         for (i=0; i<tailleTableau; i++)
  52.         {
  53.             identifiant = i;
  54.             niveau -= i;
  55.     
  56.             positionX += 176;
  57.             if (identifiant%4 == 0)
  58.             {
  59.                 positionY += 76;
  60.                 positionX = 0
  61.             }
  62.     
  63.             var fiche:Fiche = new Fiche(identifiant, tableauFiche[i][0], tableauFiche[i][1], tableauFiche[i][2], tableauFiche[i][3], tableauFiche[i][4], tableauFiche[i][5], tableauFiche[i][6], positionX, positionY, niveau, liste);
  64.         }
  65.     }
  66. }

import Fiche;
import Chargement;
import mx.utils.Delegate;

class Placement
{
    private var scene:MovieClip;
    private var liste:MovieClip;

    private var chargement:Chargement;
   
    private var tableauFiche:Array;
   
    function Placement(sceneBase:MovieClip)
    {
        scene = sceneBase;
       
        liste = scene.attachMovie("cible_mc", "liste_mc", scene.getNextHighestDepth());
       
        chargement = new Chargement(); 
        chargement.addEventListener("chargementComplet", Delegate.create(this, fichierCharge));
    }
   
    private function fichierCharge()
    {
        tableauFiche = chargement.tableauFiche;
        placementScene();
    }
   
    public function placementScene() : Void
    {
        liste._x = 5;
        liste._y = 10;
       
        placementFiche();
    }
   
    private function placementFiche() : Void
    {
        var tailleTableau:Number;
        tailleTableau = tableauFiche.length;
       
        var positionX:Number = -176;
        var positionY:Number = -76;
       
        var niveau:Number;
        niveau = scene.getNextHighestDepth() + tailleTableau;
       
        var i:Number;
        var identifiant:Number;
        for (i=0; i<tailleTableau; i++)
        {
            identifiant = i;
            niveau -= i;
   
            positionX += 176;
            if (identifiant%4 == 0)
            {
                positionY += 76;
                positionX = 0
            }
   
            var fiche:Fiche = new Fiche(identifiant, tableauFiche[i][0], tableauFiche[i][1], tableauFiche[i][2], tableauFiche[i][3], tableauFiche[i][4], tableauFiche[i][5], tableauFiche[i][6], positionX, positionY, niveau, liste);
        }
    }
}



Deuxième classe (Chargement) :

Action Script
voir codecopier dans le presse papierimprimer?

   1. import mx.events.EventDispatcher;
   2. import mx.utils.Delegate;
   3. 
   4. class Chargement extends EventDispatcher 
   5. {
   6.     public var placement:Placement;
   7.     public var tableauFiche:Array = new Array();
   8.     private var scene:MovieClip;
   9.     private var promo_xml:XML;
  10.     private var dispatchEvent:Function;
  11.     
  12.     function Chargement()
  13.     {
  14.         EventDispatcher.initialize(this);
  15.         promo_xml = new XML();
  16.         promo_xml.load("http://urltest.free.fr/flash/promoXML.php");
  17.         promo_xml.ignoreWhite = true;
  18.         promo_xml.onLoad = Delegate.create(this, traitementXML);
  19.     }
  20.     
  21.     public function traitementXML()
  22.     {
  23.         var racine = promo_xml.firstChild;
  24.         var nombreEtudiants:Number = racine.childNodes.length;
  25.                 
  26.         var i:Number;
  27.         for (i=0; i<nombreEtudiants; i++)
  28.         {
  29.             var tableauEntree:Array = new Array;
  30.             var nombreEntree:Number = racine.childNodes[i].childNodes.length
  31.             
  32.             var j:Number;
  33.             for (j=0; j<nombreEntree; j++)
  34.             {
  35.                 tableauEntree[j] = racine.childNodes[i].childNodes[j].firstChild;
  36.                 if (j == nombreEntree - 1)
  37.                 {
  38.                     tableauFiche[i] = tableauEntree;
  39.                 }
  40.             }
  41.             if (i == nombreEtudiants - 1)
  42.             {
  43.                 this.dispatchEvent({type:"chargementComplet", target:this});
  44.             }
  45.         }
  46.     }
  47. }

import mx.events.EventDispatcher;
import mx.utils.Delegate;

class Chargement extends EventDispatcher
{
    public var placement:Placement;
    public var tableauFiche:Array = new Array();
    private var scene:MovieClip;
    private var promo_xml:XML;
    private var dispatchEvent:Function;
   
    function Chargement()
    {
        EventDispatcher.initialize(this);
        promo_xml = new XML();
        promo_xml.load("http://urltest.free.fr/flash/promoXML.php");
        promo_xml.ignoreWhite = true;
        promo_xml.onLoad = Delegate.create(this, traitementXML);
    }
   
    public function traitementXML()
    {
        var racine = promo_xml.firstChild;
        var nombreEtudiants:Number = racine.childNodes.length;
               
        var i:Number;
        for (i=0; i<nombreEtudiants; i++)
        {
            var tableauEntree:Array = new Array;
            var nombreEntree:Number = racine.childNodes[i].childNodes.length
           
            var j:Number;
            for (j=0; j<nombreEntree; j++)
            {
                tableauEntree[j] = racine.childNodes[i].childNodes[j].firstChild;
                if (j == nombreEntree - 1)
                {
                    tableauFiche[i] = tableauEntree;
                }
            }
            if (i == nombreEtudiants - 1)
            {
                this.dispatchEvent({type:"chargementComplet", target:this});
            }
        }
    }
}

*/

```