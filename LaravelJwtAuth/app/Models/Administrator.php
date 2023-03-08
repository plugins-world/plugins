<?php

namespace Plugins\LaravelJwtAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Administrator extends \Dcat\Admin\Models\Administrator implements \Tymon\JWTAuth\Contracts\JWTSubject
{
    use HasFactory;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        $data['apiable'] = get_class($this);

        if (function_exists('tenant')) {
            $data['tenant_id'] = tenant('id');
        }
        
        return $data;
    }

    public function getDetail()
    {
        return [
            'admin_user_id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
        ];
    }
}
