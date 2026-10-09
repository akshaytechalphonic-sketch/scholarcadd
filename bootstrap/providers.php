<?php



return [
    App\Providers\AppServiceProvider::class,
    Modules\Loginer\Providers\LoginerServiceProvider::class,
    Modules\DynamicMaster\Providers\DynamicMasterProvider::class,
    Modules\NumberSequence\Providers\NumberSequenceProvider::class,
    Modules\Workflow\Providers\WorkflowProvider::class,
    Modules\Course\Providers\CourseServiceProvider::class,
    Modules\SystemControl\Providers\SystemControlServiceProvider::class,
    Modules\Website\Providers\WebsiteServiceProvider::class,
    Modules\Core\Providers\CoreServiceProvider::class,
    Modules\FAQS\Providers\FAQSServiceProvider::class,
    Modules\Enrolled\Providers\EnrolledServiceProvider::class,
    Modules\LandingPage\Providers\LandingPageServiceProvider::class,
    Modules\ExportExcel\Providers\ExportExcelServiceProvider::class,
    Modules\Payment\Providers\PaymentServiceProvider::class,
];
