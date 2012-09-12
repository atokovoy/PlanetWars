    
    

    var explosion = function(source) {

    	this.x = source.x;
        this.y = source.y;
        this.color = source.color;
        this.radius = 70;
        this.opacity = 6;
        this.frame = 0;

    }
    

    explosion.prototype.move = function(timeDiff){
    
    }
    
    explosion.prototype.draw = function(ctx, wctx) {

    	if (this.opacity == 0) {
    		return;
    	}
    	
    	this.opacity = this.opacity-0.5;
    	this.frame++;
    	
    	//Time for some colors
    	/**
        var gradient = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.radius);
        gradient.addColorStop(0, "rgba(255,255,255,0)");
        gradient.addColorStop(0.2, "rgba(255,216,18,"++")");
        gradient.addColorStop(.9, "rgba(255,255,255,0)");
        gradient.addColorStop(1, "rgba(255,255,255,0)");
        
        ctx.fillStyle = gradient;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, Math.PI*2, false);
        ctx.closePath();
        ctx.fill();
        **/
        
        ctx.save();
        
        ctx.globalAlpha = this.opacity/10;
        
        var gradient = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.radius);
        gradient.addColorStop(0, this.color);
        gradient.addColorStop(0.1, this.color);
        gradient.addColorStop(1, "rgba(255,255,255,0)");
        
        ctx.fillStyle = gradient;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, Math.PI*2, false);
        ctx.closePath();
        ctx.fill();
        
        
        
        var strategy = Math.random()/4;
        
        for(var i = 0; i < this.frame*10; i++) {
        	
        	var length = this.frame + i*strategy;

        	var x = this.x + length*Math.sin(0.5*i);
        	var y = this.y + length*Math.cos(0.5*i); 

        	// random
        	//var x = this.x + length*Math.sin(Math.random()*i);
        	//var y = this.y + length*Math.cos(Math.random()*i); 
        	
        	 //Time for some colors
            var gradient = ctx.createRadialGradient(x, y, 0, x, y, 3);
            gradient.addColorStop(0, this.color);
            gradient.addColorStop(1, "rgba(255,255,255,0)");
        	
        	
            ctx.fillStyle = gradient;
            ctx.beginPath();
            ctx.arc(x, y, 3, Math.PI*2, false);
            ctx.closePath();
            ctx.fill();
            
        	
        }
        
        ctx.restore();
    };
    
 
    