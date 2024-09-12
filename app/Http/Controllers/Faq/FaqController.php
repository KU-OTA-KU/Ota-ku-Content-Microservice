<?php

namespace App\Http\Controllers\Faq;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache; // Импорт фасада Cache
use App\Models\FaqTranslation;

class FaqController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/faq/get/all",
     *     summary="Get all FAQs for the specified locale",
     *     description="This endpoint retrieves all FAQs based on the 'Accept-Language' header.",
     *     tags={"FAQ"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved FAQs",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="locale", type="string", example="en"),
     *                     @OA\Property(property="title", type="string", example="How to use the app?"),
     *                     @OA\Property(property="description", type="string", example="This is a description of how to use the app.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="FAQs not found for the specified locale",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="failed"),
     *             @OA\Property(property="message", type="string", example="FAQS not found")
     *         )
     *     )
     * )
     */
    public function getAll(Request $request): JsonResponse
    {
        $locale = $request->header('Accept-Language', 'en');
        $cacheKey = "faq_all_{$locale}";
        $cacheTTL = now()->addMonth();

        $faqTranslations = Cache::get($cacheKey);

        if (!$faqTranslations) {
            $faqTranslations = FaqTranslation::where('locale', $locale)->get()->toArray();

            if (!empty($faqTranslations)) {
                Cache::put($cacheKey, $faqTranslations, $cacheTTL);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'FAQS not found'], 404);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => $faqTranslations
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/faq/get/{id}",
     *     summary="Get FAQ by ID and locale",
     *     description="This endpoint retrieves a specific FAQ based on the ID and 'Accept-Language' header.",
     *     tags={"FAQ"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the FAQ to retrieve",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved FAQ",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="faq",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="locale", type="string", example="en"),
     *                 @OA\Property(property="title", type="string", example="How to use the app?"),
     *                 @OA\Property(property="description", type="string", example="This is a description of how to use the app.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="FAQ not found for the specified locale and ID",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="failed"),
     *             @OA\Property(property="message", type="string", example="FAQ not found for the specified locale and id")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="id", type="array",
     *                     @OA\Items(type="string", example="The id field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getById(Request $request, $id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $locale = $request->header('Accept-Language', 'en');
        $cacheKey = "faq_{$id}_{$locale}";
        $cacheTTL = now()->addMonth();

        $faqTranslation = Cache::get($cacheKey);

        if (!$faqTranslation) {
            $faqTranslation = FaqTranslation::where('faq_id', $id)->where('locale', $locale)->first();

            if (!empty($faqTranslation)) {
                Cache::put($cacheKey, $faqTranslation, $cacheTTL);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'FAQS not found'], 404);
            }
        }

        return response()->json([
            'status' => 'success',
            'response' => $faqTranslation
        ], 200);
    }
}
