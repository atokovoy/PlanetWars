    function roundRect(ctx, x, y, width, height, radius, fill, stroke) {
    	  if (typeof stroke == "undefined" ) {
    	    stroke = true;
    	  }
    	  if (typeof radius === "undefined") {
    	    radius = 5;
    	  }
    	  ctx.beginPath();
    	  ctx.moveTo(x + radius, y);
    	  ctx.lineTo(x + width - radius, y);
    	  ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
    	  ctx.lineTo(x + width, y + height - radius);
    	  ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
    	  ctx.lineTo(x + radius, y + height);
    	  ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
    	  ctx.lineTo(x, y + radius);
    	  ctx.quadraticCurveTo(x, y, x + radius, y);
    	  ctx.closePath();

    	  ctx.lineWidth = 5;
    	  ctx.fillStyle = "rgba(255,255,255,.7)";
    	  ctx.fill();
    	  ctx.strokeStyle = "rgba(0,0,0,.2)";
    	  ctx.stroke();        
    }
    

    
    function roundButtonRect(ctx, x, y, width, height, radius, text) {
        
	    ctx.beginPath();

	    var gradient = ctx.createLinearGradient(x, y, x, y+height);
        gradient.addColorStop(0, "white");
        gradient.addColorStop(1, "#E6E6E6");
      
	  
    	ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
    	ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
    	ctx.lineTo(x + width, y + height - radius);
    	ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
    	ctx.lineTo(x + radius, y + height);
    	ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
    	ctx.lineTo(x, y + radius);
    	ctx.quadraticCurveTo(x, y, x + radius, y);
       
  	    ctx.closePath();
    	ctx.lineWidth = 1;
    	ctx.fillStyle = gradient;
    	ctx.fill();
    	ctx.strokeStyle = "#ccc";
    	ctx.stroke(); 
    	
    }
