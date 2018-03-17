//===========================================================================
//  SmartClient portal example -- customized components
//      Portlet, PortalColumn, and PortalLayout
//
//  ISOMORPHIC SOFTWARE CONFIDENTIAL MATERIAL
//===========================================================================



//===========================================================================
// Portlet class definition
//===========================================================================

isc.defineClass("Portlet", "Window").addMethods({

    autoDraw:false,
    showShadow:false,

    // enable predefined component animation
    animateMinimize:true,

    // Window is draggable with "outline" appearance by default.
    // "target" is the solid appearance.
    dragAppearance:"outline",
    canDrop:true,
    
    // customize the appearance and order of the controls in the window header
    // (could do this in load_skin.js instead)
    headerMembers:["minimizeButton", "headerLabel", "closeButton"],

    // show either a shadow, or translucency, when dragging a portlet
    // (could do both at the same time, but these are not visually compatible effects)
    //showDragShadow:true,
    dragOpacity:30,
    
    // these settings enable the portlet to autosize its height only to fit its contents
    // (since width is determined from the containing layout, not the portlet contents)
    vPolicy:"none",
    overflow:"visible",
    bodyProperties:{overflow:"visible"}

});


//===========================================================================
// PortalColumn class definition
//===========================================================================

isc.defineClass("PortalColumn", "VStack").addMethods({

    // leave some space between portlets
    membersMargin:6,

    // enable predefined component animation
    animateMembers:true,
    animateMemberTime:gAnimatePortletTime,

    // enable drop handling
    canAcceptDrop:true,
    
    // change appearance of drag placeholder and drop indicator
    dropLineThickness:4,
    dropLineProperties:{backgroundColor:"aqua"},
    showDragPlaceHolder:true,
    placeHolderProperties:{border:"2px solid #8289A6"}

});



//===========================================================================
// PortalLayout class definition
//===========================================================================

isc.defineClass("PortalLayout", "HLayout").addMethods({
    numColumns:2,
    membersMargin:6,
    initWidget : function () {
        this.Super("initWidget", arguments);
        // create multiple PortalColumn components
        for (var i = 0; i < this.numColumns; i++) {
            this.addMember(isc.PortalColumn.create({autoDraw:false, width:"*"}));
        }
    },
    addPortlet : function (portlet, addToTop) {
        var fewestPortlets = 999999,
            fewestPortletsColumn;
        // find the column with the fewest portlets
        for (var i=0; i < this.members.length; i++) {
            var numPortlets = this.getMember(i).members.length;
            if (numPortlets < fewestPortlets) {
                fewestPortlets = numPortlets;
                fewestPortletsColumn = this.getMember(i);
            }
        }
        fewestPortletsColumn.addMember(portlet, (addToTop ? 0 : null));
        return fewestPortletsColumn;
    }
});
