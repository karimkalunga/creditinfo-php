<?php

namespace Devocean\Creditinfo\domain\entities;

class SearchOutput
{
    private ?string $record;
    private ?string $applicant_id;
    private ?int $id;
    private ?string $updated_at;
    private ?string $created_at;

    public function __construct(
        string $record,
        string $applicant_id,
        int $id = null,
        string $updated_at = null,
        string $created_at = null
    ) {
        $this->id = $id;
        $this->applicant_id = $applicant_id;
        $this->record = $record;
        $this->updated_at = $updated_at;
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getRecord(): ?string
    {
        return $this->record;
    }

    /**
     * @return string|null
     */
    public function getApplicantId(): ?string
    {
        return $this->applicant_id;
    }
}
