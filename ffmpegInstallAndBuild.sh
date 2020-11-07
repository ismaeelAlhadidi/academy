#!/bin/bash 

# this scripts assumes Ubuntu 14.04 LTS

# ensure the following sources are in /etc/apt/sources.list
# deb http://us.archive.ubuntu.com/ubuntu/ precise multiverse 
# deb-src http://us.archive.ubuntu.com/ubuntu/ precise multiverse 
# deb http://us.archive.ubuntu.com/ubuntu/ precise-updates multiverse 
# deb-src http://us.archive.ubuntu.com/ubuntu/ precise-updates multiverse

# deb http://us.archive.ubuntu.com/ubuntu/ trusty multiverse
# deb-src http://us.archive.ubuntu.com/ubuntu/ trusty multiverse
# deb http://us.archive.ubuntu.com/ubuntu/ trusty-updates multiverse
# deb-src http://us.archive.ubuntu.com/ubuntu/ trusty-updates multiverse


# install necessary packages
sudo apt-get update
sudo apt-get install build-essential git libssl-dev --assume-yes

# build and install librtmp
git clone git://git.ffmpeg.org/rtmpdump
cd rtmpdump
cd librtmp
make
sudo make install
cd ../../

# build and install libaac
# from https://trac.ffmpeg.org/wiki/How%20to%20quickly%20compile%20libaacplus
sudo apt-get install libfftw3-dev pkg-config autoconf automake libtool unzip --assume-yes
wget http://tipok.org.ua/downloads/media/aacplus/libaacplus/libaacplus-2.0.2.tar.gz
tar -xzf libaacplus-2.0.2.tar.gz
cd libaacplus-2.0.2
./autogen.sh --enable-shared --enable-static
make
sudo make install
sudo ldconfig
cd ../

# build and install ffmpeg
sudo apt-get install yasm libfaac-dev libfdk-aac-dev libfreetype6-dev libmp3lame-dev libopencore-amrnb-dev libopencore-amrwb-dev libopenjpeg-dev libopus-dev libschroedinger-dev libspeex-dev libtheora-dev libvo-aacenc-dev libvorbis-dev libvpx-dev libx264-dev libxvidcore-dev --assume-yes
git clone https://github.com/FFmpeg/FFmpeg.git
cd FFmpeg
./configure --enable-gpl --enable-version3 --enable-nonfree --enable-postproc --enable-libaacplus --enable-libfaac --enable-libfdk-aac --enable-libfreetype --enable-libmp3lame --enable-libopencore-amrnb --enable-libopencore-amrwb --enable-libopenjpeg --enable-openssl --enable-libopus --enable-libschroedinger --enable-libspeex --enable-libtheora --enable-libvo-aacenc --enable-libvorbis --enable-libvpx --enable-libx264 --enable-libxvid --prefix=/usr/local --enable-librtmp
make
sudo make install