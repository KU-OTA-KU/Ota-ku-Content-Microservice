<?php

namespace App\Swagger;

class SwaggerAnnotations
{
    /**
     * @OA\Info(
     *     title="My API",
     *     version="1.0.0",
     *     description="This is a sample API",
     *     @OA\Contact(
     *         email="contact@example.com"
     *     ),
     *     @OA\License(
     *         name="Apache 2.0",
     *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *     )
     * )
     *
     * @OA\Server(
     *     url="http://api.example.com/v1",
     *     description="Main API Server"
     * )
     */
    public function Swagger()
    {
    }
}
