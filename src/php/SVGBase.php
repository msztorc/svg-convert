<?php

/**
 * SVG Convert Factory
 * @author Miroslaw Sztorc <mirek.sztorc@gmail.com>
 * @license MIT
 * @link https://github.com/msztorc/svg-convert
 */

namespace SVG;

class SVGBase {

    /**
     * SVG content
     * @var string
     */
    protected $svg = null;

    /**
     * SVG as XML
     * @var null
     */
    protected $xsvg = null;

    /**
     * SVG doc
     * @var null
     */
    protected $dsvg = null;

    /**
     * Output stream
     * @var string
     */
    protected $outputStream = null;

    /**
     * zoom factor [defaults to 1.0]
     * @var float
     */
    protected $zoom = 1.0;

    /**
     * width [defaults to the SVG's width]
     * @var int
     */
    protected $width = null;

    /**
     * height [defaults to the SVG's height]
     * @var int
     */
    protected $height = null;

    /**
     * Save format [png, pdf, svg, defaults to svg]
     * @var string
     */
    protected $format = 'svg';


    public function load($svg_content)
    {
        $this->dsvg = new \DOMDocument;
        $this->dsvg->loadXML($svg_content);
        $this->xsvg = new \DOMXPath($this->dsvg);
        $this->xsvg->registerNamespace('svg', 'http://www.w3.org/2000/svg');

        return $this;
    }

    /**
     * @return float
     */
    public function getZoom()
    {
        return $this->zoom;
    }

    /**
     * @param float $zoom
     * @return object
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return object
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return object
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return object
     */
    public function setFormat($format)
    {
        $this->format = strtolower($format);
        return $this;
    }

    /**
     * Open SVG file
     * @param $svgFile
     * @return bool|string
     */
    public function open($svgFile)
    {
        if (!file_exists($svgFile))
            return false;

        $this->svg = $this->outputStream = file_get_contents($svgFile);
        if ($this->svg && !empty($this->svg))
        {
            $this->load($this->svg);
            return $this;
        }
        return false;
    }

    /**
     * Set header based on output format
     */
    protected function setHeader()
    {
        switch ($this->format) {
            case 'svg':
                header('Content-type: image/svg+xml');
                break;
            case 'png':
                header('Content-type: image/png');
                break;
            case 'pdf':
                header('Content-type: application/pdf');
                break;
            case 'xml':
                header('Content-type: text/xml');
                break;
            case 'eps':
            case 'ps':
                header('Content-type: application/postscript');
                break;
        }
    }

    public function mergeWithCanvas($width, $height)
    {
        if ($this->getFormat() == 'png') {
            if (class_exists('Imagick')) {
                $image = new \Imagick();
                $image->readImageBlob($this->outputStream);

                //$image->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1, true);

                $ow = $image->getImageWidth();
                $oh = $image->getImageHeight();

                $offset_x = round(($width - $ow) / 2);
                $offset_y = round(($height - $oh) / 2);

                $image->extentImage($width, $height, -$offset_x, -$offset_y);
                $image->setImageBackgroundColor('white');

                $this->outputStream = $image->getImageBlob();

            }

            if (function_exists('imageantialias')) {
                $image = imagecreatefromstring($this->outputStream);
                imageantialias($image, true);

                $ow = imagesx($image);
                $oh = imagesy($image);

                // Creates a black image
                $canvas = imagecreatetruecolor($width, $height);

                // Fill it with white
                $white = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
                imagefill($canvas, 0, 0, $white);
                imagecopy($canvas, $image, round(($width - $ow) / 2), round(($height - $oh) / 2), 0, 0, $ow, $oh);

                //export
                ob_start();
                imagepng($canvas);
                $this->outputStream = ob_get_clean();
            }
        }

        return $this;
    }

    /**
     * Display output content
     */
    public function display()
    {
        $this->setHeader();
        echo ($this->format != 'svg') ? $this->outputStream : $this->svg;
    }

    /**
     * Download output content as file
     * @param string $name
     */
    public function download($name = 'output')
    {
        $this->setHeader();
        header('Content-Disposition: attachment; filename="' . $name . '.' . $this->format . '"');
        echo $this->outputStream;
    }

    /**
     * @return string
     */
    public function getOutputStream()
    {
        return $this->outputStream;
    }

    public function getChild($selector)
    {
        return $this->xsvg->query($selector);
    }


    /**
     * Save output content to file
     * @param $filename
     * @return bool|int
     */
    public function save($filename)
    {
        return file_put_contents($filename, $this->outputStream);
    }

}