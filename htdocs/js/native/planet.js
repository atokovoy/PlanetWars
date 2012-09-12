
    var planet = function(planetId, x, y, growth, team, image, count, radius)  {
        this.x = x;
        this.y = y;
        this.planetId = planetId; 
        this.count = parseInt(count); 
        this.image = image;
        this.growth = growth;
        this.radius = radius;
        this.drawed = false;

        this.setTeam(team);
    }
    
    planet.prototype.getConfig = function() {

    	return {planetId:this.planetId, x:this.x, y:this.y, ships:this.count, growth:this.growth, ownerId:this.team.teamId}
    }

    planet.prototype.move = function(timeDiff) {
    	// neutrality dont grow


        if (0 != this.team.teamId) {
            this.count += timeDiff * this.growth;
            this.count = Math.round(this.count * 1000000)/1000000;
        }
    }


    planet.prototype.setTeam = function(team) {
    	this.team = team;
    	this.color = team.color;
    }
    
    planet.prototype.inBounds = function(x, y) {
      return ((x - this.x) ^ 2 + (y - this.y) ^ 2 < this.radius ^ 2);
    }
    
    planet.prototype.draw = function(ctx, wctx) {

        //Time for some colors
        var gradient = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.radius);
        gradient.addColorStop(0, "rgba(255,255,255,0)");
        gradient.addColorStop(0.70, "rgba(255,255,255,0)");
        gradient.addColorStop(0.80, this.color);
        //gradient.addColorStop(0.85, this.color);
        //gradient.addColorStop(0.70, "#1e5799");
        gradient.addColorStop(.9, "rgba(255,255,255,0)");
        gradient.addColorStop(1, "rgba(255,255,255,0)");
        
        ctx.fillStyle = gradient;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, Math.PI*2, false);
        ctx.closePath();
        ctx.fill();
        
        
        roundRect(ctx, this.x+10, this.y, 40, 23, 5, "red", "5") ;
        ctx.font = "italic 9pt Calibri";
        ctx.fillStyle = "rgba(0,0,0,0.7)";
        ctx.fillText(Math.floor(this.count), this.x+14, this.y+15);

        
        if (this.drawed) return;
        
        var radius = this.radius*1.5
        wctx.drawImage(this.image, this.x - radius/2, this.y - radius/2, radius, radius);
        this.drawed = true;

    };