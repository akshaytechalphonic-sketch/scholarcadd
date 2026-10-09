<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class LeadAndFormDocs
{
    #[OA\Post(
        path: '/api/contact-store',
        operationId: 'storeContact',
        description: 'Submit user query or contact enquiry form',
        summary: 'Submit contact form enquiry',
        tags: ['Lead & Enquiry Forms'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'phone'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '+919876543210'),
                    new OA\Property(property: 'subject', type: 'string', example: 'Course Enquiry'),
                    new OA\Property(property: 'message', type: 'string', example: 'I want to learn more about the course.')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Enquiry submitted successfully')
        ]
    )]
    public function storeContact() {}

    #[OA\Get(
        path: '/api/list/contact-details',
        operationId: 'getContactDetails',
        description: 'Get company contact details (address, phone, email)',
        summary: 'Get company contact details',
        tags: ['Lead & Enquiry Forms'],
        responses: [
            new OA\Response(response: 200, description: 'Contact details retrieved')
        ]
    )]
    public function getContactDetails() {}

    #[OA\Post(
        path: '/api/consultation-store',
        operationId: 'storeConsultationEnquiry',
        description: 'Submit free consultation enquiry request',
        summary: 'Submit consultation enquiry',
        tags: ['Lead & Enquiry Forms'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'phone'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Jane Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane@example.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '+919876543210'),
                    new OA\Property(property: 'message', type: 'string', example: 'Looking for corporate training solutions.')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Consultation enquiry submitted successfully')
        ]
    )]
    public function storeConsultationEnquiry() {}

    #[OA\Post(
        path: '/api/consulting/list',
        operationId: 'getConsultingList',
        description: 'Get list of all consulting solutions & services',
        summary: 'Get consulting services list',
        tags: ['Lead & Enquiry Forms'],
        responses: [
            new OA\Response(response: 200, description: 'Consulting list retrieved')
        ]
    )]
    public function getConsultingList() {}

    #[OA\Post(
        path: '/api/consulting/view',
        operationId: 'viewConsultingPage',
        description: 'View details of consulting service by slug',
        summary: 'View consulting service page',
        tags: ['Lead & Enquiry Forms'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['slug'],
                properties: [
                    new OA\Property(property: 'slug', type: 'string', example: 'corporate-training')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Consulting page details')
        ]
    )]
    public function viewConsultingPage() {}

    #[OA\Post(
        path: '/api/appointment-store',
        operationId: 'storeScheduleAppointment',
        description: 'Book and schedule a 1-on-1 advisor appointment',
        summary: 'Book/Schedule an appointment',
        tags: ['Lead & Enquiry Forms'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'phone', 'appointment_date'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Alex Smith'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'alex@example.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '+919876543210'),
                    new OA\Property(property: 'appointment_date', type: 'string', format: 'date', example: '2026-10-15'),
                    new OA\Property(property: 'appointment_time', type: 'string', example: '14:00')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Appointment booked successfully')
        ]
    )]
    public function storeScheduleAppointment() {}

    #[OA\Post(
        path: '/api/learning-calendar-store',
        operationId: 'storeLearningCalendar',
        description: 'Submit request for full training and learning calendar schedule',
        summary: 'Submit learning calendar request',
        tags: ['Lead & Enquiry Forms'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'phone'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Sara Conner'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'sara@example.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '+919876543210')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Learning calendar request submitted')
        ]
    )]
    public function storeLearningCalendar() {}

    #[OA\Post(
        path: '/api/store-request-for-service',
        operationId: 'storeRequestForService',
        description: 'Submit specialized business enterprise service request',
        summary: 'Submit custom service request',
        tags: ['Lead & Enquiry Forms'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'phone', 'service_type'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Corporate Client'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'client@company.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '+919876543210'),
                    new OA\Property(property: 'service_type', type: 'string', example: 'Enterprise Upskilling')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Request submitted successfully')
        ]
    )]
    public function storeRequestForService() {}

    #[OA\Post(
        path: '/api/team-application-store',
        operationId: 'storeTeamApplication',
        description: 'Submit instructor or team member application',
        summary: 'Submit team or trainer job application',
        tags: ['Lead & Enquiry Forms'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'phone', 'position'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'David Miller'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'david@example.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '+919876543210'),
                    new OA\Property(property: 'position', type: 'string', example: 'Lead Agile Trainer')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Application submitted successfully')
        ]
    )]
    public function storeTeamApplication() {}
}
