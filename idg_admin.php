<div class="wrap">  
    <h2>jirnee-Share Configuration</h2>

    <?php
    if (isset($_POST['idg_ckey'])) {
        global $wpdb;
        $table = IDIDGO_PLUGIN_TABLE_PREFIX . "jirnee";
        if ($_POST['idg_apikey'] != '' && $_POST['idg_ckey'] != '') {
            $qryDelOLD = "Delete from $table";
            $wpdb->query($qryDelOLD);
            // Populate table
            $wpdb->query("INSERT INTO $table(consumerKey,apiKey)
        VALUES('{$_POST['idg_ckey']}', '{$_POST['idg_apikey']}')");

// 'admin-list-site.php’ file 
            echo "<div id=\"message\" class=\"updated\">
            <p>
            Your <strong>Consumer Key</strong> and <strong>Api Key</strong> have been Saved.
            </p>
            </div>";
        }
    }
    /* echo "<h1>THis is the admin-settings page for ididGo share plugin.</h1><br>
      The API page is currently offline.
      Sorry for the inconvinience. <br/>
      -Admin"; */
    ?>

    <?php
    global $wpdb;
    $dgCnKey = "";
    $dgApiKey = "";
    $sql = "SELECT * FROM " . IDIDGO_PLUGIN_TABLE_PREFIX . "jirnee";
    $results = $wpdb->get_results($sql);
    //echo count($results);
    if (count($results) > 0) {
        foreach ($results as $result) {
            $dgCnKey = $result->consumerKey;
            $dgApiKey = $result->apiKey;
        }
    }
    ?>
    <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>"> 
        <table><tbody>
                <tr>
                    <td> <label id="lbl_dg">Consumer Key:</label> </td>
                    <td>  <input type="text" value="<?php echo $dgCnKey; ?>" name="idg_ckey"/><br/></td>
                </tr>
                <tr>
                    <td> <label>Api Key:</label> </td>
                    <td>  <input type="text" value="<?php echo $dgApiKey; ?>" name="idg_apikey"/>
                    </td>
                </tr>
                <tr>
                    <td></td> <td><input type="submit" value="Submit" class="button"/></td> </tr>
            </tbody>
        </table>
    </form><br/>
    <div class="updated">
        <p><strong>Reminder:</strong> If you have not got the api key, <a href="https://www.jirnee.com/?sgnup=true" target="_blank">click here</a> to get the <strong>api key and consumer key</strong>.
        <br/> For any <strong>help</strong> with the plugin, <a href="https://www.jirnee.com/?content=plugin#usage" targer="_blank">click here</a>.
        </p>
    </div><br/>
    
    <div>
        <p><strong></strong><br/>
            <img src="https://www.jirnee.com/images/plgScreen.png" /></p>
        <p><h3>How to use Jirnee Share</h3>
            <strong>step 1: HashTag</strong>Jirnee share reads for the location within your TAGS. In order for Jirnee-Share to read your location, you must place a hashtag in front of the location. Example: #Location, #Paris<br/>
            <img src="https://www.jirnee.com/images/plgTags.png"/>
        </p>
        <p><strong>How Jirnee-Share will actually share</strong><br/>
        Currently Jirnee-Share is set up for Facebook users, however the goal is to add twitter, reddit and many other social media outlets.<br/>
        <img src="https://www.jirnee.com/images/plgFb.png" width="400" style="margin-top:15px;"/>
        </p>
    </div>
<!--    <div><strong>Using the Jirnee-share</div>    -->
</div>
