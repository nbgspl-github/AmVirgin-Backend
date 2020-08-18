<?php


namespace App\Http\Controllers\App\Seller\Products;


use App\Classes\Arrays;
use App\Classes\Rule;
use App\Classes\Str;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Directories;
use App\Interfaces\Tables;
use App\Models\Attribute;
use App\Models\AttributeSetItem;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BulkProductController extends ExtendedResourceController
{
    use ValidatesRequest;

    protected array $rules;
    private array $keyColumns;
    private $regex = "/^([A-Z]+)(\d+)$/";

    public function __construct()
    {
        parent::__construct();
        $this->keyColumns = [
            [
                'key' => 'name',
                'cell' => 'A1',
                'title' => 'Name'
            ], [
                'key' => 'listingStatus',
                'cell' => 'B1',
                'title' => 'Listing Status'
            ], [
                'key' => 'idealFor',
                'cell' => 'C1',
                'title' => 'Ideal For (Optional)'
            ], [
                'key' => 'procurementSla',
                'cell' => 'D1',
                'title' => 'Procurement SLA'
            ], [
                'key' => 'originalPrice',
                'title' => 'MRP',
                'cell' => 'E1',
            ], [
                'key' => 'sellingPrice',
                'title' => 'Selling Price',
                'cell' => 'F1',
            ], [
                'key' => 'fulfillmentBy',
                'cell' => 'G1',
                'title' => 'Fulfillment By'
            ], [
                'key' => 'hsn',
                'cell' => 'H1',
                'title' => 'HSN'
            ], [
                'key' => 'stock',
                'cell' => 'I1',
                'title' => 'Stock'
            ], [
                'key' => 'lowStockThreshold',
                'cell' => 'J1',
                'title' => 'Low Stock Threshold (Optional)'
            ], [
                'key' => 'description',
                'cell' => 'K1',
                'title' => 'Description'
            ], [
                'key' => 'sku',
                'cell' => 'L1',
                'title' => 'SKU'
            ], [
                'key' => 'styleCode',
                'cell' => 'M1',
                'title' => 'Style Code'
            ], [
                'key' => 'localShippingCost',
                'cell' => 'N1',
                'title' => 'Local Shipping Cost'
            ], [
                'key' => 'zonalShippingCost',
                'cell' => 'O1',
                'title' => 'Zonal Shipping Cost'
            ], [
                'key' => 'internationalShippingCost',
                'cell' => 'P1',
                'title' => 'International Shipping Cost'
            ], [
                'key' => 'packageWeight',
                'cell' => 'Q1',
                'title' => 'Package Weight'
            ], [
                'key' => 'packageLength',
                'cell' => 'R1',
                'title' => 'Package Length'
            ], [
                'key' => 'packageBreadth',
                'cell' => 'S1',
                'title' => 'Package Breadth'
            ], [
                'key' => 'packageHeight',
                'cell' => 'T1',
                'title' => 'Package Height'
            ],
        ];
        $this->rules = [
            'store' => [
                'categoryId' => ['bail', 'required', 'exists:categories,id'],
                'brandId' => ['bail', 'required', 'exists:brands,id'],
                'catalog' => ['bail', 'required', 'mimes:xls,xlsx']
            ],
            'product' => [
                'name' => ['bail', 'required', 'string', Rule::minimum(1), Rule::maximum(500)],
                'listingStatus' => ['bail', 'required', 'string', Rule::in([Product::ListingStatus['Active'], Product::ListingStatus['Inactive']])],
                'originalPrice' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
                'sellingPrice' => ['bail', 'required', 'numeric', 'min:1', 'lte:originalPrice'],
                'fulfillmentBy' => ['bail', 'required', Rule::in([Product::FulfillmentBy['Seller'], Product::FulfillmentBy['SellerSmart']])],
                'hsn' => ['bail', 'required', Rule::existsPrimary(Tables::HsnCodes, 'hsnCode')],
                'stock' => ['bail', 'required', 'numeric', 'min:0', RuleMaxStock],
                'lowStockThreshold' => ['bail', 'nullable', 'numeric', 'min:0', 'lt:stock'],
                'sku' => ['bail', 'required', 'string', 'min:1', 'max:255', 'unique:products,sku'],
                'styleCode' => ['bail', 'required', 'string', 'min:1', 'max:255'],
                'idealFor' => ['bail', 'nullable', Rule::in(Arrays::values(Product::IdealFor))],
                'procurementSla' => ['bail', 'required', 'numeric', Rule::minimum(Product::ProcurementSLA['Minimum']), Rule::maximum(Product::ProcurementSLA['Maximum'])],
                'localShippingCost' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::ShippingCost['Local']['Minimum']), Rule::maximum(Product::ShippingCost['Local']['Maximum'])],
                'zonalShippingCost' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::ShippingCost['Zonal']['Minimum']), Rule::maximum(Product::ShippingCost['Zonal']['Maximum'])],
                'internationalShippingCost' => ['bail', 'required', 'numeric', Rule::minimum(Product::ShippingCost['International']['Minimum']), Rule::maximum(Product::ShippingCost['International']['Maximum'])],
                'packageWeight' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::Weight['Minimum']), Rule::maximum(Product::Weight['Maximum'])],
                'packageLength' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::Dimensions['Length']['Minimum']), Rule::maximum(Product::Dimensions['Length']['Maximum'])],
                'packageBreadth' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::Dimensions['Breadth']['Minimum']), Rule::maximum(Product::Dimensions['Breadth']['Maximum'])],
                'packageHeight' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::Dimensions['Height']['Minimum']), Rule::maximum(Product::Dimensions['Height']['Maximum'])],
            ],
        ];
    }

    public function store(): JsonResponse
    {
        $response = responseApp();
        try {
            $validated = $this->requestValid(request(), $this->rules['store']);
            $category = Category::find($validated['categoryId']);
            $reader = new Xls();
            $attributeSet = $category->attributeSet;
            $attributes = [];
            if ($attributeSet != null) {
                $attributeSetItems = $attributeSet->items;
                $attributeSetItems->each(function (AttributeSetItem $attributeSetItem) use (&$attributes) {
                    $attribute = $attributeSetItem->attribute;
                    $attributes[] = [
                        'code' => $attribute->code,
                        'key' => $attribute->getKey(),
                        'group' => $attributeSetItem->group
                    ];
                });
            }
            $main = $reader->load(request()->file('catalog')->getPathname());
            $spreadsheet = $main->getSheetByName(Category::find($validated['categoryId'])->name);
            if ($spreadsheet->getHighestDataRow() < 2) {
                $response->status(HttpInvalidRequestFormat)->message('Uploaded sheet does not contain any data.');
            } else {
                if ($this->validateHeaderRow($spreadsheet)) {
                    for ($rowIndex = 2; $rowIndex <= $spreadsheet->getHighestDataRow(); $rowIndex++) {
                        $data = $this->payloadFromRow($spreadsheet, $rowIndex, $attributes);
                        $payload = $data['payload'];
                        $payload = $this->validatePayload($payload);
                        $payload['lowStockThreshold'] = $payload['lowStockThreshold'] ?? 0;
                        $payload['sellerId'] = $this->userId();
                        $payload['categoryId'] = $validated['categoryId'];
                        $payload['brandId'] = $validated['brandId'];
                        $images = $data['images'];
                        /**
                         * @var Product $product
                         */
                        $product = Product::create($payload);
                        foreach ($data['values'] as $key => $value) {
                            $attribute = Attribute::query()->where('code', $key)->first();
                            if ($attribute != null) {
                                $product->attributes()->create([
                                    'attributeId' => $attribute->id(),
                                    'variantAttribute' => $attribute->useToCreateVariants(),
                                    'showInCatalogListing' => $attribute->showInCatalogListing(),
                                    'visibleToCustomers' => $attribute->visibleToCustomers(),
                                    'label' => $attribute->name(),
                                    'group' => $value[1],
                                    'value' => $value[0],
                                ]);
                            }
                        }
                        $primary = true;
                        foreach ($images as $image) {
                            $path = $this->exportAsImage($image);
                            if ($path != null) {
                                $product->images()->create([
                                    'path' => $path
                                ]);
                            }
                            if ($primary) {
                                $product->update([
                                    'primaryImage' => $path
                                ]);
                                $primary = false;
                            }
                        }
                    }
                } else {
                    $response->status(HttpInvalidRequestFormat)->message('Catalog format is invalid. Please upload a proper sheet!');
                }
            }
            $response->status(HttpOkay)->message('Catalog processed successfully! Products sent for approval.');
        } catch (ValidationException $exception) {
            $response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
        } catch (\Throwable $exception) {
            $response->status(HttpServerError)->message($exception->getMessage());
        } finally {
            return $response->send();
        }
    }

    protected function validateHeaderRow(Worksheet $worksheet): bool
    {
        for ($x = 0; $x < count($this->keyColumns); $x++) {
            if ($worksheet->getCell($this->keyColumns[$x]['cell'])->getValue() !== $this->keyColumns[$x]['title']) {
                return false;
            }
        }
        return true;
    }

    protected function payloadFromRow(Worksheet $worksheet, int $rowNumber, array $attributes): array
    {
        $character = 65;
        $payload = [];
        $x = 0;
        for (; $x < count($this->keyColumns); $x++) {
            $column = $this->keyColumns[$x];
            $payload[$column['key']] = $worksheet->getCell($this->cellIndex($character++, $rowNumber))->getValue();
        }
        $values = [];
        $images = [];
        for ($x = 0; $x < count($attributes); $x++) {
            $values[$attributes[$x]['code']] = [$worksheet->getCell($this->cellIndex($character++, $rowNumber))->getValue(), $attributes[$x]['group']];
        }
        $drawings = $worksheet->getDrawingCollection();
        foreach ($drawings as $drawing) {
            $matches = [];
            if (preg_match($this->regex, $drawing->getCoordinates(), $matches) == 1 && $matches[2] == $rowNumber) {
                $images[] = $drawing;
            }
        }
        return [
            'payload' => $payload,
            'values' => $values,
            'images' => $images
        ];
    }

    protected function exportAsImage(MemoryDrawing $drawing)
    {
        if ($drawing instanceof MemoryDrawing) {
            ob_start();
            call_user_func(
                $drawing->getRenderingFunction(),
                $drawing->getImageResource()
            );
            $imageContents = ob_get_contents();
            ob_end_clean();
            $extension = 'jpg';
            switch ($drawing->getMimeType()) {
                case MemoryDrawing::MIMETYPE_PNG :
                    $extension = 'png';
                    break;
                case MemoryDrawing::MIMETYPE_GIF:
                    $extension = 'gif';
                    break;
                case MemoryDrawing::MIMETYPE_JPEG :
                    $extension = 'jpg';
                    break;
            }
            $relative = Directories::ProductImage . '/' . sprintf('%s.%s', Str::makeUuid(), $extension);
            File::put(storage_path('app/public/' . $relative), $imageContents);
            return $relative;
        }
        return null;
    }

    /**
     * @param array $payload
     * @return array
     * @throws ValidationException
     */
    protected function validatePayload(array $payload): array
    {
        return $this->arrayValid($payload, $this->rules['product']);
    }

    protected function cellIndex(int $character, int $rowIndex)
    {
        return sprintf('%c%d', $character, $rowIndex);
    }

    protected function guard()
    {
        return auth(self::SellerAPI);
    }
}