var team = function(color, id) {
	this.color = color;
	this.strategy = new strategies.half();
	this.planets = [];
	this.teamId = id;
}


team.prototype.addPlanet = function(planet) {
	this.planets.push(planet);
}

team.prototype.removePlanet = function(planet){    
    var idx = this.planets.indexOf(planet); 
    if (idx != -1) this.planets.splice(idx, 1);    
}


team.prototype.handleStep = function(world, successhandler, faulthandler, scope) {
	return this.strategy.handleStep(world, successhandler, faulthandler, scope);
}


team.prototype.setStrategy = function(strategy) {
    this.strategy = strategy;
    this.strategy.team = this;
}

