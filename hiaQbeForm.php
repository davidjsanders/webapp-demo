<!DOCTYPE html>
<html>
    <head>
        <title> List of all the mobile devices </title>
        <meta charset="utf-8" />
        <!-- Initial PHP Script -->
        <?php 
            /*
             * Set the include path for all includes on this page.
             */
            $path = $_SERVER['DOCUMENT_ROOT'];
            $path .= "/wk6hia/includes/";
            set_include_path($path);
            include "includedCSS.php"; 
            include "range.inc";
            
            /*
             * END PHP
             */
        ?>
        <!-- Link in the QBE specific CSS                                    -->
        <link href="css/qbe.css" rel="stylesheet" type="text/css">
        <!-- Load QBE specific JS                                            -->
        <script src="js/hiaFormsJs.js"></script>
    </head>
    <body onload="qbeOnLoad();">
        <!-- Include the QBE specific header                                 -->
        <?php include "header.inc";?>
        <!--                                                                 -->
        <!-- Layout the query form.                                          -->
        <!--                                                                 -->
        <h1>Query mobile Devices in the Database...</h1>
        <form id="devicesForm" method="post" action="index.php">
            <?php include "form.inc"; ?>
            <fieldset>
                <!-- Reference: http://www.w3schools.com/jsref/met_form_submit.asp -->
                <input type="submit" value="Execute Query" action="#" name="Execute Query" class="button" onclick="qbeSubmitForm(); return false;"/>
                <input type="reset" value="Reset Form" class="button" onclick="qbeResetForm(); return false;"/>
                <input type="button" value="Show All Devices" name="Show All Devices" class="button" onclick="qbeFetchAll();"/>
            </fieldset>
        </form>
        <!--                                                                 -->
        <!-- Provide instructions to the user on how to use the query form.  -->
        <!--                                                                 -->
        <div id='instructionsDiv'>
            <h3>Instructions on Querying</h3>
            <p>
                The query form above can be populated by example to execute a 
                query against the devices database. 
            </p>
            <p>
                For example, if you enter &QUOT;phone&QUOT; in the name field and 
                leave the condition as &QUOT;Contains&QUOT;, then the query will 
                find all devices which contain &QUOT;and&QUOT; within the name;
                however, if you enter &QUOT;Android&QUOT; in the name field and set
                the condition as &QUOT;=&QUOT;, then the query will find only devices
                who's name is &QUOT;Android&QUOT;
            </p>
            <p>
                Queries are built on OR conditions.
            </p>
        </div>
        <!-- Include the standard footer for the page.                   -->
        <?php include "footer.inc"; ?>
    </body>
</html>