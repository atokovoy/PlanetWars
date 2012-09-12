    if (typeof strategies == 'undefined') {
    	strategies = {}
    }


    strategies.neutrality = function(){
        this.team = null;
    }
    
    strategies.neutrality.prototype.handleStep = function(world, successhandler, faulthandler, scope) {
		successhandler.call(scope, {});
        return true;
    }
    
