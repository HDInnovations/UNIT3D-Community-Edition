<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Type;
class DiscountRule extends Model
{
    //
	protected $table = 'discount_rules';

	public function category(){
		$this->belongsTo(Type::class);
	}
}
