<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // app/Http/Controllers/ProductController.php
    public function create()
    {
        $this->authorize('create', Product::class);
        // Code for creating product
    }

}
