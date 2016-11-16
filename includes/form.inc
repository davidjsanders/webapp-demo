    <div>
        <label for="deviceRefNo">Reference Number </label>
        <select name="deviceRefNoQuery">
            <!-- Use the rangeLtEqGt() function to populate select   -->
            <?php rangeLtEqGt(); ?>
        </select>
        <input type="text" id="deviceRefNo" name="deviceRefNo"/>
    </div>
    <div>
        <label for="deviceName">Name</label>
        <select name="deviceNameQuery">
            <!-- Use the rangeContains() function to populate select -->
            <?php rangeContains(); ?>
        </select>
        <input required type="text" id="deviceName" name="deviceName"/>
    </div>
    <div>
        <label for="deviceType">Type</label>
        <select name="deviceTypeQuery">
            <!-- Use the rangeContains() function to populate select -->
            <option value="=">=</option>
        </select>
        <select name="deviceType" id="deviceType">
            <option id="deviceTypeBlank" value="" selected="selected"></option>
            <option value="phone">Phone</option>
            <option value="tablet">Tablet</option>
        </select>
<!--        <input required type="text" id="deviceType" name="deviceType"/> -->
    </div>
    <div>
        <label for="deviceColor">Color</label>
        <select name="deviceColorQuery">
            <!-- Use the rangeContains() function to populate select -->
            <?php rangeContains(); ?>
        </select>
        <input required type="text" id="deviceColor" name="deviceColor"/>
    </div>
    <div>
        <label for="deviceStockLevel">Stock Level</label>
        <select name="deviceStockQuery">
            <!-- Use the rangeLtEqGt() function to populate select   -->
            <?php rangeLtEqGt(); ?>
        </select>
        <input required min="1" max="1000" type="number" id="deviceStockLevel" name="deviceStockLevel"/>
    </div>
    <div>
        <label for="deviceSales">Sales (this month)</label>
        <select name="deviceSalesQuery">
            <!-- Use the rangeLtEqGt() function to populate select   -->
            <?php rangeLtEqGt(); ?>
        </select>
        <input required min="1" max="1000"  type="number" id="deviceSales" name="deviceSales"/>
    </div>
    <div>
        <label for="deviceRating">Customer Rating</label>
        <select name="deviceRatingQuery">
            <!-- Use the rangeLtEqGt() function to populate select   -->
            <?php rangeLtEqGt(); ?>
        </select>
        <input required min="1" max="5"  type="number" id="deviceRating" name="deviceRating"/>
    </div>
    <!--                                                             -->
    <!-- Layout control buttons to execute the query, reset the form -->
    <!-- or load all devices in the database.                        -->
    <!--                                                             -->
    <h3>Options:</h3>

