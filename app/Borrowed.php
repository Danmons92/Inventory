<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Borrowed extends Model
{
    protected $table = 'borrowed';

          /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'item_id');
    }

          /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function persons()
    {
        return $this->belongsTo(Persons::class, 'person_id');
    }
}
