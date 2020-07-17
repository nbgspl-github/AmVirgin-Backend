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
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Hello World !');
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