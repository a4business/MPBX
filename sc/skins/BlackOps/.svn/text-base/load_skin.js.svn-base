/*============================================================
    "BlackOps" theme programmatic settings
    Copyright 2003 and beyond, Isomorphic Software
============================================================*/


isc.loadSkin = function (theWindow) {
if (theWindow == null) theWindow = window;
with (theWindow) {

//----------------------------------------
// Specify skin directory
//----------------------------------------
    // must be relative to your application file or isomorphicDir
    isc.Page.setSkinDir("[ISOMORPHIC]/skins/BlackOps/")


//----------------------------------------
// Load skin style sheet(s)
//----------------------------------------
    isc.Page.loadStyleSheet("[SKIN]/skin_styles.css", theWindow)

    isc.Class.modifyFrameworkStart();

//============================================================
//  Component Skinning
//============================================================
//   1) Scrollbars
//   2) Buttons
//   3) Resizebars
//   4) Sections
//   5) Progressbars
//   6) TabSets
//   7) Windows
//   8) Dialogs
//   9) Pickers
//  10) Menus
//  11) Hovers
//  12) ListGrids
//  13) TreeGrids
//  14) Form controls
//  15) Drag & Drop
//  16) Edges
//  17) Sliders
//  18) TileList
//  19) Printing
//  20) ToolStrip
//============================================================


//----------------------------------------
// 1) Scrollbars
//----------------------------------------
    isc.Canvas.addProperties({
        showCustomScrollbars:true,
        scrollbarSize:16,
        groupLabelBackgroundColor:"black",
        groupBorderCSS:"2px solid #D9D9D9"
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

    isc.ScrollThumb.addProperties({
        capSize:6,
        vSrc:"[SKIN]vthumb.png",
        hSrc:"[SKIN]hthumb.png",
        showRollOver:true,
        showGrip:true,
        gripLength:18,
        gripBreadth:8,
        backgroundColor:"transparent"
    })
    isc.Scrollbar.addProperties({
        btnSize:22,
        showRollOver:true,
        thumbMinSize:30,
        thumbInset:2,
        thumbOverlap:7,
        backgroundColor:"#000000",
        vSrc:"[SKIN]vscroll.gif",
        hSrc:"[SKIN]hscroll.gif"
    })


//----------------------------------------
// 2) Buttons
//----------------------------------------

    isc.Button.addProperties({
        showFocusedAsOver:false
    })

    // "IButton" is the new standard button class for SmartClient applications. Application
    // code should use IButton instead of Button for all standalone buttons. Other skins may
    // map IButton directly to Button, so this single class will work everywhere. Button remains
    // for internal and advanced uses (eg if you need to mix both CSS-based and image-based
    // standalone buttons in the same application).
    isc.defineClass("IButton", "StretchImgButton").addProperties({
        src:"[SKIN]button/button.png",
        height:24,
        width:100,
        capSize:7,
        vertical:false,
        titleStyle:"buttonTitle",
        showFocused:true,
        showFocusedAsOver:false
    });

    if (isc.IButton.markAsFrameworkClass != null) isc.IButton.markAsFrameworkClass();

    isc.defineClass("IAutoFitButton", "IButton").addProperties({
        autoFit: true,
        autoFitDirection: isc.Canvas.HORIZONTAL
    });
    if (isc.IAutoFitButton.markAsFrameworkClass != null) isc.IAutoFitButton.markAsFrameworkClass();

    isc.ImgButton.addProperties({
        showFocused: true,
        showFocusedAsOver:false
    });


//----------------------------------------
// 3) Resizebars
//----------------------------------------
    // StretchImgSplitbar class renders as resize bar with
    // end caps, body, grip
    isc.StretchImgSplitbar.addProperties({
        // modify vSrc / hSrc for custom appearance
        //vSrc:"[SKIN]vsplit.gif",
        //hSrc:"[SKIN]hsplit.gif",
        capSize:10,
        showGrip:true
    })

    // ImgSplitbar renders as resizebar with resize grip only
    isc.ImgSplitbar.addProperties({
        // modify these properties for custom appearance
        //vSrc:"[SKIN]vgrip.png",
        //hSrc:"[SKIN]hgrip.png",
        //showDown:true,
        //styleName:"splitbar"
    })

    isc.Snapbar.addProperties({
        vSrc:"[SKIN]vsplit.png",
        hSrc:"[SKIN]hsplit.png",
        baseStyle:"splitbar",
	    items : [
    	    {name:"blank", width:"capSize", height:"capSize"},
    		{name:"blank", width:"*", height:"*"},
	    	{name:"blank", width:"capSize", height:"capSize"}
        ],
        showDownGrip:false,
        gripBreadth:7,
        gripLength:41,
        capSize:8
    })

    isc.Layout.addProperties({
        resizeBarSize:9,
        // Use the Snapbar as a resizeBar by default - subclass of Splitbar that
        // shows interactive (closed/open) grip images
        // Other options include the Splitbar, StretchImgSplitbar or ImgSplitbar
        resizeBarClass:"Snapbar"
    })


//----------------------------------------
// 4) Sections
//----------------------------------------
    if (isc.SectionItem) {
        isc.SectionItem.addProperties({
            sectionHeaderClass:"ImgSectionHeader",
            height:21
        })
    }
    if (isc.SectionStack) {
        isc.SectionStack.addProperties({
            backgroundColor:null,
            sectionHeaderClass:"ImgSectionHeader",
            headerHeight:21
        })
        isc.ImgSectionHeader.changeDefaults("backgroundDefaults", {
            showRollOver:true,
            showDown:false,
            showDisabledIcon:false,
            showRollOverIcon:true,
            src:"[SKIN]SectionHeader/header.png",
            icon:"[SKIN]SectionHeader/opener.png",
            iconWidth:15,
            iconHeight:13,
            capSize:3,
            titleStyle:"imgSectionHeaderTitle",
            baseStyle:"imgSectionHeader",
            backgroundColor:"transparent"
        })
        isc.SectionHeader.addProperties({
            icon:"[SKIN]SectionHeader/opener.png",
            iconWidth:15,
            iconHeight:13
        })
    }


//----------------------------------------
// 5) Progressbars
//----------------------------------------
    if (isc.Progressbar) {
        isc.Progressbar.addProperties({
            horizontalItems: [
            {name:"bar_start",size:3},
            {name:"bar_stretch",size:0},
            {name:"bar_end",size:4},
            {name:"empty_start",size:2},
            {name:"empty_stretch",size:0},
            {name:"empty_end",size:2}
            ],
            breadth:12
        })
    }


//----------------------------------------
// 6) TabSets
//----------------------------------------
    if (isc.TabSet) {
        isc.TabSet.addProperties({
            tabBarThickness:20,
            scrollerButtonSize:18,
            pickerButtonSize:19,

            scrollerVSrc:"[SKIN]vscroll.png",
            scrollerHSrc:"[SKIN]hscroll.png",
            pickerButtonHSrc:"[SKIN]hpicker.png",
            pickerButtonVSrc:"[SKIN]vpicker.png",

            closeTabIconSize:14,

            showEdges:false,
            paneContainerClassName:"tabSetContainer",

            paneMargin:5,

            showScrollerRollOver: true
        });
        isc.TabSet.changeDefaults("paneContainerDefaults", {
            showEdges:false
        })
        isc.TabBar.addProperties({
            membersMargin:4,

            styleName:"tabBar",

            // keep the tabs from reaching the curved edge of the pane (regardless of align)
            layoutEndMargin:10,

            // have the baseline overlap the top edge of the TabSet, using rounded media
            baseLineConstructor:"Canvas",
            baseLineProperties : {
                backgroundColor: "4d5258",
                overflow:"hidden",
                height:1
            },
            //baseLineSrc:"[SKIN]baseline.png",
            //baseLineCapSize:2,
            baseLineThickness:1

        })
    }
    if (isc.ImgTab) {
        isc.ImgTab.addProperties({
            src:"[SKIN]tab.png",
            capSize:3,
            showRollOver:true,
            showDown:false,
            showDisabled:false,
            titleStyle:"tabTitle"
        })
    }


//----------------------------------------
// 7) Windows
//----------------------------------------
    if (isc.Window) {
        // set to 100 to make Windows opaque
        // In Google Chrome there is a native issue where the alpha channel on .png type images
        // (such as the Window close buttons) can fail to render correctly when an opacity setting
        // is also specified. Avoid this by making the window opaque in google chrome.
        
        isc._edgeOpacity = isc.Browser.isChrome ? 100 : 85;

        isc.Window.addProperties({
            // rounded frame edges
            showEdges:true,
            edgeImage: "[SKINIMG]Window/window.png",
            customEdges:null,
            edgeSize:5,
            edgeTop:20,
			edgeOffsetTop: 0,
            edgeOpacity:isc._edgeOpacity,
            maskEdgeCenterOnly: isc._edgeOpacity < 100,
            showHeaderBackground:false, // part of edges
            showHeaderIcon:true,

            // clear backgroundColor and style since corners are rounded
            backgroundColor:null,
			border:null,
            styleName:"normal",
            edgeCenterBackgroundColor:"#000000",
            bodyColor:null,
            bodyStyle:"windowBody",

            layoutMargin:0,
            membersMargin:0,

            showFooter:false,

            showShadow:false,
            shadowDepth:5
        })


        isc.Window.changeDefaults("headerDefaults", {
            layoutMargin:0,
            height:20
        })

        isc.Window.changeDefaults("headerIconDefaults", {
            width:13,
            height:13,
            opacity:isc._edgeOpacity,
            src:"[SKIN]/Window/headerIcon.png"
        })
        isc.Window.changeDefaults("restoreButtonDefaults", {
            src:"[SKIN]/Window/maximize.png",
            opacity:isc._edgeOpacity,
            showRollOver:true,
            showDown:false,
            width:18,
            height:18
        })
        isc.Window.changeDefaults("closeButtonDefaults", {
            src:"[SKIN]/Window/close.png",
            opacity:isc._edgeOpacity,
            showRollOver:true,
            showDown:false,
            width:18,
            height:18
        })
        isc.Window.changeDefaults("maximizeButtonDefaults", {
            src:"[SKIN]/Window/maximize.png",
            opacity:isc._edgeOpacity,
            showRollOver:true,
            width:18,
            height:18
        })
        isc.Window.changeDefaults("minimizeButtonDefaults", {
            src:"[SKIN]/Window/minimize.png",
            opacity:isc._edgeOpacity,
            showRollOver:true,
            showDown:false,
            width:18,
            height:18
        })
        isc.Window.changeDefaults("toolbarDefaults", {
            buttonConstructor: "IButton"
        })

//----------------------------------------
// 8) Dialogs
//----------------------------------------
        if (isc.Dialog) {
            isc.Dialog.addProperties({
                bodyColor:"transparent",
                hiliteBodyColor:"transparent"
            })
            // even though Dialog inherits from Window, we need a separate changeDefaults block
            // because Dialog defines its own toolbarDefaults
            isc.Dialog.changeDefaults("toolbarDefaults", {
                buttonConstructor: "IButton",
                height:42, // 10px margins + 22px button
                membersMargin:10
            })
            if (isc.Dialog.Warn && isc.Dialog.Warn.toolbarDefaults) {
                isc.addProperties(isc.Dialog.Warn.toolbarDefaults, {
                    buttonConstructor: "IButton",
                    height:42,
                    membersMargin:10
                })
            }
        }

    } // end isc.Window

//----------------------------------------
// 9) Pickers
//----------------------------------------
    // add bevels and shadows to all pickers
    isc.__pickerDefaults = {
        // rounded frame edges
        showEdges:true,
        edgeImage: "[SKINIMG]Window/window.png",
        customEdges:null,
        edgeSize:5,
        edgeOpacity:isc._edgeOpacity,
        maskEdgeCenterOnly: isc._edgeOpacity < 100,
        showHeaderBackground:false, // part of edges

        // clear backgroundColor and style since corners are rounded
        backgroundColor:null,
        border:null,
        styleName:"windowBody",
        edgeCenterBackgroundColor:"#000000",

        showShadow:false,
        shadowDepth:5
    }
    if (isc.ButtonTable) {
        isc.ButtonTable.addProperties({
            backgroundColor:"#000000"
        })
    }
    if (isc.FormItem) {
        isc.FormItem.changeDefaults("pickerDefaults", isc.__pickerDefaults)
    }
    if (isc.CheckboxItem) {
        isc.CheckboxItem.addProperties({
            checkedImage:"[SKINIMG]/DynamicForm/checked.png",
            uncheckedImage:"[SKINIMG]/DynamicForm/unchecked.png",
            unsetImage:"[SKINIMG]/DynamicForm/unsetcheck.png",
            valueIconWidth:15,
            valueIconHeight:15
        })
    }

    if (isc.DateChooser) {
        isc.DateChooser.addProperties({
            headerStyle:"dateChooserButton",
            weekendHeaderStyle:"dateChooserWeekendButton",
            baseNavButtonStyle:"dateChooserNavButton",
            baseWeekdayStyle:"dateChooserWeekday",
            baseWeekendStyle:"dateChooserWeekend",
            baseBottomButtonStyle:"dateChooserBottomButton",
            alternateWeekStyles:false,

            showEdges:true,
            edgeImage:"[SKINIMG]/DateChooser/window.png",
            edgeSize:4,
            edgeBottom:27,
            todayButtonHeight:23,
            edgeOffset:1,

            edgeTop:20,
            edgeOffsetTop:0,
            headerHeight:19,
            cellPadding:0,

            edgeCenterBackgroundColor:"#000000",
            backgroundColor:null,


            showShadow:true,
            shadowDepth:6,
            shadowOffset:5,

            showDoubleYearIcon:false,
            skinImgDir:"images/DateChooser/",
            prevYearIcon:"[SKIN]doubleArrow_left.png",
            prevYearIconWidth:17,
            prevYearIconHeight:17,
            nextYearIcon:"[SKIN]doubleArrow_right.png",
            nextYearIconWidth:17,
            nextYearIconHeight:17,
            prevMonthIcon:"[SKIN]arrow_left.png",
            prevMonthIconWidth:18,
            prevMonthIconHeight:17,
            nextMonthIcon:"[SKIN]arrow_right.png",
            nextMonthIconWidth:18,
            nextMonthIconHeight:17
        })
    }


//----------------------------------------
// 10) Menus
//----------------------------------------
    if (isc.Menu) {
        isc.Menu.addProperties({
            styleName:"menuBorder",
            cellHeight:22,
            showShadow:true,
            shadowDepth:5,
            showEdges:false,
            submenuImage:{src:"[SKIN]submenu.gif", height:7, width:7},
            submenuDisabledImage:{src:"[SKIN]submenu_disabled.gif", height:7, width:7},
	        checkmarkImage:{src:"[SKIN]check.gif", width:9, height:9},
	        checkmarkDisabledImage:{src:"[SKIN]check_disabled.gif", width:9, height:9},
            bodyStyleName:"menuMain",
            bodyBackgroundColor:null
        });
    }

    if (isc.MenuButton) {
        isc.MenuButton.addProperties({
            showFocusedAsOver:true,
            menuButtonImage:"[SKIN]menu_button.gif",
            menuButtonImageUp:"[SKIN]menu_button_up.gif",
            iconWidth:7,
            iconHeight:7
        });
    }
    if (isc.IMenuButton) {
        isc.IMenuButton.addProperties({
            src:"[SKIN]button/button.png",
            height:24,
            width:100,
            capSize:7,
            vertical:false,
            titleStyle:"buttonTitle",
            showFocused:true,
            showFocusedAsOver:false,

            menuButtonImage:"[SKIN]menu_button.gif",
            menuButtonImageUp:"[SKIN]menu_button_up.gif",
            iconWidth:7,
            iconHeight:7
        });
    }

    if (isc.IconButton) {
        isc.IconButton.addProperties({
            menuIconSrc: "[SKINIMG]/Menu/submenu_down.gif"
        });
    }


//----------------------------------------
// 11) Hovers
//----------------------------------------
    if (isc.Hover) {
        isc.addProperties(isc.Hover.hoverCanvasDefaults, {
            showShadow:true,
            shadowDepth:5
        })
    }


//----------------------------------------
// 12) ListGrids
//----------------------------------------
    if (isc.HiliteRule) {
        isc.HiliteRule.changeDefaults("hiliteFormDefaults", {
            colWidths: [60, 60, 60, 60, 60, 44]
        });
    }

    if (isc.ListGrid) {
        isc.ListGrid.addProperties({
            tallBaseStyle: "tallCell",

            // Render header buttons out as StretchImgButtons
            headerButtonConstructor:"ImgButton",
            sorterConstructor:"ImgButton",
            headerMenuButtonConstructor:"ImgButton",

            sortAscendingImage:{src:"[SKIN]sort_ascending.png", width:8, height:6},
            sortDescendingImage:{src:"[SKIN]sort_descending.png", width:8, height:6},

            backgroundColor:null, bodyBackgroundColor:null,

            headerHeight:21,
            headerBackgroundColor:null,
            headerBarStyle:"headerBar",
            headerBaseStyle:"headerButton",	// bgcolor tint and borders
            headerTitleStyle:"headerTitle",

            bodyStyleName:"gridBody",

            showHeaderMenuButton:true,
            headerMenuButtonBaseStyle:"headerButton",
            headerMenuButtonTitleStyle:"headerTitle",

            headerMenuButtonIcon:"[SKIN]/ListGrid/headerMenuButton_icon.png",
            headerMenuButtonIconWidth:8,
            headerMenuButtonIconHeight:6
        })
        isc.ListGrid.changeDefaults("sorterDefaults", {
            // baseStyle / titleStyle is auto-assigned from headerBaseStyle
            src:"[SKIN]ListGrid/header.png",
            baseStyle:"sorterButton"
        })
        isc.ListGrid.changeDefaults("headerButtonDefaults", {
            showTitle:true,
            showDown:false,
            showFocused:false,
            // baseStyle / titleStyle is auto-assigned from headerBaseStyle
            src:"[SKIN]ListGrid/header.png"
        })
        isc.ListGrid.changeDefaults("headerMenuButtonDefaults", {
            showDown:false,
            showTitle:true,
            showFocused:false,
            src:"[SKIN]ListGrid/header.png"
        })
    }

//----------------------------------------
// 13) TreeGrids
//----------------------------------------
    if (isc.TreeGrid) {
        isc.TreeGrid.addProperties({
            folderIcon:"[SKIN]folder.png",
            nodeIcon:"[SKIN]file.png",
            manyItemsImage:"[SKIN]folder_file.png"
        })
    }

    if (isc.ColumnTree) {
        isc.ColumnTree.addProperties({
            folderIcon:"[SKIN]folder.png",
            nodeIcon:"[SKIN]file.png"
        });
    }

//----------------------------------------
// 14) Form controls
//----------------------------------------
    if (isc.FormItem) {isc.FormItem.addProperties({
        defaultIconSrc:"[SKIN]/controls/helper_control.gif",
        iconHeight:18,
        iconWidth:18,
        iconVAlign:"middle"
    })}

    if (isc.TextItem) {isc.TextItem.addProperties({
        height:isc.Browser.isSafari ? 22 : 20
    })}

    if (isc.SelectItem) {isc.SelectItem.addProperties({
        textBoxStyle:"selectItemText",
        showFocusedPickerIcon:true,
        pickerIconSrc:"[SKIN]/controls/selectPicker.gif",
        height:20,
        pickerIconWidth:18,
        valueIconSize:14
    })}

    if (isc.ComboBoxItem) {isc.ComboBoxItem.addProperties({
        textBoxStyle:"selectItemText",
        showFocusedPickerIcon:true,
        pendingTextBoxStyle:"comboBoxItemPendingText",
        pickerIconSrc:"[SKIN]/controls/comboBoxPicker.gif",
        height:20,
        pickerIconWidth:18
    })}
    // used by SelectItem and ComboBoxItem for picklist
    if (isc.ScrollingMenu) {isc.ScrollingMenu.addProperties({
        showShadow:true,
        shadowDepth:5
    })}
    if (isc.DateItem) {
        isc.DateItem.addProperties({
            pickerIconWidth:16,
            pickerIconHeight:16,
            pickerIconSrc:"[SKIN]/controls/date_control.gif"
        })
    }
    if (isc.ColorItem) {
        isc.ColorItem.addProperties({
            showEmptyPickerIcon: true
        });
    }
    if (isc.SpinnerItem) {
        isc.SpinnerItem.addProperties({
            textBoxStyle:"selectItemText",
            height:20
        });
        isc.SpinnerItem.changeDefaults("increaseIconDefaults", {
            width:12,
            height:10,
            showFocused:true,
            imgOnly:true,
            src:"[SKIN]/controls/spinner_control_increase.gif"
        });
        isc.SpinnerItem.changeDefaults("decreaseIconDefaults", {
            width:12,
            height:10,
            showFocused:true,
            imgOnly:true,
            src:"[SKIN]/controls/spinner_control_decrease.gif"
        });
        isc.SpinnerItem.changeDefaults("unstackedIncreaseIconDefaults", {
            src: "[SKIN]/controls/spinner_control_increase.gif",
            showFocused: true
        });
        isc.SpinnerItem.changeDefaults("unstackedDecreaseIconDefaults", {
            src: "[SKIN]/controls/spinner_control_decrease.gif",
            showFocused: true
        });
    }
    if (isc.PopUpTextAreaItem) {isc.PopUpTextAreaItem.addProperties({
        popUpIconSrc: "[SKIN]/controls/text_control.gif",
        popUpIconWidth:16,
        popUpIconHeight:16
    })}
    if (isc.ButtonItem && isc.IButton) {isc.ButtonItem.addProperties({
        showFocused:true,
        showFocusAsOver:false,
        buttonConstructor:isc.IButton,
        height:24
    })}

    if (isc.ToolbarItem && isc.IAutoFitButton) {isc.ToolbarItem.addProperties({
        buttonConstructor:isc.IAutoFitButton,
        buttonProperties: {
            autoFitDirection: isc.Canvas.BOTH
        }
    })}

    if (isc.RelativeDateItem) {
        isc.RelativeDateItem.changeDefaults("pickerIconDefaults", {
            neverDisable: false
        });
    }



//----------------------------------------
// 15) Drag & Drop
//----------------------------------------
    // drag tracker drop shadow (disabled by default because many trackers are irregular shape)
    //isc.addProperties(isc.EH.dragTrackerDefaults, {
    //    showShadow:true,
    //    shadowDepth:4
    //});
    // drag target shadow and opacity
    isc.EH.showTargetDragShadow = true;
    isc.EH.targetDragOpacity = 50;



//----------------------------------------
// 16) Edges
//----------------------------------------
    // default edge style serves as a pretty component frame/border - just set showEdges:true
    if (isc.EdgedCanvas) {
        isc.EdgedCanvas.addProperties({
            edgeSize:6,
            edgeImage:"[SKIN]/rounded/frame/FFFFFF/6.png"
        })
    }


//----------------------------------------
// 17) Sliders
//----------------------------------------
    if (isc.Slider) {
        isc.Slider.addProperties({
            thumbThickWidth:14,
            thumbThinWidth:15,
            trackWidth:7,
            trackCapSize:2,
            thumbSrc:"thumb.png",
            trackSrc:"track.png"
        });
        isc.Slider.changeDefaults("rangeLabelDefaults", {
            showDisabled: true
        });
        isc.Slider.changeDefaults("valueLabelDefaults", {
            showDisabled: true
        });
    }

//----------------------------------------
// 18) TileList
//----------------------------------------
    if (isc.TileGrid) {
        isc.TileGrid.addProperties({
            valuesShowRollOver: true
        })
    }

//----------------------------------------
// 19) Printing
//----------------------------------------


// Even in the black-ops skin use black on white for the print window
if (isc.PrintWindow) {
    isc.PrintWindow.addProperties({
        bodyStyle:"printText",
        edgeOpacity:null,
        opacity:null
    })
}
if (isc.SectionStack) {
    isc.SectionStack.addProperties({
        printStyleName:"printText"
    })
}

if (isc.ListGrid) {
    isc.ListGrid.addProperties({
        printBaseStyle:"printCell"
    })
}

if (isc.DetailViewer) {
    isc.DetailViewer.addProperties({
        printHeaderStyle:"printDetailHeader",
        printLabelStyle:"printDetailLabel",
        printCellStyle:"printDetail"
    })
}

// -------------------------------------------
// 20) ToolStrip
// -------------------------------------------

if (isc.ToolStripButton) {
    isc.ToolStripButton.addProperties({
        title:null,
        titleStyle:"buttonTitle"
    });
}


if (isc.DynamicForm) {
    isc.FormItem.addProperties({
        printTitleStyle:"printFormTitle",
        printTextBoxStyle:"printFormItem"
    });
    isc.TextItem.addProperties({
        printTextBoxStyle:"printTextItem"
    });
    isc.TextAreaItem.addProperties({
        printTextBoxStyle:"printTextItem"
    })
    isc.SelectItem.addProperties({
        printTextBoxStyle:"printTextItem"
    })
    // Don't override headerItem style for printing
    isc.HeaderItem.addProperties({
        printTextBoxStyle:null
    })
}

// -------------------------------------------
// Printing
// -------------------------------------------
if (isc.PrintWindow) {
    isc.PrintWindow.changeDefaults("printButtonDefaults", {
        height: 20
    });
}

// remember the current skin so we can detect multiple skins being loaded
if (isc.setCurrentSkin) isc.setCurrentSkin("BlackOps");

// specify where the browser should redirect if not supported
isc.Page.checkBrowserAndRedirect("[SKIN]/unsupported_browser.html");

    isc.Class.modifyFrameworkDone();
}   // end with()

}   // end loadSkin()

isc.loadSkin()
