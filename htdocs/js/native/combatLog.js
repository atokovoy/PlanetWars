
var combatLog = function(data, world) {
    this.data = data;
    this.world = world;
}
    

combatLog.prototype.getOrdersForTeam = function(step, team) {
    if (this.data.combat.length > 0
        && this.data.combat[step]
        && this.data.combat[step]
        && this.data.combat[step].order) {


        var orders = this.data.combat[step].order, issues = [];
        for (var i = 0; i < orders.length; i++) {
            var issue = orders[i];
            if (this.world.planets[issue.srcPlanet].team == team) {
                issues.push(issue);
            }
        }
        return issues;
    }
}

combatLog.prototype.getPlanetOnStep = function(step, planetId) {
    //console.debug(planetId, step, this.data.combat[step]);
    if (this.data.combat.length > 0
        && this.data.combat[step]
        && this.data.combat[step].planets) {
        var planets = this.data.combat[step].planets;
        for (var i = 0; i < planets.length; i++) {
            if (planets[i].planetId == planetId) {
                return planets[i];
            }
        }
    }
    return false;
}
