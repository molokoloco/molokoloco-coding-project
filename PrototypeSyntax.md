_Library edited from 2005 to 2007..._ Framework here : [trunk/SITE\_01\_SRC/js/proto/optional/](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC#SITE_01_SRC%2Fjs%2Fproto%2Foptional)

# Prototype 1.8 DOC #

http://prototypejs.org/api/

```
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// PROTOTYPE // http://prototypejs.org/api/  ////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    +-> http://wiki.script.aculo.us/scriptaculous/show/Prototype
    +-> http://prototypejs.org/api
    +-> http://www.sergiopereira.com/articles/prototype.js.html

// UTILITY METHODS /////////////////////////////////////////////////////////////////////////////////////

    * $(id | element) // If provided with a string, returns the element in the document with matching ID; otherwise returns the passed element
    * $$(cssRule...) // Takes an arbitrary number of CSS selectors (strings) and returns a document-order array of extended DOM elements that match any of them
      ALIAS : document.getElementsByClassName(className[, element]) // Retrieves (and extends) all the elements that have a CSS class name of className. The optional element parameter specifies a parent element to search under
    * $A(iterable) // Accepts an array-like collection (anything with numeric indices) and returns its equivalent as an actual Array object
    * $F(element) // Returns the value of a form control
    * $H([obj]) // Creates a Hash (which is synonymous to "map" or "associative array" for our purposes). A convenience wrapper around the Hash constructor
    * $R(start, end[, exclusive = false]) // Creates a new ObjectRange object
    * $w(String) // Splits a string into an Array, treating all whitespace as delimiters
    * Try.these(Function...) // Accepts an arbitrary number of functions and returns the result of the first one that doesn't throw an error
 

        // EX :
        var someNodeList = $('lstEmployees').getElementsByTagName('option');
        $A(someNodeList).each(function(node){
            alert(node.nodeName + ': ' + node.innerHTML);
        });
        
        // !!! EACH
        for (var index = 0, len = browsers.length; index < len; ++index) {
            if (userAgentStr.indexOf(browsers[index])!=-1) {
                browser = browsers[index];
                break;
            }
        }
        
        $$('div') // -> all DIVs in the document.  Same as document.getElementsByTagName('div')!
        $$('#contents') // -> same as $('contents'), only it returns an array anyway.
        $$('li.faux') // -> all LI elements with class 'faux'
        $$('#contents a[rel]') // -> all links inside the element of ID "contents" with a rel attribute
        $$('a[href="#"]') // -> all links with a href attribute of value "#" (eyeew!)
        $$('#navbar li', '#sidebar li') // -> all links within the elements of ID "navbar" or "sidebar"
    
        $$('img.divImg').each( function(e) {
            var Ename = e.getAttribute('id');
            Width = e.getAttribute('width');
            Height = e.getAttribute('height');
            new Effect.Scale(e, 60, {scaleMode:{originalWidth:Width,originalHeight:Height},scaleFrom:100});
        });
        
        $($('someElement').parentNode).hide()
        
        $w('apples bananas kiwis').each(function(fruit){
            var message = 'I like ' + fruit.
        })
        
        $("frm_inscription").getElements().invoke("observe", "change", function(event) {
          alert("hi");
        });
        
        return Try.these(
            function() { return new XMLHttpRequest() },
            function() { return new ActiveXObject('Msxml2.XMLHTTP') },
        )


// AJAX /////////////////////////////////////////////////////////////////////////////////////

    * new Ajax.Request(url[, options])
    * new Ajax.PeriodicalUpdater(container, url[, options])
    * new Ajax.Updater(container, url[, options])
    * Ajax.Responders.register(responder)
    
    // AJAX OPTIONS :
    // All requester object in the Ajax namespace share a common set of options and callbacks. Callbacks are called at various points in the life-cycle of a request, and always feature the same list of arguments

    * asynchronous true // Determines whether XMLHttpRequest is used asynchronously or not. Since synchronous usage is rather unsettling, and usually bad taste, you should avoid changing this. Seriously.
    * contentType 'application/x-www-form-urlencoded' // The Content-Type header for your request. You might want to send XML instead of the regular URL-encoded format, in which case you would have to change this.
    * encoding     'UTF-8' // The encoding for your request contents. It is best left as is, but should weird encoding issues arise, you may have to tweak it in accordance with other encoding-related parts of your page code and server side.
    * method     'post' // The HTTP method to use for the request. The other widespread possibility is 'get'. As a Ruby On Rails special, Prototype also reacts to other verbs (such as 'put' and 'delete' by actually using 'post' and putting an extra '_method' parameter with the originally requested method in there.
    * parameters     '' // The parameters for the request, which will be encoded into the URL for a 'get' method, or into the request body for the other methods. This can be provided either as a URL-encoded string or as any Hash-compatible object (basically anything), with properties representing parameters.
    * postBody     None // Specific contents for the request body on a 'post' method (actual method, after possible conversion as described in the method opt ion above). If it is not provided, the contents of the parameters option will be used instead.
    * requestHeaders // See text / Accept defaults to 'text/javascript, text/html, application/xml, text/xml, */*'
    
    // COMMON AJAX CALLBACK
    // When used on individual instances, all callbacks (except onException) are invoked with two parameters: the XMLHttpRequest object and the result of evaluating the X-JSON response header, if any (can be null).
    
    * onCreate // Triggered when the Ajax.Request object is initialized. This is after the parameters and the URL have been processed, but before first using the methods of the XHR object.
    * onComplete // Triggered at the very end of a request's life-cycle, once the request completed, status-specific callbacks were called, and possible automatic behaviors were processed.
    * onException // Triggered whenever an XHR error arises. Has a custom signature: the first argument is the requester (i.e. an Ajax.Request instance), the second is the exception object.
    * onFailure // Invoked when a request completes and its status code exists but is not in the 2xy family. This is skipped if a code-specific callback is defined, and happens before onComplete.
    * onInteractive // (Not guaranteed) Triggered whenever the requester receives a part of the response (but not the final part), should it be sent in several packets.
    * onLoaded // (Not guaranteed) Triggered once the underlying XHR object is setup, the connection open, and ready to send its actual request.
    * onLoading // (Not guaranteed) Triggered when the underlying XHR object is being setup, and its connection opened.
    * onSuccess // Invoked when a request completes and its status code is undefined or belongs in the 2xy family. This is skipped if a code-specific callback is defined, and happens before onComplete.
    * onUninitialized // (Not guaranteed) Invoked when the XHR object was just created.
    * onXYZ // With XYZ being an HTTP status code for the response...


        // EX :
        var url = '/proxy?url=' + encodeURIComponent('http://www.google.com/search?q=Prototype'); // notice the use of a proxy to circumvent the Same Origin Policy.
        new Ajax.Request(url, {
          method: 'get',
          onSuccess: function(transport) {
            var notice = $('notice');
            if (transport.responseText.match(/http:\/\/prototypejs.org/))
              notice.update('Yeah! You are in the Top 10!').setStyle({ background: '#dfd' });
            else
              notice.update('Damn! You are beyond #10...').setStyle({ background: '#fdd' });
          }
        });
        
        // "evalScripts" EVALUE LES SCRIPTS DANS LA PAGE APPELEE : ATTENTION AUX FONCTIONS : FORMAT CORRECT
        sayHi = function(){ alert('Hi'); };

        // ATTENTION A CHECKER : BUG SUR METHODE "POST" des UPDATES AJAX ?????????????????????
        
        var laRequete = new Ajax.Updater(
            'element',
            'getDerniersInscrits.php', {
                method: 'get',
                evalScripts:true,
                onLoading: $('toto').update('SAVING...'),
                parameters: Form.serialize('id_of_form_element'),
                insertion: Insertion.Before
            }
        );
        
        var laRequete = new Ajax.PerioducalUpdater(
            'element',
            'getDerniersMessages.php', {
                method: 'get',
                parameters: 'service=discussion',
                insertion: Insertion.Before,
                frequency: 5,
                decay: 1.3
            }
        );
        
        new Ajax.Request(url, {
          method: 'post',
          postBody: params,
          onComplete: function(transport) {
            if (transport.status == 200) {
                results = transport.responseText.split("\n");
                printInfo( "Données sauvegardées ;-) <br> "+results );
                mig_something_changed = false;
                window.location.reload();
            }
            else printInfo('There was a problem with the request.');
          }
        });
    
    
    transport. ....
    
        transport <object> [object XMLHttpRequest]  > transport.responseText
        options <object> [object Object]
        url <string> http://saintmarc/NOE/SITE_DEV/_actions.php?action=INITIATIVE
        method <string> post
        parameters <object> [object Object]
        body <string> nom_prenom=nom_prenom2&email=jguezennec%40proxitek.com&titre_init=titre_init2&description=description2&lien=lien2
        _complete <boolean> true


// JSON /////////////////////////////////////////////////////////////////////////////////////

        // EX :
        new Ajax.Request('/some_url', {
            method:'get',
            requestHeaders: {Accept: 'application/json'},
            onSuccess: function(transport){
                var json = transport.responseText.evalJSON(true);
            }
        });
        
        var data = '{name: 'Violet', occupation: 'character', age: 25 }';
        Object.toJSON(data);
        data == {"name":"Violet","occupation":"character","age":25};
        
        var data = '{ "name": "Violet", "occupation": "character" }'.evalJSON();
        data.name;
        //-> "Violet"


// ARRAY /////////////////////////////////////////////////////////////////////////////////////

    * clear // Clears the array (makes it empty).
    * clone // Returns a duplicate of the array, leaving the original array intact.
    * compact // Returns a new version of the array, without any null/undefined values.
    * each // Iterates over the array in ascending numerical index order.
    * first // Returns the first item in the array, or undefined if the array is empty.
    * flatten // Returns a "flat" (one-dimensional) version of the array
    * from // This is an alias for the $A() method
    * indexOf // Returns the position of the first occurrence of the argument within the array or -1
    * inspect // Returns the debug-oriented string representation of an array.
    * last // Returns the last item in the array, or undefined if the array is empty.
    * reduce // Reduces arrays: one-element arrays are turned into their unique element, while multiple-element arrays are returned untouched.
    * reverse // Returns the reversed version of the array
    * size // Returns the size of the array
    * toArray // This is just a local optimization of the mixed-in toArray from Enumerable.
    * toJSON // Returns a JSON string
    * uniq // Produces a duplicate-free version of an array
    * without // Produces a new version of the array that does not contain any of the specified values
    
        // EX :
        var myArray = [];
        myArray.each(function(item) {
            alert(item);
        });
        
        

// CLASS /////////////////////////////////////////////////////////////////////////////////////

    * create


// DATE /////////////////////////////////////////////////////////////////////////////////////

    * toJSON // Converts the date into a JSON string


// ELEMENTS // Element.Methods /////////////////////////////////////////////////////////////////////////////////////
    
    * addClassName // $('message').addClassName('read'); // Element.toggleClassName('message', 'read');
    * addMethods // Takes a hash of methods and makes them available as methods of extended elements and of the Element object.....
    * ancestors // Collects all of element's ancestors and returns them as an array of extended elements.
    * childElements // Returns element's children. This is an alias for immediateDescendants.
    * classNames // Returns a new instance of ClassNames, an Enumerable object used to read and write CSS class names of element.
    * cleanWhitespace // Removes all of element's text nodes which contain only whitespace. Returns element.
    * descendantOf // Checks if element is a descendant of ancestor.
    * descendants // Collects all of element's descendants and returns them as an array of extended elements.
    * down // Returns element's first descendant (or the n-th descendant if index is specified) that matches cssRule.......
    * empty // Tests whether element is empty (i.e. contains only whitespace).
    * extend // Extends element with all of the methods contained in Element.Methods.......
    * firstDescendant // Returns the first child that is an element.......
    * getDimensions // Finds the computed width and height of element and returns them as key/value pairs of an object
    * getElementsByClassName(element, className) // Fetches all of element's descendants which have a CSS class of className and returns them as an array of extended elements
    * getElementsBySelector(element, selector...) // Takes an arbitrary number of CSS selectors (strings) and returns a document-order array of extended children of element that match any of them
    * getHeight // Finds and returns the computed height of element
    * getStyle // Returns the given CSS property value of element. property can be specified in either of its CSS or camelized form
    * getWidth // Finds and returns the computed width of element.
    * hasClassName // Checks whether element has the given CSS className.
    * hide // Hides and returns element.
    * immediateDescendants // Collects all of the element's immediate descendants (i.e. children)
    * inspect // Returns the debug-oriented string representation of element
    * makeClipping // Simulates the poorly supported CSS clip property by setting element's overflow value to 'hidden'
    * makePositioned // Allows for the easy creation of CSS containing block by setting element's CSS position to 'relative' if its initial position is either 'static' or undefined
    * match(element, selector) // Checks if element matches the given CSS selector.
    * next(element[, cssRule][, index = 0]) // Returns element's following sibling (or the index'th one, if index is specified) that matches cssRule
    * nextSiblings // Collects all of element's next siblings and returns them as an array of extended elements.
    * observe(element, eventName, handler[, useCapture = false]) // Registers an event handler on element and returns element.
    * previous(element[, cssRule][, index = 0]) // Returns element's previous sibling (or the index'th one, if index is specified) that matches cssRule. If no cssRule is provided, all previous siblings are considered....
    * previousSiblings // Collects all of element's previous siblings and returns them as an array of extended elements.
    * readAttribute(element, attribute) // Returns the value of element's attribute or null if attribute has not been specified.
    * recursivelyCollect(element, property) // Recursively collects elements whose relationship is specified by property. property has to be a property (a method won't do!) of element that points to a single DOM node. Returns an array of extended elements
    * remove // Completely removes element from the document and returns it.
    * removeClassName(element, className) // Removes element's CSS className and returns element.
    
    $('task_link_'+id).className = 'portletTextOver';
    
    * replace(element[, html]) // Replaces element by the content of the html argument and returns the removed element.
    * scrollTo // Scrolls the window so that element appears at the top of the viewport. Returns element.
    * setStyle(element, styles) // Modifies element's CSS style properties. Styles are passed as a hash of property-value pairs in which the properties are specified in their camelized form
    * show // Displays and returns element.
    * siblings // Collects all of element's siblings and returns them as an array of extended elements.
    * stopObserving(element, eventName, handler) // Unregisters handler and returns element.
    * toggle // Toggles the visibility of element.
    * toggleClassName(element, className) // Toggles element's CSS className and returns element.
    * undoClipping // Sets element's CSS overflow property back to the value it had before Element.makeClipping() was applied
    * undoPositioned // Sets element back to the state it was before Element.makePositioned was applied to it
    * up([cssRule][, index = 0]) // returns element's first ancestor (or the index'th ancestor, if index is specified) that matches cssRule. If no cssRule is provided, all ancestors are considered....
    * update(element[, newContent]) // Replaces the content of element with the provided newContent argument and returns element. Parse JS
    * visible // Returns a Boolean indicating whether or not element is visible
    * hasAttribute(element, attribute) // Simulates the standard compliant DOM method hasAttribute for browsers missing it (Internet Explorer 6 and 7).
    
        Element.setAttribute(attribute, value)
    
        // EX :
        $('message').addClassName('read').update('I read this message!').setStyle({opacity: 0.5});
        new Effect.ScrollTo(formulaire, {offset: -16});
        
        
        $('el').setStyle({border:'none'});
        
        
        var MyUtils = {
            truncate: function(element, length){
                element = $(element);
                return element.update(element.innerHTML.truncate(length));

            },
        }
        Element.addMethods(MyUtils);
        
        $('explanation').truncate(100);
        
        var div_height = Element.getHeight(my_div);
        
        var my_div = document.createElement('div');
        Element.extend(my_div);
        my_div.addClassName('pending').hide();
        document.body.appendChild(my_div);
        
        var contact_data = $('contactform').serialize();
        var search_terms = $('search_input').getValue();
        
        idValue = idValue.gsub('media_', ''); // str_replace()
        
        $('someElement').parentNode.hide() // this will error out in IE:
        $($('someElement').parentNode).hide() // to make it cross-browser:
    
        $('infos').remove(); // Efface l'element
    
        var = 'http://monsite.fr/appli.php?id=5&rub=2'.toQueryParams(); // var contient maintenant { id: 5; rub: 2 }
        var = 'youpi-je-suis-un-lapin'.camelize(); // var contient "youpiJeSuisUnLapin"
        var = '<script>a = new Array();</script> lapin <script>b = new Array();</script>'.extractScripts() // var contient ["a = new Array();", "a = new Array();"]
        var = '<script>a = new Array();</script> lapin <script>b = new Array();</script>'.stripScripts() // var contient " lapin "
        
        var codeCouleur = (42).toColorPart(); // var contient "2a"
        var hexaColor = '#' + [128, 10, 16].invoke('toColorPart').join('');
        
        var pe = new PeriodicalExecuter(
            function() {
                alert("MECHANT LAPIN !");
            }, 69
        );


// POSITION /////////////////////////////////////////////////////////////////////////////////////

    * Position.absolutize // Turns element into an absolutely-positioned element without changing its position in the page layout.
    * Position.clone(source, target[, options]) // Clones the position and/or dimensions of source onto target as defined by the optional argument options.
    * Position.cumulativeOffset // Returns the offsets of element from the top left corner of the document.
    * Position.offsetParent // Returns element's closest positioned ancestor. If none is found, the body element is returned.
    * Position.overlap(mode, element) // Returns a Number between 0 and 1 corresponding to the proportion to which element overlaps the point previously defined by Position.within. mode can be set to either vertical or horizontal
    * Position.page // Returns the X/Y coordinates of element relative to the viewport.
    * Position.positionedOffset // Calculates the element's offset relative to its closest positioned ancestor (i.e., the element that would be returned by Position.offsetParent(element).
    * Position.prepare // Calculates document scroll offsets for use with Position.withinIncludingScrollOffsets.
    * Position.realOffset // Calculates the cumulative scroll offset of an element in nested scrolling containers.
    * Position.relativize // Turns element into an relatively-positioned element without changing its position in the page layout.
    * Position.within(element, x, y) // Indicates whether the point x, y (measured from the top-left corner of the document) is within the boundaries of element. Must be called immediately before Position.overlap.
    * Position.withinIncludingScrolloffsets(element, x, y) // Indicates whether the point x, y (measured from the top-left corner of the document) is within the boundaries of element. Used instead of Position.within whenever element is a child of a scrolling container. Must be called immediately before Position.overlap and immediately after Position.prepare.
    

// ENUMERABLE /////////////////////////////////////////////////////////////////////////////////////

    * all // http://prototypejs.org/api/enumerable
    * any
    * collect
    * detect
    * each
    * eachSlice
    * entries
    * find
    * findAll
    * grep
    * inGroupsOf
    * include
    * inject
    * invoke
    * map
    * max
    * member
    * min
    * partition
    * pluck
    * reject
    * select
    * size
    * sortBy
    * toArray
    * zip

// EVENT /////////////////////////////////////////////////////////////////////////////////////

    * element // Event.element(event) // Returns the DOM element on which the event occurred.
    * findElement // Event.findElement(event,tagName) // Returns the first DOM element with a given tag name, upwards from the one on which the event occurred.
    * isLeftClick // Event.isLeftClick(event) -> Boolean
    * observe // Event.observe(element, eventName, handler[, useCapture = false]) // Registers an event handler on a DOM element.
    * pointerX // Event.pointerX(event) -> Number // Returns the absolute horizontal position for a mouse event.
    * pointerY
    * stop // Event.stop(event)
    * stopObserving // Event.stopObserving(element, eventName, handler[, useCapture = false])
    * unloadCache // Event.unloadCache() // Unregisters all event handlers registered through observe. Automatically wired for you

        // EX :

        $(document).observe("dom:loaded", function() {
             /// ????                                    
        });
        
        swfobject.addDomLoadEvent(function() {
            // o_O //
        });
        
        function page_loaded(evt) {
            if (evt) Event.stop(evt);
        }
        Event.observe(window,'load',page_loaded,false);
        
        Event.observe(window,'load',function(event){
            //
        },false);
        
        
        Event.observe(picture, 'mouseout', function(event) {
            var rid = this.getAttribute("id");
            this.setAttribute('src',srcBak[rid]);
        });
        
        Event.observe(lwpgt, 'click', this.getGallery.bindAsEventListener(this));
        lwpgt.onclick = function() {return false;};
        
        Event.observe('myInput','keypress', function(event){ if (event.keyCode == Event.KEY_RETURN) sendMessage(); });
        
        
        
        window.formValues = '';
        stockFormValues = function(evt) {
            if (evt) Event.stop(evt);
            window.formValues = $('frm_compte').serialize();
        }
        haveChanges = function(evt) {
            if ($('frm_compte').serialize() != window.formValues) Event.stop(evt);
        }
        Event.observe(window, 'load', stockFormValues);
        Event.observe(window, 'beforeunload', haveChanges, true);
        
        
        
        $('frm_inscription').getElements().invoke('observe', 'change', function(event) {
            alert("hi");
        });
        
        
        /*    
        HANDLER
            Interface Event Handlers:
                onBlur [window, frame, select, text, textarea]     focus is lost (ie. changed or blurred) to a new element.
                onFocus [window, frame, select, text, textarea]     object gains focus. aka anti-blur!
                onLoad [window, frame, image]     window, complete frame or image(s) finishes loading.
                onResize     a window or object(MSIE only) is resized.
                onScroll     window scrolled with scrollbar or mousewheel.
                onUnload [window, frame]     window or all windows in a frame have been exited.
            
            Key Event Handlers:
                nHelp     GUI F1 key pressed. Used to override browser Help.
                onKeydown     alphanumeric key is pressed.
                onKeypress     alphanumeric key is fully pressed/released.
                onKeyup     alphanumeric key is released.
                onStop     GUI STOP key is pressed or user leaves page.
        
            Mouse Event Handlers:
                Event Name     Handler Executes When
                onClick [clickable form element]     left mouse clicks om element. Can be stopped if executing
                procedure (such as validation) returns a false signal.
                onContextmenu     right mouse button clicked.
                onDblclick     mouse button is double-clicked.
                onMousedown     either mouse button is clicked.
                onMousemove     mouse is moved.
                onMouseOut [link, area]     cursor leaves a link or area.
                onMouseOver [link, area]     cursor enters a link or area.
                
            Form Event Handlers:
                Event Name     Handler Executes When
                onChange [select, text, textarea]     last element has been changed before focus change.
                onReset [form]     the reset button is clicked.
                onSelect [text, textarea]     some text is highlighted in either of these form boxes.
                onSubmit [form]     Executes after return key is pressed or submit button is clicked.
                Allows bailout similar to the onclick event. Failure in validation
                routine is the most common reason for bailout.
            Miscellaneous Event Handlers:
                Event Name     Handler Executes When
                onAbort [image]     image load has been abandoned by hitting the STOP icon.
                onError [window, image]     window or image fails to load.
        */

// FORM /////////////////////////////////////////////////////////////////////////////////////

    * disable
    * enable
    * findFirstElement
    * focusFirstElement
    * getElements(formElement) // Returns a collection of all form controls within a form.
    * getInputs(formElement [, type [, name]]) // Returns a collection of all INPUT elements in a form. Use optional type and name arguments to restrict the search on these attributes.
    * request
    * reset
    * serialize(formElement[, getHash = false]) // Serialize form data to a string suitable for Ajax requests (default behavior) or, if optional getHash evaluates to true, an object hash where keys are form control names and values are data
    * serializeElements(elements[, getHash = false]) // Serialize an array of form elements to a string suitable for Ajax requests (default behavior) or, if optional getHash evaluates to true, an object hash where keys are form control names and values are data
    

// FORM ELEMENT /////////////////////////////////////////////////////////////////////////////////////

    * activate
    * clear // Clears the contents of a text input.
    * disable
    * enable
    * focus
    * getValue // Alias de $F()
    * present // Returns true if a text input has contents, false otherwise.
    * select // Selects the current text in a text input.
    * serialize // Creates an URL-encoded string representation of a form control in the name=value format.

        // EX :
        Form.Element.activate('myfield')
        Field.activate('myfield')
        $('myfield').activate()


    $("frm_inscription").getElements().invoke("observe", "change", function(event) {
      alert("hi");
    });



// FONCTION /////////////////////////////////////////////////////////////////////////////////////

    * bind(thisObj[, arg...]) // Wraps the function in another, locking its execution scope to an object specified by thisObj.
    * bindAsEventListener(thisObj[, arg...]) // An event-specific variant of bind which makes sure the function will recieve the current event object as the first argument when executing.
    
        // EX :
        window.name = 'I am such a beautiful window!';
        var obj = {
            name: 'A nice demo',
            fx: function() {
                alert(this.name);
            }
        };
        alert(obj.fx);
        //> I am such a beautiful window!
        alert(obj.fx.bind(obj));
        //> A nice demo


// HASH /////////////////////////////////////////////////////////////////////////////////////

    * each
    * inspect
    * keys
    * merge
    * remove
    * toJSON
    * toQueryString // Turns a hash into its URL-encoded query string representation.
    * values
    
        // EX :
        $H({ action: 'ship', order_id: 123, fees: ['fee1', 'fee2'] }).toQueryString()
        // -> action=ship&order_id=123&fees=fee1&fees=fee2


// INSERTION /////////////////////////////////////////////////////////////////////////////////////

    * After
    * Before
    * Bottom
    * Top

        // EX :
        // Insertion.Before
        <div id="cible">
        // Insertion.Top
        Contenu de élément cible
        // Insertion.Bottom
        </div>
        // Insertion.After
        
        <a href="#" onclick="new Insertion.Bottom('cible', '<li>Yet another list item!</li>'); return false;">Add another list item!</a>
        new Insertion.Top($$('body')[0], '<div>test</div>');


// NUMBER /////////////////////////////////////////////////////////////////////////////////////

    * succ // Returns the successor of the current Number, as defined by current + 1
    * times
    * toColorPart // roduces a 2-digit hexadecimal representation of the number
    * toJSON // Returns a JSON string.
    * toPaddedString(length[, radix]) // Converts the number into a string padded with 0s so that the string's length is at least equal to length. Takes an optional radix argument which specifies the base to use for conversion
    
        // EX :
        '#' + [128, 10, 16].invoke('toColorPart').join('');
        // -> '#800a10'


// PERIODICALEXECUTER /////////////////////////////////////////////////////////////////////////////////////
    
    * stop // Stops the periodical executer
    
        // EX :
        new PeriodicalExecuter(pollChatRoom, 3);

        new PeriodicalExecuter(function(pe) {
          if (!confirm('Want me to annoy you again later?')) pe.stop();
        }, 5);





// STRING /////////////////////////////////////////////////////////////////////////////////////

    * blank // Check if the string is 'blank', meaning either empty or containing only whitespace.
    * camelize // Converts a string separated by dashes into a camelCase equivalent. For instance, 'foo-bar' would be converted to 'fooBar'.
    * capitalize // Capitalizes the first letter of a string and downcases all the others.
    * dasherize // Replaces every instance of the underscore character ("_") by a dash ("-").
    * empty // Checks if the string is empty.
    * endsWith(substring) // Checks if the string ends with substring.
    * escapeHTML // Converts HTML special characters to their entity equivalents.
    * evalJSON([sanitize]) // Evaluates the JSON in the string and returns the resulting object. If the optional sanitize parameter is set to true, the string is checked for possible malicious attempts and eval is not called if one is detected.
    * evalScripts // Evaluates the content of any script block present in the string. Returns an array containing the value returned by each script.
    * extractScripts // Exctracts the content of any script block present in the string and returns them as an array of strings.
    * gsub(pattern, replacement) // Returns the string with every occurence of a given pattern replaced by either a regular string, the returned value of a function or a Template string. The pattern can be a string or a regular expression.
    * include(substring) // Check if the string contains a substring.
    * inspect([useDoubleQuotes = false]) // Returns a debug-oriented version of the string (i.e. wrapped in single or double quotes, with backslashes and quotes escaped).
    * parseQuery // Alias of toQueryParams.
    * scan(pattern, iterator) // Allows iterating over every occurrence of the given pattern (which can be a string or a regular expression). Returns the original string.
    * startsWith(substring) // Checks if the string starts with substring.
    * strip // Strips all leading and trailing whitespace from a string.
    * stripScripts // Strips a string of anything that looks like an HTML script block.
    * stripTags // Strips a string of any HTML tag.
    * sub(pattern, replacement[, count = 1]) // Returns a string with the first count occurrences of pattern replaced by either a regular string, the returned value of a function or a Template string. pattern can be a string or a regular expression.
    * succ
    * times(count) // Concatenates the string count times.
    * toArray // Splits the string character-by-character and returns an array with the result.
    * toJSON // Returns a JSON string.
    * toQueryParams([separator = '&']) // Parses a URI-like query string and returns an object composed of parameter/value pairs.
    * truncate([length = 30[, suffix = '...']]) // Truncates a string to the given length and appends a suffix to it (indicating that it is only an excerpt).
    * underscore // Converts a camelized string into a series of words separated by an underscore ("_").
    * unescapeHTML // Strips tags and converts the entity forms of special HTML characters to their normal form.
    * unfilterJSON([filter = Prototype.JSONFilter]) // Strips comment delimiters around Ajax JSON or JavaScript responses. This security method is called internally.


// TEMPLATE /////////////////////////////////////////////////////////////////////////////////////

    * evaluate

        // EX :
        var myTemplate = new Template('The TV show #{title} was created by #{author}.');
        var show = {title: 'The Simpsons', author: 'Matt Groening', network: 'FOX' };
        myTemplate.evaluate(show);


// TIME OBSERVER /////////////////////////////////////////////////////////////////////////////////////
    
    * Form.Element.Observer // new Form.Element.Observer(element, frequency, callback) // A timed observer for a specific form control.
    * Form.Observer // new Form.Observer(element, frequency, callback) // A timed observer that triggers when any value changes within the form.

        // EX :
        new Form.Element.Observer(
          'myelement',
          0.2,  // 200 milliseconds
          function(el, value){
            alert('The form control has changed value to: ' + value)
          }
        )


```