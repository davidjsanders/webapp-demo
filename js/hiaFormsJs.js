/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* Simple function to submit the form */
function qbeSubmitForm() {
    document.getElementById('devicesForm').submit();
}

/* Simple function to reset the form */
function qbeResetForm() {
    document.getElementById('devicesForm').reset();
}

/* Simple function to load the withIN89_DB_ALLDevices.php page (i.e. refresh) */
function qbeFetchAll() {
    window.location.href="index.php";
}

/* Close and return is used to return back to the index.php page and is
 * often called from onload events when the page has done some work in php,
 * usually after a successful database update.
 */
function closeAndReturn() {
    window.location.href="index.php";
}

/*
 * addOnLoad() is called to setup the add form.
 */
function addOnLoad() {
    var element = setElementsForAddUpdate();
    element.value = 'Will Be Generated';
}

/*
 * updateOnLoad() is called to setup the update form and populate data from the
 * retrieved refNo into the form.
 */
function updateOnLoad(pRefNo, pName, pType, pColor, pStockLevel, pSales, pRating) {
    /* Get the elements from the form */
    var deviceRefNo = document.getElementById("deviceRefNo");
    var deviceName = document.getElementById("deviceName");
    var deviceType = document.getElementById("deviceType");
    var deviceColor = document.getElementById("deviceColor");
    var deviceStockLevel = document.getElementById("deviceStockLevel");
    var deviceSales = document.getElementById("deviceSales");
    var deviceRating = document.getElementById("deviceRating");

    /* Get the parameters passed in and set the form fields to their values */
    deviceRefNo.value = pRefNo;
    deviceName.value = pName;
    deviceType.value = pType;
    deviceColor.value = pColor;
    deviceStockLevel.value = pStockLevel;
    deviceSales.value = pSales;
    deviceRating.value = pRating;

    /* Set focus to device name because ref no is NOT updateable. */
    deviceName.focus();

    /* Because a single form is used for all CRUD operations, we need to */
    /* remove the blank option which is used in query only.              */
    /* In hindsight, it would probably be better to add this option for  */
    /* queries, but we are at code freeze!                               */
    
    /* Reference: http://www.w3schools.com/jsref/met_select_remove.asp */
    deviceType.remove(0); // Remove the blank device type option.
}

/*
 * setElementsForAddUpdate() changes the refNo to read only and removes the
 * deviceType option for blank. It returns the refNo page element.
 */
function setElementsForAddUpdate() {
    var element = document.getElementById("deviceRefNo");
    var elementToFocusOn = document.getElementById("deviceName");
    var deviceType = document.getElementById("deviceType");
    elementToFocusOn.focus();
    element.readOnly = "readonly";

    /* Reference: http://www.w3schools.com/jsref/met_select_remove.asp */
    deviceType.remove(0); // Remove the blank device type option.

    return element;
}

/*
 * qbeOnLoad() is called to setup the query form. It's main function is to
 * set the fields on the form to not be required.
 */
function qbeOnLoad() {
    /* Get the form element.                                                  */
    var form = document.getElementById("devicesForm");

    /* Get all of the <input> elements on the form                            */
    var inputs = form.getElementsByTagName("input");

    /* Get the count of the number of elements found                          */
    var numInputs = inputs.length;

    /* Loop through all the elements as set required to null - this is query  */
    for (var i = 0; i < numInputs; i++) {
        /* Check it's not a button, submit, or reset - if it is, ignore it    */
        if (inputs[i].type !== "submit" && inputs[i].type !== "reset" && inputs[i].type !== "button") {
            inputs[i].required = null;
        }
    }
}