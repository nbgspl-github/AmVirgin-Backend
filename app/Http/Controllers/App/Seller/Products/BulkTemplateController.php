<?php


namespace App\Http\Controllers\App\Seller\Products;


use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Brand;
use App\Models\Category;
use App\Traits\ValidatesRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BulkTemplateController extends ExtendedResourceController
{
    use ValidatesRequest;

    protected array $rules;

    public function __construct()
    {
        parent::__construct();
        $this->rules = [
            'show' => [
                'categoryId' => 'bail|required|exists:categories,id',
                'brandId' => 'bail|required|exists:brands,id'
            ]
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
            $spreadsheet = new Spreadsheet();
            $worksheetA = new Worksheet($spreadsheet, 'First');
            $worksheetA->setCodeName('First');
            $worksheetA->setCellValue('A1', 'First Sheet Cell Value');
            $worksheetB = new Worksheet($spreadsheet, 'Second');
            $worksheetB->setCellValue('B1', 'Second Sheet Cell Value');
            $worksheetA->setCodeName('Second');
            $worksheetC = new Worksheet($spreadsheet, 'Third');
            $worksheetC->setCellValue('C1', 'Third Sheet Cell Value');
            $worksheetC->setCodeName('Third');
            $worksheetD = new Worksheet($spreadsheet, 'Fourth');
            $worksheetD->setCellValue('D1', 'Fourth Sheet Cell Value');
            $worksheetD->setCodeName('Fourth');
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


    protected function guard()
    {
        return auth(self::SellerAPI);
    }
}