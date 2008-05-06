/*
	.panel.active-tab-body {
		display:block;
	}
	.bar {
		background-color:#3E3E3E;
		padding:5px;
	}
	#tabs {
		height:28px;
		list-style-image:none;
		list-style-position:outside;
		list-style-type:none;
		position:absolute;
		top:55px;
	}
	#tabs li {
		float:left;
	}
	#tabs a {
		background-color:#DCDCDC;
		color:#999999;
		float:left;
		margin-left:6px;
		padding:5px 8px;
		text-decoration:none;
	}
	#tabs a.active-tab {
		background-color:#3E3E3E;
		color:#CCCCCC;
	}

	<ul id="tabs">
		<li>
			<a href="#tabX">Tab 1</a>
		</li>
	</ul>

	<div class="panel" id="tabX">
		TEST
	</div>

	//Event.observe(window,'load',function() { new divTabs('tabs'); },false);
*/
var divTabs = Class.create();
divTabs.prototype = {
	initialize : function(element) {
		this.element = $(element);
		var options = Object.extend({}, arguments[1] || {});
		this.menu = $A(this.element.getElementsByTagName('a'));
		this.show(this.getInitialTab());
		this.menu.each(this.setupTab.bind(this));
	},
	setupTab : function(elm) {
		Event.observe(elm,'mouseover',this.activate.bindAsEventListener(this),false)
	},
	activate :  function(ev) {
		var elm = Event.findElement(ev, "a");
		Event.stop(ev);
		this.show(elm);
		this.menu.without(elm).each(this.hide.bind(this));
	},
	hide : function(elm) {
		$(elm).removeClassName('active-tab');
		$(this.tabID(elm)).removeClassName('active-tab-body');
	},
	show : function(elm) {
		$(elm).addClassName('active-tab');
		$(this.tabID(elm)).addClassName('active-tab-body');
	},
	tabID : function(elm) {
		return elm.href.match(/#(\w.+)/)[1];
	},
	getInitialTab : function() {
		if(document.location.href.match(/#(\w.+)/)) {
			var loc = RegExp.$1;
			var elm = this.menu.find(function(value) { return value.href.match(/#(\w.+)/)[1] == loc; });
			return elm || this.menu.first();
		}
		else return this.menu.first();
	}
}