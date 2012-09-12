

      var imagePreloader = function(images, callback, key, imageLibrary) {
          if (!key) {
              key = 0;
          }
          if (!imageLibrary) {
        	  imageLibrary = {};
          }
         
    	  var imageObj = new Image(), img = images[key];
          
          imageObj.onload = function() {

        	  imageLibrary[img[0]] = imageObj;
              
              key++;
              
              if (key != images.length) {
            	  imagePreloader(images, callback, key, imageLibrary);
              } else {
            	  callback.call(this, imageLibrary);
              }
             
          };
          
          imageObj.src = img[1];
      }