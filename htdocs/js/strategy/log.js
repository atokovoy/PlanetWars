
    if (typeof strategies == 'undefined') {
    	strategies = {}
    }


    strategies.log = function(){
        this.team = null;
    }
    
    strategies.log.prototype.handleStep = function(world, successhandler, faulthandler, scope) {

        var teamPlanets = {}, targetPlanets = {}, step = world.step;
        for (var i=0; i < world.planets.length; i++) {
    	    if (world.planets[i].team == this.team) {
    	    	teamPlanets[world.planets[i].planetId] = world.planets[i];
    	    }
            targetPlanets[world.planets[i].planetId] = world.planets[i];
    	}


        var orders = world.log.getOrdersForTeam(step, this.team);
        if (orders) {
            for (var i = 0; i < orders.length; i++) {
                var issue = orders[i];

                if (!teamPlanets[issue.srcPlanet] || !targetPlanets[issue.targetPlanet]) {
                    console.debug(targetPlanets, teamPlanets, issue);
                } else {
                    //console.debug(issue, world.log.data);
                }

                world.squadrons.push(new squadron(issue.ships, teamPlanets[issue.srcPlanet], targetPlanets[issue.targetPlanet], issue.totalTurns));
            }
        }
		successhandler.call(scope, {});
        return true;

    }
    
