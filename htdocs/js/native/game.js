
    

    var game = function(canvas, wcanvas, teams, planets, options) {

            if (!options) {
            	options = {}
            }	
    	
        	var context = canvas.getContext("2d");
            context.clearRect(0, 0, canvas.width, canvas.height);

                    
            var wcontext = wcanvas.getContext("2d");
            wcontext.clearRect(0, 0, wcanvas.width, wcanvas.height);

            var universe = new space(canvas.width, canvas.height, options.space?options.space:{});
            universe.draw(wcontext);
            
            for (var i = 0; i < planets.length; i++) {
                planets[i].draw(context, wcontext);
            }
            this.world = {
            	step: 0,
            	time: 0,
            	explosions:[],
            	teams: teams,
            	width: canvas.width,
            	height: canvas.height,
                planets: planets,
                squadrons: []    
            };
            
            this.context = context;
            this.wcontext = wcontext;
           
   }
    
   game.prototype.play = function() {
	      	
       document.animations = new animation(this.context, this.wcontext, this.world);
	   
   }

