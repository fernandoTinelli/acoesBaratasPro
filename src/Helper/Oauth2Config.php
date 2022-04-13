<?php

namespace App\Helper;

class Oauth2Config
{
  public function getConfigFacebook(): array
  {
    return [
      "clientId" => "",
      "clientSecret" => "",
      "redirectUri" => "",
      "graphApiVersion" => ""
    ];
  }
}