<?php
  $ch = curl_init('http://api.twitter.com/1/followers/ids.xml?screen_name=kevinknoll');
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);

  $xml = new SimpleXmlElement($output, LIBXML_NOCDATA);
  $cnt = count($xml);
  $ids = $xml->id;
  $avatars = array();

  for ($i = 0; $i < $cnt; ++$i) {
    $id = $ids[$i];
    $remote = 'http://api.twitter.com/1/users/profile_image/' . $id . '?size=mini';
    $local = 'img/' . $id;
    if (file_exists($local)) {
      array_push($avatars,$local);
    } else {
      /*$img = file_get_contents($remote);
      $fp  = fopen($local, 'w+');
      fputs($fp, $img);
      fclose($fp);
      unset($img);*/
      array_push($avatars,$remote);
    }
  }
  echo json_encode($avatars);
?>