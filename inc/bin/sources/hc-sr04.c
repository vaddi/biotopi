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

#define TRIG 5
#define ECHO 6

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

float mapfloat(float x, float in_min, float in_max, float out_min, float out_max) {
  return (x - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
}

float getMM() {
	pinMode(TRIG, OUTPUT);
	digitalWrite(TRIG, LOW);
	delayMicroseconds(2);
	digitalWrite(TRIG, HIGH);
	delayMicroseconds(10);
	digitalWrite(TRIG, LOW);
	pinMode(ECHO, INPUT);
	return pulseIn(ECHO, HIGH, 3000) / 29.0 / 2.0;
/*	float MM = pulseIn(ECHO, HIGH, 1000) / 29.0 / 2.0;*/
/*	return (int)( mapfloat( MM, 0, 42, 42, 0 ) - 4.5 );*/
}

int main(void) {
        setup();

        printf("%.2f", getMM());

        return 0;
}
