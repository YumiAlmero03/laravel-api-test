<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Translation Management API",
 *         version="1.0.0",
 *         description="API for managing translations, locales, and tags"
 *     ),
 *     @OA\Server(
 *         url="/api",
 *         description="API Base URL"
 *     ),
 *     @OA\Components(
 *         @OA\SecurityScheme(
 *             securityScheme="bearerAuth",
 *             type="http",
 *             scheme="bearer",
 *             bearerFormat="JWT"
 *         )
 *     )
 * )
 */
class OpenApiSpec {}
