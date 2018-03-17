/*---------->    SimpleHeader.js    <----------*/


isc.defineClass("SimpleHeader", isc.HLayout);


isc.SimpleHeader.addProperties({
    // --- Instance Defaults --- 
    width:"100%", // full width
    height:20,
	imageSrc:"[SKIN]Window/headerIcon.png",
    backgroundColor:"white",
	imageWidth:18,
	imageHeight:null, // will use overall widget height if not specified
	titleText:"Simple Header",
	titleStyle:"tabTitle",

    // --- Instance Methods ---
    initWidget : function () {
        // call superclass implementation
        this.Super("initWidget", arguments);
    
        // on init, create the parts of this header
        this.addMembers([
        
            // img for logo image
            isc.Img.create({
                ID:this.getID()+"_image",
                src:this.imageSrc,
                width:this.imageWidth,
                height:this.imageHeight || this.getHeight(),
                layoutAlign:"center"
            }),
            
            // spacer to stretch
            isc.LayoutSpacer.create({
                ID:this.getID()+"_spacer"
            }),
            
            // label for text title
            this.label = isc.Label.create({
                ID:this.getID()+"_title",
                valign:"center",
                styleName:this.titleStyle,
                contents:this.titleText,
                wrap:false
            })
        ]);
    },

    // --- Dynamic Setters ---
    setTitleText : function (newTitleText) {
        this.titleText = newTitleText;
        this.label.setContents(newTitleText);
    },

    setTitleStyle : function (newTitleStyle) {
        this.titleStyle = newTitleStyle;
        this.label.setStyleName(newTitleStyle);
    }
});
