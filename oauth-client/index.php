<?php
const CLIENT_ID = "client_606c5bfe886e14.91787997";
const CLIENT_SECRET = "2ce690b11c94aca36d9ec493d9121f9dbd5c96a5";
const FBCLIENT_ID = "313096147158775";
const FBCLIENT_SECRET = "c4ac86c990ffd48b3322d3734ec4ed1a";
const TWITCH_CLIENTID = "30xirglymud2jdnmojkpt3abeyjdtj";
const TWITCH_SECRET = "96a6b1kkpgckwzyzdb8a6rm1okec2b";
const PAYPALCLIENT_ID = "AYXRwX1gkkJo_eB91tuJGgceVtyrmCJ6duoUoPxDB3Tf79mX7Q_y9rRzeCmfniKP5ojdgS_P8M41pYC7";
const PAYPALCLIENT_SECRET = "EGwUyo81he6kO9UZ5Ywi1wXdPn6AFF8te_mVBwr7oFYmskQhnbl1AOBD_oBIa97opPEQLIJKYvpp2J4e";


function getUser($params)
{
    $result = file_get_contents("http://oauth-server:8081/token?"
        . "client_id=" . CLIENT_ID
        . "&client_secret=" . CLIENT_SECRET
        . "&" . http_build_query($params));
    $token = json_decode($result, true)["access_token"];
    // GET USER by TOKEN
    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "Authorization: Bearer " . $token
        ]
    ]);
    $result = file_get_contents("http://oauth-server:8081/api", false, $context);
    $user = json_decode($result, true);
    var_dump($user);
}
function getFbUser($params)
{
    $result = file_get_contents("https://graph.facebook.com/oauth/access_token?"
        . "redirect_uri=https://localhost/fb-success"
        . "&client_id=" . FBCLIENT_ID
        . "&client_secret=" . FBCLIENT_SECRET
        . "&" . http_build_query($params));
    $token = json_decode($result, true)["access_token"];
    // GET USER by TOKEN
    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "Authorization: Bearer " . $token
        ]
    ]);
    $result = file_get_contents("https://graph.facebook.com/me", false, $context);
    $user = json_decode($result, true);
    var_dump($user);
}

function getTwitchUser($params)
{
    $url = 'https://id.twitch.tv/oauth2/token';
    $data = array('client_id' => TWITCH_CLIENTID, 'client_secret' => TWITCH_SECRET,
        'code' => $params['code'], 'grant_type' => $params['grant_type']);


    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { echo ("Erreur de récupération du token") ;}
    else var_dump($result);




















/*
    $postdata = http_build_query(
        array(
            'client_id' => TWITCH_CLIENTID,
            'client_secret' => TWITCH_SECRET,
            'code' => $params['code'],
            'grant_type' => $params['grant_type']
        )
    );

    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );

    $context  = stream_context_create($opts);

    $result = file_get_contents('https://id.twitch.tv/oauth2/token', false, $context);

    var_dump($result);

    /*$url = "https://id.twitch.tv/oauth2/token?"
    . "redirect_uri=https://localhost/twitch-success"
    . "&client_id=" . TWITCH_CLIENTID
    . "&client_secret=" . TWITCH_SECRET
    . "&" . http_build_query($params);
   // $result = file_get_contents();

    //$token = json_decode($result, true)["token"];
    var_dump($url);
    // GET USER by TOKEN
    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "Authorization: Bearer " . $token
        ]
    ]);
    $result = file_get_contents("https://graph.facebook.com/me", false, $context);
    $user = json_decode($result, true);
    var_dump($user);*/

}

function getPaypalUser($params)
{
    $result = file_get_contents("https://api-m.sandbox.paypal.com/v1/oauth2/token"
        . "redirect_uri=https://localhost/paypal-success"
        . "&client_id=" . PAYPALCLIENT_ID
        . "&client_secret=" . PAYPALCLIENT_SECRET
        . "&" . http_build_query($params));
    $token = json_decode($result, true)["access_token"];
    // GET USER by TOKEN
    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "Authorization: Bearer " . $token
        ]
    ]);
    $result = file_get_contents("https://api-m.sandbox.paypal.com", false, $context);
    $user = json_decode($result, true);
    var_dump($user);
}

/**
 * AUTH_CODE WORKFLOW
 *  => Get CODE
 *  => EXCHANGE CODE => TOKEN
 *  => GET USER by TOKEN
 */
/**
 * PASSWORD WORKFLOW
 * => GET USERNAME/PASSWORD (form)
 * => EXHANGE U/P => TOKEN
 * => GET USER by TOKEN
 */

$route = strtok($_SERVER['REQUEST_URI'], '?');
switch ($route) {
    case '/auth-code':
        // Gérer le workflow "authorization_code" jusqu'à afficher les données utilisateurs
    echo '<h1 style="text-align:center">Login with Auth-Code</h1>';
    echo '<div style="text-align:left;padding-top:20vh;font-size:3vh;margin-left:35%">';
    echo "<a href='http://localhost:8081/auth?"
    . "response_type=code"
    . "&client_id=" . CLIENT_ID
    . "&scope=basic&state=dsdsfsfds'>Login with oauth-server</a>";
    echo "<br><a href='https://facebook.com/v2.10/dialog/oauth?"
    . "response_type=code"
    . "&client_id=" . FBCLIENT_ID
    . "&redirect_uri=https://localhost/fb-success"
    . "&scope=email&state=dsdsfsfds'> Login with facebook</a>";
    echo "<br><a href='https://id.twitch.tv/oauth2/authorize?"
    . "client_id=" . TWITCH_CLIENTID . "&redirect_uri=https://localhost/twitch-success&response_type=code&scope=viewing_activity_read&state=c3ab8aa609ea11e793ae92361f002671'> Login with twitch <img src='img/Logo-Twitch.jpg'></img></a>";

    echo "<a href='https://api-m.sandbox.paypal.com/dialog/oauth?"
        . "response_type=code"
        . "&client_id=" . PAYPALCLIENT_ID
        . "&redirect_uri=https://localhost/paypal-success"
        . "&scope=email&state=dsdsfsfds'>Login with paypal</a>";
    break;
    case '/success':
        // GET CODE
    ["code" => $code, "state" => $state] = $_GET;
        // ECHANGE CODE => TOKEN
    getUser([
        "grant_type" => "authorization_code",
        "code" => $code
    ]);
    break;
    case '/fb-success':
        // GET CODE
    ["code" => $code, "state" => $state] = $_GET;
        // ECHANGE CODE => TOKEN
    getFbUser([
        "grant_type" => "authorization_code",
        "code" => $code
    ]);
    break;
    case '/twitch-success':
    ["code" => $code, "state" => $state] = $_GET;
    getTwitchUser([
        "grant_type" => "authorization_code",
        "code" => $code
    ]);
    break;
    case '/paypal-success':
        // GET CODE
        ["code" => $code, "state" => $state] = $_GET;
        // ECHANGE CODE => TOKEN
        getPaypalUser([
            "grant_type" => "authorization_code",
            "code" => $code
    ]);
    break;

    case '/error':
    ["state" => $state] = $_GET;
    echo "Auth request with state {$state} has been declined";
    break;
    case '/password':
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        ['username' => $username, 'password' => $password] = $_POST;
        getUser([
            "grant_type" => "password",
            "username" => $username,
            "password" => $password,
        ]);
    } else {
            // Gérer le workflow "password" jusqu'à afficher les données utilisateurs
        echo "<form method='post'>";
        echo "Username <input name='username'>";
        echo "Password <input name='password'>";
        echo "<input type='submit' value='Submit'>";
        echo "</form>";
    }
    break;
    default:
    echo 'not_found';
    break;
}




//$sdk = new OauthSDK([
//    "facebook" => [
//        "app_id",
//        "app_secret"
//    ],
//    "oauth-server" => [
//        "app_id",
//        "app_secret"
//    ]
//    ]);
//
//$sdk->getLinks() => [
//    "facebook" => "https://",
//    "oauth-server" => "http://localhost:8081/auth"
//]
//
//$token = $sdk->handleCallback();
//$sdk->getUser();
// return [
//     "firstname"=>$facebookUSer["firstname"],
//     "lastname"=>$facebookUSer["lastname"],
//     "email"=>$facebookUSer["email"],
//     "phone" =>$facebookUSer["phone_number"]
// ];
