<?php

namespace App\Http\Controllers\Web\Admin;

use App\Classes\Rule;
use App\Classes\Str;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Directories;
use App\Interfaces\Tables;
use App\Models\Category;
use App\Storage\SecuredDisk;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class CategoryController extends BaseController
{
    use ValidatesRequest;
    use FluentResponse;

    protected array $rules;

    public function __construct()
    {
        parent::__construct();
        $this->rules = [
            'store' => [
                'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
                'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
                'parentId' => ['bail', 'required', 'numeric', Rule::existsPrimary(Tables::Categories)],
                'listingStatus' => ['bail', 'required', Rule::in([Category::ListingStatus['Active'], Category::ListingStatus['Inactive']])],
                'type' => ['bail', 'required', Rule::in(Category::Types['Category'], Category::Types['SubCategory'], Category::Types['Vertical'])],
                'icon' => ['bail', 'nullable', 'image'],
                'order' => ['bail', 'required', Rule::minimum(0), Rule::maximum(255)],
                'summary' => ['bail', 'nullable', 'string']
            ],
            'update' => [
                'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
                'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
                'parentId' => ['bail', 'required', 'numeric', Rule::existsPrimary(Tables::Categories)],
                'listingStatus' => ['bail', 'required', Rule::in([Category::ListingStatus['Active'], Category::ListingStatus['Inactive']])],
                'type' => ['bail', 'required', Rule::in(Category::Types['Category'], Category::Types['SubCategory'], Category::Types['Vertical'])],
                'icon' => ['bail', 'nullable', 'image'],
                'order' => ['bail', 'required', Rule::minimum(0), Rule::maximum(255)],
                'summary' => ['bail', 'nullable', 'string']
            ],
        ];
    }

    public function index()
    {
        $categories = Category::startQuery()->isRoot(false)->orderByAscending('parentId')->get();
        return view('admin.categories.index')->with('categories', $categories);
    }

    public function create()
    {
        $roots = Category::startQuery()->isRoot()->get();
        $roots->transform(function (Category $root) {
            $category = $root->children()->get();
            $category = $category->transform(function (Category $category) {
                $subCategory = $category->children()->get();
                $subCategory = $subCategory->transform(function (Category $subCategory) {
                    $vertical = $subCategory->children()->get();
                    $vertical->transform(function (Category $vertical) {
                        return [
                            'key' => $vertical->id(),
                            'name' => $vertical->name(),
                            'type' => $vertical->type(),
                        ];
                    });
                    return [
                        'key' => $subCategory->id(),
                        'name' => $subCategory->name(),
                        'type' => $subCategory->type(),
                        'children' => [
                            'available' => $vertical->count() > 0,
                            'count' => $vertical->count(),
                            'items' => $vertical,
                        ],
                    ];
                });
                return [
                    'key' => $category->id(),
                    'name' => $category->name(),
                    'type' => $category->type(),
                    'children' => [
                        'available' => $subCategory->count() > 0,
                        'count' => $subCategory->count(),
                        'items' => $subCategory,
                    ],
                ];
            });
            return [
                'key' => $root->id(),
                'name' => $root->name(),
                'type' => $root->type(),
                'children' => [
                    'available' => $category->count() > 0,
                    'count' => $category->count(),
                    'items' => $category,
                ],
            ];
        });
        return view('admin.categories.create')->with('roots', $roots);
    }

    public function edit($id)
    {
        $category = Category::retrieve($id);
        $roots = Category::startQuery()->isRoot()->get();
        $roots->transform(function (Category $root) {
            $category = $root->children()->get();
            $category = $category->transform(function (Category $category) {
                $subCategory = $category->children()->get();
                $subCategory = $subCategory->transform(function (Category $subCategory) {
                    $vertical = $subCategory->children()->get();
                    $vertical->transform(function (Category $vertical) {
                        return [
                            'key' => $vertical->id(),
                            'name' => $vertical->name(),
                            'type' => $vertical->type(),
                        ];
                    });
                    return [
                        'key' => $subCategory->id(),
                        'name' => $subCategory->name(),
                        'type' => $subCategory->type(),
                        'children' => [
                            'available' => $vertical->count() > 0,
                            'count' => $vertical->count(),
                            'items' => $vertical,
                        ],
                    ];
                });
                return [
                    'key' => $category->id(),
                    'name' => $category->name(),
                    'type' => $category->type(),
                    'children' => [
                        'available' => $subCategory->count() > 0,
                        'count' => $subCategory->count(),
                        'items' => $subCategory,
                    ],
                ];
            });
            return [
                'key' => $root->id(),
                'name' => $root->name(),
                'type' => $root->type(),
                'children' => [
                    'available' => $category->count() > 0,
                    'count' => $category->count(),
                    'items' => $category,
                ],
            ];
        });
        if ($category != null) {
            return view('admin.categories.edit')->with('main', $category)->with('roots', $roots);
        } else {
            return responseWeb()->route('admin.categories.index')->error('Could not find a category with that key.')->send();
        }
    }

    public function store()
    {
        $response = responseWeb();
        try {
            $payload = $this->requestValid(request(), $this->rules['store']);
            $payload['icon'] = \request()->hasFile('icon') ? SecuredDisk::access()->putFile(Directories::Categories, \request()->file('icon')) : null;
            $payload['specials'] = [];
            $payload['summary'] = $this->processMarkup($payload['summary']);
            Category::query()->create($payload);
            $response->route('admin.categories.index')->success('Created category successfully.');
        } catch (ValidationException $exception) {
            $response->back()->data(\request()->all())->error($exception->getMessage());
        } catch (Throwable $exception) {
            $response->back()->data(\request()->all())->error($exception->getMessage());
        } finally {
            return $response->send();
        }
    }

    public function update($id = null)
    {
        $response = responseWeb();
        try {
            $category = Category::retrieveThrows($id);
            $payload = $this->requestValid(\request(), $this->rules['update']);
            if (\request()->hasFile('icon')) {
                $payload['icon'] = SecuredDisk::access()->putFile(Directories::Categories, \request()->file('icon'));
            }
            $payload['summary'] = $this->processMarkup($payload['summary']);
            $category->update($payload);
            $response->route('admin.categories.index')->success('Updated category successfully.');
        } catch (ModelNotFoundException $exception) {
            $response->route('admin.categories.index')->error($exception->getMessage());
        } catch (ValidationException $exception) {
            $response->back()->data(\request()->all())->error($exception->getMessage());
        } catch (Throwable $exception) {
            $response->back()->data(\request()->all())->error($exception->getMessage());
        } finally {
            return $response->send();
        }
    }

    public function delete($id)
    {
        $response = responseApp();
        try {
            $category = Category::retrieveThrows($id);
            $category->delete();
            $response->message('Category deleted successfully.')->status(HttpOkay)->send();
        } catch (ModelNotFoundException $exception) {
            $response->message($exception->getMessage())->status(HttpResourceNotFound)->send();
        } catch (Throwable $exception) {
            $response->message($exception->getMessage())->status(HttpServerError)->send();
        } finally {
            return $response->send();
        }
    }

    protected function processMarkup(?string $markup): ?string
    {
        libxml_use_internal_errors(true);
        $dom = new \domdocument();
        $dom->loadHtml($markup, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $count => $image) {
            $src = $image->getAttribute('src');
            if (preg_match('/data:image/', $src)) {
                preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
                $mimeType = $groups['mime'];
                $path = '/summary-images/' . Str::makeUuid() . '.' . $mimeType;
                SecuredDisk::access()->put($path, file_get_contents($src));
                $image->removeAttribute('src');
                $image->setAttribute('src', SecuredDisk::access()->url($path));
            }
        }
        return $dom->saveHTML();
    }
}