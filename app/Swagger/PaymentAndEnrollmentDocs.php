<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class PaymentAndEnrollmentDocs
{
    #[OA\Post(
        path: '/api/enrollments-store',
        operationId: 'storeEnrollment',
        description: 'Submit course enrollment and learner information before payment',
        summary: 'Store student course enrollment',
        tags: ['Enrollments & Payments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['course_id', 'name', 'email', 'phone'],
                properties: [
                    new OA\Property(property: 'course_id', type: 'integer', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Alice Brown'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'alice@example.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '+919876543210'),
                    new OA\Property(property: 'plan_id', type: 'integer', example: 1),
                    new OA\Property(property: 'schedule_id', type: 'integer', example: 1),
                    new OA\Property(property: 'amount', type: 'number', example: 499.00),
                    new OA\Property(property: 'currency', type: 'string', example: 'INR')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Enrollment created successfully')
        ]
    )]
    public function storeEnrollment() {}

    #[OA\Post(
        path: '/api/enrollments-update',
        operationId: 'updateEnrollmentPayment',
        description: 'Update and validate payment reference on an enrollment',
        summary: 'Validate and update enrollment payment status',
        tags: ['Enrollments & Payments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['payment_id', 'order_id'],
                properties: [
                    new OA\Property(property: 'payment_id', type: 'string', example: 'pay_N123456789'),
                    new OA\Property(property: 'order_id', type: 'string', example: 'order_N123456789')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Payment validated successfully')
        ]
    )]
    public function updateEnrollmentPayment() {}

    #[OA\Post(
        path: '/api/download',
        operationId: 'downloadInvoicePdf',
        description: 'Generate PDF receipt and return download response',
        summary: 'Generate and download enrollment Invoice PDF',
        tags: ['Enrollments & Payments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['enrollment_id'],
                properties: [
                    new OA\Property(property: 'enrollment_id', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'PDF generated successfully')
        ]
    )]
    public function downloadInvoicePdf() {}

    #[OA\Post(
        path: '/api/payment/create-order',
        operationId: 'createRazorpayOrder',
        description: 'Create Razorpay order id for frontend checkout checkout',
        summary: 'Create Razorpay payment order',
        tags: ['Enrollments & Payments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['currency', 'quantity'],
                properties: [
                    new OA\Property(property: 'currency', type: 'string', example: 'INR'),
                    new OA\Property(property: 'plain_id', description: 'Plan Mapping ID', type: 'integer', example: 1),
                    new OA\Property(property: 'quantity', type: 'integer', example: 1),
                    new OA\Property(property: 'promo_code', type: 'string', example: 'DISCOUNT10')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Razorpay order created with order_id and key',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'order_id', type: 'string', example: 'order_M4v2F9vL1'),
                        new OA\Property(property: 'payment_id', type: 'string', example: 'order_rcpt_1696078800'),
                        new OA\Property(property: 'amount', type: 'number', example: 49900),
                        new OA\Property(property: 'key', type: 'string', example: 'rzp_test_xxxx')
                    ]
                )
            )
        ]
    )]
    public function createRazorpayOrder() {}

    #[OA\Post(
        path: '/api/payment/verify-payment',
        operationId: 'verifyRazorpayPayment',
        description: 'Verify HMAC signature and finalize course registration',
        summary: 'Verify Razorpay payment signature and complete enrollment',
        tags: ['Enrollments & Payments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature'],
                properties: [
                    new OA\Property(property: 'razorpay_order_id', type: 'string', example: 'order_M4v2F9vL1'),
                    new OA\Property(property: 'razorpay_payment_id', type: 'string', example: 'pay_M4v3G0wM2'),
                    new OA\Property(property: 'razorpay_signature', type: 'string', example: 'e2b109c123...'),
                    new OA\Property(property: 'enrollment_id', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Payment verified and invoice generated')
        ]
    )]
    public function verifyRazorpayPayment() {}

    #[OA\Post(
        path: '/api/payment/initiate',
        operationId: 'initiatePayment',
        description: 'Initiate generic payment transaction',
        summary: 'Initiate payment transaction',
        tags: ['Enrollments & Payments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['amount', 'currency'],
                properties: [
                    new OA\Property(property: 'amount', type: 'number', example: 1000),
                    new OA\Property(property: 'currency', type: 'string', example: 'INR')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Payment transaction initiated')
        ]
    )]
    public function initiatePayment() {}

    #[OA\Get(
        path: '/api/payment/payment/status',
        operationId: 'getPaymentStatus',
        description: 'Check transaction payment status',
        summary: 'Check transaction payment status',
        tags: ['Enrollments & Payments'],
        parameters: [
            new OA\Parameter(
                name: 'transaction_id',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Payment status retrieved')
        ]
    )]
    public function getPaymentStatus() {}

    #[OA\Post(
        path: '/api/payment/ccavenue/pay',
        operationId: 'ccAvenuePay',
        description: 'Initiate CCAvenue payment gateway flow with encrypted form data',
        summary: 'Initiate CCAvenue payment gateway flow',
        tags: ['Enrollments & Payments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['amount', 'currency'],
                properties: [
                    new OA\Property(property: 'amount', type: 'number', example: 1000),
                    new OA\Property(property: 'currency', type: 'string', example: 'INR')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'CCAvenue encrypted parameters and redirect URL returned')
        ]
    )]
    public function ccAvenuePay() {}

    #[OA\Post(
        path: '/api/payment/ccavenue/callback',
        operationId: 'ccAvenueCallback',
        description: 'CCAvenue webhook/callback handler for instant server-to-server notifications',
        summary: 'CCAvenue webhook callback handler',
        tags: ['Enrollments & Payments'],
        responses: [
            new OA\Response(response: 200, description: 'Callback processed')
        ]
    )]
    public function ccAvenueCallback() {}

    #[OA\Post(
        path: '/api/payment/ccavenue/response',
        operationId: 'ccAvenueResponse',
        description: 'CCAvenue customer return response handler',
        summary: 'CCAvenue customer response page handler',
        tags: ['Enrollments & Payments'],
        responses: [
            new OA\Response(response: 200, description: 'Response decrypted and transaction completed')
        ]
    )]
    public function ccAvenueResponse() {}
}
