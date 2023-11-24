<?php

declare(strict_types=1);

namespace App\Specifications\Users;

use App\Helper\Number;
use App\Models\User;
use Maartenpaauw\Specifications\Specification;

/**
 * @implements Specification<User>
 */
class UniqueCPFSpecification implements Specification
{
    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate
                ->where('cpf', Number::getOnlyNumber($candidate->cpf))
                ->where('id', '!=', $candidate->id)
                ->first() == null;
    }
}
