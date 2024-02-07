<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TicketCategory.
 *
 * @property int                             $id
 * @property string                          $name
 * @property int                             $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class TicketCategory extends Model
{
    use Auditable;
    use HasFactory;
}
