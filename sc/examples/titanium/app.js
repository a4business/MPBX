/*-=-
    SmartClient AJAX RIA system
    Copyright 2000 and beyond Isomorphic Software, Inc.

    OWNERSHIP NOTICE
    Isomorphic Software owns and reserves all rights not expressly granted in this source code,
    including all intellectual property rights to the structure, sequence, and format of this code
    and to all designs, interfaces, algorithms, schema, protocols, and inventions expressed herein.

    CONFIDENTIALITY NOTICE
    The contents of this file are confidential and protected by non-disclosure agreement:
      * You may not expose this file to any person who is not bound by the same obligations.
      * You may not expose or send this file unencrypted on a public network.

    SUPPORTED INTERFACES
    Most interfaces expressed in this source code are internal and unsupported. Isomorphic supports
    only the documented behaviors of properties and methods that are marked "@visibility external"
    in this code. All other interfaces may be changed or removed without notice. The implementation
    of any supported interface may also be changed without notice.
 
    If you have any questions, please email <sourcecode@isomorphic.com>.
 
    This entire comment must accompany any portion of Isomorphic Software source code that is
    copied or moved from this file.
*/

Titanium.UI.setBackgroundColor('#000');

var tabGroup = Titanium.UI.createTabGroup();
var selectedCustomer, selectedCustomerContacts;
function customerModel(ev) {
    try {
        var contact = false, people;
        if(selectedCustomer === "Acme Inc") {
          selectedCustomerContacts = [{
              contact: false,
              fullName:"Bill Adams",
              phone:"408-344-5609",
              address:"1192 Hedding St, San Jose, CA 95112"
          }];
        } else if(selectedCustomer === "ABC Co") {
          selectedCustomerContacts = [{
              contact: false,
              fullName:"Abe Larson",
              phone:"408-901-8887",
              address:"201 San Antonio St, San Jose, CA 95112"
          }];
        }
        people = Titanium.Contacts.getPeopleWithName(selectedCustomerContacts[0].fullName);
        people && people.length && (selectedCustomerContacts[0].contact = true);
        Titanium.App.fireEvent('customer', {
            customer:selectedCustomer, 
            contacts:JSON.stringify(selectedCustomerContacts)
        });
        customerContacts.add(customerContactsWebView);
    } catch(e) {
        alert(e.message);
    }
}
function parseAddress(addressValue) {
    try {
        var parsedaddress, parsedstatezip, address = {};
        parsedaddress = addressValue && addressValue.split(','); 
        if(parsedaddress.length === 3) {
            address.Street = parsedaddress[0].trim();
            address.City = parsedaddress[1].trim();
            parsedstatezip = parsedaddress[2].trim().split(' ');
            if(parsedstatezip.length === 2) {
                address.State = parsedstatezip[0].trim();
                address.ZIP = parsedstatezip[1].trim();
            }
        }
        return address;
    } catch(e) {
        alert(e);
    }
}
var customers = Titanium.UI.createWindow({  
    title:'Customers',
    backgroundColor:'#fff'
});

var customerContacts = Ti.UI.createWindow({
    title:"Customer Contacts",
    backgroundColor:'#fff'
});
var contactSelectedView;
Titanium.App.addEventListener('contact_selected', function(ev) {
    try {
        var firstlast, first, last, address, l1, f1, l2, f2, l3, f3, l4, f4, l5, f5, l6, f6, l7, f7, b1;
        if(ev.add) {
            firstlast = ev.fullName.split(' ');
            first = firstlast[0].trim();
            last = firstlast[1].trim();
            address = parseAddress(ev.address);
            contactSelectedView = Titanium.UI.createView({
            	top:0,
            	left:0,
            	width:'100%',
            	height:'100%'
            });
            l1 = Titanium.UI.createLabel({
            	text:'First:',
            	top:10,
            	left:10,
                height:24
            });
            f1 = Titanium.UI.createTextField({
            	value:first,
                color:'gray',
                editable: false,
                enabled: false,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:10,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l1);
            contactSelectedView.add(f1);
            l2 = Titanium.UI.createLabel({
            	text:'Last:',
            	top:40,
            	left:10,
                height:24
            });
            f2 = Titanium.UI.createTextField({
            	value:last,
                color:'gray',
                editable: false,
                enabled: false,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:40,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l2);
            contactSelectedView.add(f2);
            l3 = Ti.UI.createLabel({
            	text:'Street:',
            	top:70,
            	left:10,
                height:24
            });
            f3 = Ti.UI.createTextField({
            	value:address.Street,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:70,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l3);
            contactSelectedView.add(f3);
            l4 = Ti.UI.createLabel({
            	text:'City:',
            	top:100,
            	left:10,
                height:24
            });
            f4 = Ti.UI.createTextField({
            	value:address.City,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:100,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l4);
            contactSelectedView.add(f4);
            l5 = Ti.UI.createLabel({
            	text:'State:',
            	top:130,
            	left:10,
                height:24
            });
            f5 = Ti.UI.createTextField({
            	value:address.State,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:130,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l5);
            contactSelectedView.add(f5);
            l6 = Ti.UI.createLabel({
            	text:'ZIP:',
            	top:160,
            	left:10,
                height:24
            });
            f6 = Ti.UI.createTextField({
            	value:address.ZIP,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:160,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l6);
            contactSelectedView.add(f6);
            l7 = Ti.UI.createLabel({
            	text:'phone:',
            	top:190,
            	left:10,
                height:24
            });
            f7 = Ti.UI.createTextField({
            	value:ev.phone,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:190,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l7);
            contactSelectedView.add(f7);
            b1 = Ti.UI.createButton({
            	title:'Add contact',
            	width:100,
            	height:40,
            	bottom:20
            });
            b1.addEventListener('click', function() {
            	Titanium.Contacts.createPerson({
            		firstName:f1.value,
            		lastName:f2.value,
            		phone:{"work":[f7.value]},
            		address:{"work":[address]}
            	});
            	Titanium.Contacts.save();
                customerContacts.animate({
                    view:customerContactsWebView,
                    transition:Titanium.UI.iPhone.AnimationStyle.FLIP_FROM_RIGHT
                });
            });
            contactSelectedView.add(b1);
            customerContacts.animate({
                view:contactSelectedView,
                transition:Titanium.UI.iPhone.AnimationStyle.FLIP_FROM_LEFT
            });
        } else if(ev.show) {
            firstlast = ev.fullName.split(' ');
            first = firstlast[0].trim();
            last = firstlast[1].trim();
            address = parseAddress(ev.address);
            contactSelectedView = Titanium.UI.createView({
            	top:0,
            	left:0,
            	width:'100%',
            	height:'100%'
            });
            l1 = Titanium.UI.createLabel({
            	text:'First:',
            	top:10,
            	left:10,
                height:24
            });
            f1 = Titanium.UI.createTextField({
            	value:first,
                color:'gray',
                editable: false,
                enabled: false,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:10,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l1);
            contactSelectedView.add(f1);
            l2 = Titanium.UI.createLabel({
            	text:'Last:',
            	top:40,
            	left:10,
                height:24
            });
            f2 = Titanium.UI.createTextField({
            	value:last,
                color:'gray',
                editable: false,
                enabled: false,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:40,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l2);
            contactSelectedView.add(f2);
            l3 = Ti.UI.createLabel({
            	text:'Street:',
            	top:70,
            	left:10,
                height:24
            });
            f3 = Ti.UI.createTextField({
            	value:address.Street,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:70,
                color:'gray',
                editable: false,
                enabled: false,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l3);
            contactSelectedView.add(f3);
            l4 = Ti.UI.createLabel({
            	text:'City:',
            	top:100,
            	left:10,
                height:24
            });
            f4 = Ti.UI.createTextField({
            	value:address.City,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:100,
                color:'gray',
                editable: false,
                enabled: false,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l4);
            contactSelectedView.add(f4);
            l5 = Ti.UI.createLabel({
            	text:'State:',
            	top:130,
            	left:10,
                height:24
            });
            f5 = Ti.UI.createTextField({
            	value:address.State,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:130,
                color:'gray',
                editable: false,
                enabled: false,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l5);
            contactSelectedView.add(f5);
            l6 = Ti.UI.createLabel({
            	text:'ZIP:',
            	top:160,
            	left:10,
                height:24
            });
            f6 = Ti.UI.createTextField({
            	value:address.ZIP,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:160,
                color:'gray',
                editable: false,
                enabled: false,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l6);
            contactSelectedView.add(f6);
            l7 = Ti.UI.createLabel({
            	text:'phone:',
            	top:190,
            	left:10,
                height:24
            });
            f7 = Ti.UI.createTextField({
            	value:ev.phone,
            	borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,
            	top:190,
                color:'gray',
                editable: false,
                enabled: false,
            	right:10,
                height:24,
            	width:220
            });
            contactSelectedView.add(l7);
            contactSelectedView.add(f7);
            customerContacts.animate({
                view:contactSelectedView,
                transition:Titanium.UI.iPhone.AnimationStyle.FLIP_FROM_LEFT
            });
        }
    } catch(e) {
        alert(e.message);
    }
});
customerContacts.addEventListener('load',customerModel);
customerContacts.addEventListener('focus',customerModel);
var customerContactsWebView = Titanium.UI.createWebView({
    height:'auto',
    width:'auto',
    url:"customercontacts.html",
    evaljs:true,
    evalhtml:true,
    scalesPageToFit:true
});
Titanium.App.addEventListener('customer_selected', function(ev) {
    try {
        selectedCustomer = ev.customer;
        customerContacts.addEventListener('blur',function() {
            if(contactSelectedView) {
                customerContacts.animate({
                    view:customerContactsWebView,
                    transition:Titanium.UI.iPhone.AnimationStyle.FLIP_FROM_RIGHT
                });
                customerContacts.remove(contactSelectedView);
                contactSelectedView = null;
            }
            customerContacts.remove(customerContactsWebView);
        });
        customerContacts.add(customerContactsWebView);
        customersTab.open(customerContacts,{animated:true});
    } catch(e) {
        alert(e.message);
    }
});

var customersTab = Titanium.UI.createTab({  
    icon:'KS_nav_views.png',
    title:'Customers',
    window:customers
});
var customersView = Titanium.UI.createWebView({
    height:'auto',
    width:'auto',
    url:"customers.html",
    evaljs:true,
    evalhtml:true,
    scalesPageToFit:true
});
customers.add(customersView);

var contacts = Titanium.UI.createWindow({  
    title:'Contacts',
    backgroundColor:'#fff'
});
var contactsTab = Titanium.UI.createTab({  
    icon:'KS_nav_ui.png',
    title:'Contacts',
    window:contacts
});
Titanium.App.addEventListener('address_selected', function(ev) {
    try {
        var contactMap = Titanium.UI.createWindow({
            title:"Contact Location",
            backgroundColor:'transparent'
        });
        Titanium.Geolocation.forwardGeocoder(ev.address, function(evt) {
            var mapView = Titanium.Map.createView({
                mapType: Titanium.Map.STANDARD_TYPE,
                region: {latitude:evt.latitude, longitude:evt.longitude,latitudeDelta:0.01,longitudeDelta:0.01},
                animate:true,
                regionFit:true,
                userLocation:true,
                top:0,
                bottom:0
            });
            contactMap.addEventListener('blur',function() {
                contactMap.remove(mapView);
            });
            contactMap.add(mapView);
            customersTab.open(contactMap,{animated:true});
        });
    } catch(e) {
        alert(e.message);
    }
});
Titanium.App.addEventListener('contact_add', function(ev) {
    try {
        var contact, parsedname, people, address;
        contact = JSON.parse(ev.contact);
        address = parseAddress(contact.address);
        people = Titanium.Contacts.getPeopleWithName(contact.fullName);
        if(people.length === 1) {
             Titanium.Contacts.removePerson(people[0]);
             Titanium.Contacts.save();
        }
        parsedname = contact.fullName.split(' ');
        if(parsedname.length === 2 && contact.phone && address.Street) {
            var firstName = parsedname[0].trim();
            var lastName = parsedname[1].trim();
            Titanium.Contacts.createPerson({
                firstName: firstName,
                lastName: lastName,
                phone:{"work":[contact.phone]},
                address:{"work":[address]}
            });
            Titanium.Contacts.save();
        }
    } catch(e) {
        alert(e.message);
    }
});

var contactsView = Titanium.UI.createWebView({
    height:'auto',
    width:'auto',
    url:"contacts.html",
    evaljs:true,
    evalhtml:true,
    scalesPageToFit:true
});
function contactsModel(e) {
    var people = Titanium.Contacts.getAllPeople();
    var data = [];
    people && people.forEach(function(person) {
        try {
            data.push({
                fullName:person.fullName,
                phone:JSON.stringify(person.phone),
                address:JSON.stringify(person.address)
             });
        } catch(e) {
            alert(e.message);
        }
    }, this);
    Titanium.App.fireEvent('contacts_info',{data:data});
    contacts.add(contactsView);
}
contactsView.addEventListener('load', contactsModel);
contacts.addEventListener('blur',function() {
    contacts.remove(contactsView);
});
contacts.addEventListener('focus',contactsModel);

tabGroup.addTab(customersTab);  
tabGroup.addTab(contactsTab);  
tabGroup.open();
