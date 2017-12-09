/***********************************************************************
 * mcp3008SpiTest.cpp. Sample program that tests the mcp3008Spi class.
 * an mcp3008Spi class object (a2d) is created. the a2d object is instantiated
 * using the overloaded constructor. which opens the spidev0.0 device with
 * SPI_MODE_0 (MODE 0) (defined in linux/spi/spidev.h), speed = 1MHz &
 * bitsPerWord=8.
 *
 * call the spiWriteRead function on the a2d object 20 times. Each time make sure
 * that conversion is configured for single ended conversion on CH0
 * i.e. transmit ->  byte1 = 0b00000001 (start bit)
 *                   byte2 = 0b1000000  (SGL/DIF = 1, D2=D1=D0=0)
 *                   byte3 = 0b00000000  (Don't care)
 *      receive  ->  byte1 = junk
 *                   byte2 = junk + b8 + b9
 *                   byte3 = b7 - b0
 *
 * after conversion must merge data[1] and data[2] to get final result
 *
 *
 *
 * *********************************************************************/
#include "mcp3008Spi.h"
#include "mcp3008Spi.cpp"

#define APPNAME     "mcp3008"
#define APPVERSION  "0.1"

using namespace std;

void usage() {
  printf("%s v%s - Get Values from mcp3008 ADC\n", APPNAME, APPVERSION );
  printf("Usage:\n");
  printf("%s [ARG]\n", APPNAME);
  printf("[ARG] \t = device id - Return Device Temperatur Value (°C)\n");
  printf("[ARG] \t = 0         - Return List of Devices\n");
  printf("Example:\n");
  printf("%s 28-000004d0e3cf\n", APPNAME);
  printf("25.187\n");
}


int char2int( char *c ) {
  return *c - '0';
}

int main(int argc, char **argv) {

	int a2dVal = 0;
	int a2dChannel = 0;
	if ( argv[1] != NULL && char2int( argv[1] ) != 0  ) {
		a2dChannel = char2int( argv[1] );
	} else {
    usage();
    return 0
  }

	mcp3008Spi a2d( "/dev/spidev0.0", SPI_MODE_0, 1000000, 8 );

	unsigned char data[ 3 ];

	data[0] = 1;	// first byte transmitted -> start bit
	data[1] = 0b10000000 | ( ( ( a2dChannel & 7 ) << 4 ) ); // second byte transmitted -> (SGL/DIF = 1, D2=D1=D0=0)
	data[2] = 0;	// third byte transmitted....don't care

	a2d.spiWriteRead( data, sizeof( data ) );

	a2dVal = 0;
	a2dVal = ( data[ 1 ] << 8 ) & 0b1100000000; //merge data[1] & data[2] to get result
	a2dVal |= ( data[ 2 ] & 0xff );

	cout << a2dVal << endl; // output value

  return 0;
}
