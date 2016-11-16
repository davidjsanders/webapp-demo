/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* Bring up the query form. */
function mainEnterQuery () {
    window.location.href="hiaQbeForm.php";
}

/* Fetch everything by loading the page again. */
function mainFetchAll() {
    window.location.href="index.php";
}

/* Bring up the add form. */
function mainAddDevice() {
    window.location.href="hiaAddForm.php";
}

/* Bring up the something has been deleted alert and then return to index.php */
function mainDeletedDevices(message) {
    if (message != "")
        alert(message);
    window.location.href="index.php";
}

/*
 * selectCheckedRows iterates through the devices table and returns an array
 * of all reference numbers for devices selected.
 */
function selectCheckedRows() {
    var table = document.getElementById("mobilesTable");
    var rows = table.getElementsByTagName("tr");
    var numRows = rows.length;
    var selectedRows = [];

    /*
     * If there are no rows (i.e. <tr>) in the table, then there can't be 
     * anything to select so return.
     */
    if (numRows < 1) {
        alert('There are now rows to delete, sorry.');
        return null;
    }

    /*
     * Iterate through all of the rows in the table and find out which rows
     * have their selector checked. For all selected rows, push the refNo to
     * an array.
     */
    for (var i = 1; i < numRows; i++) {
        var selector = document.getElementById("selectRow"+i);
        var refNo = 0;
        if (selector.checked == true) {
            rowsChecked = true;
            refNo = document.getElementById("refNo"+i).innerHTML;
            selectedRows.push(refNo);
        }
    }
    
    /*
     * Return the array.
     */
    return selectedRows;
}

/*
 * mainUpdateDevice() is called to update a device in the table. It checks that
 * only one row has been selected in the table and then calls hiaUpdateForm.php
 * passing the reference number to be updated.
 */
function mainUpdateDevice() {
    var selectedRows = selectCheckedRows();
    var refNo = 0;
    
    if (selectedRows.length != 1) {
        alert('Please select one device for update.');
        return;
    }
    
    refNo = selectedRows[0];
    window.location.href="hiaUpdateForm.php?refNo="+refNo;
}

/*
 * mainDelDevices() is called to validate that devices have been selected, to
 * confirm with the user they really want to do the delete, and then to submit
 * the form which then invokes the php to delete the devices.
 */
function mainDelDevices() {
    /* Get the form from the page                                             */
    var indexForm = document.getElementById("indexForm");
    /* Setup the variables needed                                             */
    var selectedRowsFound = 0;
    var rowsChecked = false;
    var deleteMessage = "About to delete devices with Reference Numbers ";
    var postDeleteMessage = "Are you sure?";
    
    /* call selectCheckedRows() to get the array of checked devices           */
    var selectedRows = selectCheckedRows();

    /* Reference: http://www.w3schools.com/js/js_popup.asp */
    /* If there's less than one row selected than tell the user you have to   */
    /* select a row before you can delete it.                                 */
    if (selectedRows.length < 1) {
        alert('Please select at least one device to delete.');
    }
    else
    {
        /* Loop through the rows found and build a confirmation message for   */
        /* the user that shows them which devices are about to be deleted.    */
        selectedRowsFound = selectedRows.length;
        for (var i = 0; i < selectedRowsFound; i++) {
            deleteMessage += selectedRows[i];
            if (i == selectedRowsFound - 1)
                deleteMessage += ". ";
            else
                deleteMessage += ", ";
        }
        /* Show the user the dialog and store the response in checkWithUser   */
        var checkWithUser = confirm(deleteMessage + postDeleteMessage);

        /* if checkWithUser is true, then the user confirmed deleteion.       */
        if (checkWithUser == true) {
            indexForm.submit();
        }
    }
    return;
}