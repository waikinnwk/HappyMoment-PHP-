<?php
$cScope         =   'https://www.googleapis.com/auth/calendar';
$cClientID      =   '431566808428-3dlgumksssh5goboalmokk17tti57135.apps.googleusercontent.com';
$cClientSecret  =   'Ngxk0EzwzIbSLGMk6MwX8C1a';
$cRedirectURI   =   'urn:ietf:wg:oauth:2.0:oob';
  
$cAuthCode      =   '4/1IDeYhNQBUvImONaZF7kSWrG6DuWLky8saH80OCNDCs.8m0e6JjdHKsYgrKXntQAax2wmVE2lAI';
 
if (empty($cAuthCode)) {
    $rsParams = array(
                        'response_type' => 'code',
                        'client_id' => $cClientID,
                        'redirect_uri' => $cRedirectURI,
                        'access_type' => 'offline',
                        'scope' => $cScope,
                        'approval_prompt' => 'force'
                     );
 
    $cOauthURL = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($rsParams);
    echo("Go to\n$cOauthURL\nand enter the given value into this script under \$cAuthCode\n");
    exit();
} // ends if (empty($cAuthCode))
elseif (empty($cRefreshToken)) {
    $cTokenURL = 'https://accounts.google.com/o/oauth2/token';
    $rsPostData = array(
                        'code'          =>   $cAuthCode,
                        'client_id'     =>   $cClientID,
                        'client_secret' =>   $cClientSecret,
                        'redirect_uri'  =>   $cRedirectURI,
                        'grant_type'    =>   'authorization_code',
                        );
    $ch = curl_init();
  
    curl_setopt($ch, CURLOPT_URL, $cTokenURL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $rsPostData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
    $cTokenReturn = curl_exec($ch);
	echo($cTokenReturn);
    $oToken = json_decode($cTokenReturn);
    echo("Here is your Refresh Token for your application.  Do not loose this!\n\n");
    echo("Refresh Token = '" . $oToken->refresh_token . "';\n");
} // ends
?>