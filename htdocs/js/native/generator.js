
    var mapGenerator = function(teams, bgs){
    	

        this.minRadius = 30;
        this.maxRadius = 100;
        
        this.minGrowth = 10;
        this.maxGrowth = 100;
        
        this.minDistance = 10;
        
        this.teams = teams;
        this.bgs = bgs;

    	
    }
    
    mapGenerator.prototype.isPosCorrect = function(planets, to){

       var from;
       for(var i = 0; i < planets.length; i++){
           from = planets[i];
           distance = Math.sqrt(Math.pow(from.x - to.x, 2) + Math.pow(from.y - to.y, 2));
           if ((distance - from.radius - to.radius) < this.minDistance ) {
           	   return false;
           }         
       }
            	
       return true;
   };
           

   mapGenerator.prototype.getMap = function(width, height, count){

	   var radius, pos, team, bg, growth, planets = [];
	   
	   for(var i = 0; i < count; i++) {
	       var limit = 10000;
		   do {	
			    // there can be infinity loop, but iam lazy
		   		radius = Math.random()*this.maxRadius;
		        radius = (radius<this.minRadius)?this.minRadius:radius;
		        pos = {x: Math.random()*width, y: Math.random()*height, radius:radius};	
		        limit--;
		        if (!limit) {
		        	pos = false;
		        	break;
		        }
		   } while (!this.isPosCorrect(planets, pos));
		   	
		   if (!pos) {
			   break;
		   }
		   
		   bg = this.bgs[Math.floor(Math.random()*this.bgs.length)];
		   team = this.teams[Math.floor(Math.random()*this.teams.length)];
		   
		   growth = this.minGrowth + Math.floor(Math.random()*(this.maxGrowth - this.minGrowth));
	       
		   planets.push(new planet(i, pos.x, pos.y, 20, team, bg, 100, radius));
	   
	   }
	   
	   return planets;
  };
        
            
            
