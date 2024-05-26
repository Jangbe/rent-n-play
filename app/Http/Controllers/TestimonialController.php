<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('user')->distinct('transaction_id')->orderBy('rating', 'desc')->limit(5)->get();
        return response()->json($testimonials);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'transaction_id' => 'required',
            'rating' => 'required',
            'comment' => 'required',
        ]);
        $validate['user_id'] = auth()->id();
        $testimonial = Testimonial::create($validate);
        return response()->json(['message' => 'Terimakasih telah memberikan testimoni', 'testimonial' => $testimonial]);
    }
}
