// raspberry Shiftregister 74x595
//        5V 5V 
//       GND GND
//     GPIO5 DATA
//     GPIO6 CLOCK
//    GPIO13 LATCH
/*
 * sr.c:
 *      Shift register test program
 *
 * Copyright (c) 2012-2013 Gordon Henderson. <projects@drogon.net>
 ***********************************************************************
 */

#include <stdio.h>
#include <wiringPi.h>
#include <wiringShift.h>

// wirinPi pins
#define DATA 21
#define CLOCK 22
#define LATCH 23

#define LSBFIRST        0
#define MSBFIRST        1

// shift register output addresses
// 128 64 32 16 8 4 2 1
int rel[] = { 1, 2, 4, 8, 16, 32, 64, 128 };
int relState[] = { LOW, LOW, LOW, LOW, LOW, LOW, LOW, LOW }; // Schaltzustände der Relais Pins

// Schieberegisterfunktion
void sendeBytes(int wert) {
  digitalWrite(LATCH,LOW);
  shiftOut(DATA, CLOCK, MSBFIRST, wert >> 8);
  shiftOut(DATA, CLOCK, MSBFIRST, wert & 255);
  digitalWrite(LATCH,HIGH);
}

void usage() {
	printf( "\n" );
	printf( "\n" );
	printf( "\n" );
}

void toggleRelais( int wert ) {
	if( wert > sizeof(rel)/sizeof(unsigned int) ) return;
/*	if( wert <= 0 ) {*/
/*		*/
/*		*/
/*	}*/
	if( relState[ wert ] ) {
		relState[ wert ] = LOW;
	} else {
		relState[ wert ] = HIGH;
	}
	
}

void setup() {
  wiringPiSetup();
  pinMode(DATA, OUTPUT);
	pinMode(CLOCK, OUTPUT);
	pinMode(LATCH, OUTPUT);
}

int my_getnbr(char *str) {
  int result, puiss;
  result = 0;
  puiss = 1;
  while (('-' == (*str)) || ((*str) == '+')) {
    if (*str == '-')
      puiss = puiss * -1;
    str++;
  }
  while ((*str >= '0') && (*str <= '9')) {
    result = (result * 10) + ((*str) - '0');
    str++;
  }
  return (result * puiss);
}

// input rel address 
// as byte eg: 1, 2, 4, 8, 16, 32, 64, 128
// as sum  eg: 3, 131
int main (int argc, char **argv) {
	
	int relIds = 0;
	int cmd = 0;
	
	if (argc >= 2) {
		/* there is 1 parameter (or more) in the command line used */
    /* argv[0] may point to the program name */
    /* argv[1] points to the 1st parameter */
    /* argv[argc] is NULL */
    if ( argv[1] != NULL ) {
/*    	str1[] = argv[1];*/
/*    	char str2[] = "set";*/
/*    	if( strcmp( str1, str2 ) == 0 ) cmd = 1;*/
			 cmd = 1;
    } 
    if ( argv[2] != NULL ) {
    	relIds = my_getnbr( argv[2] );
    	if( relIds <= 256 && relIds >= 0 ) {
    		if( relIds == 256 ) { toggleRelais(); relIds += -1; }
/*    		relIds = relIds ;*/
    	} else {
				relIds = 0;
			}	
    } 
	} 
	
	int erg;
	
  setup();
	
	switch( cmd ) {
		case 0: 
			
		break;
		case 1: 
			if( relIds >= 0 ) erg = relIds;
			sendeBytes( erg );
		break;
		default: 
			
		break;
	}
	
/*	int i*/
/*	for( i = 0; i < sizeof(rel)/sizeof(sizeof(unsigned int)); ++i ) {*/
/*		toggleRelais( i );*/
/*		if( relState[ i ] == HIGH ) erg = erg + rel[ i ];*/
/*	}	*/
	
	
	printf( "%i", erg );
	
	
  return 0 ;
}

