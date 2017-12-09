/*
 *  relais.c:
 *  BiotoPi Project
 *  Switch 16 Relais over 74x595 Shift register
 */

// Pin Layout
// raspberry 74x595
//        5V 5V
//       GND GND
//     GPIO5 DATA
//     GPIO6 CLOCK
//    GPIO13 SAVE

#include <stdio.h>
#include <stdlib.h>
#include <wiringPi.h>
#include <wiringShift.h>

#define APPNAME     "relais"
#define APPVERSION  "0.1"

#define LSBFIRST        0
#define MSBFIRST        1

// shift register output addresses
long rel[] = { 1, 2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4096, 8192, 16384, 32768 };

// Shift register function
void sendeBytes(int DATA, int CLOCK, int SAVE, long wert) {
  digitalWrite(SAVE,LOW);
  shiftOut(DATA, CLOCK, MSBFIRST, wert >> 8);
  shiftOut(DATA, CLOCK, MSBFIRST, wert & 65535);
  digitalWrite(SAVE,HIGH);
}

void usage( int DATA, int CLOCK, int SAVE ) {
  printf( "%s v%s - Switch Relais over Shiftregister 74x595\n", APPNAME, APPVERSION );
  printf( "Usage:\n" );
  printf( "%s DATA CLOCK SAVE <VALUE>\n", APPNAME );
  printf( "DATA : \t%i\n", DATA );
  printf( "CLOCK : %i\n", CLOCK );
  printf( "SAVE : \t%i\n", SAVE );
  printf( "VALUE : %i (0-65536)\n", 0 );
  printf( "Return the switched value\n" );
}

void setup( int DATA, int CLOCK, int SAVE ) {
  wiringPiSetup();
  pinMode(DATA, OUTPUT);
  pinMode(CLOCK, OUTPUT);
  pinMode(SAVE, OUTPUT);
}


// Main
int main (int argc, char **argv) {
  int DATA = 21;
  int CLOCK = 22;
  int SAVE = 23;
  long VALUE = 0;

  // get arguments
  if ( argc != 4 && argc != 5 ) {
    // no args, print usage
    usage( DATA, CLOCK, SAVE );
    return (2);
  } else if ( argc == 4 ) {
    DATA = atoi(argv[1]);
    CLOCK = atoi(argv[2]);
    SAVE = atoi(argv[3]);
    VALUE = 0;
  } else if ( argc == 5 ) {
    DATA = atoi(argv[1]);
    CLOCK = atoi(argv[2]);
    SAVE = atoi(argv[3]);
    VALUE = atoi(argv[4]);
  }

  if ( VALUE > 65536 || VALUE < 0 ) VALUE = 0;

  setup( DATA, CLOCK, SAVE );
  sendeBytes( DATA, CLOCK, SAVE, VALUE );

  printf( "%ld", VALUE );

  return (0);
}
