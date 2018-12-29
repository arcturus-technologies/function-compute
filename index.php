<?php
use RingCentral\Psr7\Response;

function handler($request, $context): Response
{
    $body = $request->getBody()->getContents();
    $body = urldecode (  $body );
    $json = json_decode( $body , true );

    if( !is_null( $json ))
    {
      $events = $json["events"][0];
      $message_type = $events["message"]["type"];

      if($message_type != "text") exit;

      $replyToken = $events["replyToken"];
      $message_text = $events["message"]["text"];
      $type = $events["type"];
      $userId = $events["source"]["userId"];

      $return_message_text = "「" . $message_text . "」じゃねーよｗｗｗ";

      $response_format_text = [
        "type" => $message_type,
        "text" => $return_message_text
      ];
 
      $post_data = [
          "replyToken" => $replyToken,
          "messages" => [$response_format_text]
      ];

      $ch = curl_init("https://api.line.me/v2/bot/message/reply");
      $accessToken = "Your Access Token";


      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . $accessToken
      ));

      $result = curl_exec($ch);

      curl_close($ch);
    }

    return new Response(
        200,
        array(
            "Content-Type" => "application/json",
        ),
        "hello world"
    );
}
