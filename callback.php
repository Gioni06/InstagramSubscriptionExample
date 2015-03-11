<?php
$debug = false; // default
/*
 * when registering a new subscription it initially calls the callback file the GET parameter "hub_challenge",
 * the subscription is considered valid when the same GET parameter is returned.
 */
if (isset ($_GET['hub_challenge'])){
    echo $_GET['hub_challenge'];
}
else{
    $myString = file_get_contents('php://input'); // get the raw incoming data stream
    $sub_update = json_decode($myString);
    $access_token = 'YOUR_ACCESS_TOKEN'; // CHANGE THIS

    foreach($sub_update as $k => $v) // can be multiple updates per call
    {
        $ch = curl_init('https://api.instagram.com/v1/tags/WirSindBebe/media/recent?access_token='.$access_token);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($ch);
        curl_close($ch);
        $enco = json_decode($json, true); // decode the json response to a php array

        // extract the first and lastname from the full_name "john doe" -> "john" & "doe"
        $name = explode(" ", $enco['data'][$k]['user']['full_name']);
        $firstname = $name[0];
        $lastname = $name[1];

        $instagram = $enco['data'][$k]['user']['username'];
        $type = $enco['data'][$k]['type'];
        $thumb = $enco['data'][$k]['images']['low_resolution']['url'];
        // If the source is an image
        if($type == 'image'){
            $pic = $enco['data'][$k]['link'];
            $vid = '';
            $src = $enco['data'][$k]['images']['standard_resolution']['url'];
        } else{
            // The source is a video
            $pic = '';
            $vid = $enco['data'][$k]['link'];
            $src = $enco['data'][$k]['videos']['standard_resolution']['url'];
        }

        // save incoming results to a DB
        require_once('DatabaseClass.php');
        $configPath = "config.php";
        $DB = new DatabaseClass($configPath);
        $DB->saveInstagramData($firstname,$lastname,$instagram,$src,$type,$thumb,$pic,$vid);

        /*
         * When in debug mode, write results to an "activity.log" file to test the incoming data.
         */
        if($debug){
            $data = array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'instagram' => $instagram,
                'scr' => $src,
                'type' => $type,
                'thumb' => $thumb,
                'pic' => $pic,
                'vid' => $vid,
                'host' => 'instagram'
            );
            file_put_contents('activity.log',json_encode($data), FILE_APPEND | LOCK_EX);
        }

    }

}

