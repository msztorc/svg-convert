<?php

/**
 * RSVG Convert - wrapper class for rsvg-convert (librsvg)
 * @author Miroslaw Sztorc <mirek.sztorc@gmail.com>
 * @license MIT
 * @link https://github.com/msztorc/svg-convert
 */

namespace SVG;

use SVG\SVGInterface;

class RSVG extends SVGManipulator implements SVGInterface
{

    /**
     * Path to rsvg-convert tool
     * @var string
     */
    private $CLI = '/usr/local/bin/rsvg-convert';

   /**
     * Pixels per inch [defaults to 90dpi]
     * @var int
     */
    private $dpiX = 90;

    /**
     * Pixels per inch [defaults to 90dpi]
     * @var int
     */
    private $dpiY = 90;

    /**
     * x-zoom factor [defaults to 1.0]
     * @var float
     */
    private $xZoom = 1.0;

    /**
     * y-zoom factor [defaults to 1.0]
     * @var float
     */
    private $yZoom = 1.0;

    /**
     * Preserve the aspect ratio [defaults to false]
     * @var bool
     */
    private $keepAspectRatio = false;

    /**
     * Background color [black, white, #abccee, #aaa...]
     * @var string
     */
    private $backgroundColor = 'None';

    /**
     * RSVG constructor.
     * @param null $file
     */
    public function __construct($file = null)
    {
        if ($file !== null)
            $this->open($file);
    }

    /**
     * @return string
     */
    public function getCLI()
    {
        return $this->CLI;
    }

    /**
     * @param string $CLI
     */
    public function setCLI($CLI)
    {
        $this->CLI = $CLI;
    }

    /**
     * @return int
     */
    public function getDpiX()
    {
        return $this->dpiX;
    }

    /**
     * @param int $dpiX
     * @return object
     */
    public function setDpiX($dpiX)
    {
        $this->dpiX = $dpiX;
        return $this;
    }

    /**
     * @return int
     */
    public function getDpiY()
    {
        return $this->dpiY;
    }

    /**
     * @param int $dpiY
     * @return object
     */
    public function setDpiY($dpiY)
    {
        $this->dpiY = $dpiY;
        return $this;
    }

    /**
     * @return float
     */
    public function getXZoom()
    {
        return $this->xZoom;
    }

    /**
     * @param float $xZoom
     * @return object
     */
    public function setXZoom($xZoom)
    {
        $this->xZoom = $xZoom;
        return $this;
    }

    /**
     * @return float
     */
    public function getYZoom()
    {
        return $this->yZoom;
    }

    /**
     * @param float $yZoom
     * @return object
     */
    public function setYZoom($yZoom)
    {
        $this->yZoom = $yZoom;
        return $this;
    }

    /**
     * @return bool
     */
    public function isKeepAspectRatio()
    {
        return $this->keepAspectRatio;
    }

    /**
     * @param bool $keepAspectRatio
     * @return object
     */
    public function setKeepAspectRatio($keepAspectRatio)
    {
        $this->keepAspectRatio = $keepAspectRatio;
        return $this;
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     * @return object
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    /**
     * Convert SVG to other format [png, pdf, ps, eps, svg, xml]
     * @return bool
     */
    public function convert()
    {
        $cwd = sys_get_temp_dir();

        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
            2 => array("file", sys_get_temp_dir() . "/stderr.log", "a") // stderr is a file to write to
        );

        $cmd = sprintf($this->CLI . ' -d %s -p %s -x %s -y %s -z %s -f %s -a %s --background-color %s',
            $this->dpiX,
            $this->dpiY,
            $this->xZoom,
            $this->yZoom,
            $this->zoom,
            $this->format,
            $this->keepAspectRatio,
            $this->backgroundColor
        );

        if ($this->width !== null && (int)$this->width > 0)
            $cmd .= ' -w ' . $this->width;
        if ($this->height !== null && (int)$this->height > 0)
            $cmd .= ' -h ' . $this->height;

        $process = proc_open($cmd, $descriptorspec, $pipes, $cwd);

        if (is_resource($process)) {

            fwrite($pipes[0], $this->svg);
            fclose($pipes[0]);

            $this->outputStream = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $return_value = proc_close($process);

            return ($return_value == -1)
                ? false
                : $this;
        }
    }
}