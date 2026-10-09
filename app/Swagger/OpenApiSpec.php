<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "ScholarAcad API & Admin Documentation",
    description: "Interactive Swagger API documentation for ScholarAcad Backend API and Admin Panel",
    contact: new OA\Contact(
        name: "ScholarAcad Team",
        email: "support@scholaracad.com"
    )
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: "ScholarAcad Primary Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "JWT Authorization header using the Bearer scheme. Example: 'Bearer {token}'"
)]
#[OA\Tag(name: "Authentication", description: "User and Admin Login, OTP Generation and Verification")]
#[OA\Tag(name: "Courses", description: "Courses Listing, Details, Categories and Plans")]
#[OA\Tag(name: "Masters & Locations", description: "Master Categories, Parent/Child Master Lists, Country, State and City lists")]
#[OA\Tag(name: "Promo Codes", description: "Promo code listing and validation")]
#[OA\Tag(name: "Dynamic Pages & CMS", description: "Pages, Dynamic CMS Sections, Footers and SEO State URLs")]
#[OA\Tag(name: "Blogs & Knowledge Center", description: "Blog lists, types, search and detailed articles")]
#[OA\Tag(name: "Lead & Enquiry Forms", description: "Contact submissions, Consultation enquiries, Appointments, Learning calendars and Team applications")]
#[OA\Tag(name: "Testimonials & FAQs", description: "Student/Client testimonials and Frequently Asked Questions")]
#[OA\Tag(name: "Enrollments & Payments", description: "Course enrollments, Razorpay & CCAvenue gateways, payment verification and invoice downloads")]
class OpenApiSpec
{
}
