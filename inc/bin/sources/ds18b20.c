/*

Based on:  

*/


#include <stdio.h>
#include <dirent.h>
#include <string.h>
#include <fcntl.h>
#include <stdlib.h>
#include <unistd.h>

int main(int argc, char **argv) {
  if (argc >= 2) {
    /* there is 1 parameter (or more) in the command line used */
    /* argv[0] may point to the program name */
    /* argv[1] points to the 1st parameter */
    /* argv[argc] is NULL */

		if ( argv[1] != NULL ) {
			char devPath[128]; // Path to device
			char buf[256];     // Data from device
			char tmpData[6];   // Temp C * 1000 reported by device 
			char path[] = "/sys/bus/w1/devices";
			ssize_t numRead;
			
			sprintf(devPath, "%s/%s/w1_slave", path, argv[1]);
			
/*			while(1) {*/
				int fd = open(devPath, O_RDONLY);
				if(fd == -1) {
					perror ("Couldn't open the w1 device.");
					return 1;   
				}
				while((numRead = read(fd, buf, 256)) > 0) {
					strncpy(tmpData, strstr(buf, "t=") + 2, 5); 
					float tempC = strtof(tmpData, NULL);
					printf("%.3f\n", tempC / 1000);
				}
				close(fd);
/*			}*/
			
		}
    
  }
  return 0;
}
