/*---------->    SimpleSlider_7.js    <----------*/

// Step 7
//	* extend setDisabled() to change visual state and cursor
//	* init disabled state

ClassFactory.defineClass("SimpleSlider", Canvas);


SimpleSlider.addProperties({
	length:200,
	vertical:true,
	
	thumbThickWidth:23,
	thumbThinWidth:17,
	trackWidth:7,
	trackCapSize:6,
	skinImgDir:"images/SimpleSlider/",
	thumbSrc:"thumb.gif",
	trackSrc:"track.gif",
	
	value:0,
	sliderTarget:null,
    
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
        
        this.setValue(this.value);
        this.setDisabled(this.disabled);
    },
    
    
    _createTrack : function () {
        return StretchImg.create({
            autoDraw:false,
            left:(this.vertical ? Math.floor((this.getWidth() - this.trackWidth)/2) : 0),
            top:(this.vertical ? 0 : Math.floor((this.getHeight() - this.trackWidth)/2)),
            width:(this.vertical ? this.trackWidth : this.getWidth()),
            height:(this.vertical ? this.getHeight() : this.trackWidth),
            vertical:this.vertical,
            capSize:this.trackCapSize,
            src:"[SKIN]" + (this.vertical ? "v" : "h") + this.trackSrc,
            skinImgDir:this.skinImgDir
        });
    },
    
    
    _createThumb : function () {
        return Img.create({
            autoDraw:false,
            left:(this.vertical ? Math.floor((this.getWidth() - this.thumbThickWidth)/2) : 0),
            top:(this.vertical ? 0 : Math.floor((this.getHeight() - this.thumbThickWidth)/2)),
            width:(this.vertical ? this.thumbThickWidth : this.thumbThinWidth),
            height:(this.vertical ? this.thumbThinWidth : this.thumbThickWidth),
            src:"[SKIN]" + (this.vertical ? "v" : "h") + this.thumbSrc,
            skinImgDir:this.skinImgDir,
            canDrag:true,
            dragAppearance:EventHandler.NONE,
            cursor:Canvas.HAND,
            dragMove:"this.parentElement._thumbMove(); return false",
            dragStart:EventHandler.stopBubbling,
            dragStop:"this.setState(''); return false",
            mouseDown:"this.setState('down')",
            mouseUp:"this.setState(''); return false"
        });
    },
    
    
    _thumbMove : function () {
        var thumbPosition;
        
        if (this.vertical) {
            thumbPosition = EventHandler.getY() - this.getPageTop();
            thumbPosition = Math.max(0, Math.min(this._usableLength, thumbPosition));
            if (thumbPosition == this._thumb.getTop()) return;
            this._thumb.setTop(thumbPosition);
        } else {
            thumbPosition = EventHandler.getX() - this.getPageLeft();
            thumbPosition = Math.max(0, Math.min(this._usableLength, thumbPosition));
            if (thumbPosition == this._thumb.getLeft()) return;
            this._thumb.setLeft(thumbPosition);
        }
        
        this.value = thumbPosition/this._usableLength;
        
        this.valueChanged();
        
        if (this.sliderTarget) EventHandler.handleEvent(this.sliderTarget, "sliderMove", this);
    },
    
    
    setValue : function (newValue) {
        this.value = newValue;
        
        var thumbPosition = this.value * this._usableLength;
        if (this.vertical)
            this._thumb.setTop(thumbPosition);
        else
            this._thumb.setLeft(thumbPosition);
        
        this.valueChanged();
        
        if (this.sliderTarget) EventHandler.handleEvent(this.sliderTarget, "sliderMove", this);
    },
    
    
    getValue : function () {
        return this.value;
    },
    
    
    valueChanged : function () {
    },
    
    
    setDisabled : function (disabled) {
        this.Super("setDisabled",arguments);
        
        if (!disabled) {
            this._track.setState("");
            this._thumb.setState("");	
            this._thumb.setCursor(Canvas.HAND);
        } else {
            this._track.setState("off");
            this._thumb.setState("off");	
            this._thumb.setCursor(Canvas.DEFAULT);
        }
    }
    
    
});
