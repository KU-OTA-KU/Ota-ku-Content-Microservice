<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasIpAddresses
{
    /**
     * The IP addresses associated with the user.
     *
     * @return BelongsToMany
     */
    public function ipAddresses(): BelongsToMany
    {
        return $this->belongsToMany(IpAddress::class, 'user_ip')->withTimestamps();
    }
}
