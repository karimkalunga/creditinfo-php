<?php

namespace Devocean\Creditinfo\domain\repo;

use Exception;

abstract class Base
{
    /**
     * @throws Exception
     */
    public function create(mixed $entity): mixed
    {
        throw new Exception("METHOD_NOT_IMPLEMENTED");
    }

    /**
     * @throws Exception
     */
    public function findAll(): mixed
    {
        throw new Exception("METHOD_NOT_IMPLEMENTED");
    }

    /**
     * @throws Exception
     */
    public function find(mixed $filter): mixed
    {
        throw new Exception("METHOD_NOT_IMPLEMENTED");
    }
}