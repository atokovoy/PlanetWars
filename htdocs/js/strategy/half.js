    if (typeof strategies == 'undefined') {
    	strategies = {}
    }


    strategies.half = function(){
        this.team = null;

    }
    
    strategies.half.prototype.handleStep = function(world, successhandler, faulthandler, scope) {
    	
    	var pushed = [];
    	
    	var teamPlanets = []; var targetPlanets = [];
    	for (var i=0; i < world.planets.length; i++) {
    	    if (world.planets[i].team == this.team) {
    	    	teamPlanets.push(world.planets[i]);
    	    } else {
    	    	targetPlanets.push(world.planets[i]);
    	    }
    	}
    	
    	if (!targetPlanets.length || !teamPlanets.length) {
    		successhandler.call(scope, {});
    		return true;
    	}
    	
    	for (var i =0; i < Math.round(teamPlanets.length/2); i++) {
    	
	        var from = teamPlanets[Math.floor(Math.random()*teamPlanets.length)];
	        var to   = targetPlanets[Math.floor(Math.random()*targetPlanets.length)];
	        
	        var cnt = Math.floor(from.count/2);
	        if (!cnt) continue;
	        
	        var found = false;
	        for (var o=0; o<pushed.lenght; o++) {
	        	if (pushed[o] == from) {
	        		found = true;
	        		break; 
	        	}
	        }
	        if (found) continue;
	        
	        pushed.push(from);
	        
	        world.squadrons.push(new squadron(cnt, from, to));
    	
    	}
        
		successhandler.call(scope, {});
        return true;
    }
    
