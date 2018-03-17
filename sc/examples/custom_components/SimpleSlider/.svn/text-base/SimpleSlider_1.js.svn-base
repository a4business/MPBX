/*---------->    SimpleSlider_1.js    <----------*/

// Step 1
//	* create, position, and size the slider and its two children (_track and _thumb)
//	* make the thumb drag-repositionable, just for fun

ClassFactory.defineClass("SimpleSlider", Canvas);


SimpleSlider.addProperties({
    length:200,
    vertical:true,
    
    thumbThickWidth:30,
    thumbThinWidth:10,
    trackWidth:4,
    
    
    initWidget : function () {
        this.Super("initWidget",	arguments);
        
        var width, height;
        
        if (this.vertical) {
            width = Math.max(this.thumbThickWidth, this.trackWidth);
            height = this.length;
        } else {
            width = this.length;
            height = Math.max(this.thumbThickWidth, this.trackWidth);
        }
        
        this.setWidth(width);
        this.setHeight(height);
        
        this._usableLength = this.length-this.thumbThinWidth;
        
        this._track = this.addChild(this._createTrack());
        this._thumb = this.addChild(this._createThumb());
    },
    
    
    _createTrack : function () {
        return Canvas.create({
            autoDraw:false,
            left:(this.vertical ? Math.floor((this.getWidth() - this.trackWidth)/2) : 0),
            top:(this.vertical ? 0 : Math.floor((this.getHeight() - this.trackWidth)/2)),
            width:(this.vertical ? this.trackWidth : this.getWidth()),
            height:(this.vertical ? this.getHeight() : this.trackWidth),
            vertical:this.vertical,
            backgroundColor:"#666666",
            overflow:Canvas.HIDDEN
        });
    },
    
    
    _createThumb : function () {
        return Canvas.create({
            autoDraw:false,
            left:(this.vertical ? Math.floor((this.getWidth() - this.thumbThickWidth)/2) : 0),
            top:(this.vertical ? 0 : Math.floor((this.getHeight() - this.thumbThickWidth)/2)),
            width:(this.vertical ? this.thumbThickWidth : this.thumbThinWidth),
            height:(this.vertical ? this.thumbThinWidth : this.thumbThickWidth),
            canDragReposition:true,
            dragAppearance:EventHandler.TARGET,
            backgroundColor:"#AAAAAA"
        });
    }


});
