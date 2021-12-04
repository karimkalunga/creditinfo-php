<?php

namespace Devocean\Creditinfo\domain\entities;

class SearchInputIndividual
{
    private ?string $first_name;
    private ?string $last_name;
    private ?string $full_name;
    private ?string $phone_number;
    private ?string $dob;
    private ?string $NIN;
    private ?string $voters_id;
    private ?string $TIN;
    private ?string $updated_at;
    private ?string $created_at;
    private ?int $id;

    public function __construct(
        string $first_name = '',
        string $last_name = '',
        string $full_name = '',
        string $phone_number = '',
        string $dob = '',
        string $NIN = '',
        string $voters_id = '',
        string $TIN = '',
        string $create_at = null,
        string $updated_at = null,
        int $id = null,
    )
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->full_name = $full_name;
        $this->phone_number = $phone_number;
        $this->dob = $dob;
        $this->NIN = $NIN;
        $this->voters_id = $voters_id;
        $this->TIN = $TIN;
        $this->created_at = $create_at;
        $this->updated_at = $updated_at;
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getDob(): ?string
    {
        return $this->dob;
    }

    /**
     * @return string|null
     */
    public function getNIN(): ?string
    {
        return $this->NIN;
    }

    /**
     * @return string|null
     */
    public function getTIN(): ?string
    {
        return $this->TIN;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    /**
     * @return string|null
     */
    public function getVotersId(): ?string
    {
        return $this->voters_id;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
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
}