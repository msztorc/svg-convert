#!/bin/bash
sudo apt-get update
sudo apt-get install -y libcairo2-dev libspectre-dev librsvg2-dev libpoppler-glib-dev librsvg2-bin
sudo apt-get install -y build-essential chrpath libssl-dev libxft-dev libfreetype6-dev libfreetype6 libfontconfig1-dev libfontconfig1 -y
sudo wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-2.1.1-linux-x86_64.tar.bz2
sudo tar xvjf phantomjs-2.1.1-linux-x86_64.tar.bz2 -C /usr/local/share/
sudo ln -s /usr/local/share/phantomjs-2.1.1-linux-x86_64/bin/phantomjs /usr/local/bin/phantomjs
chmod +x src/js/psvg-convert.js
sudo cp src/js/psvg-convert.js /usr/local/bin/psvg-convert