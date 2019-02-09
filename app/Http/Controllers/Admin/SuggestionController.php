<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Suggestion;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::with("user") -> paginate();

        return view("admin.suggestion.index", compact("suggestions"));
    }

    public function show(Suggestion $suggestion) {
        return view("admin.suggestion.show", compact("suggestion"));
    }

    public function destroy(Suggestion $suggestion)
    {
        $suggestion -> delete();
        return redirect() -> route("admin.suggestion.index") ->with('success','Suggestion was deleted successfully');
    }
}
