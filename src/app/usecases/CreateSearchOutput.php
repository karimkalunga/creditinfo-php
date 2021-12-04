<?php

namespace Devocean\Creditinfo\app\usecases;

use Devocean\Creditinfo\domain\entities\SearchOutput;
use Exception;

class CreateSearchOutput
{
    private mixed $searchOutputRepo;

    public function __construct(mixed $searchOutputRepo)
    {
        $this->searchOutputRepo = $searchOutputRepo;
    }

    /**
     * @throws Exception
     */
    public function save(SearchOutput $searchOutput): SearchOutput
    {
        return $this->searchOutputRepo->create($searchOutput);
    }
}