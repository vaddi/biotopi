/*
 *  dht.c:
 *  BiotoPi Project
 *  Output DS18b20 Temperature Sensor Data
 */

// PIN Settings
// Raspberry DS18b20
//      3,3V 3,3V
//       GND GND
//     GPIO4 Data

//#include <sys/types.h>
#include <dirent.h>
#include <errno.h>
#include <string.h>
#include <stdio.h>
#include <fcntl.h>
#include <stdlib.h>
#include <unistd.h>
//#include <glob.h>

#define APPNAME     "ds18b20"
#define APPVERSION  "0.1"

char PATH[] = "/sys/bus/w1/devices";

void list_devices() {
  DIR *dir;
  struct dirent *ent;
  int total = 0;

  if ( ( dir = opendir ( PATH ) ) != NULL ) {
    /* print all the files and directories within directory except first both (. and ..) */
    while ( ( ent = readdir ( dir ) ) != NULL ) {
      if ( total > 1 ) {
        printf ("%s\n", ent->d_name);
      }
      ++total;
    }
    closedir (dir);
  }
}

void usage() {
  printf("%s v%s - Get Values DS18b20 Sensors\n", APPNAME, APPVERSION );
  printf("Usage:\n");
  printf("%s [ARG] [PATH]\n", APPNAME);
  // list all connected devices
  printf("- List of devices -\n");
  list_devices();
  printf("- End of list -\n");
  printf("[PATH]\t = path      - %s\n", PATH);
  printf("[ARG] \t = device id - Return Device Temperatur Value (°C)\n");
  printf("[ARG] \t = 0         - Return List of Devices\n");
  printf("Example:\n");
  printf("%s 28-000004d0e3cf\n", APPNAME);
  printf("25.187\n");
}

int main(int argc, char **argv) {
  if (argc == 2 || argc == 3 ) {
    if( argc == 3 ) {
      // TODO add path
      //PATH = argv[2];
    }
    if ( argv[1] != NULL ) {
      if ( atoi(argv[1]) == 0 ) {
        // list all devices
        list_devices();
      } else {
        char devPath[128]; // Path to device
        char buf[256];     // Data from device
        char tmpData[6];   // Temp C * 1000 reported by device
        ssize_t numRead;

        // concat device PATH
        sprintf(devPath, "%s/%s/w1_slave", PATH, argv[1]);

        int fd = open(devPath, O_RDONLY);
        if(fd == -1) {
          perror ("Couldn't open the w1 device.");
          return 1;
        }
        while((numRead = read(fd, buf, 256)) > 0) {
				  strncpy(tmpData, strstr(buf, "t=") + 2, 5);
				  float tempC = strtof(tmpData, NULL);
				  printf("%.2f\n", tempC / 1000);
			  }
			  close(fd);
      }
		}
  } else {
    usage();
    return 2;
  }
  return 0;
}
