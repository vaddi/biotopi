/*
 *  hc-sr04.c:
 *  BiotoPi Project
 *  Output HC-SR04 Sensor Value
 *
 */
// raspberry HC-SR04
//        5V 5V
//       GND GND
//    GPIO24 Trigger
//    GPIO25 Echo

#include <stdio.h>
#include <stdlib.h>
#include <wiringPi.h>
#include <sys/time.h>

#define TRUE 1

#define APPNAME     "hc-sr04"
#define APPVERSION  "0.1"

void usage( TRIG, ECHO ) {
  printf("%s v%s - Get Values from HC-SR04 Sensors\n", APPNAME, APPVERSION );
  printf("Usage:\n");
  printf("%s TRIG ECHO\n", APPNAME);
  printf("TRIGGER PIN : %i\n", TRIG );
  printf("ECHO PIN  \t: %i\n", ECHO );
  printf("Returns distance in mm\n");
}

void setup() {
  wiringPiSetup();
}

int pulseIn(int pin, int level, int timeout) {
   struct timeval tn, t0, t1;
   long micros;
   gettimeofday(&t0, NULL);
   micros = 0;
   while (digitalRead(pin) != level) {
      gettimeofday(&tn, NULL);
      if (tn.tv_sec > t0.tv_sec) micros = 1000000L; else micros = 0;
      micros += (tn.tv_usec - t0.tv_usec);
      if (micros > timeout) return 0;
   }
   gettimeofday(&t1, NULL);
   while (digitalRead(pin) == level) {
      gettimeofday(&tn, NULL);
      if (tn.tv_sec > t0.tv_sec) micros = 1000000L; else micros = 0;
      micros = micros + (tn.tv_usec - t0.tv_usec);
      if (micros > timeout) return 0;
   }
   if (tn.tv_sec > t1.tv_sec) micros = 1000000L; else micros = 0;
   micros = micros + (tn.tv_usec - t1.tv_usec);
   return micros;
}

float getMM( int TRIG, int ECHO ) {
	pinMode(TRIG, OUTPUT);
	digitalWrite(TRIG, LOW);
	delayMicroseconds(2);
	digitalWrite(TRIG, HIGH);
	delayMicroseconds(10);
	digitalWrite(TRIG, LOW);
	pinMode(ECHO, INPUT);
	return pulseIn(ECHO, HIGH, 3000) / 29.0 / 2.0;
}

int main(int argc, char **argv) {
  int TRIG = 5;
  int ECHO = 6;

  if (argc != 3) {
    usage( TRIG, ECHO );
    return 2;
  }

  // get argv
  TRIG = atoi(argv[1]);
  ECHO = atoi(argv[2]);

  setup();
  printf("%.2f", getMM( TRIG, ECHO ));
  return 0;
}
