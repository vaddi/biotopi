/* 
  usbreset -- send a USB port reset to a USB device 
   
  source: http://askubuntu.com/a/661
	   http://marc.info/?l=linux-usb&m=121459435621262&w=2
  
  Usage: 
  1. Compile the program: 
    $ cc usbreset.c -o usbreset  
  2. Get the Bus and Device ID of the USB device you want to reset
    $ lsusb
    Bus 002 Device 003: ID 0fe9:9010 DVICO
  3. Make our compiled program executable:
    $ chmod +x usbreset
  4. Execute the program with sudo privilege; make necessary substitution for <Bus> and <Device> ids as found by running the lsusb command:
    $ sudo ./usbreset /dev/bus/usb/002/003

*/

#include <stdio.h>
#include <unistd.h>
#include <fcntl.h>
#include <errno.h>
#include <sys/ioctl.h>

#include <linux/usbdevice_fs.h>


int main(int argc, char **argv) {
    const char *filename;
    int fd;
    int rc;

    if (argc != 2) {
        fprintf(stderr, "Usage: usbreset device-filename\n");
        return 1;
    }
    filename = argv[1];

    fd = open(filename, O_WRONLY);
    if (fd < 0) {
        perror("Error opening output file");
        return 1;
    }

    printf("Resetting USB device %s\n", filename);
    rc = ioctl(fd, USBDEVFS_RESET, 0);
    if (rc < 0) {
        perror("Error in ioctl");
        return 1;
    }
    printf("Reset successful\n");

    close(fd);
    return 0;
}

// end of file
