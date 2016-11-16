<?php
    /*
     * Define function rangeLtEqGt()
     * -----------------------------
     * Populates an HTML <select> item so the same options can be
     * reused multiple times WITHOUT having to duplicate code. Populates
     * the select with <, <=, =, >=, >.
     * 
     * Used for number fields.
     * 
     */
    function rangeLtEqGt() {
        echo '<option value="<">&lt; (less than)</option>';
        echo '<option value="<=">&lt;= (less than or equal)</option>';
        echo '<option value="=" selected="selected">= (equals)</option>';
        echo '<option value=">">&gt; (greater than)</option>';
        echo '<option value=">=">&gt;= (greater than or equal)</option>';
    }

    /*
     * Define function rangeContains()
     * -----------------------------
     * Populates an HTML <select> item so the same options can be
     * reused multiple times WITHOUT having to duplicate code. Populates
     * the select with Contains (like) or =.
     * 
     * Used for text fields
     * 
     */
    function rangeContains() {
        echo '<option value="like" selected="selected">Contains</option>';
        echo '<option value="=">Equals</option>';
    }
?>