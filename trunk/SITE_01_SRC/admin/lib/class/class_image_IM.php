<?

/**
 * ImageMagick binaries implementation for Image_Transform package
 * @link       http://pear.php.net/package/Image_Transform
 */

require_once('class_image_Transform.php');

/**
 * ImageMagick binaries implementation for Image_Transform package
 * @link http://www.imagemagick.org/
 **/
 
class Image_Transform_Driver_IM extends Image_Transform
{
    /**
     * associative array commands to be executed
     * @var array
     * @access private
     */
    var $command;

    /**
     * Class constructor
     */
    function Image_Transform_Driver_IM()
    {
        $this->__construct();
    } // End Image_IM


    /**
     * Class constructor
     */
    function __construct()
    {
		global $convert;

        if (empty($convert)) {
            $this->error = '"Convert" is not defined';
            return false;
        }

		$this->_init();

    } // End Image_IM

    /**
     * Initialize the state of the object
     **/
    function _init()
    {
        $this->command = array();
    }

    /**
     * Load an image.
     *
     * This method doesn't support remote files.
     *
     * @param string filename
     *
     * @return mixed TRUE or a PEAR error object on error
     * @see PEAR::error()
     */
    function load($image)
    {
        $this->_init();
        if (!file_exists($image)) {
            $this->error = 'The image file ' . $image . ' doesn\'t exist';
        }
        $this->image = $image;
        $result = $this->_get_image_details($image);
        return true;

    } // End load

    /**
     * Image_Transform_Driver_IM::_get_image_details()
     *
     * @param string $image the path and name of the image file
     * @return none
     */
    function _get_image_details($image)
    {
        $retval = parent::_get_image_details($image);
	    if (!$retval) {
            unset($retval);

            if (!file_exists(C_IMAGICK.'identify'.(C_PLATFORM == 'WINDOWS' ? '.exe' : ''))) {
                $this->error = 'Couldn\'t find "identify" binary';
				return false;
            }
            $cmd = $this->_prepare_cmd(C_IMAGICK, 'identify',  '-format %w:%h:%m ' . escapeshellarg($image));
            exec($cmd, $res, $exit);

            if ($exit == 0) {
                $data  = explode(':', $res[0]);
                $this->img_x = $data[0];
                $this->img_y = $data[1];
                $this->type  = strtolower($data[2]);
                $retval = true;
            } else {
                return $this->error("Cannot fetch image or images details.", true);
            }

        }

        return $retval;
    }

    /**
     * Resize the image.
     *
     * @access private
     *
     * @param int   $new_x   New width
     * @param int   $new_y   New height
     * @param mixed $options Optional parameters
     *
     * @return true on success or PEAR Error object on error
     * @see PEAR::error()
     */
    function _resize($new_x, $new_y, $options = NULL)
    {
        if (isset($this->command['resize'])) {
            $this->error = 'You cannot scale or resize an image more than once without calling save() or display()';
			return false;
        }
        $this->command['resize'] = '-geometry ' . ((int) $new_x) . 'x' . ((int) $new_y) . '!';

        $this->new_x = $new_x;
        $this->new_y = $new_y;

        return true;
    } // End resize

    /**
     * rotate
     *
     * @param   int     angle   rotation angle
     * @param   array   options no option allowed
     * @return mixed TRUE or a PEAR error object on error
     */
    function rotate($angle, $options = NULL)
    {
        $angle = $this->_rotation_angle($angle);
        if ($angle % 360) {
            $this->command['rotate'] = '-rotate '.(float)$angle;
        }
        return true;

    } // End rotate

    /**
     * Crop image
     *
     * @author Ian Eure <ieure@websprockets.com>
     * @since 0.8
     *
     * @param int width Cropped image width
     * @param int height Cropped image height
     * @param int x X-coordinate to crop at
     * @param int y Y-coordinate to crop at
     *
     * @return mixed TRUE or a PEAR error object on error
     */
    function crop($width, $height, $x = 0, $y = 0) {
        // Do we want a safety check - i.e. if $width+$x > $this->img_x then we
        // raise a warning? [and obviously same for $height+$y]
        $this->command['crop'] = '-crop ' . ((int) $width)  . 'x' . ((int) $height) . '+' . ((int) $x) . '+' . ((int) $y);

        // I think that setting img_x/y is wrong, but scaleByLength() & friends
        // mess up the aspect after a crop otherwise.
        $this->new_x = $this->img_x = $width - $x;
        $this->new_y = $this->img_y = $height - $y;

        return true;
    }

	
	function cropCenter($width, $height) {
		// $convert convert $file $option -resize $taille -quality 85 -gravity center -crop $crop+0+0 $fileDest
		
		$this->command['crop'] = '-gravity center -crop ' . ((int) $width)  . 'x' . ((int) $height) . '+' . ((int) $x) . '+' . ((int) $y);
		
		// I think that setting img_x/y is wrong, but scaleByLength() & friends
		// mess up the aspect after a crop otherwise.
		$this->new_x = $this->img_x = $width - $x;
		$this->new_y = $this->img_y = $height - $y;
		
		return true;
    }
	
	function WM($width, $height) { // WATER MARK
		
		die('todo');
		
		$maxWidth = $width < $maxWidth ? $width : $maxWidth;
		$maxHeight = $height < $maxHeight ? $height : $maxHeight;
		$taille = $maxWidth.'x'.$maxHeight;
		exec(" $convert convert $option -resize $taille -quality 85 $file canvas.miff ");
		exec($composite." -compose over -gravity SouthEast ../../medias/watermark_dacomex_png24.png canvas.miff $fileDest "); // IMG to CANVAS
    }

	function POLA() { // POLAROID

		$this->command['pola'] = '-bordercolor white -border 5 -bordercolor rgb(100,100,100) -background  none -rotate '.rand(-7,7).' -background black (+clone -shadow 30x4+2+2) +swap -background none -flatten -depth 8 ';

		//$this->command['pola'] = '-bordercolor white -border 5 -bordercolor rgb(100,100,100) -background  none -rotate '.rand(-7,7).' -background black (+clone -shadow 30x4+2+2) +swap -background none -flatten -depth 8 ';
		
		//exec(" $convert convert {$fileDir}temp.jpg -bordercolor white -border 5 -bordercolor rgb(100,100,100) -background  none -rotate ".rand(-7,7)." -background black ( +clone -shadow 30x4+2+2 ) +swap -background none -flatten -depth 8 -quality 85 $fileDest");
	}
	
	
    /**
     * addText
     *
     * @param   array   options     Array contains options
     *                              array(
     *                                  'text'  The string to draw
     *                                  'x'     Horizontal position
     *                                  'y'     Vertical Position
     *                                  'Color' Font color
     *                                  'font'  Font to be used
     *                                  'size'  Size of the fonts in pixel
     *                                  'resize_first'  Tell if the image has to be resized
     *                                                  before drawing the text
     *                              )
     *
     * @return mixed TRUE or a PEAR error object on error
     * @see PEAR::error()
     */
    function addText($params)
    {
         $params = array_merge($this->_get_default_text_params(), $params);
         extract($params);
         if (true === $resize_first) {
             // Set the key so that this will be the last item in the array
            $key = 'ztext';
         } else {
            $key = 'text';
         }
         $this->command[$key] = '-font ' . escapeshellarg($font)
            . ' -fill ' . escapeshellarg($color)
            . ' -draw \'text ' . escapeshellarg($x . ',' . $y)
            . ' "' . escapeshellarg($text) . '"\'';
         // Producing error: gs: not found gs: not found convert: Postscript delegate failed [No such file or directory].
        return true;

    } // End addText

    /**
     * Adjust the image gamma
     *
     * @access public
     * @param float $outputgamma
     * @return mixed TRUE or a PEAR error object on error
     */
    function gamma($outputgamma = 1.0) {
        if ($outputgamme != 1.0) {
            $this->command['gamma'] = '-gamma ' . (float) $outputgamma;
        }
        return true;
    }

    /**
     * Convert the image to greyscale
     *
     * @access public
     * @return mixed TRUE or a PEAR error object on error
     */
    function greyscale() {
        $this->command['type'] = '-type Grayscale';
        return true;
    }

    /**
     * Horizontal mirroring
     *
     * @access public
     * @return TRUE or PEAR Error object on error
     */
    function mirror() {
        // We can only apply "flop" once
        if (isset($this->command['flop'])) {
            unset($this->command['flop']);
        } else {
            $this->command['flop'] = '-flop';
        }
        return true;
    }

    /**
     * Vertical mirroring
     *
     * @access public
     * @return TRUE or PEAR Error object on error
     */
    function flip() {
        // We can only apply "flip" once
        if (isset($this->command['flip'])) {
            unset($this->command['flip']);
        } else {
            $this->command['flip'] = '-flip';
        }
        return true;
    }

    /**
     * Save the image file
     * @access public
     * @param $filename string  the name of the file to write to
     * @param $quality  quality image dpi, default=75
     * @param $type     string  (JPEG, PNG...)
     * @return mixed TRUE or a PEAR error object on error
     */
    function save($filename, $type = '', $quality = NULL) {// A refaire... bouh !
       
		global $convert,$debug;
		$type = strtoupper(($type == '') ? $this->type : $type);
        switch ($type) {
            case 'JPEG': $type = 'JPG'; break;
        }
        
		// A refaire... bouh !
        $options = array();
        if (!is_null($quality)) {
            $options['quality'] = $quality;
        }
        $quality = $this->_getOption('quality', $options, 75);


		$cmd = $this->_prepare_cmd( $convert, 'convert', escapeshellarg($this->image).' '.implode(' ', $this->command).' -quality '.((int) $quality).' '.$type.':'.escapeshellarg($filename).' 2>&1');
       
	    exec($cmd, $res, $exit);

		if ($debug) db($cmd,implode('. ', $res));
		
        return ($exit == 0) ? true : implode('. ', $res);
		
		/*
		$filenameNew = '';
		foreach($this->command as $command) {
			$cmd = $this->_prepare_cmd( $convert, 'convert', escapeshellarg(($filenameBak != '' ? $filenameNew: $this->image)).' '.$command.' -quality '.((int) $quality).'  '.$type.':'.escapeshellarg($filename).' 2>&1');
        exec($cmd, $res, $exit);
			if ($res) $this->error .= implode('. ', $res);
			else {
				$filenameNew = $filename;
			}
			if ($debug) db($cmd,implode('. ', $res));
		}
		*/
    }

    /**
     * Display image without saving and lose changes
     * This method adds the Content-type HTTP header
     * @access public
     * @param string type (JPEG,PNG...);
     * @param int quality 75
     * @return mixed TRUE or a PEAR error object on error
     */
    function display($type = '', $quality = NULL)
    {
        $type    = strtoupper(($type == '') ? $this->type : $type);
        switch ($type) {
            case 'JPEG':
                $type = 'JPG';
                break;
        }
        $options = array();
        if (!is_null($quality)) {
            $options['quality'] = $quality;
        }
        $quality = $this->_getOption('quality', $options, 75);

        $this->_send_display_headers($type);

        $cmd = $this->_prepare_cmd(C_IMAGICK,'convert',implode(' ',$this->command).' -quality '.((int) $quality).' '.$this->image.' '.$type.":-");
        passthru($cmd);

        if (!$this->keep_settings_on_save) {
            $this->free();
        }
        return true;
    }

    /**
     * Destroy image handle
     *
     * @return void
     */
    function free()
    {
        $this->command = array();
        $this->image = '';
        $this->type = '';
    }

} // End class ImageIM