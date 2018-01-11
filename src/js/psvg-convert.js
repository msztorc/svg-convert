#!/usr/bin/env phantomjs

/*
 * SVG converter to png or pdf format using PhantomJS
 *
 * Usage: svg-convert.js inputfile.svg outputfile.png [--width=800 --height=600 --zoom=2.5 --format=png|pdf]
 * 
 * author: Miroslaw Sztorc <miroslaw@sztorc.com>
 * license: MIT
 */

var sys = require('system');
var args = sys.args;

if (args.length < 3) {
    console.log("Usage: svg-convert.js inputfile.svg outputfile.png [--width=800 --height=600 --zoom=2.5 --format=png|pdf]");
    phantom.exit(1);
}

//input file
var infile = args[1];

//output file
var outfile = args[2];

//default values
var rwidth   = 0; //auto
var rheight  = 0; //auto
var rzoom    = 1.0;
var rformat  = outfile.split('.').pop().toLowerCase();

var page = require('webpage').create();

args.forEach(function(arg, i) {
    
    // width arg
    if (arg.trim().search('--width=') > -1)
        rwidth = arg.trim().split("=")[1];

    //height arg
    if (arg.trim().search('--height=') > -1)
        rheight = arg.trim().split("=")[1];

    //zoom arg
    if (arg.trim().search('--zoom=') > -1)
        rzoom = arg.trim().split("=")[1];

    //format arg
    if (arg.trim().search('--format=') > -1)
        rformat = arg.trim().split("=")[1];
});

if (rformat != 'png' && rformat != 'pdf') rformat = 'png';

page.open(infile, function() {

    var svg = getSVGRect();
    var vBox = getSVGVBox();

    //new width
    var nwidth = (vBox.width > 0) ? vBox.width : svg.width;
    if (rwidth > 0) nwidth = rwidth;

    //new height
    var nheight = (vBox.height > 0) ? vBox.height : svg.height;
    if (rheight > 0) nheight = rheight;

    if (rzoom > 1) {
        nwidth = Math.round(svg.width*rzoom);
        nheight = Math.round(svg.height*rzoom);
    }

    window.resizeTo(nwidth, nheight);
    page.viewportSize = { width: nwidth, height: nheight };

    var fsvg = getSVGRect();
    //page.clipRect = { left: fsvg.left, top: fsvg.top, width: fsvg.width, height: fsvg.height };

    page.render(outfile, {'format': rformat });
    phantom.exit(0);
});

function getSVGRect()
{
    return page.evaluate(function () {
        var svg = document.getElementsByTagName('svg')[0];
        return svg.getBoundingClientRect();
    });
}

function getSVGVBox()
{
    return page.evaluate(function () {
        var svg = document.getElementsByTagName('svg')[0];
        var vba = svg.getAttribute('viewBox');

        var vBox = { 'left': 0, 'top': 0, 'width': 0, 'height': 0 };

        if (vba != null && vba != '' && vba.split(' ').length == 4)
        {
            var vb = vba.split(' ');

            vBox.left = parseFloat(vb[0]);
            vBox.top = parseFloat(vb[1]);
            vBox.width = parseFloat(vb[2]);
            vBox.height = parseFloat(vb[3]);
        }

        return vBox;
    })

}