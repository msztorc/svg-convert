<?php
namespace SVG;

interface SVGInterface
{
    public function getCLI();
    public function setCLI($CLI);
    public function open($file);
    public function getZoom();
    public function setZoom($zoom);
    public function getWidth();
    public function setWidth($width);
    public function getHeight();
    public function setHeight($height);
    public function getFormat();
    public function setFormat($format);
    public function convert();
    public function display();
    public function download($name = 'output');
    public function save($filename);

}