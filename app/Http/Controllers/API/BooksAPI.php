<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksAPI extends Controller
{
    public function availableBooks(Request $request) {
        return Book::doesntHave('reservation')->paginate(10);
    }
}
