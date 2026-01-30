<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocaleRequest;
use App\Http\Requests\UpdateLocaleRequest;
use App\Models\Locale;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class LocaleController extends Controller
{
    /**
     * @OA\PathItem(
     *     path="/locales",
     *     @OA\Get(
     *         summary="List all locales",
     *         tags={"Locales"},
     *         security={{"bearerAuth":{}}},
     *         @OA\Response(
     *             response=200,
     *             description="List of locales",
     *             @OA\JsonContent(
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="code", type="string", example="en"),
     *                     @OA\Property(property="name", type="string", example="English")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Locale::orderBy('code')->get());
    }

    /**
     * @OA\PathItem(
     *     path="/locales",
     *     @OA\Post(
     *         summary="Create a new locale",
     *         tags={"Locales"},
     *         security={{"bearerAuth":{}}},
     *         @OA\RequestBody(
     *             required=true,
     *             description="Locale data",
     *             @OA\JsonContent(
     *                 required={"code", "name"},
     *                 @OA\Property(property="code", type="string", example="fr"),
     *                 @OA\Property(property="name", type="string", example="French")
     *             )
     *         ),
     *         @OA\Response(
     *             response=201,
     *             description="Locale created successfully",
     *             @OA\JsonContent(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="code", type="string"),
     *                 @OA\Property(property="name", type="string")
     *             )
     *         ),
     *         @OA\Response(response=422, description="Validation error")
     *     )
     * )
     */
    public function store(StoreLocaleRequest $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:10|unique:locales,code',
            'name' => 'required|string|max:50',
        ]);

        return response()->json(Locale::create($data), 201);
    }

    /**
     * @OA\PathItem(
     *     path="/locales/{id}",
     *     @OA\Get(
     *         summary="Get locale by ID",
     *         tags={"Locales"},
     *         security={{"bearerAuth":{}}},
     *         @OA\Parameter(
     *             name="id",
     *             in="path",
     *             description="Locale ID",
     *             required=true,
     *             @OA\Schema(type="integer")
     *         ),
     *         @OA\Response(
     *             response=200,
     *             description="Locale details",
     *             @OA\JsonContent(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="code", type="string"),
     *                 @OA\Property(property="name", type="string")
     *             )
     *         ),
     *         @OA\Response(response=404, description="Locale not found")
     *     )
     * )
     */
    public function show(Locale $locale)
    {
        try {
            return response()->json($locale);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Locale not found'], 404);
        }
    }

    /**
     * @OA\PathItem(
     *     path="/locales/{id}",
     *     @OA\Put(
     *         summary="Update a locale",
     *         tags={"Locales"},
     *         security={{"bearerAuth":{}}},
     *         @OA\Parameter(
     *             name="id",
     *             in="path",
     *             description="Locale ID",
     *             required=true,
     *             @OA\Schema(type="integer")
     *         ),
     *         @OA\RequestBody(
     *             required=true,
     *             description="Locale data",
     *             @OA\JsonContent(
     *                 required={"code", "name"},
     *                 @OA\Property(property="code", type="string", example="fr"),
     *                 @OA\Property(property="name", type="string", example="French")
     *             )
     *         ),
     *         @OA\Response(
     *             response=200,
     *             description="Locale updated successfully",
     *             @OA\JsonContent(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="code", type="string"),
     *                 @OA\Property(property="name", type="string")
     *             )
     *         ),
     *         @OA\Response(response=404, description="Locale not found"),
     *         @OA\Response(response=422, description="Validation error")
     *     )
     * )
     */
    public function update(StoreLocaleRequest $request, Locale $locale)
    {
        try {
            $locale->update($request->validated());
            return response()->json($locale);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Locale not found'], 404);
        }
    }

    /**
     * @OA\PathItem(
     *     path="/locales/{id}",
     *     @OA\Delete(
     *         summary="Delete a locale",
     *         tags={"Locales"},
     *         security={{"bearerAuth":{}}},
     *         @OA\Parameter(
     *             name="id",
     *             in="path",
     *             description="Locale ID",
     *             required=true,
     *             @OA\Schema(type="integer")
     *         ),
     *         @OA\Response(response=204, description="Locale deleted successfully"),
     *         @OA\Response(response=404, description="Locale not found")
     *     )
     * )
     */
    public function destroy(Locale $locale)
    {
        try {
            $locale->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Locale not found'], 404);
        }
    }
}
