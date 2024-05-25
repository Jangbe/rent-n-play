<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::with('transactions')->get());
        // return DataTables::of(User::query())->toJson();
    }
}
