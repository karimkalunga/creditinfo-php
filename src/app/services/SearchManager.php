<?php

namespace Devocean\Creditinfo\app\services;

use Exception;

abstract class SearchManager
{
    /**
     * @param array $searchInput
     * @param string $inputTag company | individual
     * @return mixed score | report data
     * @throws Exception
     */
    public function getReport(array $searchInput, string $inputTag): mixed
    {
        throw new Exception("METHOD_NOT_IMPLEMENTED");
    }

    /**
     * @throws Exception
     */
    public function getReportByApplicant(string $applicantId): array
    {
        throw new Exception("METHOD_NOT_IMPLEMENTED");
    }
}
