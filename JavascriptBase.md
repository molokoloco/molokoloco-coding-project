**Sommaire :**



## JAVASCRIPT RELATED LINKS ##

Javascript Cheat Sheet :

  * http://overapi.com/javascript/

Good advices here :

  * https://developer.mozilla.org/en/JavaScript/Reference/
  * http://google-styleguide.googlecode.com/svn/trunk/javascriptguide.xml
  * http://bonsaiden.github.com/JavaScript-Garden/
  * https://developer.mozilla.org/en/Introduction_to_Object-Oriented_JavaScript
  * http://addyosmani.com/resources/essentialjsdesignpatterns/book/#designpatternsjquery
  * http://tech.pro/blog/1402/five-patterns-to-help-you-tame-asynchronous-javascript
  * http://coding.smashingmagazine.com/2012/11/05/writing-fast-memory-efficient-javascript/ (new)
  * http://google-styleguide.googlecode.com/svn/trunk/javascriptguide.xml?showone=Tips_and_Tricks
  * http://www.addyosmani.com/resources/essentialjsdesignpatterns/book/
  * http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
  * http://addyosmani.com/blog/tools-for-jquery-application-architecture-the-printable-chart/
  * http://www.timmywillison.com/pres/operators/
  * http://javascriptweblog.wordpress.com/2010/08/30/understanding-javascripts-this/
  * http://engineering.socialcast.com/2011/06/javascript-memory-management/
  * http://code.google.com/p/jslibs/wiki/JavascriptTips#language_advanced_Tips_&_Tricks
  * http://jeremyckahn.github.com/blog/2012/07/01/treating-javascript-like-a-30-year-old-language/
  * http://flippinawesome.org/2013/11/25/fun-with-javascript-native-array-functions/

**Work in progress : ECMAScript 5 compatibility table**
  * http://kangax.github.com/es5-compat-table/

## CONSOLE in your browser ##

Ctrl + Maj + C
.... ou F12 dans le navigateur...


  * Firefox, Firebug : http://getfirebug.com/wiki/index.php/Main_Page
  * http://code.google.com/intl/fr-FR/chrome/devtools/docs/overview.html
  * https://developers.google.com/chrome-developer-tools/docs/scripts
  * http://www.andismith.com/blog/2011/11/25-dev-tool-secrets/

```
console.log(window);
console.log('Ma reponse json', someObj);

console.time("Execution time took");
// Some code to execute
console.timeEnd("Execution time took");

console.profile();
// Some code to execute
console.profileEnd();

var a = 1, b = "1";
console.assert(a === b, "A doesn't equal B");

console.log(console.__proto__); // Console inception ! #protips ;)

var C = Function.prototype.bind.call(console.log, console);

```

console.log(FILE, LINE, FUNCTION)

## THE DOM ##

Not enough space here...
So... the basis..

```

console.log(window);
console.log(document);
document.querySelector('#myaudio').style.marginTop = '10px';

```

## OPERATOR && COMPARAISONS ##

  * Arithmetic : +, -, **, /, %, ++, --, unary -, unary +
  * Assignment : =,**=, /=, %=, +=, -=, <<=, >>=, >>>=, &=, ^=, |=
  * Bitwise : &, |, ^, ~, <<, >>, >>>
  * Comparison : ==, !=, ===, !==, >, >=, <, <=
  * Logical : &&, ||, !
  * String : + and +=

```
var i = 0;
alert(i++);

var foo = "baz",
    checkFoo = foo.indexOf("bar");

if ( checkFoo !== -1 ) {}
// We can do
if ( ~checkFoo ) {}

```

True and False Boolean Expressions
```
// The following are all false in boolean expressions:
null
undefined
'' // the empty string
0 // the number

// But be careful, because these are all true:
'0' // the string
[] // the empty array
{} // the empty object
```

## REGEX ##

  * http://code.google.com/p/molokoloco-coding-project/wiki/RegexSyntax

```

alert('10 13 21 48 52'.replace(/d+/g, '*')); //replace all numbers with *

alert('10 13 21 48 52'.replace(/d+/g, function(match) {
	return parseInt(match) < 30 ? '*' : match;
}));

alert(/w{3,}/.test('Hello')); // w == word : alerts 'true'

function findWord(word, string) {
	var instancesOfWord = string.match(new RegExp('\b'+word+'\b', 'ig'));
	alert(instancesOfWord);
}
findWord('car', 'Carl went to buy a car but had forgotten his credit card.');


function Replacer( conversionObject ) {
        var regexpStr = '';
        for ( var k in conversionObject )
                regexpStr += (regexpStr.length ? '|' : '') + k;
        var regexpr = new RegExp(regexpStr,'ig'); // g: global, m:multi-line i: ignore case
        return function(s) { return s.replace(regexpr, function(str, p1, p2, offset, s) { var a = conversionObject[str]; return a == undefined ? str : a }) }
}

var myReplacer = Replacer( { '<BR>':'\n', '&amp;':'&', '&lt;':'<', '&gt;':'>', '&quot;':'"' } );

Print( myReplacer('aa<BR>a &amp;&amp;&amp;&lt;') );

```

## STRING ##

```

var str = 'hello world';
var pointer = str.indexOf('wor'); // pointer is equal to 6
var pointer = str.search('wor');
var pointer = str.search(/wor/); // regexp

var newstr = str.slice(0,-1); // Remove the Last Character of a String
var newstr = str.substring(1, str.length); // Remove the first Character
var newstr = str.substr(0,1).toUpperCase()+str.substr(1); // UC first

// String to array
'0100'.split(''); // ['0','1','0','0'];
'toto,tutu,tata'.split(','); // ['toto','tutu','tata'];

if (totalViews > 0) {
    for (var i = 0, views = ''; i < totalViews; i++)
        views += '<li><a href="#screen_'+(i+1)+'">'+(i+1)+'</a></li>';
    document.write(views); views = null;
}

// Comparing a JavaScript String to Multiple Values
if (fruit.match(/^(banana|lemon|mango|pineapple)$/)) {
    handleYellowFruit();
}

var str = 'This string spans multiple lines\
Need quoting of single \'quotes\'\
But no need for "double quotes".\
Because of the CDATA, I can also include some <span>html</span>\
Which can make use of <attributes with="double quotes"/> but at the same\
time, I can make use of the > and < signs without worrying about\
xml validity.';

var str = <><![CDATA[
This string spans multiple lines
Doesn't need quoting of single quotes
And the same goes for "double quotes".
Because of the CDATA, I can also include some <span>html</span>
Which can make use of <attributes with="double quotes"/> but at the same
time, I can make use of the > and < signs without worrying about
xml validity.
]]></>;
```

## ARRAYS / OBJECTS & ITERATIONS ##

  * http://jsperf.com/native-loop-performance/11
  * http://jsfiddle.net/molokoloco/qeWhB/

Preparation code

```
<script src="//code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.6.0/underscore-min.js"></script>
<script>
  Benchmark.prototype.setup = function() {
    // Populate the base array
    var arr = [];
    for (var i = 0; i < 1024; i++) arr[i] = i;
  };
</script>
```

Test -  Ops/sec

```
for (var i = 0; i < arr.length; i++) { arr[i] + 1 }

for (var i = 0, length = arr.length; i < length; i++) { arr[i] + 1 }

for (var i = arr.length - 1; i >= 0; i--) { arr[i] + 1 }

for (var key in arr) { arr[key] + 1 }

var i = 0;
while (i++ < arr.length) { arr[i - 1] + 1 }

var i = 0, length = arr.length;
while (i++ < length) { arr[i - 1] + 1 }

var length = arr.length;
while (length--) { arr[length] + 1 }

var value;
while(value = arr.pop()) { value + 1 }

var value;
do { value + 1 } while(value = arr.pop())

arr.forEach(function(value) { value + 1 })

arr.map(function(value) { return value + 1 })

$.each(arr, function(value) { value + 1 })

_.each(arr, function(value) { value + 1 })

for (var value = arr.pop(); value = arr.pop();) { value + 1 }

for (var value = arr.shift(); value = arr.shift();) { value + 1 }

[(arr[key] + 1) for (key in arr)]
```

...

```
var mobile = ('DeviceOrientationEvent' in window || 'orientation' in window);

var arr = ['va1', 'val2', 300];
for (var index in arr) alert(index+' : '+arr[index]);

var obj = {'x':200, 'y': 300, 'color':'white'};
for (var key in obj) alert(key+' : '+obj[key]);

// Node lists are often implemented as node iterators with a filter. 
// This means that getting a property like length is O(n), 
// and iterating over the list by re-checking the length will be O(n^2).

var paragraphs = document.getElementsByTagName('p');
for (var i = 0, len = paragraphs.length; i < len; i++) {
  doSomething(paragraphs[i]);
}

// do this !
for (var i=0, item; item=arr[i]; i++) {
   // do something with item
}

// cached outside loop
var len = myArray.length;
for (var i = 0; i < len; i++) { alert(i+' : '+myArray[i]); }

// cached inside loop
for (var i = 0, len = myArray.length; i < len; i++) {}

// cached outside loop using while
var len = myArray.length;
while (len--) { }

// Functions perfs : http://jsperf.com/map-an-object-s-keys-to-an-array/5

var dimensions = {};
for (var i = 0; i < 1000; i++) dimensions[i] = i;
    
Object.prototype.properties = function() {
    var p = [];
    for (var k in this) p.push(k);
    return p;
};

// Execute
var arr1 = $.map(dimensions, function(value, index) {
    return index;
});
var arr2 = dimensions.properties();
var arr3 = properties(dimensions);
var arr4 = Object.keys(dimensions);


// To transform the arguments object into an array you should make :
function foo() {
  Array().slice(arguments);
}
// or better :
function foo() {
  Array.prototype.slice.call(arguments);
}

// Labels
var i,j;

loop1:
for (i = 0; i < 3; i++) {      // The first for statement is labeled "loop1"
   loop2:
   for (j = 0; j < 3; j++) {   // The second for statement is labeled "loop2"
      if (i == 1 && j == 1)  continue loop1;
      else console.log("i = " + i + ", j = " + j);
   }
}

```

Generator Expressions - JavaScript 1.7

```
[ y for ( y in [5,6,7,8,9] ) ] // is [0,1,2,3,4]
// and
[ y for each ( y in [5,6,7,8,9] ) ] // is [5,6,7,8,9]
// Because in for extracts index names, and for each extracts the values

[1, 2, 3].forEach(function(item) { alert(item) });

var squares = [item * item for each (item in [1, 2, 3])];

[alert(item) forEach (item in [1, 2, 3])];

var obj = { foo: 1, bar: 2, baz: 3 };
[alert(name + "=" + obj[name]) for (name in obj)];

[alert(key + "=" + val) for ([key, val] in Iterator({a:1,b:2,c:3}))]

// THIS

["hemanth","gnumanth","yomanth"].forEach(function (name) {
    console.log(this.msg + " " + name);
}, this);

```


  * http://flippinawesome.org/2013/11/25/fun-with-javascript-native-array-functions/

Inversely, we could create a FIFO (first in first out) queue using .unshift and .shift.

```
function Queue () {
    this._queue = []
}

Queue.prototype.next = function () {
    return this._queue.shift()
}

Queue.prototype.add = function () {
    return this._queue.unshift.apply(this._queue, arguments)
}

queue = new Queue()
queue.add(1,2,3)

queue.next()
// <- 1
```

## DATA OBJECTS ##

```
var intkeys = ['val1', 'val2'];

var obj1 = {x: 10, y: 3};

var elements = 'div a p quote all tags'.split(' ');

var obj2 = {'Point Kiukiu': [{to: 'Hanaiapa', distance: 19}, {to: 'Mt Feani', distance: 15}, {to: 'Taaoa', distance: 15}],
            'Taaoa': []};

typeof 'abc'; // string
typeof String('abc'); // string
typeof new String('abc'); // object
typeof (new String('abc')).valueOf(); // string

if ('a' in {a: 1, b: 2}) // true

// Objects in JavaScript don’t have an order. They’re dictionaries;
// If the order is important, use an array, not an object
// But for some purpose... :

// Sorting multi-Array...
var shapes = [
    [5, 'Pentagon'],
    [3, 'Triangle'],
    [8, 'Octagon'],
    [4, 'Rectangle']
];
shapes.sort(function(a, b) { return a[1] - b[1]; });

// Sorting complex object
var movies = [
    {id:5, title:'Pentagon'},
    {id:3, title:'Triangle'}
];
var objSortByTitle = function (a, b) { var x = a.title.toLowerCase(); var y = b.title.toLowerCase(); return ((x < y) ? -1 : ((x > y) ? 1 : 0)); };

movies.sort(objSortByTitle);

// Randomize array/obj
card_values.sort(function() { return Math.round(Math.random()) - 0.5; });

// Remove an item by value in an Array object
var arr = ['a', 'b', 'c', 'd'];
var pos = arr.indexOf( 'c' );
pos > -1 && arr.splice( pos, 1 );
Print( arr ); // prints: a,b,d

// indexOf() and lastIndexOf() Works on Array (JavaScript 1.6)
var obj = {};
var arr = [ 'foo', 567, obj, 12.34 ];
Print( arr.indexOf(obj) ); // prints: 2

//Insert an array in another array
var a = [1,2,3,7,8,9]
var b = [4,5,6]
var insertIndex = 3;
a.splice.apply(a, Array.concat(insertIndex, 0, b));
Print(a); // prints: 1,2,3,4,5,6,7,8,9

// Cloning ARRAY
var a = [[1], [2], [3]];
var b = a.slice(0);
var c = $.extend(true, [], a);

//Remove an item by value in an Array object
var arr = ['a', 'b', 'c', 'd'];
var pos = arr.indexOf( 'c' );
pos > -1 && arr.splice( pos, 1 );
Print( arr ); // prints: a,b,d

// Get the number of properties of an object  
// JavaScript 1.8

Object.keys({ foo:55, bar:99 }).length // is: 2 

// JavaScript 1.2:

(function (obj) { 
   var m, count = 0; 
   for (m in obj ) { 
   if (Object.prototype.hasOwnProperty.call(obj, m)) count += 1; 
   }
   return count;
})({ foo:55, bar:99 }); // is 2

```

## Math ##

```
var featured = [
	'http://soundcloud.com/noisia/deadmau5-raise-your-weapon',
	'http://soundcloud.com/bpitch-control/moderat-a-new-error',
	'http://soundcloud.com/four-tet/angel-echoes-caribou-remix'
];
var url = document.getElementById('url');
url.value = featured[Math.floor(Math.random() * featured.length)];
```


## COMMENTS ##

```
/**
 * Adds two numbers.
 * @param {number} num1 The first number to add.
 * @param {number} num2 The second number to add.
 * @return {number} The result of adding num1 and num2.
 */
function addNumbers (num1, num2) {
  return num1 + num2;
}
```

## FUNCTIONS ##

```
// Function Declarations
function a() {
    return 3;
}

// Anonymous function expression
var a = function() {
    return 3;
}

// Named function expression
var a = function bar() {
    return 3;
}

// Self invoking function expression
(function sayHello() {
    alert("hello!");
})();

// Modular closure augmented
var MODULE = (function () {
    var my = {}, 
        privateVariable = 1; 
     
    function privateMethod() { 
       my.size = {w:320, h:240};
       // ... 
    } 
    
    my.moduleProperty = 1; 
    my.moduleMethod = function () { 
        // ... 
    }; 
     
    return my; 
}(MODULE || {}));


// Multiple-value returns (JavaScript 1.7)
function f() { return [1, 2]; }
var [a, b] = f();

// Optional named function arguments
function foo({ name:name, project:project}) {
    Print( project );
    Print( name );
}
foo({ name:'soubok', project:'jslibs' })
foo({ project:'jslibs', name:'soubok'})

```

## Object prototype ##

Examples...

```
HTMLElement.prototype.hasClass = function(cls){
    return this.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
};

HTMLElement.prototype.addClass = function(cls){
    if (cls instanceof Array){
        for (var i = 0; i < cls.length; i++){
            if (!this.hasClass(cls[i])) this.className += (' ' + cls[i]);
        }
    }else if (!this.hasClass(cls)) this.className += (' ' + cls);
};

HTMLElement.prototype.removeClass = function(cls){
    if (cls instanceof Array){
        for (var i = 0; i < cls.length; i++){
            if (this.hasClass(cls)) this.className = this.className.replace(new RegExp('(\\s|^)'+cls+'(\\s|$)'), ' ');
        }
    }else if (this.hasClass(cls)) this.className = this.className.replace(new RegExp('(\\s|^)'+cls+'(\\s|$)'), ' ');
};
```

## FUNCTIONS on functions ##

```

/*
The first invocation of f() will display the value of 10, because this references the global object. The second invocation (via the call method) however, will display the value 15. 15 is the value of the x property inside object o. The call() method invokes the function and uses its first parameter as the this pointer inside the body of the function. In other words - we've told the runtime what object to reference as this while executing inside of function f(). 
*/

var x = 10;
var o = { x: 15 };

function f(message) {
    alert(message);
    alert(this.x);
}

f("invoking f");
f.call(o, "invoking f via call");

/*
The apply() method is identical to call(), except apply() requires an array as the second parameter. The array represents the arguments for the target method. 
*/

var value = myFunction.apply(this, args);

var db = function(){ 'console' in window && console.log.call(console, arguments) };

var C = Function.prototype.bind.call(console.log, console);

function(a, b){
    var args = msg ? ' (' + [].join.call( arguments, ', ' ) + ')' : '';
    $('body').append( '<br/>' + msg + args );
}

// run the code, if any inside of the script tag that included this script.
var scripts = document.getElementsByTagName("script");
eval.call(window, scripts[scripts.length - 1].innerHTML); 

var i, hasOwn = Object.prototype.hasOwnProperty;  
for (i in man) if (hasOwn.call(man, i)) { // filter only this object properties
    console.log(i, ":", man[i]);  
}

Change current object (this) of a function call
function test(arg) {

  Print( this[0]+' '+this[1]+' '+arg );
}

var arr = ['foo', 'bar'];
test.call(arr, 'toto'); // prints: foo bar toto
```

Mapping "console.log()" to "log()"

```
window.log = Function.prototype.bind.call(console.log, console);
```

Smooth override ;)

```
Math.rand = function(min, max){
    return Math.floor(Math.random() * (max - min + 1)) + min;
};
```

## PROTOTYPE (OOP) ##

From the prototype example illustrated here : http://phrogz.net/js/classes/OOPinJS.html
Also note :  http://javascriptweblog.wordpress.com/2010/03/16/five-ways-to-create-objects/

```
function Person(n,race){ 
	this.constructor.population++;

	// ************************************************************************ 
	// PRIVATE VARIABLES AND FUNCTIONS 
	// ONLY PRIVELEGED METHODS MAY VIEW/EDIT/INVOKE 
	// *********************************************************************** 
	var alive=true, age=1;
	var maxAge=70+Math.round(Math.random()*15)+Math.round(Math.random()*15);
	function makeOlder(){ return alive = (++age <= maxAge) } 

	var myName=n?n:"John Doe";
	var weight=1;


	// ************************************************************************ 
	// PRIVILEGED METHODS 
	// MAY BE INVOKED PUBLICLY AND MAY ACCESS PRIVATE ITEMS 
	// MAY NOT BE CHANGED; MAY BE REPLACED WITH PUBLIC FLAVORS 
	// ************************************************************************ 
	this.toString=this.getName=function(){ return myName } 

	this.eat=function(){ 
		if (makeOlder()){ 
			this.dirtFactor++;
			return weight*=3;
		} else alert(myName+" can't eat, he's dead!");
	} 
	this.exercise=function(){ 
		if (makeOlder()){ 
			this.dirtFactor++;
			return weight/=2;
		} else alert(myName+" can't exercise, he's dead!");
	} 
	this.weigh=function(){ return weight } 
	this.getRace=function(){ return race } 
	this.getAge=function(){ return age } 
	this.muchTimePasses=function(){ age+=50; this.dirtFactor=10; } 


	// ************************************************************************ 
	// PUBLIC PROPERTIES -- ANYONE MAY READ/WRITE 
	// ************************************************************************ 
	this.clothing="nothing/naked";
	this.dirtFactor=0;
} 


// ************************************************************************ 
// PUBLIC METHODS -- ANYONE MAY READ/WRITE 
// ************************************************************************ 
Person.prototype.beCool = function(){ this.clothing="khakis and black shirt" } 
Person.prototype.shower = function(){ this.dirtFactor=2 } 
Person.prototype.showLegs = function(){ alert(this+" has "+this.legs+" legs") } 
Person.prototype.amputate = function(){ this.legs-- } 


// ************************************************************************ 
// PROTOTYOPE PROERTIES -- ANYONE MAY READ/WRITE (but may be overridden) 
// ************************************************************************ 
Person.prototype.legs=2;


// ************************************************************************ 
// STATIC PROPERTIES -- ANYONE MAY READ/WRITE 
// ************************************************************************ 
Person.population = 0;


// Here is the code that uses the Person class 
function RunGavinsLife(){ 
	var gk=new Person("Gavin","caucasian");       //New instance of the Person object created. 
	var lk=new Person("Lisa","caucasian");        //New instance of the Person object created. 
	alert("There are now "+Person.population+" people");

	gk.showLegs(); lk.showLegs();                 //Both share the common 'Person.prototype.legs' variable when looking at 'this.legs' 

	gk.race = "hispanic";                         //Sets a public variable, but does not overwrite private 'race' variable. 
	alert(gk+"'s real race is "+gk.getRace());    //Returns 'caucasian' from private 'race' variable set at create time. 
	gk.eat(); gk.eat(); gk.eat();                 //weight is 3...then 9...then 27 
	alert(gk+" weighs "+gk.weigh()+" pounds and has a dirt factor of "+gk.dirtFactor);

	gk.exercise();                                //weight is now 13.5 
	gk.beCool();                                  //clothing has been update to current fashionable levels 
	gk.clothing="Pimp Outfit";                    //clothing is a public variable that can be updated to any funky value 
	gk.shower();
	alert("Existing shower technology has gotten "+gk+" to a dirt factor of "+gk.dirtFactor);

	gk.muchTimePasses();                          //50 Years Pass 
	Person.prototype.shower=function(){           //Shower technology improves for everyone 
		this.dirtFactor=0;
	} 
	gk.beCool=function(){                         //Gavin alone gets new fashion ideas 
		this.clothing="tinfoil";
	};

	gk.beCool(); gk.shower();
	alert("Fashionable "+gk+" at " 
		+gk.getAge()+" years old is now wearing " 
		+gk.clothing+" with dirt factor " 
		+gk.dirtFactor);

	gk.amputate();                                //Uses the prototype property and makes a public property 
	gk.showLegs(); lk.showLegs();                 //Lisa still has the prototype property 

	gk.muchTimePasses();                          //50 Years Pass...Gavin is now over 100 years old. 
	gk.eat();                                     //Complains about extreme age, death, and inability to eat. 
}
```


Behind a prototype "pure" object there is that :

```
Function.prototype = {
    arguments: null,
    length: 0,
    call: function(){
        // secret code
    },
    apply: function(){
        // secret code
    }
    ...
}
```

That's well explained here :
http://net.tutsplus.com/tutorials/javascript-ajax/prototypes-in-javascript-what-you-need-to-know/

## "hoists" variables ##

  * http://synonym.dartlang.org/

```
// JavaScript "hoists" variables to the top of
// their scope.  So the following function:
function printName() {
  console.log('Hello, ' + name);
  var name = 'Bob';
}
// is equivalent to this function:
function printName() {
  var name;
  console.log('Hello, ' + name);
  name = 'Bob';
}
```

## CLOSURE ##

```

// You don’t need to make a separate counter object and call its
// increment method (as you would in Java) – just make a function
// that has private state through its closure

var getUniqueName = (function(time) {
    return function(prefix) {
        return (prefix ? prefix : 'id_') + time++;
    }
})((new Date()).getTime());

getUniqueName(); //returns id_1337814984218
getUniqueName('toto_'); //returns toto_1337814984219
getUniqueName(); //returns id_1337814984220

// Idem

var counter = (function (i) {
  return function counter () { return ++i }
})(0);


// Closures by example

function CreateAdder(add) {
   return function(value) {
       return value+add;
   }
}

var myAdder5 = CreateAdder( 5 );
var myAdder6 = CreateAdder( 6 );
Print( myAdder5( 2 ) ); // prints 7
Print( myAdder6( 4 ) ); // prints 10
```

From http://net.tutsplus.com/tutorials/javascript-ajax/closures-front-to-back/
Using Closures To Extend The Language
At this point, it should be relatively easy to see that closures are vital to writing top notch JavaScript. Let’s apply what we know about closures to augmenting one of JavaScript’s native types (gasp!). With our focus on function objects, let’s augment the native Function type:

```
Function.prototype.cached = function() {
   var self = this, //"this" refers to the original function
      cache = {}; //our local, lexically scoped cache storage
   return function(args) {
      if(args in cache) return cache[args];
      return cache[args] = self(args);
   };
};
```

This little gem allows any and every function to create a cached version of itself. You can see the function returns a function itself, so this enhancement can be applied and used like so:

```
Math.sin = Math.sin.cached();
Math.sin(1) // => 0.8414709848078965
Math.sin(1) // => 0.8414709848078965 this time pulled from cache
```

## Constructor ##

```
function toto(val) { // parent class
        this.print = function() {
                Print(val);
        }
}

titi.prototype = new toto;

function titi(val) { // child class
        this.constructor(val);
}

(new titi(7)).print(); // prints 7
```

## SINGLETON PATERN ##

```
// http://www.addyosmani.com/resources/essentialjsdesignpatterns/book/

var SingletonTester = (function(){
 
  //args: an object containing arguments for the singleton
  function Singleton(args) {
 
   //set args variable to args passed or empty object if none provided.
    var args = args || {};
    //set the name parameter
    this.name = 'SingletonTester';
    //set the value of pointX
    this.pointX = args.pointX || 6; //get parameter from arguments or set default
    //set the value of pointY
    this.pointY = args.pointY || 10;  
 
  }
   
 //this is our instance holder
  var instance;
 
 //this is an emulation of static variables and methods
  var _static = {
    name: 'SingletonTester',
   //This is a method for getting an instance
 
   //It returns a singleton instance of a singleton object
    getInstance: function (args){
      if (instance === undefined) {
        instance = new Singleton(args);
      }
      return instance;
    }
  };
  return _static;
})();
 
var singletonTest = SingletonTester.getInstance({pointX: 5});
console.log(singletonTest.pointX); // outputs 5
```

## PROTOTYPE INHERANCE ##

```
// Employee inherit from human... the custom way

// Define Human class
function Human() {
    this.setName = function (fname, lname) {
        this.fname = fname;
        this.lname = lname;
    }
    this.getFullName = function () {
        return this.fname + " " + this.lname;
    }
}
 
// Define the Employee class
function Employee(num) {
    this.getNum = function () {
        return num;
    }
};
// Let Employee from Human
Employee.prototype = new Human();
 
// Instantiate an Employee object
var john = new Employee("4815162342");
john.setName("John", "Doe");
alert(john.getFullName() + "'s employee number is " + john.getNum());
```

## Class Inheritance ##

```
var Animal = function(opts){}
Animal.prototype.talk   = function(){ return 'mumble';  }
Animal.prototype.sleep  = function(){ return 'zzzzz';   }

var Cat = function(opts){
    // call the parent class constructor (one line)
    Animal.call(this, opts)
}

// inherit from Animal methods (two lines)
Cat.prototype = new Animal();
Cat.prototype.constructor = Animal;

// override talk method
Cat.prototype.talk = function(){
    return "maow"
}

var cat = new Cat()
console.log("cat sleep as ", cat.sleep()); // cat sleep as zzzzz
console.log("cat talk as ", cat.talk()); // cat talk as maow

console.log("Cat instanceof Cat", cat instanceof Cat); // Cat instanceof Cat true
console.log("Cat instanceof Animal", cat instanceof Animal); // Cat instanceof Animal true

```

## PROTOTYPE OBSERVER IMPLEMENTATION ##

From http://www.addyosmani.com/resources/essentialjsdesignpatterns/book/
```
function Observer(){
    this.functions = [];
}

Observer.prototype = {
    subscribe : function(fn) {
        this.functions.push(fn);
    },
    
    unsubscribe : function(fn) {
        this.functions = this.functions.filter(
            function(el) {
                if ( el !== fn ) {
                    return el;
                }
            }
        );
    },
    
    update : function(o, thisObj) {
        var scope = thisObj || window;
        this.functions.forEach(
            function(el) {
                el.call(scope, o);
            }
        );
    }
};

// Subscribing and publishing

// Publishers are in charge of "publishing" eg: Creating the Event
// They're also in charge of "notifying" (firing the event)
var obs = new Observer;
obs.update('here is some test information');

// Subscribers basically... "subscribe" (or listen)
// And once they've been "notified" their callback functions are invoked
var fn = function() {
    // my callback stuff
};
obs.subscribe(fn);

// Unsubscribe if you no longer wish to be notified
obs.unsubscribe(fn);
```

## MICRO EVENTS ##

```
/**
 * microevents.js - https://github.com/jeromeetienne/microevent.js
*/
tQuery.MicroeventMixin	= function(destObj){
	destObj.bind	= function(event, fct){
		if(this._events === undefined) 	this._events	= {};
		this._events[event] = this._events[event]	|| [];
		this._events[event].push(fct);
		return fct;
	};
	destObj.unbind	= function(event, fct){
		if(this._events === undefined) 	this._events	= {};
		if( event in this._events === false  )	return;
		this._events[event].splice(this._events[event].indexOf(fct), 1);
	};
	destObj.trigger	= function(event /* , args... */){
		if(this._events === undefined) 	this._events	= {};
		if( this._events[event] === undefined )	return;
		var tmpArray	= this._events[event].slice(); 
		for(var i = 0; i < tmpArray.length; i++){
			tmpArray[i].apply(this, Array.prototype.slice.call(arguments, 1))
		}
	}
};

```

## FACADE ##

  * http://www.addyosmani.com/futureproofjs/

```
var module = (function() {
   var _private = {
		i:5,
		get: function() {
		   console.log('current : '+this.i);
		},
		set: function(val) {
		   this.i = val;
		}
   };
   return {
	   facade : function(args) {
		   _private.set(args.val);
		   _private.get()
	   }
   }
}());

module.facade({run:true, val:10});
```

## OBSERVABLE MIXIN (and Constructor Functions) ##

From : http://peter.michaux.ca/articles/mixins-and-constructor-functions
JSFIddle : http://jsfiddle.net/molokoloco/rNTvw/

```
var observableMethods = function() {

    this.observers = [];

    this.observe = function(observer) {
        this.observers.push(observer);
    };

    this.notify = function(data) {
        for (var i = 0, ilen = this.observers.length; i < ilen; i++) {
            this.observers[i](data);
        }
    }
};

var person = {
    name: 'Steve',
    setName: function(name) {
        var oldName = this.name;
        this.name = name;
        this.notify({
            oldName: oldName,
            newName: this.name
        });
    }
};

observableMethods.call(person);
person.observe(function(data) {
    document.write(data.oldName + ' was renamed to ' + data.newName);
});

person.setName('Sarah');​
```

## New (JS Keyword ##

  * http://javascriptplayground.com/blog/2012/12/the-new-keyword-in-javascript
  * https://developer.mozilla.org/en-US/docs/JavaScript/Reference/Global_Objects/Object/constructor


## THIS is what ? ##

  * http://henrycode.tumblr.com/post/37627169791/javascript-clarifying-the-keyword-this

```
var a = {
    b: function() {
        return this;
    }
};

// Invoke a property
a.b(); // a

var c = {};
c.d = a.b;
c.d(); // c

// Invoke a variable
var foo = a.b;
foo(); // window

// Invoke using Function.prototype.apply
a.b.apply(d); // d

// Invoke from a variable
var a = {
    b: function() {
        var c = function() { return this; }
        return c();
    }
};

a.b(); //window

// The same applies to self invoking functions
var a = {
    b: function() {
        return (function() { return this; })()
    }
};

a.b(); //window
```

## Stats analytics de manière asynchrone ##

```

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-XXXXX-X']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script');
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    ga.setAttribute('async', 'true');
    document.documentElement.firstChild.appendChild(ga);
  })();

```


## Simulate threads ##

> using yield operator (JavaScript 1.7)

```
//// thread definition
function Thread( name ) {
    for ( var i = 0; i < 5; i++ ) {
        Print(name+': '+i);
        yield;
    }
}

//// thread management
var threads = [];

// thread creation
threads.push( new Thread('foo') );
threads.push( new Thread('bar') );

// scheduler
while (threads.length) {
    var thread = threads.shift();
    try {
        thread.next();
        threads.push(thread);
    } catch(ex if ex instanceof StopIteration) {}
}
```

prints :

foo: 0
bar: 0
foo: 1
bar: 1
foo: 2
bar: 2
foo: 3
bar: 3
foo: 4
bar: 4

## STRINGS ##

```
Chaine.anchor("nom_a_donner"); Transforme le texte Chaine en ancrage HTML.
Chaine.big() Augmente la taille de la police.
Chaine.blink() Transforme la chaîne en texte clignotant.
Chaine.bold() Met le texte en gras (balise <B>).
Chaine.charAt(position) Retourne le caractère situé à la position donnée en paramètre
Chaine.charCodeAt(position) Renvoie le code Unicode du caractère situé à la position donnée en paramètre
concat(chaîne1, chaîne2[, ...]) Permet de concaténer les chaînes passées en paramètre, c est-à-dire de les joindre bout à bout.
Chaine.fixed() Transforme la Chaine en caractères de police fixe (balise <TT>)
Chaine.fontcolor(couleur) Modifie la couleur du texte (admet comme argument la couleur en hexadécimal ou en valeur littérale)  
Chaine.fontsize(Size) Modifie la taille de la police, en afectant la valeur passée en paramètre
Chaine.fromCharCode(code1[, code2, ..]) Renvoie une chaîne de caractères composée de caractères correspondant au(x) code(s) Unicode donné(s) en paramètre.
Chaine.indexOf(sous-chaîne, position) Retourne la position d une sous-chaîne (lettre ou groupe de lettres) dans une chaîne de caractère, en effectuant la recherche de gauche à droite, à partir de la position spécifiée en paramètre.
Chaine.italics() Transforme le texte en italique (balise <I>)
Chaine.lastIndexOf(sous-chaîne, position) La méthode est similaire à indexOf(), à la différence que la recherche se fait de droite à gauche:
Retourne la position d une sous-chaîne (lettre ou groupe de lettres) dans une chaîne de caractère, en effectuant la recherche de droite à gauche, à partir de la position spécifiée en paramètre.
Chaine.link(URL) Transforme le texte en hypertexte (balise <A href>)
Chaine.small() Diminue la taille de la police
Chaine.strike() Transforme le texte en texte barré (balise <strike>)
Chaine.sub() Transforme le texte en indice (balise <sub>)
Chaine.substr(position1, longueur) La méthode retourne une sous-chaîne commençant à l index dont la position est donnée en argument et de la longueur donnée en paramètre.
Chaine.substring(position1, position2) La méthode retourne la sous-chaîne (lettre ou groupe de lettres) comprise entre les positions 1 et 2 données en paramètre.
Chaine.sup() Transforme le texte en exposant (balise <sup>).
Chaine.toLowerCase() Convertit tous les caractères d une chaîne en minuscule.
Chaine.toSource() Renvoie le code source de création de l objet.
Chaine.toUpperCase() Convertit tous les caractères d une chaîne en majuscule.
Chaine.valueOf() Renvoie la valeur de l objet String.
```

## Built-In Functions ##

```
escape()     Many special characters cause problems when submitting information to a 0CGI server. These characters include $ # ! spaces and tabs. An example of a safely encoded string is coded = escape('& '). [returns "%26%20"]. See also unescape.
eval()     Evaluates a string and returns a numeric value. An example is eval(x) + eval(y); If x and y are strings '1' and '2' the result is three. Without the eval function the value would be 12!
isFinite()     Tests whether the variable is a finite number. Returns false if contents is not a number or is infinite. An example of usage is flag = isFinite(var_name);
isNaN()     Tests whether the variable is not a number. Returns true if contents is not a decimal based number. An example of usage is flag = isNaNum(var_name);
Number()     Converts the object argument to a number representing the object s value. If the value cannot be represented by a legitimate number, the "Not-a-Number" value, NaN is returned.Note the inconsistency in case!!
parseFloat()     Turns a string into a floating-point number. If the first character can t be converted the result is NaN. Otherwise the conversion stops at first character that can t be converted. The function is decimal base only! an example of usage is n = parseFloat('23.45');
parseInt()     Turns a string into an integer number. If the first character can t be converted the result is NaN. Otherwise the conversion stops at first character that can t be converted. If a second parameter is included, the numeric base can be binary, octal, decimal or hexadecimal. An example is n = parseInt('55',10); which places the number 55 into the base 10 variable n.
unescape()     Recovers an escaped string. An example is decoded = unescape('%26%20') [returns "& "].

new     Creates a copy or instance of an object for use or modification within a program. For example now = new Date; creates a Date object called now that can be affected by all the Date properties and methods. Technically new is an operator but it works very much like a function!
this     A shorthand convention to allow working with the current object method without naming it. This is used to save retyping a method name.
with     A shorthand convention to allow working with the current object without naming it. This is used to save retyping a long object name. An example is:

with (document) {
	writeln(lastmodified);
	writeln(location);
	writeln(title);
}

```

## Objects Functions ##

  * Array Object
    * properties - constructor, length, prototype
    * methods - concat(), join(), pop(), push(), reverse(), shift(), slice(), sort(), splice(), toLocaleString(), toString(), unshift(), valueOf()
  * Boolean Object
    * properties - constructor, prototype
    * methods - toString(), valueOf()
  * Date Object
    * properties - constructor, prototype
    * methods - getDate(), getDay(), getFullYear(), getHours(), getMilliseconds(), getMinutes(), getMonth(), getSeconds(), getTime(), getTimeZoneOffset(), getUTCDate(), getUTCDay(), getUTCFullYear(), getUTCHours(), getUTCMilliseconds(), getUTCMinutes(), getUTCMonth(), getUTCSeconds(), getVarDate(), getYear(), parse(), setDate(), setFullYear(), setHours(), setMilliseconds(), setMinutes(), setMonth(), setSeconds(), setTime(), setUTCDate(), setUTCFullYear(), setUTCHours(), setUTCMilliseconds(), setUTCMinutes(), setUTCMonth(), setUTCSeconds(), setUTCTime(), setUTCYear(), setYear(), toLocaleString(), toUTCString(), toString(), UTC(), valueOf()
  * Error Object
    * properties - description, number
    * methods -
  * Function Object
    * properties - arguments[.md](.md), caller, constructor, prototype
    * methods - toString(), valueOf()
  * Global Object
    * properties - Infinity, NaN, undefined
    * methods - escape(), eval(), isFinite(), isNaN(), parseFloat(), parseInt(), unescape()
  * Math Object
    * properties - E, LN10, LN2, LOG10E, LOG2E, PI, SQRT2, SQRT1\_2
    * methods - abs(), acos(), asin(), atan(), atan2(), ceil(), cos(), exp(), floor(), log(), max(), min(), pow(), random(), round(), sin(), sqrt(), tan()
  * Number Object
    * properties - MAX\_VALUE, MIN\_VALUE, NaN, NEGATIVE _INFINITY, POSITIVE\_INFINITY, constructor, prototype
    * methods - toLocaleString(), toString(), valueOf()
  * Object Object
    * properties - constructor, prototype
    * methods - toLocaleString(), toString(), unwatch(), valueOf(), watch()
  * RegExp Object
    * properties - $1 - $9, index, input, lastIndex, lastMatch, lastParen, leftContext, rightContext
  * String Object
    * properties - constructor, length, prototype
    * display methods - anchor(), big(), blink(), bold(), fixed(), fontcolor(), fontsize(), italics(), small(), strike(), sub(), sup()
    * manipulation methods - charAt(), charCodeAt(), concat(), fromCharCode(), indexOf(), lastIndexOf(), link(), match(), replace(), search(), slice(), split(), substr(), substring(), toLowerCase(), toUpperCase(), toString(), valueOf()_

  * Document Object - Primary Output
    * content properties - anchors[.md](.md), applets[.md](.md), embeds[.md](.md), forms[.md](.md), images[.md](.md), links [.md](.md), plugins[.md](.md)
    * display properties - alinkColor, bgColor, fgColor, linkColor, vlinkColor
    * information properties - cookie, domain, lastModified, location, mimeTypes, referrer, title, URL
    * methods - open(), write(), writeln()
  * Form Objects - Primary Input
    * form properties - name, target, action, method, encoding, elements, length
    * form methods - submit(), reset()
    * button, hidden, reset, and submit object properties - name, value, type
    * checkbox properties - name, value, type, checked, default checked
    * password properties - name, value, type, default value
    * radio button properties - name, value, type, checked, default checked, length
    * select properties - name, value, type, length, options, defaultSelected, index, selected, selectedIndex
    * text, textarea properties - name, value, defaultvalue
    * input, textarea and select methods - focus(), blur(), select()
  * History Object - Access the browser s history file
    * properties - length
    * methods - back(), forward(), go()
  * Location Object
    * properties - hash, hostname, href, pathname, port, protocol, search
    * methods - reload(), replace()
  * Navigator Object - Root of all Objects
    * properties - appCodeName, appName, appVersion, platform, userAgent
    * methods - javaEnabled()
  * Window Object
    * properties - defaultStatus, frames, length, name, opener, parent, self, status, top, window
    * methods - alert(), blur(), clearInterval(), clearTimeout(), close(), confirm(), focus(), open(), print(), prompt(), scroll(), setInterval(), setTimeout(), timeoutID()
    * contained objects -
      * Screen Object
      * properties - availHeight, availLeft, availTop, availWidth, colorDepth, height, pixelDepth, width


## HANDLERs ##

Interface Event Handlers:

  * onBlur [window, frame, select, text, textarea]     focus is lost (ie. changed or blurred) to a new element.
  * onFocus [window, frame, select, text, textarea]     object gains focus. aka anti-blur!
  * onLoad [window, frame, image]     window, complete frame or image(s) finishes loading.
  * onResize     a window or object(MSIE only) is resized.
  * onScroll     window scrolled with scrollbar or mousewheel.
  * onUnload [window, frame]     window or all windows in a frame have been exited.

Key Event Handlers:

  * nHelp     GUI F1 key pressed. Used to override browser Help.
  * onKeydown     alphanumeric key is pressed.
  * onKeypress     alphanumeric key is fully pressed/released.
  * onKeyup     alphanumeric key is released.
  * onStop     GUI STOP key is pressed or user leaves page.

Mouse Event Handlers:

  * Event Name     Handler Executes When
  * onClick [form element](clickable.md)     left mouse clicks om element. Can be stopped if executing
  * procedure (such as validation) returns a false signal.
  * onContextmenu     right mouse button clicked.
  * onDblclick     mouse button is double-clicked.
  * onMousedown     either mouse button is clicked.
  * onMousemove     mouse is moved.
  * onMouseOut [link, area]     cursor leaves a link or area.
  * onMouseOver [link, area]     cursor enters a link or area.
  * 
Form Event Handlers:

  * Event Name     Handler Executes When
  * onChange [select, text, textarea]     last element has been changed before focus change.
  * onReset [form](form.md)     the reset button is clicked.
  * onSelect [text, textarea]     some text is highlighted in either of these form boxes.
  * onSubmit [form](form.md)     Executes after return key is pressed or submit button is clicked.
  * Allows bailout similar to the onclick event. Failure in validation
  * routine is the most common reason for bailout.
  * 
Miscellaneous Event Handlers:

  * Event Name     Handler Executes When
  * onAbort [image](image.md)     image load has been abandoned by hitting the STOP icon.
  * onError [window, image]     window or image fails to load.


## Pre-Loading ##



&lt;link rel="prefetch" href="http://www.webtutorialplus.com/html5-dns-content-prefetching/" /&gt;





&lt;link rel="prerender" href="http://www.webtutorialplus.com/html5-dns-content-prefetching/" /&gt;



## unEscapeHtml ##

```

// No need to remove the child text node, as the garbage collector (GC memory manager) will.
String.prototype.unescapeHtml= function() {
    var t = document.createElement('div');
    t.innerHTML = this;
    return t.firstChild.nodeValue;
}
var hello = "Hello Jos&eacute;";
alert(hello.unescapeHtml());
```

Escape HTML

```
var el = document.createElement("i"),
    textNode = document.createTextNode("");

el.appendChild(textNode);

function escapeHTMLDOM(text){
  textNode.nodeValue = text
  return el.innerHTML
}
```

## DEBOUNCING EVENTS ##

https://github.com/WickyNilliams/headroom.js/blob/master/src/Debouncer.js

```
window.requestAnimationFrame = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame;

/**
 * Handles debouncing of events via requestAnimationFrame
 * @see http://www.html5rocks.com/en/tutorials/speed/animations/
 * @param {Function} callback The callback to handle whichever event
 */
function Debouncer (callback) {
  this.callback = callback;
  this.ticking = false;
}
Debouncer.prototype = {
  constructor : Debouncer,

  /**
   * dispatches the event to the supplied callback
   * @private
   */
  update : function() {
    this.callback && this.callback();
    this.ticking = false;
  },

  /**
   * ensures events don't get stacked
   * @private
   */
  requestTick : function() {
    if(!this.ticking) {
      requestAnimationFrame(this.rafCallback || (this.rafCallback = this.update.bind(this)));
      this.ticking = true;
    }
  },

  /**
   * Attach this as the event listeners
   */
  handleEvent : function() {
    this.requestTick();
  }
};
```
## DEBUG and CONSOLE ##

```

// Pour debugger X variables dans la console du navigateur...
// "Ctrl + Maj + C" pour ouvrir la console

var db = function() { 'console' in window && console.log.call(console, arguments); };

db('Test', window);

var C = Function.prototype.bind.call(console.log, console);
C('test');

// Well ok, somebody got better ? ^^ // http://html5boilerplate.com/
window.log = function(){
  log.history = log.history || [];  
  log.history.push(arguments);
  arguments.callee = arguments.callee.caller;  
  if(this.console) console.log( Array.prototype.slice.call(arguments) );
};
(function(b){function c(){}for(var d="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),a;a=d.pop();)b[a]=b[a]||c})(window.console=window.console||{});

```

```
console.log('Niveau 1');
console.group('Voilà un groupe');
console.log('Niveau 2');
console.log('Niveau 2 toujours');
console.group('Voilà un sous-groupe !');
console.log('Niveau 3');
console.info('Niveau 3');
console.warn('Niveau 3');
console.groupEnd();
console.groupEnd();
console.log("De retour au niveau 1 ");
```

# ES5 compatibility table #

  * http://kangax.github.com/es5-compat-table/

```
Object.create
Object.defineProperty
Object.defineProperties
Object.getPrototypeOf
Object.keys
Object.seal
Object.freeze
Object.preventExtensions
Object.isSealed
Object.isFrozen
Object.isExtensible
Object.getOwnPropertyDescriptor
Object.getOwnPropertyNames
Date.prototype.toISOString
Date.now
Array.isArray
JSON
Function.prototype.bind
String.prototype.trim
Array.prototype.indexOf
Array.prototype.lastIndexOf
Array.prototype.every
Array.prototype.some
Array.prototype.forEach
Array.prototype.map
Array.prototype.filter
Array.prototype.reduce
Array.prototype.reduceRight
Getter in property initializer
Setter in property initializer
Property access on strings
Reserved words as property names
```

### Compare object ###

https://github.com/prettycode/Object.identical.js/blob/master/Object.identical.js

```
/*
    Original script title: "Object.identical.js"; version 1.12
    Copyright (c) 2011, Chris O'Brien, prettycode.org
    http://github.com/prettycode/Object.identical.js
*/

Object.identical = function (a, b, sortArrays) {
        
    function sort(object) {
        if (sortArrays === true && Array.isArray(object)) {
            return object.sort();
        }
        else if (typeof object !== "object" || object === null) {
            return object;
        }

        return Object.keys(object).sort().map(function(key) {
            return {
                key: key,
                value: sort(object[key])
            };
        });
    }
    
    return JSON.stringify(sort(a)) === JSON.stringify(sort(b));
};
```

# Math with JS - Circalize - COS/SIN #

  * http://fightcodegame.com/profile/molokoloco/

```
Robot.prototype.angleToPoint = function(mePos, newPos){
  	var xDiff = mePos.x - newPos.x,
        yDiff = mePos.y - newPos.y,
        rads  = Math.atan2(-yDiff, xDiff);
  	return rads * (180 / Math.PI);
};

Robot.prototype.distToPoint = function(mePos, newPos){
  	var xDiff = mePos.x - newPos.x,
  	    yDiff = mePos.y - newPos.y,
  	    hyp   = Math.sqrt(Math.pow(yDiff, 2) + Math.pow(xDiff, 2));
	return hyp;
};
```

Position of a point on a circle, given an angle

```
var posX = centerX + (rayon + 20) * Math.cos(angle / 180 * Math.PI),
    posY = centerY + (rayon + 20) * Math.sin(angle / 180 * Math.PI);
```

jQuery Circalize plugin for CLOCK
http://jsfiddle.net/molokoloco/V2rFN/

```
increase = (Math.PI * 2) / $targets.length, // Rad cheeseCake  
angle = Math.PI * (options.startAngle / 180); // convert from DEG to RAD
```

### Micro Templating ###

http://ejohn.org/blog/javascript-micro-templating/

```
// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed
(function(){
  var cache = {};
 
  this.tmpl = function tmpl(str, data){
    // Figure out if we're getting a template, or if we need to
    // load the template - and be sure to cache the result.
    var fn = !/\W/.test(str) ?
      cache[str] = cache[str] ||
        tmpl(document.getElementById(str).innerHTML) :
     
      // Generate a reusable function that will serve as a template
      // generator (and which will be cached).
      new Function("obj",
        "var p=[],print=function(){p.push.apply(p,arguments);};" +
       
        // Introduce the data as local variables using with(){}
        "with(obj){p.push('" +
       
        // Convert the template into pure JavaScript
        str
          .replace(/[\r\t\n]/g, " ")
          .split("<%").join("\t")
          .replace(/((^|%>)[^\t]*)'/g, "$1\r")
          .replace(/\t=(.*?)%>/g, "',$1,'")
          .split("\t").join("');")
          .split("%>").join("p.push('")
          .split("\r").join("\\'")
      + "');}return p.join('');");
   
    // Provide some basic currying to the user
    return data ? fn( data ) : fn;
  };
})();
```

You would use it against templates written like this (it doesn’t have to be in this particular manner – but it’s a style that I enjoy):

```
<script type="text/html" id="item_tmpl">
  <div id="<%=id%>" class="<%=(i % 2 == 1 ? " even" : "")%>">
    <div class="grid_1 alpha right">
      <img class="righted" src="<%=profile_image_url%>"/>
    </div>
    <div class="grid_6 omega contents">
      <p><b><a href="/<%=from_user%>"><%=from_user%></a>:</b> <%=text%></p>
    </div>
  </div>
</script>
```

## MS to whatever ##

```
var calc = function (m) {
    return function(n) { return Math.round(n * m); };
};
var stamp = {
    seconds: calc(1e3),
    minutes: calc(6e4),
    hours:   calc(36e5),
    days:    calc(864e5),
    weeks:   calc(6048e5),
    months:  calc(26298e5),
    years:   calc(315576e5)
};
console.log(stamp.days(1)) /// 86400000 ms == 1 day
```

## Time Ago ##

https://github.com/iatek/jquery-socialist/blob/master/jquery.socialist.js


```
var timeAgo = function(date_str) {
	date_str = date_str.replace('+0000','Z');
	var time_formats = [
		[60, 'just now', 1],
		[120, '1 minute ago', '1 minute from now'],
		[3600, 'minutes', 60], 
		[7200, '1 hour ago', '1 hour from now'],
		[86400, 'hours', 3600], 
		[172800, 'yesterday', 'tomorrow'], 
		[604800, 'days', 86400], 
		[1209600, 'last week', 'next week'], 
		[2419200, 'weeks', 604800], 
		[4838400, 'last month', 'next month'], 
		[29030400, 'months', 2419200], 
		[58060800, 'last year', 'next year'], 
		[2903040000, 'years', 29030400], 
		[5806080000, 'last century', 'next century'], 
		[58060800000, 'centuries', 2903040000] 
	];
	var time = ('' + date_str).replace(/-/g,"/").replace(/[TZ]/g," ").replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	if (time.substr(time.length-4,1) == ".") time = time.substr(0, time.length-4);
	var seconds = (new Date - new Date(time)) / 1000;
	var token = 'ago', list_choice = 1;
	if (seconds < 0) {
		seconds = Math.abs(seconds);
		token = 'from now';
		list_choice = 2;
	}
	var i = 0, format;
	while (format = time_formats[i++])
		if (seconds < format[0]) {
			if (typeof format[2] == 'string')
				return format[list_choice];
			else
				return Math.floor(seconds / format[2]) + ' ' + format[1] + ' ' + token;
		}
	return time;
};
```

### Konami Code ###
https://twitter.com/molokoloco/status/501753473747415040

```
// FULL

var s = [],
    k = '38,38,40,40,37,39,37,39,66,65', // ↑↑↓↓←→←→BA
    p = function(e) {
        s.push(e.which);
        if (s.join(',').indexOf(k) >= 0) {
            $(document).off('keydown', p);
            alert('Konami'); 
        }
    };
$(document).on('keydown', p);

// SHORT
a='',onkeydown=function(e){a+=e.which;/38384040373937396665/.test(a)&&(a=[],alert("Konami"))}
```