<?php

declare(strict_types=1);

namespace App\Specifications\Users;

use App\Models\User;
use Maartenpaauw\Specifications\Specification;

/**
 * @implements Specification<User>
 */
class SecurePasswordSpecification implements Specification
{
    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        $patternPasswd = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/";
        return (bool)preg_match($patternPasswd, $candidate->password);
    }
}
