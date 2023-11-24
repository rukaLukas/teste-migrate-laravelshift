<?php
declare(strict_types=1);

namespace App\Specifications\Alerts;

use Maartenpaauw\Specifications\Specification;

/**
 * @implements Specification<Alert>
 */
class UniqueAlertSpecification implements Specification
{
    protected $id;
    
    public function __construct(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        $id = is_null($candidate->id) ? null :  $this->id;
        return $candidate
            ->where('name', $candidate->name)
            ->where('mother_name', $candidate->mother_name)
            ->where('target_public_id', $candidate->target_public_id)
            ->where('id', '!=', $id)
            ->first() == null;
    }
}
