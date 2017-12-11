<?php

namespace App\Services\Clients;

use App\Services\Contracts\MangaInterface;

class MangaCoverClient extends Client implements MangaInterface
{
    protected $apiUrl = 'mcd.iosphe.re/api/v1/';
    protected $apiSecure = false;

    public function __construct()
    {
        parent::__construct($this->apiUrl);
    }

    public function find($key)
    {
        // TODO: Implement find() method.
    }

    public function manga($id)
    {
        // TODO: Implement manga() method.
    }

    public function authors($id)
    {
        // TODO: Implement authors() method.
    }

    public function characters($id)
    {
        // TODO: Implement characters() method.
    }
}
