<?php

namespace App\Http\Modules\Admin\Controllers\Web;

use App\Classes\ColumnNavigator;
use App\Http\Modules\Admin\Requests\Category\StoreRequest;
use App\Http\Modules\Admin\Requests\Category\UpdateRequest;
use App\Library\Enums\Categories\Types;
use App\Library\Http\WebResponse;
use App\Models\AttributeSetItem;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CategoryController extends WebController
{
	protected array $keyColumns;

	public function __construct ()
	{
		parent::__construct();
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
					0, 1, 2, 3, 4, 5, 6, 7
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

	public function index ()
	{
		return view('admin.categories.index')->with('categories',
			$this->paginateWithQuery(
				Category::startQuery()->isRoot(false)->orderByAscending('parent_id')->builder()
			)
		);
	}

	public function create ()
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
							'key' => $vertical->id,
							'name' => $vertical->name,
							'type' => $vertical->type,
						];
					});
					return [
						'key' => $subCategory->id,
						'name' => $subCategory->name,
						'type' => $subCategory->type,
						'children' => [
							'available' => $vertical->count() > 0,
							'count' => $vertical->count(),
							'items' => $vertical,
						],
					];
				});
				return [
					'key' => $category->id,
					'name' => $category->name,
					'type' => $category->type,
					'children' => [
						'available' => $subCategory->count() > 0,
						'count' => $subCategory->count(),
						'items' => $subCategory,
					],
				];
			});
			return [
				'key' => $root->id,
				'name' => $root->name,
				'type' => $root->type,
				'children' => [
					'available' => $category->count() > 0,
					'count' => $category->count(),
					'items' => $category,
				],
			];
		});
		return view('admin.categories.create')->with('roots', $roots);
	}

	public function edit (Category $category)
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
							'key' => $vertical->id,
							'name' => $vertical->name,
							'type' => $vertical->type,
						];
					});
					return [
						'key' => $subCategory->id,
						'name' => $subCategory->name,
						'type' => $subCategory->type,
						'children' => [
							'available' => $vertical->count() > 0,
							'count' => $vertical->count(),
							'items' => $vertical,
						],
					];
				});
				return [
					'key' => $category->id,
					'name' => $category->name,
					'type' => $category->type,
					'children' => [
						'available' => $subCategory->count() > 0,
						'count' => $subCategory->count(),
						'items' => $subCategory,
					],
				];
			});
			return [
				'key' => $root->id,
				'name' => $root->name,
				'type' => $root->type,
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

	public function store (StoreRequest $request) : \Illuminate\Http\RedirectResponse
	{
		Category::query()->create($request->validated());
		return redirect()->route('admin.categories.index')->with('success',
			'Category created successfully.'
		);
	}

	public function update (UpdateRequest $request, Category $category) : \Illuminate\Http\RedirectResponse
	{
		$category->update($request->validated());
		return redirect()->route('admin.categories.index')->with('success',
			'Category details updated successfully.'
		);
	}

	public function delete (Category $category) : \Illuminate\Http\JsonResponse
	{
		$category->descendants()->each->delete();
		$category->delete();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Category along with its descendants deleted successfully.'
		);
	}

	public function downloadTemplate ($id)
	{
		$response = responseWeb();
		try {
			/**
			 * @var Category $category
			 * @var Collection $attributes
			 */
			$category = Category::query()->whereKey($id)->where('type', Types::Vertical)->first();
			$spreadsheet = new Spreadsheet();
			$spreadsheet->removeSheetByIndex(0);
			$worksheetIndex = $spreadsheet->createSheet();
			$worksheetIndex->setTitle('Index');
			$worksheetMain = $spreadsheet->createSheet();
			$worksheetMain->setTitle($category->name);
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
			$writer = new Xls($spreadsheet);
			$response = new StreamedResponse(
				function () use ($writer) {
					$writer->save('php://output');
				}
			);
			$response->headers->set('Content-Type', 'application/vnd.ms-excel');
			$response->headers->set('Content-Disposition', sprintf('attachment;filename="%s_%s.xls"', $category->name, Carbon::now()->timestamp));
			$response->headers->set('Cache-Control', 'max-age=0');
		} catch (ModelNotFoundException $exception) {
			$response->error('Could not fin category for that key.');
		} catch (\Throwable $exception) {
			$response->error($exception->getMessage());
		} finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}
}