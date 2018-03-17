/*
	Isomorphic SmartClient Skin File

	This file loads a 'skin' for your Isomorphic SmartClient applications.
	To load this skin, reference this file in a <SCRIPT> tag immediately
	after your Isomorphic SmartClient loader:

		<SCRIPT ...>


*/

isc.loadSkin = function (theWindow) {
if (theWindow == null) theWindow = window;
with (theWindow) {
	//
	//	Step 1:  Set the Isomorphic SmartClient skinDir to point to this directory
	//
	//			 NOTE: The path provided here MUST be relative to your application
	//					file or the already set up Isomorphic dir.
	//
	//			 ** If you move this skin, you must change the skinDir below!***
	//
	isc.Page.setSkinDir("[ISOMORPHIC]/skins/Cupertino/");


	//
	//	Step 2:  Load the stylesheet (you can load app-specific stylesheets here, too, if you like)
	//
	isc.Page.loadStyleSheet("[SKIN]/skin_styles.css", theWindow);

    isc.Class.modifyFrameworkStart();

	//
	//	Step 3: Customize the SmartClient client framework below
	//
	isc.Canvas.setProperties({
		// this skin uses custom scrollbars
		showCustomScrollbars:true
	});

    if(isc.Browser.isIE && isc.Browser.version >= 7 && !isc.Browser.isIE9) {
        isc.Canvas.setAllowExternalFilters(false);
        isc.Canvas.setNeverUseFilters(true);
        if(isc.Window) {
          isc.Window.addProperties({
                modalMaskOpacity:null,
                modalMaskStyle:"normal"
            });
            isc.Window.changeDefaults("modalMaskDefaults", { src : "[SKIN]opacity.png" });

        }
    }

    // define IButton so examples that support the new SmartClient skin image-based
    // button will fall back on the CSS-based Button with this skin
	isc.ClassFactory.defineClass("IButton", "Button");
	if (isc.IButton.markAsFrameworkClass != null) isc.IButton.markAsFrameworkClass();

	isc.ClassFactory.defineClass("IAutoFitButton", "AutoFitButton");
	if (isc.IAutoFitButton.markAsFrameworkClass != null) isc.IAutoFitButton.markAsFrameworkClass();

	// Have IMenuButton duplicate MenuButton
	isc.ClassFactory.defineClass("IMenuButton", "MenuButton");
	if (isc.IMenuButton.markAsFrameworkClass != null) isc.IMenuButton.markAsFrameworkClass();

    if (isc.Menu) {
        isc.Menu.addProperties({
            styleName:"menuBorder",
            bodyStyleName:"menuMain"
        });
    }

	// ListGrid skinning
	if (isc.ListGrid) {
		isc.ListGrid.addProperties({
			bodyBackgroundColor:"#DEDEDE",
			// copy the header (.button) background-color to match when sort arrow is hidden
			backgroundColor:"#CCCCCC",
			showSortArrow:isc.ListGrid.CORNER,
            expansionFieldImageWidth : 16,
            expansionFieldImageHeight : 16
		});
		isc.ListGrid.changeDefaults("summaryRowDefaults",
		    {bodyBackgroundColor:"#EFEFEF"}
		);
	}

	// TabSet skinning
    if (isc.TabSet) {
        // In Netscape Navigator 4.7x, set the backgroundColor directly since the css
        // background colors are not reliable
        if (isc.Browser.isNav) {
            isc.TabSet.addProperties({paneContainerDefaults:{backgroundColor:"#EEEEEE"}});
        }
        isc.TabBar.addProperties({height:19, baseLineThickness:3, baseLineCapSize:0,
                                         leadingMargin:8});
    }
	if (isc.ImgTab) isc.ImgTab.addProperties({capSize:11});

	// Window skinning
	if (isc.Window) {
		isc.Window.addProperties({backgroundColor:"#DEDEDE",
                                  showHeaderBackground:true,
                                  headerBackgroundConstructor:"Img",
						          headerSrc:"[SKIN]Window/headerBackground.gif",
						          hiliteHeaderSrc:"[SKIN]Window/headerBackground_hilite.gif",
                                  showFooter:false,
						          layoutMargin:3});
		isc.addProperties(isc.Window.getInstanceProperty("headerDefaults"),
						  {height:16,
						   layoutMargin:0,
						   membersMargin:0}
						 );
		isc.addProperties(isc.Window.getInstanceProperty("headerIconDefaults"),
						  {width:19, extraSpace:10});

        // Dialog (prompt) shows no header in edge media - ensure message is centered.
        isc.Dialog.changeDefaults("bodyDefaults",
            {layoutTopMargin:15, layoutLeftMargin:15, layoutRightMargin:15, layoutBottomMargin:15});
        isc.Dialog.changeDefaults("messageStackDefaults",
            {layoutMargin:10, layoutBottomMargin:10});

        isc.addProperties(isc.Dialog.Prompt.bodyDefaults,
            {layoutTopMargin:15, layoutBottomMargin:15, layoutLeftMargin:15, layoutRightMargin:15});
        isc.addProperties(isc.Dialog.Prompt.messageStackDefaults,
            {layoutMargin:10, layoutBottomMargin:10});

    }

    if (isc.SelectItem) {isc.SelectItem.addProperties({
        valueIconSize:13
    });}
    if (isc.ComboBoxItem) {isc.ComboBoxItem.addProperties({
        pendingTextBoxStyle:"comboBoxItemPendingText"
    })}
    if (isc.ColorItem) {
        isc.ColorItem.addProperties({
            showEmptyPickerIcon: true
        });
    }
    if (isc.SpinnerItem) {
        isc.SpinnerItem.changeDefaults("increaseIconDefaults", {
            showOver:true
        });
        isc.SpinnerItem.changeDefaults("decreaseIconDefaults", {
            showOver:true
        });
        isc.SpinnerItem.changeDefaults("unstackedIncreaseIconDefaults", {
            showOver:true
        });
        isc.SpinnerItem.changeDefaults("unstackedDecreaseIconDefaults", {
            showOver:true
        });
    }
    if (isc.RelativeDateItem) {
        isc.RelativeDateItem.changeDefaults("pickerIconDefaults", {
            neverDisable: false
        });
    }

    // DateChooser icons
    if (isc.DateChooser) {
        isc.DateChooser.addProperties({
            showDoubleYearIcon:false,
            skinImgDir:"images/DateChooser/"
        });
    }


    // ToolStrip skinning
    if (isc.ToolStrip) {
        isc.ToolStrip.changeDefaults("dropLineDefaults", {
            className:"toolStripLayoutDropLine"
        });
    }

    // Default EdgedCanvas skinning (for any canvas where showEdges is set to true)
    if (isc.EdgedCanvas) {
        isc.EdgedCanvas.addProperties({
            edgeSize:4,
            edgeImage:"[SKIN]/rounded/frame/A3B2CC/4.png"
        })
    }

    if (isc.Slider) {
        isc.Slider.changeDefaults("rangeLabelDefaults", {
            showDisabled: true
        });
        isc.Slider.changeDefaults("valueLabelDefaults", {
            showDisabled: true
        });
    }

    // remember the current skin so we can detect multiple skins being loaded
    if (isc.setCurrentSkin) isc.setCurrentSkin("Cupertino");

	//
	//  Step 4: Specify where the browser should redirect if the browser is
	// 			not supported.
	//
	isc.Page.checkBrowserAndRedirect("[SKIN]/unsupported_browser.html");

    isc.Class.modifyFrameworkDone();
}
}


// call the loadSkin routine
isc.loadSkin()

