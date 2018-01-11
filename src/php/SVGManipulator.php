<?php

/**
 * SVG Manipulator
 * @author Miroslaw Sztorc <mirek.sztorc@gmail.com>
 * @license MIT
 * @link https://github.com/msztorc/svg-convert
 */

namespace SVG;

class SVGManipulator extends SVGBase {


    public function setAttribute($selector, $attribute, $value)
    {
        $xgroup = $this->xsvg->query('//svg:'. $selector);

        foreach ($xgroup as $node)
        {
            $node->setAttribute($attribute, $value);
        }

        $this->svg = $this->outputStream = $this->dsvg->saveXML();
        return $this;
    }

	public function getAttribute($selector, $attribute)
	{
		$xgroup = $this->xsvg->query('//svg:'. $selector);
		var_dump(count($xgroup ));
		return (isset($xgroup[0])) ? $xgroup[0]->getAttribute($attribute) : false;
	}

    public function setStyle($selector, $property, $value)
    {
        $this->svg = preg_replace('/(' . $selector . '\s*\{[\w\s:\-;\(\)#]*)(' . $property . '\s*:)([^;\}]+)(;|\})/Ui', '$1' . $property . ':' . $value . '$4', $this->svg);
        return $this;
    }

    public function appendStyle($selector, $property, $value)
    {
        $this->svg = str_replace('</style>', $selector . '{' . $property . ':' . $value . '}</style>', $this->svg);
        return $this;
    }

    public function prependStyle($selector, $property, $value)
    {
        $this->svg = str_replace('<style>', $selector . '<style>{' . $property . ':' . $value . '}', $this->svg);
        return $this;
    }

}