// Framework enhancements needed:
//      minValue, maxValue, numValues setters so we can reuse slider
//      valueIsChanging flag so we can dismiss when drag ends
//      window.addMembers/window.removeMembers (and deprecate items, use members)
//      click and pull support - redirect drag events from icon to thumb

ClassFactory.defineClass("PopupSliderItem", "TextItem").addProperties({

icons:[{}],

iconClick : function (form, item, icon) {
    var iconRect = this.getIconPageRect(icon);
    if (!this._slider) this.makeSlider();
    this._slider.settingValue = true;
    this._slider.setValue(this.getValue());
    this._slider.moveTo(iconRect[0],iconRect[1]-10);
    this._slider.show();
},

makeSlider : function () {
    this._slider = isc.Slider.create({
        autoDraw:false,
        showTitle:false,
        showValue:false,
        showRange:false,
        width:25,
        height:200,
        backgroundColor:"#f0f0f0",
        showEdges:true, edgeImage:"[SKIN]/rounded/frame/FFFFFF/4.png", edgeSize:4, 
        value:this.getValue(),
        minValue:this.minValue,
        maxValue:this.maxValue,
        numValues:this.numValues,
        _item:this,
        valueChanged: function () {
            if (this.settingValue) {
                this.settingValue = false;
                return;
            }
            this._item.setValue(this.value);
            this._item.handleChange(this.value);
            if (!isc.EH.dragTarget) this.hide();
        }
    });
    this.observe(window[this._slider.getID()+"_thumb"], "dragStop", "observer._slider.hide()");
}

}); // end PopupSliderItem definition
