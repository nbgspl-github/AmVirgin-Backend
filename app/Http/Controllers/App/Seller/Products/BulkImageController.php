<?php


namespace App\Http\Controllers\App\Seller\Products;


use App\Classes\Arrays;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Directories;
use App\Models\Product;
use App\Models\ProductImage;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class BulkImageController extends ExtendedResourceController
{
    use ValidatesRequest;

    protected array $rules;

    protected string $regex = "/^([a-z0-9A-Z-]{10,20})_\d+$/";

    public function __construct()
    {
        parent::__construct();
        $this->rules = [
            'store' => [
                'images' => ['bail', 'required', 'min:1', 'max:128'],
                'images.*' => ['bail', 'image', 'max:5124']
            ]
        ];
    }

    public function store(): JsonResponse
    {
        $response = responseApp();
        try {
            $validated = $this->requestValid(request(), $this->rules['store']);
            $images = $validated['images'];
            $notMatched = Arrays::Empty;
            Collection::make($images)->each(function (UploadedFile $file) use (&$notMatched) {
                $matches = Arrays::Empty;
                if (preg_match($this->regex, pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), $matches) != 0) {
                    $sku = $matches[0];
                    $product = Product::query()->where('sku', $sku)->first();
                    if ($product != null) {
                        $product->images->associate(ProductImage::create([
                            'path' => SecuredDisk::access()->putFile(Directories::ProductImage, $file, 'public')
                        ]));
                    } else {
                        $notMatched[] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    }
                } else {
                    $notMatched[] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                }
            });
            $string = 'Images uploaded successfully.';
            if (count($notMatched) > 0) {
                $string .= ' Some images did not match the requested format.';
            }
            $response->status(HttpOkay)->message($string)->setValue('notMatched', $notMatched);
        } catch (ValidationException $exception) {
            $response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
        } catch (\Throwable $exception) {
            $response->status(HttpServerError)->message($exception->getMessage());
        } finally {
            return $response->send();
        }
    }

    protected function guard()
    {
        return auth(self::SellerAPI);
    }
}