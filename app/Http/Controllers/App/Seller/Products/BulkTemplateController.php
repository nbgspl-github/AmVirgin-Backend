<?php


namespace App\Http\Controllers\App\Seller\Products;


use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Brand;
use App\Models\Category;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

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
//        $response = responseApp();
//        try {
//            /**
//             * @var Category $category
//             * @var Brand $brand
//             * @var Collection $attributes
//             */
//            $validated = $this->requestValid(request(), $this->rules['show']);
//            $category = Category::find($validated['categoryId']);
//            $brand = Brand::find($validated['brandId']);
//            $attributes = $category->attributes;
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Hello World !');
            $writer = new Xls($spreadsheet);
            response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, 'Template.xlsx', [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition', 'attachment;filename="ExportScan.xls"',
                'Cache-Control', 'max-age=0'
            ]);
//
//        } catch (ValidationException $exception) {
//            $response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
//        } catch (\Throwable $exception) {
//            $response->status(HttpServerError)->message($exception->getMessage());
//        } finally {
//            return $response->send();
//        }
    }

    protected function guard()
    {
        return auth(self::SellerAPI);
    }
}