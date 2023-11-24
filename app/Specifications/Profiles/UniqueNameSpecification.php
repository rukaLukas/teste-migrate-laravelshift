<?php

declare(strict_types=1);

namespace App\Specifications\Profiles;

use Maartenpaauw\Specifications\Specification;

/**
 * @implements Specification<Profile>
 */
class UniqueNameSpecification implements Specification
{
    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate->where('name', $candidate->name)->first() == null;
    }
}
