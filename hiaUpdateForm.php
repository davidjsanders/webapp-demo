<?php
    /*
     * Set the include path for all includes on this page.
     */
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= "/includes/";
    set_include_path($path);

    /* Include the database connection info                      */
    include "db.inc";

    $loadFunction = "updateOnLoad();";
    /*
     * If the page is loaded with a GET request, then it has either been called
     * from index.php or the user has entered the url directly in the browser.
     * So, we will check that refNo is passed and if it is show the values for
     * edit. If refNo doesn't exist in the database, then the page closes and
     * returns to index.php.
     */
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        if (isset($_GET["refNo"])) {
            /*
             * Result variables. The output from the database will be bound
             * to these variables.
             */
            $resultRefNo = $_GET["refNo"];  // Store the refNo passed in the URL
            $resultName = "";
            $resultType = "";
            $resultColor = "";
            $resultStockLevel = 0;
            $resultSales = 0;
            $resultRating = 0;

            /*
             * Query will select all the fields from dsandersdevices for the
             * refNo passed.
             */
            $queryStatement = "select name, deviceType, colour, stockLevel, salesThisMonth, customerRating ".
                              "from dsandersdevices ".
                              "where refNo = ? ";

            /*
             * Issue the prepare statement to tell the database to get the
             * query ready.
             */
            $stmt = $mysqli->prepare($queryStatement);

            /*
             * Bind the parameters for the query. The i string specifies
             * we are passing in one parameter, which is the integer refNo.
             */
            $stmt->bind_param(
                    "i", 
                    $resultRefNo
                    );

            /*
             * Execute the query BUT do NOT fetch any rows.
             */
            $stmt->execute();

            /*
             * Bind the results to PHP variables
             */
            $stmt->bind_result($resultName, 
                               $resultType, 
                               $resultColor, 
                               $resultStockLevel, 
                               $resultSales, 
                               $resultRating
                              );

            /*
             * Fetch the first row. We ignore multiple rows.
             */
            $success = $stmt->fetch();
            /*
             * Check that the query returned at least one row. If it didn't
             * set the page to close and return to index.php.
             */
            if (!$success)
                $loadFunction = "closeAndReturn();";
            else {
                /*
                 * A row was returned, so pass it to a JavaScript function which
                 * will be executed on load and set the field values. It's done
                 * this way because the form is shared via a php include, so we
                 * can't do 
                 * 
                 *   <input type="text" <?php echo (($checkSales) ? echo "value='".$resultRefNo."'"?>...
                 * 
                 * If we did the statement above, we would have to change the
                 * included form and that would affect ALL CRUD pages.
                 * 
                 */
                $loadFunction = 
                        "updateOnLoad(".
                            "'".$resultRefNo."', ".
                            "'".$resultName."', ".
                            "'".$resultType."', ".
                            "'".$resultColor."', ".
                            "'".$resultStockLevel."', ".
                            "'".$resultSales."', ".
                            "'".$resultRating."'".
                        ");";
            }
        }
        else
        {
            /*
             * If we are here, then no refNo was passed in the URL, so close
             * the form and return to index.php
             */
            $loadFunction = "closeAndReturn();";
        }
    }
    /*
     * This is the post section where this page will post to itself and save
     * changes to the record retrieved in the GET above.
     */
    if ($_SERVER["REQUEST_METHOD"] === "POST") 
    {
        /* Set the load function to be closeAndReturn when we're done.        */
        $loadFunction = "closeAndReturn();";
        /* Get the updated values from the post.                              */
        $queryRefNo = $_POST["deviceRefNo"];
        $queryName = $_POST["deviceName"];
        $queryType = $_POST["deviceType"];
        $queryStockLevel = $_POST["deviceStockLevel"];
        $queryColor = $_POST["deviceColor"];
        $querySales = $_POST["deviceSales"];
        $queryRating = $_POST["deviceRating"];

        /*
         * Build the SQL command string. 
         */
        $queryStatement = 
                "update dsandersdevices ".
                "set name = ?, ".
                    "deviceType = ?, ".
                    "colour = ?, ".
                    "stockLevel = ?, ".
                    "salesThisMonth = ?, ".
                    "customerRating = ? ".
                "where refNo = ?";

        /*
         * Issue the prepare statement to tell the database to get the
         * query ready.
         */
        $stmt = $mysqli->prepare($queryStatement);

        /*
         * Bind the parameters for the query. The sssiiii string specifies
         * we are passing in seven parameters, the first three are 
         * strings (s) and the last four integers (i).
         */
        $stmt->bind_param(
                "sssiiii", 
                $queryName, 
                $queryType, 
                $queryColor, 
                $queryStockLevel,
                $querySales,
                $queryRating,
                $queryRefNo
                );

        /*
         * Execute the query BUT do NOT fetch any rows.
         */
        $stmt->execute();
        $mysqli -> close(); /* Close the database connection */
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title> List of all the mobile devices </title>
        <meta charset="utf-8" />
        <!-- Initial PHP Script -->
        <?php 
            include "includedCSS.php"; 
            include "range.inc";
        ?>
        <!-- Link in the QBE specific CSS                                    -->
        <link href="css/add.css" rel="stylesheet" type="text/css">
        <!-- Load QBE specific JS                                            -->
        <script src="js/hiaFormsJs.js"></script>
    </head>
    <!-- 
        Notice that the onload function is produced by php depeneding on the
        value of the $loadFunction string.
    -->
    <body onload="<?php echo $loadFunction;?>">
        <!-- Include the QBE specific header                                 -->
        <?php include "header.inc";?>
        <!--                                                                 -->
        <!-- Layout the add form.                                            -->
        <!--                                                                 -->
        <h1>Update a mobile Device in the Database...</h1>
        <form id="devicesForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <!-- Include the form fields so we take a DRY approach -->
            <?php include "form.inc"; ?>
            <fieldset>
                <!-- Reference: http://www.w3schools.com/jsref/met_form_submit.asp -->
                <input type="submit" name="Save Changes" value="Save Changes" class="button"/>
                <input type="reset" name="Reset Form" value="Reset Form" class="button" onclick="qbeResetForm(); addOnLoad(); return false;"/>
                <input type="button" value="Cancel" name="Show All Devices" class="button" onclick="qbeFetchAll();"/>
            </fieldset>
        </form>
        <!-- Include the standard footer for the page.                   -->
        <?php include "footer.inc"; ?>
    </body>
</html>
