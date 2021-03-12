<?php

namespace App\Http\Modules\Admin\Controllers\Web;

use App\Http\Modules\Admin\Requests\Genre\StatusRequest;
use App\Http\Modules\Admin\Requests\Genre\StoreRequest;
use App\Http\Modules\Admin\Requests\Genre\UpdateRequest;
use App\Models\Video\Genre;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class GenreController extends WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function index (): Renderable
    {
        return view('admin.genres.index')->with('genres',
            Genre::query()->latest()->get()
        );
    }

    public function create (): Renderable
    {
        return view('admin.genres.create');
    }

    public function edit (Genre $genre): Renderable
    {
        return view('admin.genres.edit')->with('genre', $genre);
    }

    public function store (StoreRequest $request): RedirectResponse
    {
        Genre::query()->create($request->validated());
        return redirect()->route('admin.genres.index')->with('success', 'Genre created successfully.');
    }

    public function update (UpdateRequest $request, Genre $genre): RedirectResponse
    {
        $genre->update($request->validated());
        return redirect()->route('admin.genres.index')->with('success', 'Genre updated successfully.');
    }

    public function status (StatusRequest $request, Genre $genre): \Illuminate\Http\JsonResponse
    {
        $genre->update($request->validated());
        return responseApp()->prepare(
            [], \Illuminate\Http\Response::HTTP_OK, 'Status updated successfully.'
        );
    }

    /**
     * @param Genre $genre
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function delete (Genre $genre): \Illuminate\Http\JsonResponse
    {
        $genre->delete();
        return responseApp()->prepare(
            [], \Illuminate\Http\Response::HTTP_OK, 'Genre deleted successfully.'
        );
    }
}