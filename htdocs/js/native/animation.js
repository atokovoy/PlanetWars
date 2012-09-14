
    var animation = function(context, wcontext, world) {
    	this.context = context;
    	this.wcontext = wcontext;
    	this.world = world;
    	this.stoped = true;
    	this.stepTime = 50;
    }


    animation.prototype.stop = function() {
    	this.stoped = true;
    }

    animation.prototype.next = function() {
    	if (!this.stoped) return;
    	this.play();
    	this.stoped = true;
    }
    
    animation.prototype.nextStep = function(successhandler, faulthandler, scope, team) {

    	if (!team) team = 0;

    	if (team >= this.world.teams.length) {
    	    successhandler.call(scope, {});
    	    return;
    	}
    	
    	this.world.teams[team].handleStep(this.world, function(){
    		this.nextStep(successhandler, faulthandler, scope, ++team);
    	}, function(result) {
    		faulthandler.call(scope, result);	
    	}, this);
    	
    	
    }
    	
    animation.prototype.play = function() {
    	if (!this.stoped) return;
    	this.stoped = false;
    	
	  	this.nextStep(function(){
	  		// request new frame
	  		this.animate(this.world);
	  	}, function(result){
	  		console.debug('fault of next step', result);
	  	}, this);
    	
    	
	    
    }

    animation.prototype.animate = function(world, lastTime) {
      
      var context = this.context;
      var wcontext = this.wcontext;
      // update
      var date = new Date();
      var time = date.getTime();
      
      if (!lastTime) {
    	  lastTime = time;
      }
      
      var timeDiff = time - lastTime;

      if (timeDiff > this.stepTime) {
          timeDiff = timeDiff - Math.floor(timeDiff / this.stepTime) * this.stepTime;
      }

      var step = Math.floor((world.time + timeDiff) / this.stepTime);
      var stepChanged = false; 
      
      
      if (step > world.step) {    	  
    	  world.step++;
    	  var correllation = world.time + timeDiff - step * this.stepTime;
    	  timeDiff = timeDiff - correllation;
    	  world.time = step * this.stepTime;
    	  time = time - correllation;
    	  stepChanged = true;
      } else {
    	  world.time = world.time + timeDiff;
      }
      

      
      // pixels / second
      lastTime = time;
      
      // clear
      context.clearRect(0, 0, world.width, world.height);
      
      
      
     /**
        context.globalCompositeOperation = 'source-in';
        context.fillStyle = 'rgba(128,128,128,0.85)';
        context.fillRect(0, 0, world.width, world.height);

        // dot drawing style
        context.globalCompositeOperation = 'lighter';
        context.fillStyle = 'rgba(128,128,128,0.5)';
      **/





      for(var i = 0; i < world.planets.length; i++) {
            world.planets[i].move(timeDiff / this.stepTime);
      }

      var fleets = world.squadrons;
      world.squadrons=[];
      for(var i = 0; i < fleets.length; i++) {
          if (!fleets[i].finished) {
              fleets[i].move(timeDiff / this.stepTime);
              world.squadrons.push(fleets[i]);
          }
      }


      //test
      if (stepChanged && world.log) {
          for(var i = 0; i < world.planets.length; i++) {
              var planet = world.log.getPlanetOnStep(world.step-1, i);
              if (planet && !(parseInt(planet.ships) == Math.round(world.planets[i].count)))console.debug(i, parseInt(planet.ships) == Math.round(world.planets[i].count), parseInt(planet.ships), Math.round(world.planets[i].count), world.log);
          }
      }
      
      for(var i = 0; i < world.planets.length; i++) {
    	  world.planets[i].draw(context, wcontext);
      }
      
      for(var i = 0; i < world.squadrons.length; i++) {
    	  world.squadrons[i].draw(context, wcontext);
      }

      /* validating on war end. have to be refactored
      var teams = [], cteam;
      for(var i = 0; i < world.planets.length; i++) {
    	  cteam = world.planets[i].team.teamId;
          if (-1 === teams.indexOf(cteam)) {teams.push(cteam);console.debug(cteam);}
      }
      for(var i = 0; i < world.squadrons.length; i++) {
    	  cteam = world.squadrons[i].team.teamId;
          if (-1 === teams.indexOf(cteam)) {teams.push(cteam);console.debug(cteam);}
      }
      console.debug(teams.length);
      if (teams.length == 1) {
          this.stoped = true;
      }
      */

      /**
      roundButtonRect(context, 10, 10, 66, 66, 4);
      context.font = "italic 9pt Calibri";
      context.fillStyle = "rgba(0,0,0,0.7)";
      context.fillText('STEP: '+world.step, 20, 30);
      context.fillText('FPS: '+ Math.round(1000/timeDiff), 20, 60);
      **/
      
      
      var self = this;
      if (stepChanged) {
    	  
    	  if (self.stoped) {
    		  this.world = world;
    		  return;
    	  }


    	  //console.log('requestAnimFrame');
    	  self.nextStep(function(){
    		  // update
    	      var date = new Date();
    	      var time = date.getTime();
    		  // request new frame
    		  requestAnimFrame(function() {
    		      self.animate(world, time);
    		  });
    	  }, function(result){
    		  console.debug('fault of next step', result);
    	  }, self);
    	  return;
      }
      // request new frame
	  requestAnimFrame(function() {
	      self.animate(world, lastTime);
	  });
	   
     
    }
   

   window.requestAnimFrame = (function(callback) {
       return window.requestAnimationFrame || 
       window.webkitRequestAnimationFrame || 
       window.mozRequestAnimationFrame || 
       window.oRequestAnimationFrame || 
       window.msRequestAnimationFrame ||
       function(callback) {
         window.setTimeout(callback, 1000 / 60);
       };
   })();