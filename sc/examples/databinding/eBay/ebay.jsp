<%@ taglib uri="/WEB-INF/iscTaglib.xml" prefix="isomorphic" %>
<HTML><HEAD><TITLE>EBay Demo</TITLE>
<isomorphic:loadISC includeModules="FileLoader,EBay" skin="SmartClient"/>
<SCRIPT SRC=../../../tools/visualBuilder/eBayAuthToken.js></SCRIPT>
</HEAD><BODY>
<link rel="stylesheet" type="text/css" href="/tools/visualBuilder/eBay.css">
<SCRIPT>
<isomorphic:loadDS name="itemCategoriesDS"/>
<isomorphic:loadDS name="itemCategoryListingsDS"/>
<isomorphic:loadDS name="searchItemListingsDS"/>

Page.setAppImgDir("/tools/visualBuilder/graphics/");
isc.setAutoDraw(false);

isc.TreeGrid.create({
    ID: "itemCategories",
    width: 250,
	showResizeBar: true,
    autoFetchData: true,
    showHeader: false,
    dataSource: "itemCategoriesDS",
	leafClick: function (viewer, leaf) {
        itemListings.setDataSource(itemCategoryListingsDS);
		itemListings.filterData({CategoryID: leaf.CategoryID});
	}
});

isc.DynamicForm.create({
    ID: "searchListings",
    width: 550,
    numCols: 4,
    colWidths: [150, 100, 200, 100],
    saveOnEnter: true,
    saveData : function () {
        itemListings.setDataSource(searchItemListingsDS);
        itemListings.filterData({
            Query: this.getValue("search"), 
            SearchType: this.getValue("galleryOnly") ? "Gallery" : "All"
        });
    },
    fields: [
        {name: "galleryOnly", title: "Gallery Only", type: "boolean", showTitle: false},
        {name: "search", title: "Search", width: 200},
        {name: "searchButton", title: "Search", startRow: false, type: "button", click: "searchListings.saveData()"}
    ]
});

isc.ListGrid.create({
	ID: "itemListings",
	dataSource: "searchItemListingsDS",
    recordClick : function (viewer, record) {
        itemDetails.setContentsURL(record.ListingDetails.ViewItemURL);
    }
});

isc.HTMLPane.create({
    ID: "itemDetails",
    contentsType: "page"
});

isc.SectionStack.create({
    ID: "rightLayout",
    visibilityMode: "multiple",
    sections: [
        {title: "Search", expanded: true, items: [searchListings]},
        {title: "Item Listings", expanded: true, items: [itemListings]},
        {title: "Item Details", expanded: true, items: [itemDetails]}
    ]
});

isc.HLayout.create({
	ID: "pageLayout",
	autoDraw: true,
	width: "100%",
	height: "100%",
	members: [itemCategories, rightLayout]
});

</SCRIPT>
</BODY></HTML>
