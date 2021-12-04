<?php

namespace Devocean\Creditinfo\domain\entities;

class SearchInputCompany
{
    private string $registration_number;
    private string $company_name;
    private ?string $created_at;
    private ?string $updated_at;
    private string $TIN;
    private ?int $id;

    public function __construct(
        string $registration_number  = '',
        string $company_name = '',
        string $TIN = '',
        int $id = null,
        string $created_at = null,
        string $updated_at = null
    )
    {
        $this->registration_number = $registration_number;
        $this->company_name = $company_name;
        $this->TIN = $TIN;
        $this->id = $id;
        $this->updated_at = $updated_at;
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getRegistrationNumber(): string
    {
        return $this->registration_number;
    }

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    /**
     * @return string|null
     */
    public function getTIN(): ?string
    {
        return $this->TIN;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}