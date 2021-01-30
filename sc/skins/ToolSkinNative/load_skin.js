/*
	Isomorphic SmartClient Tools Skin File

    This file contains skinning modifications for SmartClient Tools, allowing controls in
    SmartClient tools to have a different appearance from standard application controls

	To load this skin, reference this file in a <SCRIPT> tag immediately
	after your Isomorphic SmartClient loader:

		<SCRIPT ...>
*/

isc.loadToolSkin = function (theWindow) {
if (theWindow == null) theWindow = window;
with (theWindow) {


isc.Class.modifyFrameworkStart();

isc.overwriteClass("TScrollThumb", "ScrollThumb");
isc.overwriteClass("THScrollThumb", "TScrollThumb");
isc.overwriteClass("TVScrollThumb", "TScrollThumb");
isc.overwriteClass("TScrollbar", "Scrollbar");
isc.overwriteClass("TPropertySheet", "PropertySheet");
isc.overwriteClass("TSnapbar", "Snapbar");
isc.overwriteClass("TLayout", "Layout");
isc.overwriteClass("TSectionItem", "SectionItem");
isc.overwriteClass("TSectionStack", "SectionStack");
isc.overwriteClass("TSectionHeader", "SectionHeader");
isc.overwriteClass("TImgSectionHeader", "ImgSectionHeader");
isc.overwriteClass("TImgSectionHeader2", "TImgSectionHeader");
isc.overwriteClass("TButton", "IButton");
isc.overwriteClass("TAutoFitButton", "TButton").addProperties({
    autoFit: true,
    autoFitDirection: isc.Canvas.HORIZONTAL
});
isc.overwriteClass("TTabSet", "TabSet");
isc.overwriteClass("TTab", "ImgTab");


if (isc.DynamicForm) {
    isc.overwriteClass("TDynamicForm", "DynamicForm");
}

if (isc.ComponentEditor) {
    isc.overwriteClass("TComponentEditor", "ComponentEditor");

}

isc.overwriteClass("TListGrid", "ListGrid");
isc.overwriteClass("TTreeGrid", "TreeGrid");

if (isc.ListPalette) {
    isc.overwriteClass("TListPalette", "ListPalette");

    isc.overwriteClass("TTreePalette", "TreePalette");

    isc.overwriteClass("TEditTree", "EditTree");
}

isc.overwriteClass("TTextItem", "TextItem");
isc.overwriteClass("TColorItem", "ColorItem");
isc.overwriteClass("TTextAreaItem", "TextAreaItem");
isc.overwriteClass("TSelectItem", "SelectItem");
isc.overwriteClass("TRadioGroupItem", "RadioGroupItem");
isc.overwriteClass("TCheckboxItem", "CheckboxItem");
isc.overwriteClass("TExpressionItem", "ExpressionItem");
isc.overwriteClass("TButtonItem", "ButtonItem");
isc.overwriteClass("THTMLFlow", "HTMLFlow");
isc.overwriteClass("TMenu", "Menu");
isc.overwriteClass("TMenuButton", "MenuButton");

if (isc.Window) {
    isc.overwriteClass("TWindow", "Window");
    isc.overwriteClass("TSaveFileDialog", "SaveFileDialog");
    isc.overwriteClass("TLoadFileDialog", "LoadFileDialog");
}

if (isc.PropertySheet) {
    isc.PropertySheet.changeDefaults(
        "TExpressionItemDefaults", 
        isc.addProperties({}, isc.PropertySheet.getPrototype().ExpressionItemDefaults)
    );
    
    isc.PropertySheet.changeDefaults(
        "TSelectItemDefaults", 
        isc.addProperties({}, isc.PropertySheet.getPrototype().SelectItemDefaults)
    );
    isc.PropertySheet.changeDefaults(
        "TTextItemDefaults", 
        isc.addProperties({}, isc.PropertySheet.getPrototype().TextItemDefaults)
    );
    isc.PropertySheet.changeDefaults(
        "TColorItemDefaults", 
        isc.addProperties({}, isc.PropertySheet.getPrototype().ColorItemDefaults)
    );
    isc.PropertySheet.changeDefaults(
        "TTextAreaItemDefaults", 
        isc.addProperties({}, isc.PropertySheet.getPrototype().TextAreaItemDefaults)
    );
    isc.PropertySheet.changeDefaults(
        "TCheckboxItemDefaults", 
        isc.addProperties({}, isc.PropertySheet.getPrototype().CheckboxItemDefaults)
    );
    isc.PropertySheet.changeDefaults(
        "TSectionItemDefaults", 
        isc.addProperties({}, isc.PropertySheet.getPrototype().SectionItemDefaults)
    );
    
}
    // remember the current skin so we can detect multiple skins being loaded
    if (isc.setCurrentSkin) isc.setCurrentSkin("ToolSkinNative");

isc.Class.modifyFrameworkDone();

} // End of with(theWindow) block

}   // END of loadToolSkin function definition
// call the loadToolSkin routine
isc.loadToolSkin()
