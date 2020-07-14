<?php

namespace App\Http\Controllers\App\Seller\Rating;

use App\Http\Controllers\BaseController;
// use App\Http\Controllers\Web\ExtendedResourceController;
use Illuminate\Http\Request;
use App\Models\ReviewRating;

class ReviewRatingController extends BaseController
{
    	public function getRatingList() : JsonResponse {
		$response = responseApp();

		$per_page = request()->get('per_page') ?? '';
		$page_no = request()->get('page') ?? '1';
		if (empty($per_page)) {
			$per_page = 10;
		}
		try {  
			// $ratingC = ReviewRating::startQuery()->withRelations('order')->useAuth(); 
			$ratingC = ReviewRating::with('customer')->where('sellerId', auth('seller-api')->id()); 
			if (!empty(request()->get('status'))) { 
				$ratingC->withWhere('status',request()->get('status'));
			}if (!empty(request()->get('query'))) { 
				$ratingC->search(request()->get('query'),'orderNumber');
			}if (!empty(request()->get('from')) && !empty(request()->get('to'))) {
				$from= request()->get('from');
				$toDate= request()->get('to');
				$ratingC->withWhereBetween('created_at',$from,$toDate);
			} 
			$ratingCollection = $ratingC->get();
			$total = count($ratingCollection);
			$totalRec = $ratingCollection->total(); 
			$meta = [
				// 'pagination' => [
				// 	'pages' => countRequiredPages($totalRec, $per_page),
				// 	'current_page' => $page_no,
				// 	'items' => ['total' => $total, 'totalRec' => $totalRec, 'chunk' => $per_page], 
				// ],
				'avg' => $ratingCollection->avg('star'),
			]; 
			// $resourceCollection = PaymentListResource::collection($ratingCollection);
			$response->status(HttpOkay)->message('Listing all Rating with avg rating for this seller.')->setValue('meta', $meta)->setValue('data', $ratingCollection); 
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}
}
