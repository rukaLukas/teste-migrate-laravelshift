<?php

declare(strict_types=1);

namespace App\Specifications;

use Maartenpaauw\Specifications\Specification;

/**
 * @implements Specification<User>
 */
class UniqueCpfSpecification implements Specification
{
    protected $id;
    
    public function __construct(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * isSatisfiedBy function
     *
     * @param mixed $candidate
     * @return boolean
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate
            ->where('cpf', $candidate->cpf)
            ->where('id', '!=', $this->id)
            ->first() == null;
    }
}
