<?php

namespace App\Http\Controllers\App\Seller\Rating;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\ReviewRating;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Throwable;

class RatingController extends ExtendedResourceController
{
    protected array $rules;

    public function __construct()
    {
        parent::__construct();
        $this->rules = [
            'show' => [
                'chunk' => ['bail', 'nullable', 'numeric', 'min:1', 'max:1000'],
                'page' => ['bail', 'nullable', 'numeric', 'min:1'],
            ]
        ];
    }

    public function show(): JsonResponse
    {
        $response = responseApp();

        $per_page = request()->get('per_page') ?? '';
        $page_no = request()->get('page') ?? '1';
        if (empty($per_page)) {
            $per_page = 10;
        }
        try {
            // $ratingC = SellerOrder::startQuery()->withRelations('order')->withRelations('sellerBank:id,accountHolderName,accountHolderName,bankName,branch,ifsc')->useAuth();
            $today = Carbon::today();
            $ratingC = ReviewRating::with('customer')->where('sellerId', auth('seller-api')->id());
            if (!empty(request()->get('status'))) {
                $ratingC->where('status', request()->get('status'));
            }
            if (!empty(request()->get('query'))) {
                $keywords = request()->get('query');
                $ratingC->where('orderNumber', 'LIKE', "%{$keywords}%");
                $ratingC->orWhere('commentMsg', 'LIKE', "%{$keywords}%");
                $ratingC->orWhere('rate', 'LIKE', "%{$keywords}%");
            }
            if (!empty(request('days'))) {
                $ratingC->where('created_at', '>=', $today->subDays(request('days')));
            }
            if (!empty(request()->get('from')) && !empty(request()->get('to'))) {
                $from = request()->get('from');
                $toDate = request()->get('to');
                $ratingC->whereBetween('created_at', [$from, $toDate]);
            }
            $orderCollection = $ratingC->get();


            // $total = count($orderCollection);
            // $totalRec = $orderCollection->total();
            $meta = [
                // 'pagination' => [
                // 	'pages' => countRequiredPages($totalRec, $per_page),
                // 	'current_page' => $page_no,
                // 	'items' => ['total' => $total, 'totalRec' => $totalRec, 'chunk' => $per_page],
                // ],
                'avg' => $orderCollection->avg('rate'),
            ];
            $response->status(HttpOkay)->message('Listing all rating and performance stats.')->setValue('meta', $meta)->setValue('data', $orderCollection);
        } catch (Throwable $exception) {
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
