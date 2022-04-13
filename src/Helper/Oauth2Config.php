<?php

namespace App\Helper;

class Oauth2Config
{
  public function getConfigFacebook(): array
  {
    return [
      "clientId" => "{}",
      "clientSecret" => "{}",
      "redirectUri" => "https://localhost:8000/login/oauth?done=facebook",
      "graphApiVersion" => "v2.10",
    ];
  }
}