        

    var squadron = function(count, from, to, totalSteps) {
        //Random position on the canvas
        this.x = from.x;
        this.y = from.y;
        this.color = from.color;
        this.count = count;
        this.finished = false;
        this.id = Math.round(Math.random()*100);
        this.team = from.team;
        
        from.count = from.count - this.count;
        
        this.target = to;
        
        var distance = Math.sqrt(Math.pow(from.x - to.x, 2) + Math.pow(from.y - to.y, 2));
        var totalTime  = totalSteps?(totalSteps):Math.ceil(distance/100);
                
        this.xStep = (from.x - to.x) / totalTime;
        this.yStep = (from.y - to.y) / totalTime;

        this.timeLeft = totalTime;
        this.explosion = false;

    }
    
    squadron.prototype.getExplosion = function(){
        return new explosion(this);
    };
    
    squadron.prototype.move = function(timeDiff){
    	

    	if (this.finished) {
    		return;
    	}
    	
    	if (this.timeLeft) {
    		
    		this.timeLeft = this.timeLeft - timeDiff;
            this.timeLeft = Math.round(this.timeLeft * 1000000)/1000000;
        	this.x = - this.xStep * timeDiff + this.x;
            this.y = - this.yStep * timeDiff + this.y;
            
    	}

    	if (this.explosion) {
    		this.explosion.move(timeDiff);
    	} else if (!this.timeLeft) {
    		
    		if (this.target.team == this.team) {
    			this.finished = true;
    			this.target.count = parseInt(this.target.count) + parseInt(this.count);
    		} else {
    			if (this.target.count > this.count) {
        			this.target.count = this.target.count - this.count;
        		} else {
        			this.target.count = this.count - this.target.count;
        			this.target.setTeam(this.team);
        		}
        		this.explosion = this.getExplosion();
    		}
    		
    	}        
        
    };
    
    
    
    squadron.prototype.draw = function(ctx, wctx) {
    	
    	if (this.finished) {
    		return;
    	}
    	    	
    	if (this.explosion) {
    		this.explosion.draw(ctx);
    		return;
    	}
    	
    	
/**
    	ctx.save();
    	
    	context.globalCompositeOperation = 'source-in';
        context.fillStyle = 'rgba(128,128,128,0.85)';
        context.fillRect(0, 0, world.width, world.height);

        // dot drawing style
        context.globalCompositeOperation = 'lighter';
        context.fillStyle = 'rgba(128,128,128,0.5)';
        
        ctx.restore();
  	
    	**/
    	//return;
//
        this.radius = 5;
        
    	//*  */return;
    	/**
    	var imageObj = new Image(), self = this;
        imageObj.width = 50;
        imageObj.height = 50;
        imageObj.onload = function() {
        	ctx.drawImage(imageObj, self.x, self.y, 20, 30);
        };
        imageObj.src= 'svg/space-craft.svg';
        **/
        
      //Time for some colors
      /**
        var p = this;
      
        var gradient = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.radius);
        gradient.addColorStop(0, "white");
        gradient.addColorStop(0.4, p.color);
        gradient.addColorStop(0.4, p.color);
        gradient.addColorStop(1, "black");
        
        ctx.fillStyle = gradient;
        ctx.arc(p.x, p.y, p.radius, Math.PI*2, false);
        ctx.fill();
        
        return;
        
        
        
        
        
    	
        //ctx.globalAlpha = 0.4;
        ctx.globalCompositeOperation = "source-over";
        //Lets reduce the opacity of the BG paint to give the final touch
        ctx.fillStyle = "rgba(0, 0, 0, 0.1)";
        ctx.fillRect(this.x-50, this.y-50, 100, 100);
        
        //Lets blend the particle with the BG
        ctx.globalCompositeOperation = "lighter";
        
        ctx.save();
        
    	**/
    	
    	
    	
    	 //Time for some colors
        var gradient = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.radius+9);
        gradient.addColorStop(0, this.color);
        gradient.addColorStop(1, "rgba(255,255,255,0)");
        
        ctx.fillStyle = gradient;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius+9, Math.PI*2, false);
        
        ctx.closePath();
        ctx.fill();
    	
    	
    	
        for(var i=0; i<10;i++) {
            
        	var radius = this.radius-2-i*0.4;
        	if (radius<2) {
        		radius = 2-i*0.1;
        	}
        	
        	var gradient = ctx.createRadialGradient(this.x + this.xStep*i*0.15, (this.y+this.yStep*i*0.15), 0, this.x + this.xStep*i*0.15, (this.y+this.yStep*i*0.15), radius);
            gradient.addColorStop(0, "white");
            gradient.addColorStop(1, "rgba(255,255,255,0)");
            ctx.fillStyle = gradient;
            ctx.beginPath();
            ctx.arc(this.x + this.xStep*i*0.15, (this.y+this.yStep*i*0.15),radius, Math.PI*2, false);
            ctx.closePath();
            ctx.fill();
        }
        
        //ctx.restore();
        

        //Time for some colors
        var gradient = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.radius-3+3);
        gradient.addColorStop(0, "white");
        gradient.addColorStop(0.24, this.color);
        gradient.addColorStop(0.50, this.color);
        //gradient.addColorStop(0.70, "#1e5799");
        gradient.addColorStop(1, "rgba(255,255,255,0.9)");
        
        ctx.fillStyle = gradient;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius-3, Math.PI*2, false);
        
        ctx.closePath();
        ctx.fill();

        roundRect(ctx, this.x+10, this.y, 40, 23, 5, "red", "5") ;
        ctx.font = "italic 9pt Calibri";
        ctx.fillStyle = "rgba(0,0,0,0.7)";

        ctx.fillText(this.count, this.x+14, this.y+15);
        
    };
    

    
