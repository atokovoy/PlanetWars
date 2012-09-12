
    var space = function(width, height, options) {
        this.width = width;
        this.height = height;
        this.options = {
             stars: 200,
             psize: 50,
        }
        if (options) {
        	for(var o in options){
        		//this.options[o] = options[o];
        	}
        }
    }
    
    
    
    space.prototype.draw = function(wctx) {
    	          

        //var imageObj = this.image;
        //imageObj.width = this.width;
        //imageObj.height = this.height;
        //wctx.drawImage(imageObj, 0, 0, this.width, this.height);
        
        var pcanvas = document.createElement('canvas'), img = new Image();
        pcanvas.height = pcanvas.width = this.options.psize;
        
        var pctx = pcanvas.getContext("2d");
        for (i = 0; i <= this.options.stars; i++) {
//          for (i = 0; i <= ; i++) {
          // Get random positions for stars.
          var x = Math.random() * (this.options.psize) + 10;
          var y = Math.random() * this.options.psize + 10;
          var o = Math.round(Math.random()*100)/100;
          if (0.1 > o) o = 0.1; else if (0.5 < o) o = 0.4;

          var r = Math.round(Math.random()*255);
          var g = Math.round(Math.random()*255);
          var b = Math.round(Math.random()*255);
          
          if (r < 200)
        	  pctx.fillStyle = "rgba("+r+","+g+","+b+", "+o+")";
          else
        	  pctx.fillStyle = "rgba(255,255,255, "+o+")";
          
          var o = Math.round(Math.random()*100)/100;
          if (0.1 > o) o = 0.1; else if (0.5 < o) o = 0.6;
         
          
          // Draw an individual star.
          pctx.beginPath();
          pctx.arc(x, y, o, 0, 1, true);
          pctx.closePath();
          pctx.fill();
          
          
        } 
        
        // get image data
    

        img.src = pcanvas.toDataURL();
        
        wctx.fillStyle = wctx.createPattern(img, "repeat");
        console.debug(wctx, wctx.canvas.width, wctx.height);
        wctx.fillRect(2, 2, 100, 100);

        this.drawed = true;
        
    };