<!DOCTYPE html>
<html>
    <head>
        <title> List of all the mobile devices </title>
        <meta charset="utf-8" />
		<!-- Update D Sanders : 15 Nov 2016 -->
        <!-- Initial PHP Script -->
        <?php 
            if ( isset$_SERVER["DEBUG"]() ) {
				error_reporting(E_ALL);
				ini_set('display_errors', 'On');
            }

            /*
             * Set the include path for all includes on this page.
             */
            $path = $_SERVER['DOCUMENT_ROOT'];
            $path .= "/includes/;";
            set_include_path($path);
            include "includedCSS.php"; 
            
            /*
             * checkString($stringToCheck, $permissibleValues, $maxLength)
             * 
             * A function to check that the query parameter (e.g. = or like)
             * is valid and that SQL injection is not being used. Simply checks
             * a string against an array of permissible values.
             * 
             * $stringToCheck - this is the string we want to validate
             * $permissibleValues - the string has to be in this array
             * $maxLength - the maximum length of the string to check, which
             *              simply helps to avoid checking the array if the
             *              string is already over length.
             * 
             * e.g. checkString("1' and '1'=='1'", {'<','<=','=','>','>='}, 2)
             *   - would fail because the input string is greater than 2
             * e.g. checkString("--", {'<','<=','=','>','>='}, 2)
             *   - would also fail because the input string is not in the array
             * 
             */
            function checkString($stringToCheck, $permissibleValues, $maxLength) {
                $invalidStringFound = false;

                if ($stringToCheck > $maxLength) {
                    return false;
                }
                /* Reference: http://php.net/manual/en/function.in-array.php */
                return in_array($stringToCheck, $permissibleValues, true);
            }
            
            /* define the valid options for number query fields */
            $LtGtEqArray = array("<","<=","=",">",">=");
            /* define the valid options for text query fields */
            $containsArray = array("like", "=");
        ?>
    </head>
    <?php 
        $statusMessage = "";
        $isDelete = false;

        /* Include the database connection info                      */
        /* include "db.inc"; */
        $mysqli = new mysqli($_SERVER["DB_HOST"], $_SERVER["DB_USER"], $_SERVER["DB_PASS"], $_SERVER["DB_DB"]);

        /*
         * Check if the page has been called from a post
         */
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            /*
             * Then check if the item deleteAction is there - if it is,
             * then we've been called from index.php itself and the only
             * action from that route is delete. So, delete any rows
             * passed and then carry on loading the form.
             */
            if ($_POST["deleteAction"]) {
                $isDelete = true;
                
                /* Prepare the status message which will be shown after the deletion */
                $statusMessage = "Deleted devices with the following reference numbers: ";

                /* Get the array of devices to delete */
                $selectorArray = $_POST["selector"];

                /* Find the length of the array */
                $selectorLength = count($selectorArray);

                /* Prepare the SQL Delete command */
                $deleteStatement = "delete from dsandersdevices where refNo = ?";

                /* Prepare the SQL Delete statement with the database */
                $stmt = $mysqli->prepare($deleteStatement);

                /* Loop through every device and delete it */
                for ($i = 0; $i < $selectorLength; $i++) {
                    /* Bind the parameters for the current row */
                    $stmt->bind_param(
                            "i", 
                            $selectorArray[$i]
                            );
                    /* Execute the delete command */
                    $stmt->execute();
                    /* Build the status message for the user */
                    if ($i == $selectorLength - 1)
                        $statusMessage .= $selectorArray[$i];
                    else
                        $statusMessage .= $selectorArray[$i].", ";
                  }
            }
        }
    ?>
    <body>
        <!-- Include the header.inc html                                     -->
        <?php include "header.inc";?>
        <?php 
            if ($isDelete) echo "<script>mainDeletedDevices(\"".$statusMessage."\")</script>";; 
        ?>
        <!--                                                                 -->
        <!-- #mainSection is where ALL user output is shown.                 -->
        <!--                                                                 -->
        <div id="mainSection">
            <h1>11-15-2016: Mobile Devices in the Database...</h1>
            <?php
                /*
                 * Main PHP body for withIN89_DB_ALLDevices.php
                 * --------------------------------------------
                 * Purpose: This section of code defines all query fields, checks
                 * to see if fields have been passed into the page, and prepares
                 * the variables used in the query. Then, the code block prepares
                 * a SQL statement and then executes it but DOES NOT fetch rows.
                 */
            
                /* Query Variables */
                 $emptyQueryFields = 0;     // Number of fields with no query data
                 $doCatchAll = "";          // If no fields were given, we should
                                            // perform a catch all query
                 
                 $queryRefNo = " ";          // The value entered by the user in QBE
                 $refNoQuery = "=";         // The condition selected by the user
                 $queryName = " ";           // The value entered by the user in QBE
                 $nameQuery = "=";          // The condition selected by the user
                 $queryType = " ";           // The value entered by the user in QBE
                 $typeQuery = "=";          // The condition selected by the user
                 $queryStockLevel = "";     // The value entered by the user in QBE
                 $stockQuery = "=";         // The condition selected by the user
                 $queryColor = " ";          // The value entered by the user in QBE
                 $colorQuery = "=";         // The value entered by the user in QBE
                 $salesQuery = "=";         // The value entered by the user in QBE
                 $ratingQuery = "=";        // The value entered by the user in QBE

                 /* Check if the Ref No was filled in on the query form       */
				 $queryRefNo = $_POST["deviceRefNo"];
                 if (!$queryRefNo) {
                     $emptyQueryFields++;
                 } else {
                     /* Find out if <, <=, =, >, or >= was chosen by the user */
                     $refNoQuery = $_POST["deviceRefNoQuery"];
                     /* Check that there aren't any SQL Injections */
                     if (!checkString($refNoQuery, $LtGtEqArray ,2)) {
                         $refNoQuery = "=";
                     }
                 }

                 /* Check if the name was filled in on the query form         */
                 $queryName = $_POST["deviceName"];
                 if (!$queryName) {
                     $emptyQueryFields++;
                 } else {
                     $nameQuery = $_POST["deviceNameQuery"];
                     /* Check that there aren't any SQL Injections */
                     if (!checkString($nameQuery, $containsArray ,4)) {
                         $nameQuery = "like";
                     }
                     /* Find out if contains or equals was chosen by the user */
                     if ($nameQuery === "like") {
                         $queryName = "%".$queryName."%";
                     }
                 }

                 /* Check if the device type was filled in on the query form  */
                 $queryType = $_POST["deviceType"];
                 if (!$queryType) {
                     $emptyQueryFields++;
                 } else {
                     $typeQuery = $_POST["deviceTypeQuery"];
                     /* Check that there aren't any SQL Injections */
                     if (!checkString($typeQuery, $containsArray ,4)) {
                         $typeQuery = "like";
                     }
                     /* Find out if contains or equals was chosen by the user */
                     if ($typeQuery === "like") {
                         $queryType = "%".$queryType."%";
                     }
                 }

                 /* Check if the color was filled in on the query form        */
                 $queryColor = $_POST["deviceColor"];
                 if (!$queryColor) {
                     $emptyQueryFields++;
                 } else {
                     $colorQuery = $_POST["deviceColorQuery"];
                     /* Check that there aren't any SQL Injections */
                     if (!checkString($colorQuery, $containsArray ,4)) {
                         $colorQuery = "like";
                     }
                     /* Find out if contains or equals was chosen by the user */
                     if ($colorQuery === "like") {
                         $queryColor = "%".$queryColor."%";
                     }
                 }

                 /* Check if the Stock Level was filled in on the query form  */
                 $queryStockLevel = $_POST["deviceStockLevel"];
                 if (!$queryStockLevel) {
                     $emptyQueryFields++;
                 } else {
                     $stockQuery = $_POST["deviceStockQuery"];
                     /* Check that there aren't any SQL Injections */
                     if (!checkString($stockQuery, $LtGtEqArray, 2)) {
                         $stockQuery = "=";
                     }
                 }

                 /* Check if the sales was filled in on the query form        */
                 $querySales = $_POST["deviceSales"];
                 if (!$querySales) {
                     $emptyQueryFields++;
                 } else {
                     /* Find out if <, <=, =, >, or >= was chosen by the user */
                     $salesQuery = $_POST["deviceSalesQuery"];
                     /* Check that there aren't any SQL Injections */
                     if (!checkString($salesQuery, $LtGtEqArray, 2)) {
                         $salesQuery = "=";
                     }
                 }

                 /* Check if the rating was filled in on the query form       */
                 $queryRating = $_POST["deviceRating"];
                 if (!$queryRating) {
                     $emptyQueryFields++;
                 } else {
                     /* Find out if <, <=, =, >, or >= was chosen by the user */
                     $ratingQuery = $_POST["deviceRatingQuery"];
                     /* Check that there aren't any SQL Injections */
                     if (!checkString($ratingQuery, $LtGtEqArray, 2)) {
                         $ratingQuery = "=";
                     }
                 }

                 /* If all the fields were empty then we need to do a catchall */
                 if ($emptyQueryFields === 7) {
                     $doCatchAll = "or name like '%'";
                 }

                 /*
                  * Result variables. The output from the database will be bound
                  * to these variables.
                  */
                 $resultRefNo = "";
                 $resultName = "";
                 $resultType = "";
                 $resultColor = "";
                 $resultStockLevel = 0;
                 $resultSales = 0;
                 $resultRating = 0;

                 /*
                  * I wanted to use prepared statements as I have a query form
                  * and wanted to prevent SQL Injection. The best guidance I
                  * could find on this was using mysqli so I chose this option. 
                  * 
                  * References:
                  * 1. http://mattbango.com/notebook/code/prepared-statements-in-php-and-mysqli/
                  * 2. http://www.w3schools.com/php/php_mysql_prepared_statements.asp
                  * 3. http://www.databasejournal.com/features/php/article.php/3599166/Connecting-and-prepared-statements-with-the-mysqli-extension.htm
                  */
                 
                 /*
                  * Build a query string. This string builds up in two ways:
                  * First, certain fields are given the option of being exact
                  * matches or contains or <,<=,=,>=,> qualifiers, so the query
                  * string is built up depending upon preset options.
                  * Second, the values to be used in the query are represented
                  * by ? (question marks) which we will bind variables to - this
                  * helps avoid SQL Injection attacks by ensuring only set
                  * values can be passed and avoiding the use of -- comments to
                  * change the behavior of a query (see references above).
                  */
                 $queryStatement = "select refno, name, deviceType, colour, stockLevel, salesThisMonth, customerRating ".
                                   "from dsandersdevices ".
                                   "where refno ".$refNoQuery." ? ".
                                   "or name ".$nameQuery." ? ".
                                   "or deviceType ".$typeQuery." ? ".
                                   "or colour ".$colorQuery." ? ".
                                   "or stockLevel ".$stockQuery." ? ".
                                   "or salesThisMonth ".$salesQuery." ? ".
                                   "or customerRating ".$ratingQuery." ? ".
                                   $doCatchAll.
                                   "order by refno";
                 
                 /*
                  * Issue the prepare statement to tell the database to get the
                  * query ready.
                  */
                 $stmt = $mysqli->prepare($queryStatement);
                 /*
                  * Bind the parameters for the query. The isssi string specifies
                  * we are passing in five parameters, the first and last are
                  * integers (i) and the other three are strings (s).
                  */
                 $stmt->bind_param(
                         "isssiii",
                         $queryRefNo,
                         $queryName,
                         $queryType,
                         $queryColor,
                         $queryStockLevel,
                         $querySales,
                         $queryRating
                         );
                 
                 /*
                  * Execute the query BUT do NOT fetch any rows.
                  */
                 $stmt->execute();
                 
                 /*
                  * Bind the results to PHP variables
                  */
                 $stmt->bind_result($resultRefNo,
                                    $resultName, 
                                    $resultType, 
                                    $resultColor, 
                                    $resultStockLevel, 
                                    $resultSales, 
                                    $resultRating
                                   );
            ?>
            <!-- Draw the table                                              -->
            <form id="indexForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <table id="mobilesTable">
                    <caption>Mobiles in Stock</caption>
                    <thead>
                        <tr>
                            <th id="selectTH">Select</th>
                            <th id="refNoTH">Ref. No.</th>
                            <th id="nameTH">Name</th>
                            <th id="deviceTypeTH">Device Type</th>
                            <th id="colorTH">Color</th>
                            <th id="stockLevelTH" class="right">Stock Level</th>
                            <th id="salesTH" class="right">Sales (this month)</th>
                            <th id="ratingTH" class="right">Customer Rating</th>
                        </tr>
                    </thead>
                    <!--                                                         -->
                    <!-- php code block to start a while loop and fetch each row -->
                    <!-- from the database. Notice the HTML included with in the -->
                    <!-- block between while and endwhile                        -->
                    <?php 
                        $i = 0;
                        while ($stmt->fetch()) :
                    ?> 
                        <!-- For every row, add the following HTML to the page   -->
                        <tr>
                            <td class="selector">
                                <!-- Give the selector a unique ID so we can use -->
                                <!-- it later to identify rows when chosen for   -->
                                <!-- edit or delete                              -->
                                <input name="selector[]" value="<?php echo $resultRefNo; ?>" type="checkbox" id="selectRow<?php echo ++$i ?>"/>
                            </td>
                            <td class = "dataColumn">
                                <label id="refNo<?php echo $i ?>" for="selectRow<?php echo $i; ?>"><?php echo $resultRefNo; ?></label>
                            </td>
                            <td class = "dataColumn">
                                <label for="selectRow<?php echo $i; ?>"><?php echo $resultName; ?></label>
                            </td>
                            <td class = "dataColumn">
                                <label for="selectRow<?php echo $i; ?>"><?php echo $resultType; ?></label>
                            </td>
                            <td class = "dataColumn">
                                <label for="selectRow<?php echo $i; ?>"><?php echo $resultColor; ?></label>
                            </td>
                            <td class = "dataColumn right">
                                <label for="selectRow<?php echo $i; ?>"><?php echo $resultStockLevel; ?></label>
                            </td>
                            <td class = "dataColumn right">
                                <label for="selectRow<?php echo $i; ?>"><?php echo $resultSales; ?></label>
                            </td>
                            <td class = "dataColumn right">
                                <label for="selectRow<?php echo $i; ?>"><?php echo $resultRating; ?></label>
                            </td>
                        </tr>
                    <!-- end of while loop                                       -->
                    <?php 
                        endwhile;
                        $mysqli -> close(); /* Close the database connection */
                    ?>
                </table>
                <!-- Create the options for managing the devices table, currently-->
                <!-- only Query and reload are provided.                         -->
                <h3>Database Options:</h3>
                <fieldset>
                    <input type="button" value="Add a Device" class="button" onclick="mainAddDevice();"/>
                    <input type="button" value="Update Selected Device" class="button" onclick="mainUpdateDevice();"/>
                    <input type="submit" value="Delete Selected Devices" class="button" onclick="mainDelDevices(); return false;"/>
                    <input type="button" value="Enter a Query" class="button" onclick="mainEnterQuery();"/>
                    <input type="button" value="Show All Devices" class="button" onclick="mainFetchAll();"/>
                    <!-- This input is used purely to indicate to index.php that it needs to deal with a delete request -->
                    <input name="deleteAction" type="text" value="deleteAction" style="display: none;"/>
                </fieldset>
            </form>
            <!-- Include the standard footer for the page.                   -->
            <?php include "footer.inc"; ?>
        </div>
    </body>
</html>
