<?php
class Api
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $certificate;
    public function __construct(string $baseUrl, string $clientId, string $clientSecret, string $certificate)
    {
        $this->baseUrl = $baseUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->certificate = $certificate;
    }

    //https://www.youtube.com/watch?v=6Es3i2eH5K4
}
