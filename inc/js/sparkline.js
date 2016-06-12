/*
 * Find all elements of CSS class "sparkline", parse their
 * content as a series of numbers, and replace it with a
 * graphical representation.
 *
 * Define sparklines with markup like this:
 *   <span class="sparkline">3 5 7 6 6 9 11 15</span>
 *
 * Style sparklines with CSS like this:
 *   .sparkline { background-color: #ddd; color: red; }
 *
 * - Sparkline color is from the computed style of the CSS
 *   color property.
 * - Sparklines are transparent, so the normal background
 *   color shows through.
 * - Sparkline height is from the data-height attribute if
 *   defined or from the computed style for the font-size
 *   otherwise.
 * - Sparkline width is from the data-width attribute if it
 *   is defined or the number of data points times data-dx
 *   if that is defined or the number of data points times
 *   the height divided by 6
 * - The minimum and maximum values of the y axis are taken
 *   from the data-ymin and data-ymax attributes if they 
 *   are defined, and otherwise come from the minimum and
 *   maximum values of the data.
 */
// Run this code when the document first loads
window.addEventListener("load", function() {
//  sparkline();
}, false);  // last argument to addEventListener()

function drawLine( context, height, width ) {
	context.beginPath();
	context.moveTo(0,height);
	context.lineTo(width -5,height);
	context.strokeStyle = "rgb(0,0,0)";
	context.lineWidth = 0.5;
	context.setLineDash([1,1.5]);
	context.stroke(); 
}

function sparkline( id ) {
	// Find all elements of class "sparkline"
    var elts = document.getElementsByClassName( id );
    // Loop through those elements
    main: for(var e = 0; e < elts.length; e++) { 
        var elt = elts[e];

        // Get content of the element and convert to an
        // array of numbers.  If the conversion fails, skip
        // this element.
        var content = elt.textContent || elt.innerText;
        // Trim leading and trailing whitespace
        var content = content.replace(/^\s+|\s+$/g, "");
        // Remove comments
        var text = content.replace(/#.*$/gm, "");
        // Convert newlines, etc., to spaces
        text = text.replace(/[\n\r\t\v\f]/g, " ");
        // Split numbers on commas or spaces
        var data = text.split(/\s+|\s*,\s*/);
        // For each split piece of the string
        for(var i = 0; i < data.length; i++) {
            data[i] = Number(data[i]); // Convert to number
            if (isNaN(data[i]))        // On failure
                continue main;         // skip this elt.
        }

        // Now compute the color, width, height, and y axis
        // bounds of the sparkline from the data, from data-
        // attributes of the element, and from the computed
        // style of the element
        var style = getComputedStyle(elt, null); 
        var color = style.color;
//				for(var i = 0; i < data.length; i++) {
//				  if( data[i] >= 50 ) {
//				  	color = "rgb( 255, 0, 0 )";
//				  }
//				}
				
				var size =
						parseInt(elt.getAttribute("size")) ||
						1;
        var height =
            parseInt(elt.getAttribute("data-height")) ||
            parseInt(style.fontSize) || 20;
        var datadx = parseInt(elt.getAttribute("data-dx"));
        var width =
            parseInt(elt.getAttribute("data-width")) ||
            data.length*(datadx || height/6);
        var ymin =
            parseInt(elt.getAttribute("data-ymin")) ||
            Math.min.apply(Math, data) - ( size * 2);
        var ymax =
            parseInt(elt.getAttribute("data-ymax")) ||
            Math.max.apply(Math, data) + ( size * 2);
        if (ymin >= ymax) ymax = ymin + 1;

        // Create the canvas element
        var canvas = document.createElement("canvas"); 
        canvas.width = width;     // Set canvas dimensions
        canvas.height = height;
        // Use the element content as a tooltip if title is not set
        if( elt.getAttribute("title") === undefined || elt.getAttribute("title") === null ) canvas.title = content;   
        elt.innerHTML = "";      // Erase existing content
        elt.appendChild(canvas); // Insert canvas into elt

        // Now plot the points in the canvas
        var context = canvas.getContext('2d');
        for(var i = 0; i < data.length; i++) { 
            // Transform (i,data[i]) to canvas coordinates
            var x = width*i/data.length;
            var y = (ymax-data[i])*height/(ymax-ymin);
            // Draw a line to (x,y). Note that the first
            // call to lineTo() does a moveTo() instead.
            context.lineTo(x,y); 
        }
        
        if( elt.getAttribute("color") !== "" && elt.getAttribute("color") === "bw" ) {
        	var gradient = context.createLinearGradient(0,0,0,height);
					gradient.addColorStop("0","#BBB");
					gradient.addColorStop("0.3","#999");
					gradient.addColorStop("0.7","#777");
					gradient.addColorStop("1","#000");
					context.strokeStyle = gradient; 
        } else if( elt.getAttribute("color") !== "" && elt.getAttribute("color") === "auto" ) {
        	var gradient = context.createLinearGradient(0,0,0,height);
					gradient.addColorStop("0","#F00");
					gradient.addColorStop("0.3","#FF0");
					gradient.addColorStop("0.7","#FF0");
					gradient.addColorStop("1","#090");
					context.strokeStyle = gradient; 
        } else if( elt.getAttribute("color") !== "" && elt.getAttribute("color") !== "auto" ) {
        	var col = elt.getAttribute("color").split(" ");
        	var gradient = context.createLinearGradient(0,0,0,height);
					gradient.addColorStop("0",col[0]);
					gradient.addColorStop("0.3",col[1]);
					gradient.addColorStop("0.7",col[1]);
					gradient.addColorStop("1",col[2]);
				  context.strokeStyle = gradient;
        } else {
        	context.strokeStyle = color; // Specify color
        }
        
        context.lineWidth = size;		// set line thickness
        context.stroke();           // and draw it
        
				drawLine( context, 0 , width ); // Top line
				var grid = parseInt( elt.getAttribute("grid")) || 0;
				if( grid >= 1 ) {
					// gridlines 
					// if height > 20, every 10px a line
					// if height < 20, only one line on half size
					var gridlines = ( ( height / 10 ) > 2 ) ? ( height / 10 ) : ( height / 2 );
					var tempgrid = 0;
					for(var i = 0; i <= gridlines; i++) {
						tempgrid += ( ( height / 10 ) > 2 ) ? ( height / 10 ) : ( height / 2 );
						drawLine( context, tempgrid , width );
					}
				}
				
				drawLine( context, height  , width ); // Bottom line 
				
				context = null;   
    }
    
}
