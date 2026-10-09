<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class AuthDocs
{
    #[OA\Post(
        path: '/login-check',
        operationId: 'loginCheck',
        description: 'Authenticate user with email and password to receive access token and user info',
        summary: 'Admin and User Login',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@hotelres.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'admin@123')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful login response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Login successful'),
                        new OA\Property(property: 'token', type: 'string', example: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'),
                        new OA\Property(property: 'user', type: 'object')
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid credentials')
        ]
    )]
    public function loginCheck() {}

    #[OA\Post(
        path: '/api/send-otp',
        operationId: 'sendOtp',
        description: 'Generates a 6-digit OTP and emails it to the user',
        summary: 'Send Email OTP',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OTP sent successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'OTP sent successfully.')
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function sendOtp() {}

    #[OA\Post(
        path: '/api/verify-otp',
        operationId: 'verifyOtp',
        description: 'Verifies the OTP sent to user email address',
        summary: 'Verify Email OTP',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'otp'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'otp', type: 'string', example: '123456')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OTP verified successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'OTP verified successfully.')
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Invalid or expired OTP')
        ]
    )]
    public function verifyOtp() {}
}
