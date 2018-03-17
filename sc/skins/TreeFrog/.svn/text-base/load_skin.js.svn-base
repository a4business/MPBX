/*============================================================
    "SmartClient" theme programmatic settings
    SmartClient v5.5
    Copyright 2001-2007, Isomorphic Software
============================================================*/


isc.loadSkin = function (theWindow) {
if (theWindow == null) theWindow = window;
with (theWindow) {


//----------------------------------------
// Specify skin directory
//----------------------------------------
    // must be relative to your application file or isomorphicDir
    isc.Page.setSkinDir("[ISOMORPHIC]/skins/TreeFrog/")


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
//============================================================


	isc.Canvas.addProperties({
		groupBorderCSS: "1px solid #808080"
	});

//----------------------------------------
// 1) Scrollbars
//----------------------------------------
    isc.Canvas.addProperties({
        showCustomScrollbars:true,
        scrollbarSize:18
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
        capSize:5,
        vSrc:"[SKIN]vthumb.png",
        hSrc:"[SKIN]hthumb.png",
        showGrip:true,
        gripLength:13,
        gripBreadth:5,
        backgroundColor:"transparent"
    })
    isc.Scrollbar.addProperties({
        allowThumbDownState:true,
        btnSize:18,
        thumbMinSize:25,
        startThumbOverlap:-5,
        endThumbOverlap:-2,
        vSrc:"[SKIN]vscroll.png",
        hSrc:"[SKIN]hscroll.png",
        showTrackEnds: true,
        trackStartSize:10,
        trackEndSize:10,
        startImg:{name:"start", width:19, height:19}
    })


//----------------------------------------
// 2) Buttons
//----------------------------------------

    isc.Button.addProperties({
        showFocused:true,
        showFocusedAsOver:false
    });

    // "IButton" is the new standard button class for SmartClient applications. Application
    // code should use IButton instead of Button for all standalone buttons. Other skins may
    // map IButton directly to Button, so this single class will work everywhere. Button remains
    // for internal and advanced uses (eg if you need to mix both CSS-based and image-based
    // standalone buttons in the same application).
    isc.defineClass("IButton", "StretchImgButton").addProperties({
        src:"[SKIN]button/button.png",
        height:25,
        width:100,
        capSize:7,
        vertical:false,
        // TreeFrog button media looks best when 25px tall with
        // title text floating just below the top of the button
        valign:"top",
        labelVPad:4,
        titleStyle:"buttonTitle",
        showFocused:true,
        showFocusedAsOver:false
    });

    if (isc.ToolStripButton) {
        isc.ToolStripButton.addProperties({
            title:null
        });
    }            

    isc.defineClass("IAutoFitButton", "IButton").addProperties({
        autoFit: true,
        autoFitDirection: isc.Canvas.HORIZONTAL
    });

    if (isc.IButton.markAsFrameworkClass != null) isc.IButton.markAsFrameworkClass();
    if (isc.IAutoFitButton.markAsFrameworkClass != null) isc.IAutoFitButton.markAsFrameworkClass();



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
        showGrip:true,
        gripLength:18,
        gripBreadth:9
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
        capSize:8,
        gripLength:18,
        gripBreadth:9
    })

    isc.Layout.addProperties({
        resizeBarSize:13,
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
            height:28
        })
    }
    if (isc.SectionStack) {
        isc.SectionStack.addProperties({
            sectionHeaderClass:"ImgSectionHeader",
            headerHeight:28,
            itemStartIndent: 2,
            itemEndIndent: 1
        })
        isc.ImgSectionHeader.changeDefaults("backgroundDefaults", {
            showRollOver:true,
            showDown:false,
            showDisabledIcon:false,
            showRollOverIcon:true,
            src:"[SKIN]SectionHeader/header.png",
            icon:"[SKIN]SectionHeader/opener.png",
            iconSize:18,
            padding:1,
            capSize:9,
            titleStyle:"imgSectionHeaderTitle",
            baseStyle:"imgSectionHeader",
            backgroundColor:"transparent"
        })
        isc.SectionHeader.addProperties({
            icon:"[SKIN]SectionHeader/opener.png",
            iconSize:18
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
            tabBarThickness:23,
            scrollerButtonSize:19,
            pickerButtonSize:21,

            symmetricScroller:false,
            symmetricPickerButton:false,

            scrollerSrc:"[SKIN]scroll.png",
            pickerButtonSrc:"[SKIN]picker.png",

            paneContainerClassName:"normal",

            showPaneContainerEdges:true,
            showPartialEdges:true,

            paneMargin:5,

            symmetricEdges:false,
            topEdgeSizes:{defaultSize:2, top:1, bottom:25},
            bottomEdgeSizes:{defaultSize:2, bottom:1, top:25},
            leftEdgeSizes:{defaultSize:2, left:1, right:25},
            rightEdgeSizes:{defaultSize:2, right:1, left:25},

            topEdgeOffsets:{right:1, bottom:2},
            bottomEdgeOffsets:{right:1, top:2},
            leftEdgeOffsets:{bottom:1, right:2},
            rightEdgeOffsets:{bottom:1, left:2}
        });
        isc.TabSet.changeDefaults("paneContainerDefaults", {
            edgeImage:"[SKIN]/TabSet/ts.png",
            edgeCenterBackgroundColor:"#FFFFFF"
        })
        isc.TabBar.addProperties({
            stackZIndex:"firstOnTop",
            memberOverlap:16,

            // keep the tabs from reaching the curved edge of the pane (regardless of align)
            layoutEndMargin:10,

            // have the baseline overlap the top edge of the TabSet, using rounded media
            baseLineSrc:"[SKIN]baseline.png",
            baseLineThickness:1,
            baseLineCapSize:2
        })
    }
    if (isc.ImgTab) {
        isc.ImgTab.addProperties({
            src:"[SKIN]tab.png",
            items:[
                {name:"start", width:8, height:8},
                {name:"stretch", width:"*", height:"*"},
                {name:"end", width:28, height:28}
            ],
            labelLengthPad:8,
            showRollOver:true,
            showDown:false,
            titleStyle:"tabTitle"
        })
    }


//----------------------------------------
// 7) Windows
//----------------------------------------
    if (isc.Window) {
        isc.Window.addProperties({
            // rounded frame edges
            showEdges:true,
            edgeImage:"[SKIN]/Window/w.png",
            customEdges:null,
            edgeSize:7,
            edgeBottom:2,
            edgeTop:29,
            edgeOffset:2,
            edgeOffsetTop:4,
            edgeOffsetBottom:2,
            // set minimize height large enough to show header + 1px (sides) + 2px (footer) media
            minimizeHeight:32,
            showHeaderBackground:false,

            edgeOverflow:"hidden",

            // clear backgroundColor and style since corners are rounded
            backgroundColor:null,
            styleName:"normal",
            edgeCenterBackgroundColor:"white",
            bodyColor:"transparent",

            layoutMargin:0,
            membersMargin:0,
            showFooter:false,
            showShadow:true,
            shadowSoftness:2,
            shadowOffset:4
        })

        isc.Window.changeDefaults("headerDefaults", {
            layoutMargin:0,
            layoutLeftMargin:5,
            layoutRightMargin:5,
            height:18, extraSpace: 7
        })





        isc.Window.changeDefaults("headerBackgroundDefaults", {
            capSize:7
        })

        isc.Window.changeDefaults("headerIconDefaults", {
            width:14,
            height:14,
            extraSpace:2,
            src:"[SKIN]/Window/headerIcon.png"
        })
        isc.Window.changeDefaults("restoreButtonDefaults", {
             src:"[SKIN]/Window/restore.png",
             showRollOver:true,
             showDown:false,
             width:18,
             height:18
        })
        isc.Window.changeDefaults("closeButtonDefaults", {
             src:"[SKIN]/Window/close.png",
             showRollOver:true,
             showDown:false,
             width:18,
             height:18
        })
        isc.Window.changeDefaults("maximizeButtonDefaults", {
             src:"[SKIN]/Window/maximize.png",
             showRollOver:true,
             width:18,
             height:18
        })
        isc.Window.changeDefaults("minimizeButtonDefaults", {
             src:"[SKIN]/Window/minimize.png",
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
                height:45, // 10px margins + 25px button
                membersMargin:10
            })
            if (isc.Dialog.Warn && isc.Dialog.Warn.toolbarDefaults) {
                isc.addProperties(isc.Dialog.Warn.toolbarDefaults, {
                    buttonConstructor: "IButton",
                    height:45,
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
        showEdges:true,
        edgeSize:6,
        edgeImage:"[SKIN]/rounded/frame/FFFFFF/6.png",
        //edgeShowCenter: true, // not available for ridge edges
        backgroundColor:"#C7C7C7",
        showShadow:true,
        shadowDepth:6,
        shadowOffset:5
    }
    if (isc.ButtonTable) {
        isc.ButtonTable.addProperties({
            backgroundColor:"#FFFFFF"
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

    // Removed line adding __pickerDefaults to ColorChooser properties here. ColorChooser is
    // obsolete, though it retains a global mapping to its replacement, ColorPicker. These
    // default picker properties don't work well with ColorPicker because it is based on a
    // Window rather than a Canvas, and so already has its own decorations.
    //
    // The result was particularly bad in TreeFrog because the backgroundColor setting was
    // breaking TreeFrog's curved corners and wiping out the separate header bar


    if (isc.DateChooser) {
        isc.DateChooser.addProperties({
            headerStyle:"dateChooserButton",
            baseNavButtonStyle:"dateChooserNavButton",
            baseWeekdayStyle:"dateChooserWeekday",
            baseWeekendStyle:"dateChooserWeekend",
            alternateWeekStyles:true,
            showEdges:true,
            edgeSize:6,
            edgeImage:"[SKIN]/Picker/p.png",
            edgeBottom:18,
            edgeOffset:2,
            edgeCenterBackgroundColor:"#FFFFFF",
            backgroundColor:null,
            showShadow:true,
            shadowDepth:6,
            shadowOffset:5,
            showDoubleYearIcon:false,
            skinImgDir:"images/DateChooser/",
            prevYearIcon:"[SKIN]doubleArrow_left.png",
            prevYearIconWidth:15,
            prevYearIconHeight:13,
            prevMonthIcon:"[SKIN]arrow_left.png",
            prevMonthIconWidth:7,
            prevMonthIconHeight:13,
            nextYearIcon:"[SKIN]doubleArrow_right.png",
            nextYearIconWidth:15,
            nextYearIconHeight:13,
            nextMonthIcon:"[SKIN]arrow_right.png",
            nextMonthIconWidth:7,
            nextMonthIconHeight:13,
            navButtonConstructor: "Button"
        });
        isc.DateChooser.changeDefaults("monthChooserButtonDefaults", { width: 40 });
        isc.DateChooser.changeDefaults("yearChooserButtonDefaults", { width: 40 });

        if (isc.DateGrid) {
            isc.DateGrid.addProperties({
                dateFieldWidth: 24
            })
        }
    }
    if (isc.MultiFilePicker) {
        isc.MultiFilePicker.addProperties({
            backgroundColor:"#C7C7C7"
        })
    }
    if (isc.RelationPicker) {
        isc.RelationPicker.addProperties({
            backgroundColor:"#C7C7C7"
        })
    }


//----------------------------------------
// 10) Menus
//----------------------------------------
    if (isc.Menu) {
        isc.Menu.addProperties({
            // Increase cellHeight to accomodate text + borders in "over" state.
            cellHeight:25,
            showShadow:true,
            shadowDepth:5,
            showEdges:true,
            edgeSize:10,
            edgeOffset:10,
            edgeOffsetLeft:2,
            edgeOffsetRight:2,
            edgeTop:17,
            edgeBottom:17,
            edgeImage:"[SKIN]/Menu/m.png",
            edgeCenterBackgroundColor:"#F7F7F7",
            submenuImage:{src:"[SKIN]submenu.png", height:15, width:8},
            submenuDisabledImage:{src:"[SKIN]submenu_disabled.png", height:15, width:8},
            // get rid of everything that could occlude center segment:
            // borders around table
            tableStyle:"normal",
            // XXX unreachable from CSS
            bodyBackgroundColor:"transparent"
            // also: non-rollover cell styles for menu need to avoid setting bgColor
            // for square/tinted edges only:
            //edgeBackgroundColor:"#fff0ff"
        }

        /*
        // submenus
        isc.Menu.addProperties({
            cellHeight:25,
            showShadow:true,
            shadowDepth:5,
            showEdges:true,
            edgeSize:10,
            edgeOffsetLeft:2,
            edgeOffsetRight:2,
            edgeImage:"[SKIN]/Menu/sm.png",
            edgeCenterBackgroundColor:"#F7F7F7",
            tableStyle:"normal",
            bodyBackgroundColor:"transparent"
        }
        */
    )}

    if (isc.MenuButton) {
        isc.MenuButton.addProperties({
            menuButtonImage:"[SKIN]menu_button.png",
            menuButtonImageUp:"[SKIN]menu_button_up.png",
            showFocused:false,
            iconWidth:12,
            iconHeight:8
        });
    }
    if (isc.IMenuButton) {
        isc.IMenuButton.addProperties({

            src:"[SKIN]button/button.png",
            height:25,
            width:100,
            capSize:7,
            vertical:false,
            labelVPad:4,
            titleStyle:"buttonTitle",
            showFocused:true,
            showFocusedAsOver:false,

            menuButtonImage:"[SKIN]menu_button.png",
            menuButtonImageUp:"[SKIN]menu_button_up.png",
            showFocused:false,
            iconWidth:12,
            iconHeight:8
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
            // Render header buttons out as StretchImgButtons
            headerButtonConstructor:"ImgButton",
            sorterConstructor:"ImgButton",
            headerMenuButtonConstructor:"ImgButton",

            sortAscendingImage:{src:"[SKIN]sort_ascending.png", width:12, height:11},
            sortDescendingImage:{src:"[SKIN]sort_descending.png", width:12, height:11},

            backgroundColor:"#FFFFFF",
            headerBackgroundColor:"#EFF1F1;",
            headerHeight:21,
            headerBaseStyle:"headerButton",	// bgcolor tint and borders
            headerTitleStyle:"headerTitle",

            headerBarStyle:"headerBar",
            bodyStyleName:"gridBody",

            showHeaderMenuButton:true,
            headerMenuButtonConstructor:"ImgButton",
            headerMenuButtonWidth:17,
            headerMenuButtonSrc:"[SKIN]/ListGrid/header_menu.gif",
            headerMenuButtonIcon:null

            //,groupIcon:"[SKINIMG]/TreeGrid/folder.png"
        })
        isc.ListGrid.changeDefaults("sorterDefaults", {
            // baseStyle / titleStyle is auto-assigned from headerBaseStyle
            src:"[SKIN]ListGrid/header.png",
            baseStyle:"sorterButton"
        })
        isc.ListGrid.changeDefaults("headerButtonDefaults", {
            showTitle:true,
            showDown:false,
            // baseStyle / titleStyle is auto-assigned from headerBaseStyle
            src:"[SKIN]ListGrid/header.png"
        })
        isc.ListGrid.changeDefaults("headerMenuButtonDefaults", {
            showDown:false,
            showRollOver:false
        })
        isc.ListGrid.changeDefaults("summaryRowDefaults", {
            bodyBackgroundColor:"#EFF1F1"
        })
    }

//----------------------------------------
// 13) TreeGrids
//----------------------------------------
    if (isc.TreeGrid) {
        isc.TreeGrid.addProperties({
            folderIcon:"[SKIN]folder.png",
            nodeIcon:"[SKIN]file.png",
            manyItemsImage:"[SKIN]folder_file.png",
            showFullConnectors:true
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
        showFocusedPickerIcon:true,
        pickerIconSrc:"[SKIN]/controls/selectPicker.png",
        height:20,
        pickerIconWidth:20,
        valueIconSize:14
    })}

    if (isc.ComboBoxItem) {isc.ComboBoxItem.addProperties({
        showFocusedPickerIcon:true,
        pickerIconSrc:"[SKIN]/controls/comboBoxPicker.png",
        pendingTextBoxStyle:"comboBoxItemPendingText",
        height:20, // pickerIcon automatically sizes to this height
        pickerIconWidth:20
    })}
    // used by SelectItem and ComboBoxItem for picklist
    if (isc.ScrollingMenu) {isc.ScrollingMenu.addProperties({
        border:"1px solid #606060",
        showShadow:true,
        shadowDepth:5
    })}
    if (isc.DateItem) {
        isc.DateItem.addProperties({
            pickerIconWidth:14,
            pickerIconHeight:14,
            pickerIconSrc:"[SKIN]/controls/date_control.png"
        })
    }
    if (isc.ColorItem) {
        isc.ColorItem.addProperties({
            showEmptyPickerIcon: true
        });
    }
    if (isc.SpinnerItem) {
        isc.SpinnerItem.addProperties({
            height:20
        });
        isc.SpinnerItem.changeDefaults("increaseIconDefaults", {
            width:20,
            height:10,
            showOver:true,
            showFocused:true,
            imgOnly:true,
            src:"[SKIN]/controls/spinner_control_increase.png"
        });
        isc.SpinnerItem.changeDefaults("decreaseIconDefaults", {
            width:20,
            height:10,
            showOver:true,
            showFocused:true,
            imgOnly:true,
            src:"[SKIN]/controls/spinner_control_decrease.png"
        });
        isc.SpinnerItem.changeDefaults("unstackedIncreaseIconDefaults", {
            src:"[SKIN]/controls/spinner_control_increase.png",
            width: 20,
            showOver:true,
            showFocused:true
        });
        isc.SpinnerItem.changeDefaults("unstackedDecreaseIconDefaults", {
            src:"[SKIN]/controls/spinner_control_decrease.png",
            width: 20,
            showOver:true,
            showFocused:true
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
        height:25
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
            thumbThickWidth:16,
            thumbThinWidth:16,
            trackWidth:6,
            trackCapSize:3,
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
// 18) Calendar
//----------------------------------------
    if (isc.Calendar) {
        isc.Calendar.changeDefaults("datePickerButtonDefaults", {
            showDown:false,
            showOver : false,
            src:"[SKIN]/DynamicForm/date_control.png"
        });


    }
// -------------------------------------------
// ExampleViewPane - used in the feature explorer
// -------------------------------------------
    if (isc.ExampleViewPane) {
        isc.ExampleViewPane.addProperties({
            styleName:"normal"
        });
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
    if (isc.setCurrentSkin) isc.setCurrentSkin("TreeFrog");

    // specify where the browser should redirect if not supported
    isc.Page.checkBrowserAndRedirect("[SKIN]/unsupported_browser.html");

    isc.Class.modifyFrameworkDone();

}   // end with()
}   // end loadSkin()

isc.loadSkin()

