<?php

namespace Devocean\Creditinfo\infrastructure\db\repos\mysql;

use Devocean\Creditinfo\domain\repo\SearchOutputRepo;
use Devocean\Creditinfo\infrastructure\db\orm\eloquent\models\SearchOutputModel;
use Devocean\Creditinfo\domain\entities\SearchOutput;

class SearchOutputImpl extends SearchOutputRepo
{
    public function create(mixed $entity): SearchOutput
    {
        $result = SearchOutputModel::updateOrCreate(
            ['applicant_id' => $entity->getApplicantId()],
            ['record' => $entity->getRecord()]
        );
        return new SearchOutput(
            $result->record,
            $result->applicant_id,
            $result->id,
            $result->updated_at,
            $result->created_at
        );
    }

    public function findByApplicant(string $applicantId): ?SearchOutput
    {
        $result = SearchOutputModel::where('applicant_id', $applicantId)->first();
        if ($result) {
            return new SearchOutput(
                $result->record,
                $result->applicant_id,
                $result->id,
                $result->updated_at,
                $result->created_at
            );
        }
        return null;
    }
}