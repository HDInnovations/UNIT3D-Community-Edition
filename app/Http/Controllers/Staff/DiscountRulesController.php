<?php

namespace App\Http\Controllers\Staff;

use App\DiscountRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Type;
use Stripe\Discount;

class DiscountRulesController extends Controller
{
	public function create(){
		$discount = new DiscountRule;
		if(request()->freeleech_time && request()->freeleech_time_unit ){
			$discount->freeleech_time = request()->freeleech_time * request()->freeleech_time_unit;
		}

		if(request()->torrent_min_size && request()->torrent_min_size_unit){
			$discount->torrent_min_size = request()->torrent_min_size * request()->torrent_min_size_unit;
		}

		if(request()->torrent_max_size && request()->torrent_max_size_unit){
			$discount->torrent_max_size = request()->torrent_max_size * request()->torrent_max_size_unit;
		}

		if((request()->freeleech && request()->freeleech == 1) && request()->freeleech_time && request()->freeleech_time_unit){
			$discount->freeleech = request()->freeleech;
			$discount->freeleech_time = request()->freeleech_time * request()->freeleech_time_unit;
		}else{
			$discount->freeleech = 0;
		}

		if( request()->type ){
			$discount->category = request()->type;
		}

		if( request()->counted_traffic ){
			$discount->discount =  request()->counted_traffic;
		}

		$discount->save();
		return redirect(route('Staff.discounts'));
	}

	public function editPage( $id ){
		$discount = DiscountRule::findOrFail($id);
		$types = Type::all();
		return view(
			'Staff.discount.edit',
			[
				'discount' => $discount,
				'types' => $types,
			]
		);
	}

	public function modify( $id ){
		$discount = DiscountRule::findOrFail($id);
		if(request()->freeleech){
			$discount->freeleech = 1;
		}

		if(request()->freeleech_time && request()->freeleech_time_unit ){
			$discount->freeleech_time = request()->freeleech_time * request()->freeleech_time_unit;
		}

		if(request()->torrent_min_size && request()->torrent_min_size_unit){
			$discount->torrent_min_size = request()->torrent_min_size * request()->torrent_min_size_unit;
		}

		if(request()->torrent_max_size && request()->torrent_max_size_unit){
			$discount->torrent_max_size = request()->torrent_max_size * request()->torrent_max_size_unit;
		}

		if((request()->freeleech && request()->freeleech == 1) && request()->freeleech_time && request()->freeleech_time_unit){
			$discount->freeleech = 1;
			$discount->freeleech_time = request()->freeleech_time * request()->freeleech_time_unit;
		}else{
			$discount->freeleech = 0;
		}

		if( request()->type ){
			$discount->category = request()->type;
		}
		$discount->save();
	}

	public function delete( $id ){
		DiscountRule::findOrFail( $id )->delete();
		return redirect(route('Staff.discounts'));
	}

	public function settings(){
		$type = Type::all();
		$discounts = DiscountRule::all();
		return view('Staff.discount.index',[
			'types' => $type,
			'discounts' => $discounts
		]);
	}
}
