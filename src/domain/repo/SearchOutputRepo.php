<?php

namespace Devocean\Creditinfo\domain\repo;

use Devocean\Creditinfo\domain\entities\SearchOutput;
use Exception;

abstract class SearchOutputRepo extends Base
{
    /**
     * @throws Exception
     */
    public function findByApplicant(string $applicantId): ?SearchOutput
    {
        throw new Exception("METHOD_NOT_IMPLEMENTED");
    }
}
