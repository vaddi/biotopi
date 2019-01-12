#!/bin/bash

DEVICE="/dev/ttyUSB0"

RESULT=$( sudo head -n2 ${DEVICE} | tr -d "\n" )

echo ${RESULT}

exit 0

# end of file
