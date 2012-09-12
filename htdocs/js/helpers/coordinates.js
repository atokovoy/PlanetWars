var coordinatesSystem = function(){
	

	var distance = function(from, map){
		
		var dist = 10000, length = 0, to;
		for (var i = 0; i < map.length; i++) {
			to = map[i];
			length = Math.sqrt(Math.pow(from.x - to.x, 2) + Math.pow(from.y - to.y, 2));
			if (length && length < dist) {
				dist = length;
			}
		}
		return dist;
		 
	}

    var cloneData = function (obj) {

        if (obj instanceof Array) {
            var clone = [];
            for (var a = 0; a < obj.length; a++){
                clone[a] = cloneData(obj[a]);
            }
        } else {
            var clone = {};
        }

        for(var i in obj) {
            if(obj[i] instanceof Array) {
                for(var a = 0; a < obj[i].length; a++){
                    clone[i][a] = cloneData(obj[i][a]);
                }
            } else if (typeof(obj[i]) == "object"){
                clone[i] = cloneData(obj[i]);
            } else  {
                clone[i] = obj[i];
            }
        }
        return clone;
    }


	
	return {
		recalculateMap: function(rmap, width, height){

			var border = 70;

            var map = cloneData(rmap);

			var x = 0, y = 0, pos, pr;
			for (var i = 0; i < map.length; i++) {
				pos = map[i];
				pos.x = parseFloat(pos.x);
				pos.y = parseFloat(pos.y);
				if (pos.x > x) x = pos.x;
			    if (pos.y > y) y = pos.y;
			}
			

			var xpr = (width - border*2) / x; 
			var ypr = (height - border*2) / y; 
			
			
			pr = (xpr < ypr) ? xpr : ypr;
			

			var cx = 0, cy = 0;
			if (x > y) {
				cx = (width - border*2 - x * pr)/2;
			} else {
				cy = (height - border*2 - y * pr)/2;
			}
			
			for (i = 0; i < map.length; i++) {
			    map[i].x = map[i].x * pr + border + cx;
			    map[i].y = map[i].y * pr + border + cy;
			}
			
			for (var i = 0; i < map.length; i++) {
				map[i].radius = distance(map[i], map)/2.1;
			}

            console.debug('-', map);
			return map;
			
		}
	}

}();

