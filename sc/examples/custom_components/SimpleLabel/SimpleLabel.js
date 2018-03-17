/*---------->    SimpleLabel.js    <----------*/


ClassFactory.defineClass("SimpleLabel",Canvas);


SimpleLabel.addProperties({

	align:"left",
	valign:"center",
	borderSize:2,
	height:20,

    getInnerHTML : function () {
        return "<TABLE WIDTH=" + this.width
		    		+ " HEIGHT=" + this.height
                    + " CELLSPACING=0 CELLPADDING=0 BORDER=" + this.borderSize
                    + "><TR><TD ALIGN=" + this.align
                    + " VALIGN=" + this.valign
                    +">" + this.contents
                    + "</TD></TR></TABLE>";
    },

    setAlign : function (newValue) {
        this.align = newValue;
        this.markForRedraw();
    }

});