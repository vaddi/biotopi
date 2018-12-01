/*
 *  dht.c:
 *	Simple program to output DHT 11 or 22 Sensor values
 *
 */

// pin settings
// raspberry DHT11
//      3,3V 3,3V
//       GND GND
//    GPIO27 Data (Default PIN 2. use "gpio readall" for get the PIN for the used GPIO Port)

#include <wiringPi.h>

#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>

#define MAXTIMINGS	1000
#define APPNAME     "dht"
#define APPVERSION  "0.1"

int dht_data[5] = { 0, 0, 0, 0, 0 };
int empty[0];


// Functions

void usage( int PIN, int TYPE ) {
  printf("%s v%s - Get Values from DHT Sensors\n", APPNAME, APPVERSION );
  printf("Usage:\n");
  printf("%s PIN <TYPE>\n", APPNAME);
  printf("PIN : %i\n", PIN );
  printf("TYPE : %i\n", TYPE );
  printf("Returns Humidity (%%) and Temperatur (°C)\n");
}

int *read_dht_data( int PIN ) {
	uint8_t laststate	= HIGH;
	uint8_t counter		= 0;
	uint8_t j		= 0, i;
	dht_data[0] = dht_data[1] = dht_data[2] = dht_data[3] = dht_data[4] = 0;

	/* pull pin up for 10 ms */
  pinMode(PIN, OUTPUT);
  digitalWrite(PIN, HIGH);
  delay(10);
  /* pull pin down for 18 milliseconds */
	digitalWrite( PIN, LOW );
	delay( 18 );
	/* then pull it up for 40 microseconds */
	digitalWrite( PIN, HIGH );
	delayMicroseconds( 40 );
	/* prepare to read the pin */
	pinMode( PIN, INPUT );

	/* detect change and read data */
	for ( i = 0; i < MAXTIMINGS; i++ ) {
		counter = 0;
		while ( digitalRead( PIN ) == laststate ) {
			counter++;
			delayMicroseconds( 1 );
			if ( counter == 255 ) {
				break;
			}
		}
		laststate = digitalRead( PIN );

		if ( counter == 255 )
			break;

		/* ignore first 3 transitions */
		if ( (i >= 4) && (i % 2 == 0) ) {
			/* shove each bit into the storage bytes */
			dht_data[j / 8] <<= 1;
			if ( counter > 16 )
				dht_data[j / 8] |= 1;
			j++;
		}
	}

	/*
	 * check we read 40 bits (8bit x 5 ) + verify checksum in the last byte
	 * print it out if data is good
	 */
	if ( (j >= 40) && (dht_data[4] == ( (dht_data[0] + dht_data[1] + dht_data[2] + dht_data[3]) & 0xFF) ) ) {
		return dht_data;
	} else {
		return empty;
	}
}

int *getData( int PIN ) {
	// get data
	int *dht_data = read_dht_data( PIN );
	// re run if data is empty
	while ( dht_data[0] == 0 ) {
		dht_data = read_dht_data( PIN );
	}
  return dht_data;
}


// MAIN

int main(int argc, char **argv) {

	if ( wiringPiSetup() == -1 )
		exit( 1 );

  int PIN = 2;
  int TYPE = 22; // default TYPE

  // get arguments
  if ( argc == 1 ) {
    // no args, print usage
    usage( PIN, TYPE );
    return (2);
  } else if ( argc == 2 ) {
    // get pin argv
    PIN = atoi(argv[1]);
  } else if ( argc == 3 ) {
    // get pin and type argv
    PIN = atoi(argv[1]);
    TYPE = atoi(argv[2]);
  }

  int *dht_data;
  // get data
  if( TYPE == 22 ) {
	  dht_data = (int*) getData( PIN );
    float t, h;
    h = (float)dht_data[0] * 256 + (float)dht_data[1];
    h /= 10;
    t = (float)(dht_data[2] & 0x7F)* 256 + (float)dht_data[3];
    t /= 10.0;
    if ((dht_data[2] & 0x80) != 0)  t *= -1;
    printf( "%.2f %.2f", h, t );
  } else {
    dht_data = (int*) getData( PIN );
    printf( "%d.%d %d.%d", dht_data[0], dht_data[1], dht_data[2], dht_data[3]);
  }

	return(0);
}

