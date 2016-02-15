#!/bin/bash

# compile all c files in this folder and place them in the bin folder

BINFOLDER="../";

for cfile in *.c; 
do 
	
	echo -n "Processing $cfile file.."; 
	compile=`gcc -Wall -o "$BINFOLDER${cfile%%.*}" "$cfile" -lwiringPi -lm`;
	if [ "$compile" != "" ] ; then
		echo " fail";
	else
		echo " done";
	fi
#	gcc -Wall -o bmp085 bmp085_first.c -lwiringPi -lm;

done



