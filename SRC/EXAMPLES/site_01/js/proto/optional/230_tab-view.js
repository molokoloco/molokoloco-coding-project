/************************************************************************************************************
(C) www.dg.com, October 2005
This is a script from www.dg.com. You will find this and a lot of other scripts at our website.	
www.dg.com
Alf Magne Kalleland
************************************************************************************************************/		
var textPadding = 3; // Padding at the left of tab text - bigger value gives you wider tabs
var strictDocType = true; 
var tabView_maxNumberOfTabs = 5;	// Maximum number of tabs

var dg_tabObj = new Array();
var activeTabIndex = new Array();
var MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false;

var regExp = new RegExp(".*MSIE ([0-9]\.[0-9]).*","g");
var navigatorVersion = navigator.userAgent.replace(regExp,'$1');

var ajaxObjects = new Array();
var tabView_countTabs = new Array();
var tabViewHeight = new Array();
var tabDivCounter = 0;
var closeImageHeight = 8;
var closeImageWidth = 8;


function setPadding(obj,padding){
	var span = obj.getElementsByTagName('SPAN')[0];
	span.style.paddingLeft = padding + 'px';	
	span.style.paddingRight = padding + 'px';	
}

function showTab(parentId,tabIndex) {
	var parentId_div = parentId + "_";
	if (!$('tabView' + parentId_div + tabIndex)){
		return;
	}
	if (activeTabIndex[parentId]>=0){
		if (activeTabIndex[parentId]==tabIndex){
			return;
		}
		
		//db('tabTab'+parentId_div + activeTabIndex[parentId]);
										   
		var obj = $('tabTab'+parentId_div + activeTabIndex[parentId]);
		obj.className='tabInactive';
		var img = obj.getElementsByTagName('IMG')[0];
		if (img.src.indexOf('onglet_')==-1)img = obj.getElementsByTagName('IMG')[1];
		img.src = 'images/css/images/onglet_right_inactive.png';
		$('tabView' + parentId_div + activeTabIndex[parentId]).style.display='none';
	}
	
	var thisObj = $('tabTab'+ parentId_div +tabIndex);	
		
	thisObj.className='tabActive';
	var img = thisObj.getElementsByTagName('IMG')[0];
	if (img.src.indexOf('onglet_')==-1)img = thisObj.getElementsByTagName('IMG')[1];
	img.src = 'images/css/images/onglet_right_active.png';
	
	$('tabView' + parentId_div + tabIndex).style.display='block';
	activeTabIndex[parentId] = tabIndex;

	var parentObj = thisObj.parentNode;
	var aTab = parentObj.getElementsByTagName('DIV')[0];
	countObjects = 0;
	var startPos = 2;
	var previousObjectActive = false;
	while(aTab){
		if (aTab.tagName=='DIV'){
			if (previousObjectActive){
				previousObjectActive = false;
				startPos-=2;
			}
			if (aTab==thisObj){
				startPos-=2;
				previousObjectActive=true;
				setPadding(aTab,textPadding+1);
			}else{
				setPadding(aTab,textPadding);
			}
			
			aTab.style.left = startPos + 'px';
			countObjects++;
			startPos+=2;
		}			
		aTab = aTab.nextSibling;
	}
	
	return;
}

function tabClick() {
	var idArray = this.id.split('_');		
	showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));
}

function rolloverTab() {
	if (this.className.indexOf('tabInactive')>=0){
		this.className='inactiveTabOver';
		var img = this.getElementsByTagName('IMG')[0];
		if (img.src.indexOf('onglet_')<=0)img = this.getElementsByTagName('IMG')[1];
		img.src = 'images/css/images/onglet_right_over.png';
	}
	
}
function rolloutTab() {
	if (this.className ==  'inactiveTabOver'){
		this.className='tabInactive';
		var img = this.getElementsByTagName('IMG')[0];
		if (img.src.indexOf('onglet_')<=0)img = this.getElementsByTagName('IMG')[1];
		img.src = 'images/css/images/onglet_right_inactive.png';
	}
	
}

function hoverTabViewCloseButton() {
	this.src = this.src.replace('close.png','close_over.png');
}

function stopHoverTabViewCloseButton() {
	this.src = this.src.replace('close_over.png','close.png');
}

function initTabs(mainContainerID,tabTitles,activeTab,width,height,closeButtonArray,additionalTab) {
	
	//db('initTabs : '+mainContainerID);
		
	if (!closeButtonArray) closeButtonArray = new Array();
	
	if (!additionalTab || additionalTab=='undefined'){			
		dg_tabObj[mainContainerID] = $(mainContainerID);
		width = width + '';
		if (width.indexOf('%')<0)width= width + 'px';
		dg_tabObj[mainContainerID].style.width = width;
					
		height = height + '';
		if (height.length>0){
			if (height.indexOf('%')<0)height= height + 'px';
			dg_tabObj[mainContainerID].style.height = height;
		}
		
		tabViewHeight[mainContainerID] = height;
		
		var tabDiv = document.createElement('DIV');		
		var firstDiv = dg_tabObj[mainContainerID].getElementsByTagName('DIV')[0];	
		
		dg_tabObj[mainContainerID].insertBefore(tabDiv,firstDiv);	
		tabDiv.className = 'dg_tabPane';			
		tabView_countTabs[mainContainerID] = 0;

	}
	else {
		var tabDiv = dg_tabObj[mainContainerID].getElementsByTagName('DIV')[0];
		var firstDiv = dg_tabObj[mainContainerID].getElementsByTagName('DIV')[1];
		height = tabViewHeight[mainContainerID];
		activeTab = tabView_countTabs[mainContainerID];
	}
	
	if (!tabTitles.length) {
		return true;
	}
	
	if (tabTitles.length) {
		for (var no=0;no<tabTitles.length;no++){
			var aTab = document.createElement('DIV');
			aTab.id = 'tabTab' + mainContainerID + "_" +  (no + tabView_countTabs[mainContainerID]);
			aTab.onmouseover = rolloverTab;
			aTab.onmouseout = rolloutTab;
			aTab.onclick = tabClick;
			aTab.className='tabInactive';
			tabDiv.appendChild(aTab);
			var span = document.createElement('SPAN');
			span.innerHTML = tabTitles[no];
			span.style.position = 'relative';
			aTab.appendChild(span);
			
			if (closeButtonArray[no]){
				var closeButton = document.createElement('IMG');
				closeButton.src = 'images/css/images/close.png';
				closeButton.height = closeImageHeight + 'px';
				closeButton.width = closeImageHeight + 'px';
				closeButton.setAttribute('height',closeImageHeight);
				closeButton.setAttribute('width',closeImageHeight);
				closeButton.style.position='absolute';
				closeButton.style.top = '8px';
				closeButton.style.right = '0px';
				closeButton.onmouseover = hoverTabViewCloseButton;
				closeButton.onmouseout = stopHoverTabViewCloseButton;
				
				span.innerHTML = span.innerHTML + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';	
				
				var deleteTxt = span.innerHTML+'';
	
				closeButton.onclick = function(){ deleteTab(this.parentNode.innerHTML) };
				span.appendChild(closeButton);
			}
			
			var img = document.createElement('IMG');
			img.valign = 'bottom';
			img.src = 'images/css/images/onglet_right_inactive.png';
			// IE5.X FIX
			if ((navigatorVersion && navigatorVersion<6) || (MSIE && !strictDocType)){
				img.style.styleFloat = 'none';
				img.style.position = 'relative';	
				img.style.top = '4px'
				span.style.paddingTop = '4px';
				aTab.style.cursor = 'hand';
			}	// End IE5.x FIX
			aTab.appendChild(img);
		}
	}
	
	var tabs = dg_tabObj[mainContainerID].getElementsByTagName('DIV');
	var divCounter = 0;
	for (var no=0;no<tabs.length;no++){
		if (tabs[no].className=='dg_aTab' && tabs[no].parentNode.id == mainContainerID){
			if (height.length>0)tabs[no].style.height = height;
			tabs[no].style.display='none';
			tabs[no].id = 'tabView' + mainContainerID + "_" + divCounter;
			divCounter++;
		}			
	}
	tabView_countTabs[mainContainerID] = tabView_countTabs[mainContainerID] + tabTitles.length;	
	showTab(mainContainerID,activeTab);

	return activeTab;
}	

function resetTabIds(parentId) {
	var tabTitleCounter = 0;
	var tabContentCounter = 0;
	var divs = dg_tabObj[parentId].getElementsByTagName('DIV');
	for (var no=0;no<divs.length;no++){
		if (divs[no].className=='dg_aTab'){
			divs[no].id = 'tabView' + parentId + '_' + tabTitleCounter;
			tabTitleCounter++;
		}
		if (divs[no].id.indexOf('tabTab')>=0){
			divs[no].id = 'tabTab' + parentId + '_' + tabContentCounter;	
			tabContentCounter++;
		}		
	}
	tabView_countTabs[parentId] = tabContentCounter;
}

function loadAjaxInTab(parentId,tabId,tabContentUrl) {
	
	var TabIdName = 'tabView'+parentId+'_'+tabId;
	//if ($(TabIdName).innerHTML == '') {
		var ajaxIndex = ajaxObjects.length;
		// To run with Prototype.js
		var specs = tabContentUrl.split('?');
		var tabContentUrl = specs[0]
		var tabUrlparameters = specs[1];
		ajaxObjects[ajaxIndex] = new Ajax.Updater(
			TabIdName,
			tabContentUrl, {
				method: 'get',
				parameters: tabUrlparameters,
				evalScripts:true,
				contentType: 'text/html',
				encoding: 'iso-8859-1'
			}
		);
	//}
	if (activeTabIndex[parentId] != tabId) showTab(parentId,tabId);
	
	/* // Not reload twice the same if call from onclick onglet
	if (this.id) // ce bouton // 
		this.onclick = tabClick();
	*/
}

function addAjaxContentToTab(parentId,tabTitle,tabContentUrl) {
	if (!activeTabIndex[parentId] && activeTabIndex[parentId] != 0) return true; // Open HREF
	if (!parentId) parentId = 'divMainTab';
	if (tabTitle == '') { // Current by defaut
		var index = [];
		index[0] = parentId;
		index[1] = activeTabIndex[parentId];
	}
	else var index = getTabIndexByTitle(parentId,tabTitle);
	if (index) {
		var ajaxIndex = ajaxObjects.length;
		tabId = index[1];
		parentId = index[0];
		loadAjaxInTab(parentId,tabId,tabContentUrl);
	}	
	return false; // DON'T Open HREF
}

function getTabIndexByTitle(parentId,tabTitle) {
	var regExp = new RegExp("(.*?)&nbsp.*$","gi");
	tabTitle = tabTitle.replace(regExp,'$1');
	
	if (parentId && parentId != 'undefined') {
		var divs = $(parentId).getElementsByTagName('DIV');
		for (var no=0;no<divs.length;no++){
			if (divs[no].id.indexOf('tabTab')>=0){
				var span = divs[no].getElementsByTagName('SPAN')[0];
				var regExp2 = new RegExp("(.*?)&nbsp.*$","gi");
				var spanTitle = span.innerHTML.replace(regExp2,'$1');
				
				if (spanTitle == tabTitle){
					var tmpId = divs[no].id.split('_');						
					return Array(parentId,tmpId[tmpId.length-1].replace(/[^0-9]/g,'')/1);
				}
			}
		}
	}
	else {
		for (var prop in dg_tabObj){
			//var prop = dg_tabObj[prop]; // 'divMainTab'; // Main Conteneur
			if ($(prop)) {		
				var divs = $(prop).getElementsByTagName('DIV');
				for (var no=0;no<divs.length;no++){
					if (divs[no].id.indexOf('tabTab')>=0){
						var span = divs[no].getElementsByTagName('SPAN')[0];
						var regExp2 = new RegExp("(.*?)&nbsp.*$","gi");
						var spanTitle = span.innerHTML.replace(regExp2,'$1');
						
						if (spanTitle == tabTitle){
							var tmpId = divs[no].id.split('_');						
							return Array(prop,tmpId[tmpId.length-1].replace(/[^0-9]/g,'')/1);
						}
					}
				}
			}
		}
	}
	return false;
}

function createNewTab(parentId,tabTitle,tabContent,tabContentUrl,closeButton,loadnow) {
	//db('createNewTab : '+parentId+' , '+tabTitle+' , '+tabContent+' , '+tabContentUrl+' , '+closeButton+' , '+loadnow);
	if (!tabView_countTabs[parentId] && tabView_countTabs[parentId] != 0) return true; // Open HREF

	if (tabView_countTabs[parentId]>=tabView_maxNumberOfTabs) {
		printInfo('Vous devez effacer des onglets'); // Maximum number of tabs reached - return
		return false;
	}
	if (getTabIndexByTitle(parentId,tabTitle)) {
			//db('addAjaxContentToTab');
			addAjaxContentToTab(parentId,tabTitle,tabContentUrl); // Already Exist ?
	}
	else { // Create
		var div = document.createElement('DIV');
		div.className = 'dg_aTab';
		dg_tabObj[parentId].appendChild(div);		
	
		var tabId = initTabs(parentId,Array(tabTitle),0,'','',Array(closeButton),true);
		if (tabContent) div.innerHTML = tabContent;
		
		if (tabContentUrl && loadnow)
			loadAjaxInTab(parentId,tabId,tabContentUrl);
		else if (tabContentUrl && !loadnow)
			$('tabTab'+parentId+'_'+tabId).onclick =  function(){ loadAjaxInTab(parentId,tabId,tabContentUrl); };
	}
	return false; // DON'T Open HREF
}

function deleteTab(tabLabel,tabIndex,parentId) {
	if (tabLabel){
		var index = getTabIndexByTitle(parentId,tabLabel);
		if (index){
			deleteTab(false,index[1],index[0]);
		}
	}
	else if (tabIndex>=0){
		if ($('tabTab' + parentId + '_' + tabIndex)){
			var obj = $('tabTab' + parentId + '_' + tabIndex);
			var id = obj.parentNode.parentNode.id;
			obj.parentNode.removeChild(obj);
			var obj2 = $('tabView' + parentId + '_' + tabIndex);
			obj2.parentNode.removeChild(obj2);
			resetTabIds(parentId);
			activeTabIndex[parentId]=-1;
			showTab(parentId,(tabIndex-1>0?tabIndex-1:0));
		}			
	}
}