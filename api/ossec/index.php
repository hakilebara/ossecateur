<?php
require '../Slim/Slim.php';
require '../NotORM.php';

$pdo = new PDO("mysql:dbname=ossec", "root");
$db = new NotORM($pdo);

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
  'log.enabled' => true
));

$app->options("/alerts", function() use ($app) {
  $app->response()->header('Access-Control-Allow-Credentials', true);
  $app->response()->header('Access-Control-Request-Method', "*");
  $app->response()->header('Access-Control-Allow-Origin', "http://localhost:4200"); //Allow JSON data to be consumed
  $app->response()->header('Access-Control-Allow-Headers', "API_KEY, Origin, X-Requested-With, Content-Type, Accept, Authorization, withCredentials"); //Allow JSON data to be consumed
  $app->response()->header('Access-Control-Allow-Methods', "*"); //Allow JSON data to be consumed
  $app->response()->header('Access-Control-Allow-Methods', "POST, PUT, DELETE, GET, OPTIONS"); //Allow JSON data to be consumed
});

//$app->token = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$app->post("/auth", function() use ($app, $db){
  $app->response()->header("Access-Control-Allow-Origin","*");
  $req = $app->request();
  $username = $req->post('username');
  $password = $req->post('password');

  if ($username == 'nocapac' && $password == 'glglgl') {
      echo json_encode(array(
        "success" => true,
        "token"   => base64_encode("$username:$password")
      ));
  } else{
    echo json_encode(array(
      "success" => false,
      "message" => "Invalid Username/Password"
    ));
  }
});

function isTokenProvidedValid($req, $res) { 
  //uncomment to disable authentication
  return true;
  $userToken = $req->headers->get('token');
  //file_put_contents("ossec_api.log", print_r($userToken, true));
  //file_put_contents("ossec_api.log", print_r(base64_decode($userToken), true));
  if (!$userToken) {
    echo json_encode(array(
      $res->setStatus(401),
      "error" => "Invalid Token ".$userToken
    ));
    return false;
  }
  list($username, $password) = explode(':', base64_decode($userToken));
  if ($username == 'nocapac' && $password == 'glglgl') {
    return true;
  } 
  else {
    echo json_encode(array(
      $res->setStatus(401),
      "error" => "Invalid Token ".$userToken
    ));
    return false;
  }
};

function serializeDateToIso8601 ($timestamp){
  return gmdate("Y-m-d\TH:i:s\Z", strtotime($timestamp));
}

function serializeIso8601toDate ($iso8601){
  return date('Y-m-d H:i:s',strtotime($iso8601));
}

$app->get("/alerts", function() use ($app, $db){
  if (isTokenProvidedValid($app->request(), $app->response()))
  {
    $alerts   = array();  
    foreach ($db->alert() as $alert) {

      $rule = $db->signature()
        ->where("rule_id", $alert["rule_id"])
        ->fetch();
      
      $data = $db->data()
        ->where(array(
          "id" => $alert["id"],
          "server_id" => $alert["server_id"]
        ))
        ->fetch();

      $acknowledgement = $db->acknowledgement()
        ->where("alert_id", $alert["id"])
        ->fetch();

      $is_acknowledged = $acknowledgement ? true : false;

      $comments = array();
      foreach ($db->comment()->select("id")->where("alert_id", $alert["id"]) as $comment) {
        // DEBUG:
        //$app->log->debug(print_r($comment["id"], true));
        $comments[] = $comment["id"];
      }



      $alerts[] = array(
        "id"                           => $alert["id"],
        "server"                       => $alert->server['hostname'],
        "description"                  => $rule['description'],
        "level"                        => $rule['level'],
        "full_log"                     => $data['full_log'],
        "timestamp"                    => gmdate("Y-m-d\TH:i:s\Z", $alert['timestamp']),
        "acknowledgement"              => $acknowledgement["id"],
        "comments"                     => $comments
        //"is_acknowledged"              => $is_acknowledged,
        //"acknowledgement_author"       => $acknowledgement["owner"],

        // return null timestamp instead of default epoch time if alert is not acknowledged
        //"acknowledgement_timestamp"    => $acknowledgement ? serializeDateToIso8601($acknowledgement["timestamp"]) : null
      );
    }
    $app->response()->header("Content-Type","application/json");
    $app->response()->header("Access-Control-Allow-Origin","*");
    #DEBUG
    #file_put_contents("ossec_api.log", print_r(getallheaders(), true));
    echo json_encode(array("alerts" => $alerts));
  }
});

$app->post("/acknowledgements", function() use ($app, $db){
  $app->response()->header("Content-Type","application/json");
  $app->response()->header("Access-Control-Allow-Origin","*");
  $request = json_decode($app->request->getBody(), true);
  // DEBUG:
  //$app->log->debug(print_r($request, true));
  $data = array(
    "owner"    => $request["acknowledgement"]["owner"],
    "alert_id"  => $request["acknowledgement"]["alert"],
  );
  $result = $db->acknowledgement->insert($data);
  $app->log->debug(print_r($result, true));


});

//$app->put("/alerts/:id", function($id) use ($app, $db){
  //$app->response()->header("Content-Type", "application/json");
  //$app->response()->header("Access-Control-Allow-Origin","*");
  //$alert = $db->alert()->where("id", $id);
  //if ($alert->fetch()) {
    //$request_body = $app->request->getBody();
    //$date = array(
      //"server"
      //"description"
      //"level"
      //"full_log"
      ////TODO: convert request iso8601 timestamp to epoch integer
      //"timestamp"
    //);
  //}
//});

$app->get("/acknowledgements", function() use ($app, $db){
  $acknowledgements = array();
  #file_put_contents("ossec_api.log", print_r(getallheaders(), true));
  foreach ($db->acknowledgement() as $acknowledgement) {
    $acknowledgements[] = array(
      "id"              => $acknowledgement["id"],
      "alert"           => $acknowledgement["alert_id"],
      "owner"           => $acknowledgement["owner"],
      "timestamp"       => serializeDateToIso8601($acknowledgement['timestamp'])
      //"timestamp"       => gmdate("Y-m-d\TH:i:s\Z", $acknowledgement['timestamp']),
    );    
  }
  $app->response()->header("Content-Type","application/json");
  $app->response()->header("Access-Control-Allow-Origin","*");
  echo json_encode(array("acknowledgements" => $acknowledgements));
});

$app->get("/acknowledgements/:id", function($id) use ($app, $db){
  $app->response()->header("Content-Type", "application/json");
  $app->response()->header("Access-Control-Allow-Origin","*");
  $acknowledgement = $db->acknowledgement()->where("id", $id);

  if ($data = $acknowledgement->fetch()) {
    echo json_encode(array("acknowledgement" => [
      "id"              => $id,
      "alert"           => $data["alert_id"],
      "owner"           => $data["owner"],
      "timestamp"       => serializeDateToIso8601($data['timestamp'])
      //"timestamp"       => gmdate("Y-m-d\TH:i:s\Z", $data['timestamp']),
      ]
    ));
  }
  else{
    echo json_encode(array(
      $app->response->setStatus(404),
      "status" => '404',
      "message" => "Acknowledgement does not exist"
    ));
  }
});

$app->get("/comments/:id", function($id) use ($app, $db){
  $app->response()->header("Content-Type", "application/json");
  $app->response()->header("Access-Control-Allow-Origin","*");
  $comment = $db->comment()->where("id", $id);

  if ($data = $comment->fetch()) {
    echo json_encode(array("comment" => [
      "id"              => $id,
      "author"          => $data["author"],
      "content"         => $data["content"],
      "timestamp"       => serializeDateToIso8601($data["timestamp"])
      ]
    ));
  }
  else{
    echo json_encode(array(
      $app->response->setStatus(404),
      "status" => '404',
      "message" => "Comment does not exist"
    ));
  }
});

$app->post("/comments", function() use ($app, $db){
  $app->response()->header("Content-Type","application/json");
  $app->response()->header("Access-Control-Allow-Origin","*");
  $request = json_decode($app->request->getBody(), true);
  // DEBUG:
  //$app->log->debug(print_r($request, true));
  $data = array(
    "alert_id"   => $request["comment"]["alert"],
    "author"     => $request["comment"]["author"],
    "content"    => $request["comment"]["content"],
    "timestamp"  => serializeIso8601toDate($request["comment"]["timestamp"])
  );
  //$app->log->debug(print_r($data, true));
  $result = $db->comment->insert($data);
});

$app->run();
?>
