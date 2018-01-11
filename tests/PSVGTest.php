<?php

use SVG\SVG;

class PSVGTest extends PHPUnit_Framework_TestCase {

    public function testOpenFileWithDefaultConstructor()
    {
        $svg = SVG::init();
        $svg->open(dirname(__FILE__) . '/gfx/car.svg');
        $this->assertNotEmpty($svg->getOutputStream());

    }

    public function testPhantomConvert1()
    {
        $file = dirname(__FILE__) . '/gfx/car.svg';
        $ofile = dirname(__FILE__) . '/gfx/pcar.png';

        $svg = SVG::init('phantomjs', $file)->setFormat('png')->convert()->save($ofile);

        $this->assertTrue(file_exists($ofile) && filesize($ofile) > 0);

    }

	public function testPhantomConvert2()
	{
		$file = dirname(__FILE__) . '/gfx/gallardo.svg';
		$ofile = dirname(__FILE__) . '/gfx/pgallardo.png';

		$svg = SVG::init('phantomjs', $file)->setFormat('png')->convert()->save($ofile);

		$this->assertTrue(file_exists($ofile) && filesize($ofile) > 0);

	}

	public function testPhantomConvert3()
	{
		$file = dirname(__FILE__) . '/gfx/tiger.svg';
		$ofile = dirname(__FILE__) . '/gfx/ptiger.png';

		$svg = SVG::init('phantomjs', $file)->setFormat('png')->convert()->save($ofile);

		$this->assertTrue(file_exists($ofile) && filesize($ofile) > 0);

	}

	public function testPhantomConvert4()
	{
		$file = dirname(__FILE__) . '/gfx/ubuntu.svg';
		$ofile = dirname(__FILE__) . '/gfx/pubuntu.png';

		$width = 420;
		$height = 420;

		$svg = SVG::init('phantomjs', $file)
				->setFormat('png')
				->setWidth($width)
				->setHeight($height)
				->convert()
				->save($ofile);

		$this->assertTrue(file_exists($ofile) && filesize($ofile) > 0);

		list($fwidth, $fheight) = @getimagesize($ofile);
		$this->assertTrue($width === $fwidth && $height === $fheight);
	}

	public function testPhantomConvert5()
	{
		$file = dirname(__FILE__) . '/gfx/debian.svg';
		$ofile = dirname(__FILE__) . '/gfx/pdebian.png';

		$width = 420;
		$height = 420;

		$svg = SVG::init('phantomjs', $file)
				->setFormat('png')
				->setWidth($width)
				->setHeight($height)
				->convert()
				->save($ofile);

		$this->assertTrue(file_exists($ofile) && filesize($ofile) > 0);

		list($fwidth, $fheight) = @getimagesize($ofile);
		$this->assertTrue($width === $fwidth && $height === $fheight);

	}

    public function testSetAttribute()
    {
        $debian = dirname(__FILE__) . '/gfx/debian.svg';

        $odebian1 = dirname(__FILE__) . '/gfx/pdebian-blue.png';
        $odebian2 = dirname(__FILE__) . '/gfx/pdebian-green.png';

        //debian
        $svg2 = SVG::init('phantomjs', $debian);
        $svg2->setAttribute('path', 'fill', '#131C77')->setFormat('png')->convert()->save($odebian1);
        $svg2->setAttribute('path', 'fill', '#06A70D')->setFormat('png')->convert()->save($odebian2);

		$this->assertTrue(file_exists($odebian1) && filesize($odebian1) > 0);
		$this->assertTrue(file_exists($odebian2) && filesize($odebian2) > 0);

    }

	public function testSetAttribute2()
	{
		$ubuntu = dirname(__FILE__) . '/gfx/ubuntu.svg';
		$out = dirname(__FILE__) . '/gfx/pubuntu{n}.png';

		//ubuntu
		for ($i=1;$i<4;$i++)
		{
			$svg = SVG::init('phantomjs', $ubuntu);

			if ($i == 2)
			{
				$svg->setAttribute('use[1]', 'fill', '#f40');
				$svg->setAttribute('use[2]', 'fill', '#f80');

				$svg->setAttribute('use[3]', 'fill', '#d00');
				$svg->setAttribute('use[4]', 'fill', '#f40');

				$svg->setAttribute('use[5]', 'fill', '#f80');
				$svg->setAttribute('use[6]', 'fill', '#d00');
			}

			if ($i == 3)
			{
				$svg->setAttribute('use[1]', 'fill', '#f80');
				$svg->setAttribute('use[2]', 'fill', '#d00');

				$svg->setAttribute('use[3]', 'fill', '#f40');
				$svg->setAttribute('use[4]', 'fill', '#f80');

				$svg->setAttribute('use[5]', 'fill', '#d00');
				$svg->setAttribute('use[6]', 'fill', '#f40');
			}

			//echo $svg->getOutputStream();

			$svg->setFormat('png')->convert()->save(str_replace('{n}', $i, $out));
			unset($svg);
		}

	}

}