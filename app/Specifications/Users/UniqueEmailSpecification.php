<?php

declare(strict_types=1);

namespace App\Specifications\Users;

use App\Models\User;
use Maartenpaauw\Specifications\Specification;

/**
 * @implements Specification<User>
 */
class UniqueEmailSpecification implements Specification
{
    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate
            ->where('email', $candidate->email)
            ->where('id', '!=', $candidate->id)
            ->first() == null;
    }
}
