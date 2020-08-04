<?php


namespace App\Http\Controllers\App\Seller\Products;


use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\AttributeSetItem;
use App\Models\Brand;
use App\Models\Category;
use App\Traits\ValidatesRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BulkTemplateController extends ExtendedResourceController
{
    use ValidatesRequest;

    protected array $rules;

    protected array $imageGuidelines;

    protected array $keyColumns;

    public function __construct()
    {
        parent::__construct();
        $this->rules = [
            'show' => [
                'categoryId' => 'bail|required|exists:categories,id',
                'brandId' => 'bail|required|exists:brands,id'
            ]
        ];
        $this->imageGuidelines = [
            [
                "header" => true,
                "text" => "Image criteria",
            ],
            [
                "header" => false,
                "text" => "All the images should have minimum image resolution of.",
            ],
            [
                "header" => false,
                "text" => "You must provide minimum 2 images for this category.",
            ],
            [
                "header" => false,
                "text" => "You can provide maximum 8 images for this category.",
            ],
            [
                "header" => true,
                "text" => "Image standards",
            ],
            [
                "header" => false,
                "text" => "Primary images should be in white background.",
            ],
            [
                "header" => false,
                "text" => "Images with grey background can be used for the white colored product.",
            ],
            [
                "header" => false,
                "text" => "Product should be displayed without packing.",
            ],
            [
                "header" => false,
                "text" => "Solo product image without any props.",
            ],
            [
                "header" => false,
                "text" => "Movie shots & scenes are not allowed. Celebrity face should not be morphed. However, if celebrity is brand ambassador then you can include celebrity images as image except primary image",
            ],
            [
                "header" => false,
                "text" => "The same model has to be used for all shots of a product in lifestyle image.",
            ],
            [
                "header" => false,
                "text" => "The product should be center aligned and cover a minimum of 90% of the frame.",
            ],
            [
                "header" => true,
                "text" => "Following Images will be rejected",
            ],
            [
                "header" => false,
                "text" => "Graphic/ Inverted/ Pixelated image are not accepted.",
            ],
            [
                "header" => false,
                "text" => "Images with text/Watermark are not acceptable in primary images.",
            ],
        ];
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
                'title' => 'Original Price',
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
    }

    public function show()
    {
        $response = responseApp();
        try {
            /**
             * @var Category $category
             * @var Brand $brand
             * @var Collection $attributes
             */
            $validated = $this->requestValid(request(), $this->rules['show']);
            $category = Category::find($validated['categoryId']);
            $brand = Brand::find($validated['brandId']);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            $images = $reader->load(public_path("static/Images.xls"));
            $spreadsheet = new Spreadsheet();
            $spreadsheet->removeSheetByIndex(0);
            $worksheetIndex = $spreadsheet->createSheet();
            $worksheetIndex->setTitle('Index');
            $worksheetMain = $spreadsheet->createSheet();
            $worksheetMain->setTitle($category->name);
            $worksheetImages = $spreadsheet->createSheet();
            $worksheetImages->setTitle('Image Guidelines');
            $attributeSet = $category->attributeSet;
            $index = 1;
            $character = 65;
            if ($attributeSet != null) {
                $attributeSetItems = $attributeSet->items;
                $attributeSetItems->each(function (AttributeSetItem $attributeSetItem) use (&$worksheetIndex, &$index, &$character, &$items, $category) {
                    $attribute = $attributeSetItem->attribute;
                    $richText = new RichText();
                    $payable = $richText->createTextRun($attribute->name);
                    $payable->getFont()->setUnderline(true);
                    $payable->getFont()->setColor(new Color(Color::COLOR_DARKBLUE));
                    $worksheetIndex->setCellValue(sprintf('%c%d', $character, $index), $richText);
                    $worksheetIndex->getCell(sprintf('%c%d', $character, $index))->getHyperlink()->setUrl(sprintf("sheet://' %s' !A1", $category->name));
                    $worksheetIndex->getCell(sprintf('%c%d', $character, $index))->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $worksheetIndex->getColumnDimension(sprintf('%c', $character))->setAutoSize(true);
                    $currentIndex = 2;
                    foreach ($attribute->values() as $value) {
                        $worksheetIndex->setCellValue(sprintf('%c%d', $character, $currentIndex), $value);
                        $worksheetIndex->getCell(sprintf('%c%d', $character, $currentIndex))->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $currentIndex++;
                    }
                    $character++;
                });
            } else {
                $worksheetIndex->setCellValue('A1', 'No attributes found!');
            }
            $index = 1;
            $character = 65;
            $worksheetImages->getColumnDimension('A')->setAutoSize(true);
            foreach ($this->imageGuidelines as $guideline) {
                $item = $guideline;
                $cell = sprintf('%c%d', $character, $index);
                if ($item['header'] == true) {
                    if ($index != 2)
                        $worksheetImages->setCellValue($cell, '');
                    $index++;
                    $cell = sprintf('%c%d', $character, $index);
                    $worksheetImages->setCellValue($cell, $item['text']);
                    $worksheetImages->getCell($cell)->getStyle()->getFont()->setBold(true)->setColor(new Color(Color::COLOR_BLACK));
                    $worksheetImages->getCell($cell)->getStyle()->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('E0E0E0');
                } else {
                    $worksheetImages->setCellValue($cell, $item['text']);
                }
                $index++;
            }
            $index = 1;
            $character = 65;
            foreach ($this->keyColumns as $column) {
                $worksheetMain->setCellValue($column['cell'], $column['title']);
                $worksheetMain->getCell($column['cell'])->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
            for ($x = $character; $x < 91; $x++) {
                $worksheetMain->getColumnDimension(sprintf('%c', $x))->setAutoSize(true);
            }
            $writer = new Xls($spreadsheet);
            $response = new StreamedResponse(
                function () use ($writer) {
                    $writer->save('php://output');
                }
            );
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s_%s_%s.xls"', $category->name, $brand->name, Carbon::now()->timestamp));
            $response->headers->set('Cache-Control', 'max-age=0');
            return $response;

        } catch
        (ValidationException $exception) {
            $response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
        } catch (\Throwable $exception) {
            dd($exception);
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