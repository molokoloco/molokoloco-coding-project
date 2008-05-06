<?php
/**
 * Node class file
 * @author Mathieu Lachance <lachance.mathieu@poincomm.com>
 * @link http://www.poincomm.com/
 * @copyright Copyright &copy; 2007 POINCOMM
 * @license http://creativecommons.org/licenses/by/2.5/ca/
 * @version 1.1 2007-02-15 21h00
 */

/**
 * Class Node
 * The Node class is used to generate a tree architecture.
 * Each Node contains :
 * - a name, wich is not necessarly unique to the tree, the $name protected property
 * - the reference of his parent, the $parent protected property
 * - an integer indexed array containing all the references of the child nodes, the $child protected property
 * 
 * When adding a new Node with the addChild method, the node class generate a public user defined property called
 * as same as the new Node name.
 * Therefore, this kind of dynamic property can be accessed freely with the powerfull syntax :
 * - $tree->nodeName->... when there is only one node named nodeName
 * - $tree->nodeName[index]->... when there is more than one node named nodeName
 * 
 * Moreover, each node can be exported as xml with the use of the export method.
 * They can be re-imported from xml with the combine use of SimpleXML extension and the import method.
 * - TO DO : find a better way than using SimpleXML extension
 */
class Node
{
  /**
  * @var string $name the node name
  */
  protected $name = null;
  /**
  * @var Node $parent a reference to the parent node
  */
  protected $parent = null;
  /**
  * @var array $childs an integer indexed array containing all the references to the child nodes
  */
  protected $childs = null;
  /**
  * @var int $count the number of child nodes
  */
  protected $count = null;
  
  /**
   * Constructor
   * @param string $name the node name
   */
  public function __construct($name = null){
    $this->name = (string)$name;            // ensure the $name is a string an assign it to the node name
    $this->childs = array();                // initialise the childs array
    $this->count = 0;                       // initialise the count of the childs array
  }
  
  /**
   * Wether :
   * - create a public user defined property called by the node $n name and
   *   assign the node $n to this user defined property
   * - append the node $n to the public user defined property called by the node $n name
   * @param Node $n the node to append
   */
  public function addChild(Node $n){
    $n->parent = $this;                     // assign the parent node to the node $n
    $name = $n->name;                       // get the node $n name
    if (!(isset($this->$name))) {           // check wether the public user defined property $name is already defined
      $this->$name = $n;                    // assign the node $n to the user defined property $name
    }
    else{
      if (!(is_array($this->$name))){       // if the public user defined property $name is not an array containing nodes
        $this->$name = array($this->$name); // convert the user defined property as an array
      }
      array_push($this->$name, $n);         // append the new node $n to the user defined property
    }
    array_push($this->childs, $n);          // append the new node $n to the childs array
    $this->count++;                         // increments $this->count the number of child nodes
  }
  
  /**
   * export the node and all his childs to an xml format
   * @param $level the node indentation deep
   * @return string $x the xml output of the node
   */
  public function export($level = null){
    $level = (int)$level;
    $whitespace = str_repeat("  ", $level); // TO DO : this one could be a constant
    if ($this->count == 0){                 // if the current node is empty
      return "$whitespace<$this->name/>\n"; // return a closing tag of the current node name as the xml output
    }
    $x = "$whitespace<$this->name>\n";      // open an tag of the current node name
    foreach($this->childs as $child){       // for each child of the current node
      $x .= $child->export($level+1);       // export the child node
    }
    $x .= "$whitespace</$this->name>\n";    // close the opened tag of the current node name
    return $x;                              // return the xml output of the current node
  }

  /**
   * import all xml nodes from a SimpleXMLElement
   * @param string $xml
   */
  public function import(SimpleXMLElement $sxe, Node $p = null){
    $this->name = $sxe->getName();          // reasign the node name with the root element of the SimpleXMLElelment
    $this->parent = $p;                     // assign the parent node    
    if (count($sxe->children()) > 0){       // if the xml node is not empty
      foreach($sxe->children() as $child){  // for each children
        $n = new Node($child->getName());   // create the new node
        $this->addChild($n);                // append the new node
        $n->import($child, $this);          // now import all nodes from the child element of the SimpleXMLElement
      }
    }
  }
  
}


/* start of benchmark */
$start = microtime(true);

/* export test */
$n = new Node("html");
$n->addChild(new Node("head"));
$n->head->addChild(new Node("meta"));
$n->head->addChild(new Node("meta"));
$n->addChild(new Node("body"));
$n->body->addChild(new Node("div"));
$n->body->addChild(new Node("div"));
$n->body->div[1]->addChild(new Node("span"));

echo $n->export(); // ouput : <html><head><meta /><meta /></head><body><div /><div><span /></div></body></html>

/* import test */
$x = new SimpleXMLElement($n->export());
$n = new Node();
$n->import($x);

echo $n->export(); // ouput : <html><head><meta /><meta /></head><body><div /><div><span /></div></body></html>

/* serialization test */
$n = serialize($n);
$n = unserialize($n);

echo $n->export(); // ouput : <html><head><meta /><meta /></head><body><div /><div><span /></div></body></html>

/* end of benchmark */
$end = microtime(true);
echo $end - $start; // ouput : 0.00129795074463 on an toshiba tecra s3 1.87GHz with 512mb of RAM

?>
