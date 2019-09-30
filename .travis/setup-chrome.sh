#!/bin/bash
echo "Download chrome driver and unzip it"
LATEST_CHROMEDRIVER_VERSION=`curl -s "https://chromedriver.storage.googleapis.com/LATEST_RELEASE"`
wget -c -nc --retry-connrefused --tries=0 "https://chromedriver.storage.googleapis.com/${LATEST_CHROMEDRIVER_VERSION}/chromedriver_linux64.zip"
unzip -o -q chromedriver_linux64.zip
echo "unzip complete"
chmod 755 chromedriver
export DISPLAY=:99.0
#sh -e /etc/init.d/xvfb start
#sleep 3 # give xvfb some time to start
curl http://concrete5-test.test