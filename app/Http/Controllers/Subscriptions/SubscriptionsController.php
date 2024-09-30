<?php

namespace App\Http\Controllers\Subscriptions;

use App\Models\SubscriptionsTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SubscriptionsController extends Controller
{
    public function getAll(Request $request): JsonResponse
    {
        $locale = $request->header('Accept-Language', 'en');
        $cacheKey = "subscriptions_all_{$locale}";
        $cacheTTL = now()->addMonth();

        $subscriptionTranslations = Cache::get($cacheKey);

        if (!$subscriptionTranslations) {
            $subscriptionTranslations = SubscriptionsTranslation::where('locale', $locale)->get()->toArray();

            if (!empty($subscriptionTranslations)) {
                Cache::put($cacheKey, $subscriptionTranslations, $cacheTTL);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Subscriptions not found'], 404);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => $subscriptionTranslations
        ], 200);
    }

    public function getById(Request $request, $id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $locale = $request->header('Accept-Language', 'en');
        $cacheKey = "subscriptions_{$id}_{$locale}";
        $cacheTTL = now()->addMonth();

        $subscriptionTranslation = Cache::get($cacheKey);

        if (!$subscriptionTranslation) {
            $subscriptionTranslation = SubscriptionsTranslation::where('subscriptions_id', $id)->where('locale', $locale)->first();

            if (!empty($subscriptionTranslation)) {
                Cache::put($cacheKey, $subscriptionTranslation, $cacheTTL);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Subscription not found'], 404);
            }
        }

        return response()->json([
            'status' => 'success',
            'response' => $subscriptionTranslation
        ], 200);
    }
}
