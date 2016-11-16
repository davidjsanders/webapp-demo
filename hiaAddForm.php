<?php
    $loadFunction = "addOnLoad();";
    /*
     * Set the include path for all includes on this page.
     */
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= "/wk6hia/includes/";
    set_include_path($path);

    if ($_SERVER["REQUEST_METHOD"] === "POST") 
    {
        $loadFunction = "closeAndReturn();";

        /* Include the database connection info                      */
        include "db.inc";

        $queryName = $_POST["deviceName"];
        $queryType = $_POST["deviceType"];
        $queryStockLevel = $_POST["deviceStockLevel"];
        $queryColor = $_POST["deviceColor"];
        $querySales = $_POST["deviceSales"];
        $queryRating = $_POST["deviceRating"];

        /*
         * Build the SQL command string. 
         */
            $queryStatement = "insert into dsandersDevices values (".
                    " (select max(d.refno)+1 from dsandersDevices as d), ".
                    " ?, ".
                    " ?, ".
                    " ?, ".
                    " ?, ".
                    " ?, ".
                    " ? ".
                    ")";

        /*
         * Issue the prepare statement to tell the database to get the
         * query ready.
         */
        $stmt = $mysqli->prepare($queryStatement);

        /*
         * Bind the parameters for the query. The isssi string specifies
         * we are passing in five parameters, the first three are 
         * strings (s) and the last three integers (i).
         */
        $stmt->bind_param(
                "sssiii", 
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
    <body onload="<?php echo $loadFunction; ?>">
        <!-- Include the QBE specific header                                 -->
        <?php include "header.inc";?>
        <!--                                                                 -->
        <!-- Layout the add form.                                            -->
        <!--                                                                 -->
        <h1>Add a mobile Device to the Database...</h1>
        <form id="devicesForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <?php include "form.inc"; ?>
            <fieldset>
                <!-- Reference: http://www.w3schools.com/jsref/met_form_submit.asp -->
                <input type="submit" name="Add Device" value="Add Device" class="button"/>
                <input type="reset" name="Reset Form" value="Reset Form" class="button" onclick="qbeResetForm(); addOnLoad(); return false;"/>
                <input type="button" value="Cancel" name="Show All Devices" class="button" onclick="qbeFetchAll();"/>
            </fieldset>
        </form>
        <!-- Include the standard footer for the page.                   -->
        <?php include "footer.inc"; ?>
    </body>
</html>