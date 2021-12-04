<?php

namespace Devocean\Creditinfo\app\usecases;

use Devocean\Creditinfo\domain\entities\SearchOutput;

class GetSearchOutput
{
    private mixed $searchOutputRepo;

    public function __construct(mixed $searchOutputRepo)
    {
        $this->searchOutputRepo = $searchOutputRepo;
    }

    public function retrieveByApplicant(string $applicantId): ?SearchOutput
    {
        return $this->searchOutputRepo->findByApplicant($applicantId);
    }
}