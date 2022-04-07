<?php

$url = 'http://laravel.localhost/ex-app/auth-informations';
$ch = curl_init();

$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZjRjN2IzZmUyOTlmYmM1ZDQzODU1NDA2YzRkMDI3ODJmZTc5NDIxNTcwNzE4MzZkOTRlNGM1MDE4MzhjYzk1YWQ3ZjIyOGU0NzZmNGUwMTkiLCJpYXQiOjE2NDg3OTQyNTMuMDQ1NjU5LCJuYmYiOjE2NDg3OTQyNTMuMDQ1Njc1LCJleHAiOjE2ODAzMzAyNTIuOTc3NDkyLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.m_kZRuOWdmjWzz8p_7m-ljEEaS-jZZhYOHmuBbbxLGQO6V4qxFDSdp9fivMp0f9zEMDvbtA36rUkYzmzp36hMhRfSEDXQP4cyHF-alxOpLtP8IO2_c9eXHTB6xKwIvnDR_xTEz4oICXlJlI_WkAI4B5sxQWvjJZykGECaGaAtaZoGyfMRwh95yZjC-cAKPZ6VRcTELuXk1mNNNhosawvcW5HeYmOEGqxvx8vAV6sGzr1PzZhK-H7sk4gWDCHwjqJx68Mf1OZAfZmM9n50yOB1Ks7mnR84M0VnhdaeIjy69HOEOpGvLggM8FNdWyo-pxjpMUDjdgwsHSHhiJwzujbu4AqTxBX8rR9PJjJvaerHV8GLeDcLZ88IvE6YBmXcVNv2GIur5hqXV89lUs1J7kjPO2lpB599D2bTDLzkE7RJun-aap7Z0AmcfL2cYR25eqHiiYptip-gYFJpBm7b6cWz1hkZX2UwbyqVVqhnmE2-wmew_00CaCKuhLdxuiBR7w4xJVnSSvZjd8_UwJQyGk5pgU6NxfDWvxyFg603mKW1njz75q9eIouleYZIJHKroYnyYMsHH-js9Iik2jWcM1d1CF04UwTwB_kunEHV9q8VglNo-hiaie3W5BVgDNX3b75w3G5pX1g2GLb0mNDscvB19zZtYazBTyx-ZU-YUr33ls';

$header = [
    "Content-Type:application/json",
    'Accept:application/json',
    "Authorization:Bearer {$token}",
    "Cache-Control:no-cache",
];

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

$server_output = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    return $server_output;
}
