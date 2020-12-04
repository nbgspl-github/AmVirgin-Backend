<?php


namespace App\Http\Controllers\App\Seller\Products;


use App\Classes\ColumnNavigator;
use App\Exceptions\ValidationException;
use App\Http\Controllers\AppController;
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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BulkTemplateController extends AppController
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
                'title' => 'Name'
            ], [
                'key' => 'listingStatus',
                'title' => 'Listing Status',
                'items' => [
                    'active', 'inactive'
                ]
            ], [
                'key' => 'idealFor',
                'title' => 'Ideal For (Optional)',
                'items' => [
                    'boys', 'girls', 'men', 'women'
                ]
            ], [
                'key' => 'procurementSla',
                'title' => 'Procurement SLA',
                'items' => [
                    0, 1, 2
                ]
            ], [
                'key' => 'originalPrice',
                'title' => 'MRP',
            ], [
                'key' => 'sellingPrice',
                'title' => 'Selling Price',
            ], [
                'key' => 'fulfillmentBy',
                'title' => 'Fulfillment By',
                'items' => [
                    'seller', 'seller-smart'
                ]
            ], [
                'key' => 'hsn',
                'title' => 'HSN'
            ], [
                'key' => 'stock',
                'title' => 'Stock'
            ], [
                'key' => 'lowStockThreshold',
                'title' => 'Low Stock Threshold (Optional)',
                'items' => [
                    10
                ]
            ], [
                'key' => 'description',
                'title' => 'Description'
            ], [
                'key' => 'sku',
                'title' => 'SKU'
            ], [
                'key' => 'styleCode',
                'title' => 'Style Code'
            ], [
                'key' => 'localShippingCost',
                'title' => 'Local Shipping Cost'
            ], [
                'key' => 'zonalShippingCost',
                'title' => 'Zonal Shipping Cost'
            ], [
                'key' => 'internationalShippingCost',
                'title' => 'International Shipping Cost'
            ], [
                'key' => 'packageWeight',
                'title' => 'Package Weight'
            ], [
                'key' => 'packageLength',
                'title' => 'Package Length'
            ], [
                'key' => 'packageBreadth',
                'title' => 'Package Breadth'
            ], [
                'key' => 'packageHeight',
                'title' => 'Package Height'
            ],
        ];
    }

    public function showBak()
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
            $spreadsheet = new Spreadsheet();
            libxml_use_internal_errors(true);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $html = $reader->loadFromString($category->summary_excel);
            $summary = $html->getSheet(0);
            $summary->setTitle('Summary Sheet');
            $spreadsheet->removeSheetByIndex(0);
            $spreadsheet->addSheet($summary, 0);
            $worksheetIndex = $spreadsheet->createSheet();
            $worksheetIndex->setTitle('Index');
            $worksheetMain = $spreadsheet->createSheet();
            $worksheetMain->setTitle($category->name);
            $worksheetImages = $spreadsheet->createSheet();
            $worksheetImages->setTitle('Image Guidelines');
            $attributeSet = $category->attributeSet;
            $navigatorMain = new ColumnNavigator();
            $count = 0;
            foreach ($this->keyColumns as $column) {
                $worksheetMain->setCellValue($navigatorMain->currentCell(), $column['title']);
                $worksheetMain->getCell($navigatorMain->currentCell())->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $navigatorMain->nextCell();
                $count++;
            }
            $navigator = new ColumnNavigator();
            for ($x = 1; $x <= $count; $x++) {
                $worksheetMain->getColumnDimension($navigator->currentColumn())->setAutoSize(true);
                $navigator->nextColumn();
            }
            $navigator = new ColumnNavigator();
            if ($attributeSet != null) {
                $attributeSetItems = $attributeSet->items;
                $attributeSetItems->each(function (AttributeSetItem $attributeSetItem) use (&$worksheetIndex, $category, $navigator, $navigatorMain, $worksheetMain) {
                    $navigator->moveToFirstRow();
                    $attribute = $attributeSetItem->attribute;
                    $richText = new RichText();
                    $payable = $richText->createTextRun($attribute->code);
                    $payable->getFont()->setUnderline(true);
                    $payable->getFont()->setColor(new Color(Color::COLOR_DARKBLUE));
                    $worksheetIndex->setCellValue($navigator->currentCell(), $richText);
                    $worksheetMain->setCellValue($navigatorMain->currentCell(), $richText);
                    $worksheetMain->getCell($navigatorMain->currentCell())->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $worksheetMain->getColumnDimension($navigatorMain->currentColumn())->setAutoSize(true);
                    $worksheetMain->getCell($navigatorMain->currentCell())->getHyperlink()->setUrl(sprintf("sheet://'%s'!%s", 'Index', $navigator->currentCell()));
                    $worksheetIndex->getCell($navigator->currentCell())->getHyperlink()->setUrl(sprintf("sheet://'%s'!%s", $category->name, $navigatorMain->currentCell()));
                    $worksheetIndex->getCell($navigator->currentCell())->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $worksheetIndex->getColumnDimension($navigator->currentColumn())->setAutoSize(true);
                    $navigator->advanceNextRow();
                    foreach ($attribute->values() as $value) {
                        $worksheetIndex->setCellValue($navigator->currentCell(), $value);
                        $worksheetIndex->getCell($navigator->currentCell())->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $navigator->advanceNextRow();
                    }
                    $navigator->nextColumn();
                    $navigatorMain->nextCell();
                });
            } else {
                $worksheetIndex->setCellValue('A1', 'No attributes found!');
            }
            for ($i = 1; $i <= 8; $i++) {
                if ($i == 1)
                    $worksheetMain->setCellValue($navigatorMain->currentCell(), 'Product Image (Front)');
                else
                    $worksheetMain->setCellValue($navigatorMain->currentCell(), 'Product Image (Extra)');
                $worksheetMain->getCell($navigatorMain->currentCell())->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $worksheetMain->getColumnDimension($navigatorMain->currentColumn())->setAutoSize(true);
                $navigatorMain->nextCell();
                $count++;
            }
            foreach ($this->keyColumns as $column) {
                if (isset($column['items'])) {
                    $navigator->moveToFirstRow();
                    $worksheetIndex->getCell($navigator->currentCell())->setValue($column['title']);
                    $worksheetIndex->getCell($navigator->currentCell())->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $worksheetIndex->getColumnDimension($navigator->currentColumn())->setAutoSize(true);
                    foreach ($column['items'] as $item) {
                        $navigator->advanceNextRow();
                        $worksheetIndex->getCell($navigator->currentCell())->setValue($item);
                        $worksheetIndex->getCell($navigator->currentCell())->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    $navigator->nextCell();
                }
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
        } catch (ValidationException $exception) {
            $response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
        } catch (\Throwable $exception) {
            $response->status(HttpServerError)->message($exception->getMessage());
        } finally {
            return $response->send();
        }
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
            if ($category->catalog != null) {
                $response = new BinaryFileResponse(storage_path('app/public/' . $category->getAttributes()['catalog']));
                $response->headers->set('Content-Type', 'application/vnd.ms-excel');
                $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s_%s_%s.xls"', $category->name, $brand->name, Carbon::now()->timestamp));
                $response->headers->set('Cache-Control', 'max-age=0');
                return $response;
            } else {
                $response->status(HttpOkay)->message('No catalog available for category!');
            }
        } catch (ValidationException $exception) {
            $response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
        } catch (\Throwable $exception) {
            $response->status(HttpServerError)->message($exception->getMessage());
        } finally {
            return $response->send();
        }
    }

    protected function addListToCell(Worksheet $worksheet, string $cell, array $items)
    {
        $validation = $worksheet->getCell($cell)->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Value is not in list.');
        $validation->setPromptTitle('Pick from list');
        $validation->setPrompt('Please pick a value from the drop-down list.');
        $validation->setFormula1(sprintf("\"%s\"", implode(',', $items)));
    }

    protected function addYesNoCell(Worksheet $worksheet, string $cell)
    {

    }

    protected function addActiveInactiveCell(Worksheet $worksheet, string $cell, array $items)
    {

    }


    protected function guard()
    {
        return auth(self::SellerAPI);
    }
}