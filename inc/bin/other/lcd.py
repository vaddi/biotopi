
import sys
import datetime
from lcd_display import lcd

#print 'Num Args: ', len(sys.argv)
#print 'Args: ', str(sys.argv)
#today = date.today()

datetime = str( datetime.datetime.now().strftime("%d.%m.%y %H:%M:%S") )
#date = str( datetime.datetime.now().strftime("%Y-%m-%d") )
#time = str( datetime.datetime.now().strftime("%H:%M:%S") )

all = str(sys.argv[1])

#all = all.replace( "{time}", time)
#all = all.replace( "{date}", date)
all = all.replace( "{datetime}", datetime)

line1 = all[:20]
line2 = all[20:40]
line3 = all[40:60]
line4 = all[60:80]

#total = line1[:-80]

my_lcd = lcd()

my_lcd.display_string( line1, 1)
my_lcd.display_string( line2, 2)
my_lcd.display_string( line3, 3)
my_lcd.display_string( line4, 4)

#my_lcd.clear()
#my_lcd.backlight_off()
#my_lcd.display_off()
#my_lcd.display_on()

