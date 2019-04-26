<?php

/**
 * PSVG Convert - wrapper class for PhantomJS render script
 * @author Miroslaw Sztorc <mirek.sztorc@gmail.com>
 * @license MIT
 * @link https://github.com/msztorc/svg-convert
 */

namespace SVG;

use SVG\SVGInterface;

class PSVG extends SVGManipulator implements SVGInterface
{

    /**
     * Path to PhantomJS
     * @var string
     */
    private $CLI = 'phantomjs';


    /**
     * PSVG constructor.
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
     * Convert SVG to other format [png, pdf, svg]
     * @return bool
     */
    public function convert()
    {
        if ($this->getFormat() == 'svg')
            return $this;

        $tmp = $cwd = sys_get_temp_dir();

        $svg_tmp = $tmp . DIRECTORY_SEPARATOR . substr(md5(microtime()), -8) . '.svg';
        file_put_contents($svg_tmp, $this->svg);

        $out_tmp = $tmp . DIRECTORY_SEPARATOR . substr(md5(microtime()), -8) . '.' . $this->format;

        $cmd = sprintf($this->CLI . ' ' . realpath(dirname(__FILE__) . '/../js/psvg-convert.js') . ' %s %s --zoom=%s --format=%s',
            $svg_tmp,
            $out_tmp,
            $this->zoom,
            $this->format
        );

        if ($this->width !== null && (int)$this->width > 0 && $this->height !== null && (int)$this->height > 0)
            $cmd .= ' --width=' . $this->width . ' --height=' . $this->height;

        //execute command
        exec($cmd, $output, $return_value);

        if (!$return_value)
            $this->outputStream = file_get_contents($out_tmp); //stream_get_contents($pipes[1]);

        //rm temp files
        @unlink($svg_tmp);
        @unlink($out_tmp);


        return (!$return_value)
            ? $this
            : false;

    }
}