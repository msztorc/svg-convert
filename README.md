![svg-convert](https://sztorc.com/svg-convert/svg-convert.png)

# svg-convert
Various helpers to convert SVG files to other formats, especially PNG and PDF. Package includes CLI (phantomjs script) to render and php adapter to manipulate and convert SVG format using phantomjs or rsvg-convert (librsvg).


## Install PhantomJS

Before installing PhantomJS, you will need to install some required packages on your system. 
You can install all of them with the following commands:

`sudo apt-get update`

`sudo apt-get install build-essential chrpath libssl-dev libxft-dev libfreetype6-dev libfreetype6 libfontconfig1-dev libfontconfig1 -y`

Next, you will need to download the PhantomJS. You can download the latest stable version of the PhantomJS from their official website. 

`wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-2.1.1-linux-x86_64.tar.bz2`

Extract the downloaded archive file to desired system location:

`sudo tar xvjf phantomjs-2.1.1-linux-x86_64.tar.bz2 -C /usr/local/share/`

Next, create a symlink of PhantomJS binary file to systems bin directory:

`sudo ln -s /usr/local/share/phantomjs-2.1.1-linux-x86_64/bin/phantomjs /usr/local/bin/`

### Use psvg-convert script globally
```bash
chmod +x src/js/psvg-convert.js
sudo cp src/js/psvg-convert.js /usr/bin/psvg-convert
```

## Install RSVG (only if you want to convert svg using librsvg)

`sudo apt-get update`

``sudo apt-get install libcairo2-dev libspectre-dev librsvg2-dev \
                      libpoppler-glib-dev librsvg2-bin``

## Examples

#### Convert SVG to PNG format using PhantomJS CLI script

`psvg-convert inputfile.svg outputfile.png`


##### Usage
`psvg-convert svgfile outputfile [--width=800 --height=600 --zoom=2.5 --format=png|pdf]`



#### Convert SVG file in PHP

Initializing method with `phantomjs` argument when you want use to PhantomJS engine
```php
$svg = SVG::init('phantomjs');
```

or if you want to use rsvg-convert
```php
$svg = SVG::init('rsvg');
```

```php
<?php

    $svg = SVG::init('phantomjs', 'inputfile.svg');
    $svg->setFormat('png');
    $svg->convert();
    $svg->save('outputfile.png');
```

#### Manipulate and convert SVG file in PHP

```php
<?php

    $svg = SVG::init('phantomjs', 'inputfile.svg');
    $svg->setAttribute('path', 'fill', '#131C77'); //change fill color to all paths
    $svg->setFormat('png');
    $svg->convert();
    $svg->save('outputfile.png');
```

#### Other useful methods

Open file
```php
$svg->open('file.svg'); //open file
```

Set zoom
```php
$svg->setZoom(2.5); //enlarge original svg size up to 2.5x
```

Set size
```php
$svg->setWidth(500); //set output width to 500px
$svg->setHeight(300); //set output height to 300px

//echo 'output size: ' . $svg->getWidth() . 'x' . $svg->getHeight();
```

Set output format
```php
$svg->setFormat('pdf'); //set output format to pdf

//echo 'output format: ' . $svg->getFormat();
```

Display file
```php
$svg->display(); //display image
```

Download file
```php
$svg->download(); //download file
```

Save file
```php
$svg->save('file.png'); //save file
```

#### Image tests

All images you can find in unit tests folder.

| PhantomJS | rsvg-convert |
| --- | :---: |
| <img src="https://sztorc.com/svg-convert/images/pgallardo.png" width="420">  | <img src="https://sztorc.com/svg-convert/images/rgallardo.png" width="420"> |
| <img src="https://sztorc.com/svg-convert/images/pcar.png" width="420">  | <img src="https://sztorc.com/svg-convert/images/rcar.png" width="420"> |
| <img src="https://sztorc.com/svg-convert/images/pubuntu.png" width="420">  | <img src="https://sztorc.com/svg-convert/images/rubuntu.png" width="420"> |
| <img src="https://sztorc.com/svg-convert/images/pdebian.png" width="420">  | <img src="https://sztorc.com/svg-convert/images/rdebian.png" width="420"> |
| <img src="https://sztorc.com/svg-convert/images/ptiger.png" width="420">  | <img src="https://sztorc.com/svg-convert/images/rtiger.png" width="420"> |


#### SVG manipulation

<img src="https://sztorc.com/svg-convert/images/pdebian.png" width="100"> <img src="https://sztorc.com/svg-convert/images/pdebian-blue.png" width="100"> <img src="https://sztorc.com/svg-convert/images/pdebian-green.png" width="100">
```
    $svg = SVG::init('phantomjs', 'debian.svg');
    $svg->setAttribute('path', 'fill', '#131C77')->setFormat('png')->convert()->save('debian-blue.png');
    $svg->setAttribute('path', 'fill', '#06A70D')->setFormat('png')->convert()->save('debian-green.png');
``` 

<img src="https://sztorc.com/svg-convert/images/pubuntu1.png" width="100"> <img src="https://sztorc.com/svg-convert/images/pubuntu2.png" width="100"> <img src="https://sztorc.com/svg-convert/images/pubuntu3.png" width="100"> <img src="https://sztorc.com/svg-convert/images/ubuntu.gif" width="100">
```
$svg1 = SVG::init('phantomjs', 'ubuntu.svg')->setFormat('png')->convert()->save('ubuntu1.png');

$svg2 = SVG::init('phantomjs', 'ubuntu.svg')
    ->setAttribute('use[1]', 'fill', '#f40')
    ->setAttribute('use[2]', 'fill', '#f80')
    ->setAttribute('use[3]', 'fill', '#d00')
    ->setAttribute('use[4]', 'fill', '#f40')
    ->setAttribute('use[5]', 'fill', '#f80')
    ->setAttribute('use[6]', 'fill', '#d00')
    ->setFormat('png')->convert()->save('ubuntu2.png');

$svg3 = SVG::init('phantomjs', 'ubuntu.svg')
    ->setAttribute('use[1]', 'fill', '#f80')
    ->setAttribute('use[2]', 'fill', '#d00')
    ->setAttribute('use[3]', 'fill', '#f40')
    ->setAttribute('use[4]', 'fill', '#f80')
    ->setAttribute('use[5]', 'fill', '#d00')
    ->setAttribute('use[6]', 'fill', '#f40')
    ->setFormat('png')->convert()->save('ubuntu3.png');
```

Create gif using imagemagick

`convert -loop 0 -delay 25 ubuntu1.png ubuntu2.png ubuntu3.png ubuntu.gif`

## License
MIT