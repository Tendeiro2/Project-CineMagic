<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GenreFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class GenreController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Genre::class);
    }

    public function index(): View
    {
        return view('genres.index')
            ->with('genres', Genre::orderBy('name')->paginate(20)->withQueryString());
    }

    public function create(): View
    {
        $newgenre = new Genre();
        return view('genres.create')
            ->with('genre', $newgenre);
    }

    public function store(GenreFormRequest $request): RedirectResponse
    {

        $newgenre = Genre::create($request->validated());
        $url = route('genres.show', ['genre' => $newgenre]);
        $htmlMessage = "genre <a href='$url'><u>{$newgenre->name}</u></a> ({$newgenre->code}) has been created successfully!";
        return redirect()->route('genres.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function edit(Genre $genre): View{
        return view('genres.edit')->with('genre', $genre);
    }

    public function update(GenreFormRequest $request, Genre $genre): RedirectResponse
    {
        $genre->update($request->validated());
        $url = route('genres.show', ['genre' => $genre]);
        $htmlMessage = "genre <a href='$url'><u>{$genre->name}</u></a> ({$genre->code}) has been updated successfully!";
        return redirect()->route('genres.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(Genre $genre): RedirectResponse
    {

        $moviesCount = Movie::where('genre_code', $genre->code)->count();

        if ($moviesCount > 0) {
            $alertType = 'danger';
            $alertMsg = "Genre {$genre->name} ({$genre->code}) cannot be deleted because it has associated movies!";
            return redirect()->route('genres.index')
                ->with('alert-type', $alertType)
                ->with('alert-msg', $alertMsg);
        }

        $genre->delete();

        $alertType = 'success';
        $alertMsg = "Genre {$genre->name} ({$genre->code}) has been deleted successfully!";

        return redirect()->route('genres.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }
}
