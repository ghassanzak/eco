<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        // $this->middleware('auth');
    }
    public function index() {
        $user = auth('api')->user();
        return Category::all();
    }
}
