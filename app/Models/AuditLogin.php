<?php

namespace App\Models;

use App\Abstracts\AbstractModel;

class AuditLogin extends AbstractModel
{   

    const TYPE_LOGIN = 1;
    const TYPE_LOGOUT = 2;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
