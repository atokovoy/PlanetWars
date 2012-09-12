
    if (typeof strategies == 'undefined') {
    	strategies = {}
    }


    strategies.bot = function(opt){
        this.url = opt.url;
    }
    
    strategies.bot.prototype.handleStep = function(world, cteam, successhandler, faulthandler, scope) {

    	var params = {
    		planets:[],
    		squadrons:[]
    	};
    	
    	for (var i = 0; i < world.planets; i++){
    		params.planets.push(world.planets[i].getConfig());
    	}
    	
    	for (var i = 0; i < world.squadrons; i++){
    		params.planets.push(world.planets[i].getConfig());
    	}
    	
    	
    	$.post("bots/sample.php", params, function(result){
    		if (result.success) {
            	successhandler.call(scope, result);
            } else {
            	faulthandler.call(scope, result);
            }
    	}, "json");
    	
    }
    
