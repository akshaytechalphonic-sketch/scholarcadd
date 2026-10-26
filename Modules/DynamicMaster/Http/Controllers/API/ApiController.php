<?php
namespace Modules\DynamicMaster\Http\Controllers\API;

use App\Helpers\MailHelper;
use App\Mail\SendOtpMail;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use App\Mail\ContactAdminMail;
use App\Mail\ContactUserMail;
use Illuminate\Support\Facades\Mail;
use Log;
use Modules\Course\Models\AboutGeography;
use Modules\Course\Models\Alumni;
use Modules\Course\Models\Certificateimage;
use Modules\Course\Models\Certificatetext;
use Modules\Course\Models\CertificationEligibility;
use Modules\Course\Models\CourseBenefits;
use Modules\Course\Models\CourseCategory;
use Modules\Course\Models\CourseCertificationPath;
use Modules\Course\Models\CourseCurriculumFaq;
use Modules\Course\Models\CourseHeadingDescription;
use Modules\Course\Models\CourseImages;
use Modules\Course\Models\CourseLearningObjectives;
use Modules\Course\Models\CoursePDF;
use Modules\Course\Models\CourseSalary;
use Modules\Course\Models\CourseSchedule;
use Modules\Course\Models\CourseTrainer;
use Modules\Course\Models\CourseYouTube;
use Modules\Course\Models\DynamicCourseHeadline;
use Modules\Course\Models\DynamicCourseTuitionFee;
use Modules\Course\Models\State;
use Modules\DynamicMaster\Models\ConsultationEnquiry;
use Modules\DynamicMaster\Models\ContactSubmission;
use Modules\DynamicMaster\Models\Country;
use Modules\DynamicMaster\Models\EmailOtp;
use Modules\DynamicMaster\Models\LearningCalender;
use Modules\DynamicMaster\Models\PlanMapping;
use Modules\DynamicMaster\Models\RequestForService;
use Modules\DynamicMaster\Models\ScheduleAppointment;
use Modules\DynamicMaster\Models\TeamApplication;
use Modules\FAQS\Models\FaqsGeneral;
use Modules\Website\Models\Consulting;
use Modules\Website\Models\HiringCompanies;
use Str;
use Exception;
use Illuminate\Http\Request;
use Modules\Course\Models\Course;
use Modules\Website\Models\Pages;
use Modules\Website\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Modules\Website\Models\Testimonial;
use Modules\Course\Models\CourseCountry;
use Modules\Course\Models\CourseHeading;
use Illuminate\Support\Facades\Validator;
use Modules\Course\Models\CourseOverviews;
use Tymon\JWTAuth\Facades\JWTAuth as Auth;
use Modules\Course\Models\CourseHighlights;
use Modules\Course\Models\NewExamStructure;
use Modules\Website\Models\KnowledgeCenter;
use Modules\Course\Models\DynamicCoursePages;
use Modules\Course\Models\Course_Whatyoulearn;
use Modules\Course\Models\CourseWhyScholarAcad;
use Modules\DynamicMaster\Models\SectionDetail;
use Modules\Course\Models\DynamicCourseKeyfeatures;
use Modules\Course\Models\DynamicCourseSectionheading;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use function PHPUnit\Framework\returnArgument;
use Modules\DynamicMaster\Models\Feature;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Modules\DynamicMaster\Models\stateURL;
use Illuminate\Support\Collection;
use Modules\FAQS\Models\CourseFaqs;
// use Modules\Website\Models\Consulting;

class ApiController extends Controller
{

    public function blogType()
    {
        try {
            $user = Auth::user();
            $companyId = $user ? $user->company_id : 1;

            // Get blog types for the company
            $blogTypes = masterParentData('Knowledge Center Category', $companyId);

            if (empty($blogTypes)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No blog types found.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => $blogTypes
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching blog types.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get all blogs belonging to blog types of the logged-in user's company
     */
    public function allBlogs(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'blog_type_id' => 'required|integer',
                'page' => 'sometimes|integer|min:1',
                'per_page' => 'sometimes|integer|min:1|max:100'
            ]);

            // If validation fails, return error response
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $blogTypeId = $request->input('blog_type_id');
            $perPage = $request->input('per_page', 10); // Default 10 items per page

            // Fetch blogs with pagination
            $blogs = KnowledgeCenter::where('resource_category', $blogTypeId)
                ->where('status', 'Active')
                ->when($request->input('course_category_id'), function ($query) use ($request) {
                    $query->where('course_category_id', $request->course_category_id);
                })
                ->orderBy('created_at', 'desc')

                ->paginate($perPage);

            // Customize response to remove URLs
            $response = [
                'success' => true,
                'data' => $blogs->items(),
                'pagination' => [
                    'current_page' => $blogs->currentPage(),
                    'per_page' => $blogs->perPage(),
                    'total' => $blogs->total(),
                    'last_page' => $blogs->lastPage(),
                ]
            ];

            return response()->json($response, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching blogs.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function blogDetail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'blog_id' => 'required|string|exists:tbl_knowledge_center,url_title',
            ]);

            // If validation fails, return error response
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }


            $blogId = $request->input('blog_id');

            $blog = KnowledgeCenter::where('url_title', $blogId)->first();
            $blog->user_views = $blog->user_views + 1;
            $blog->save();

            if (!$blog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $blog
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching blog details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function allPages(Request $request)
    {
        try {

            // ✅ Step 2: Build query
            $pages = Pages::select(
                'id',
                'title',
                'slug',
                'meta_title',
                'meta_description',
                'status',
                'pages_img'
            )->where('status' , 'Active')->get();

            $pages->transform(function ($page) {
                $page->pages_img = $page->pages_img ? asset($page->pages_img) : null;
                return $page;
            });

            return response()->json([
                'success' => true,
                'message' => 'Pages fetched successfully',
                'data' => $pages
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No pages found.'
            ], 404);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching pages: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    public function pageDetail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|string|exists:tbl_pages,slug',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $page = Pages::select(
                'id',
                'title',
                'slug',
                'meta_title',
                'meta_description',
                'status',
                'pages_img',
                'content'
            )->where('slug', $request?->id)->first();

            $page->pages_img = $page->pages_img ? asset($page->pages_img) : null;

            return response()->json([
                'success' => true,
                'message' => 'Page details fetched successfully',
                'data' => $page
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.'
            ], 404);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching page details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    public function pageBySlug(Request $request, string $slug)
    {
        try {
            $page = Pages::select(
                'id',
                'title',
                'slug',
                'meta_title',
                'meta_description',
                'status',
                'pages_img',
                'content'
            )->where('slug', $slug)->where(function($q) {
                $q->where('status', 1)->orWhere('status', true)->orWhere('status', 'Active');
            })->first();

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found.'
                ], 404);
            }

            $page->pages_img = $page->pages_img ? asset($page->pages_img) : null;

            return response()->json([
                'success' => true,
                'message' => 'Page details fetched successfully',
                'data' => $page
            ], 200);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching page by slug: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    public function contactDetail()
    {
        try {
            $data = Contact::first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No contact details found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Contact details retrieved successfully.',
                'data' => $data,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving contact details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function recentBlogs(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate request
            $request->validate([
                'limit' => 'sometimes|integer|min:1|max:50' // Optional limit parameter
            ]);

            $limit = $request->input('limit', 5); // Default 5 recent posts

            // Fetch recent blogs
            $blogs = KnowledgeCenter::
                orderBy('created_at', 'desc')
                ->get()
                ->take($limit);

            // Customize response to remove URLs
            $response = [
                'success' => true,
                'data' => $blogs,
            ];

            return response()->json($response, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching recent blogs.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sectionList(Request $request)
    {
        try {

            $validated = $request->validate([
                'limit' => 'sometimes|integer|min:1|max:100',
            ]);

            $query = SectionDetail::select('id', 'section');

            if ($request->has('limit')) {
                $sections = $query->limit($validated['limit'])->get();
            } else {
                $sections = $query->get();
            }

            if ($sections->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No sections found.',
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sections retrieved successfully.',
                'data' => $sections,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching sections.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewSection(Request $request)
    {
        // dd($req  uest);
        try {
            $validated = $request->validate([
                'sections' => 'required|array|min:1',
                'sections.*' => 'string|max:255',
            ]);

            $sections = SectionDetail::whereIn('section', $validated['sections'])
                ->get();

            if ($sections->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching sections found.',
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sections retrieved successfully.',
                'data' => $sections,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching for sections.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function testimonialList(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'course_id' => 'nullable|string|exists:tbl_courses,url_title',
                'country_id' => 'nullable|string',
                'state_id' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors occurred.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $filterData = self::getFilterKeys($request->all());



            $data = Testimonial::query()
                ->when($filterData?->course_id, fn($q) => $q->where('course_id', $filterData->course_id))
                ->when($filterData?->country_id, fn($q) => $q->where('country_id', $filterData->country_id))
                ->when($filterData?->state_id, fn($q) => $q->where('state_id', $filterData->state_id))
                ->get();



            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching testimonials: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching testimonials.',
                'error' => $e->getMessage(), // optional: remove in production
            ], 500);
        }
    }

    public function allFAQ(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_id' => ' nullable|integer|exists:tbl_courses,id',
            ]);

            $data = (object) [
                'course_id' => $validated['course_id'],
                'country_id' => null,
                'state_id' => null,
            ];

            $faq = self::getFilterData(new FaqsGeneral(), $data);

            return response()->json([
                'status' => true,
                'data' => $faq,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('FAQ Fetch Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching FAQs.',
            ], 500);
        }

    }

    public function storeContact(Request $request)
    {
        try {

            $validated = $request->validate([
                'fullName' => 'required|string|max:255',
                'enquiryType' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'country_id' => 'nullable|string|max:255',
                'course_id' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'deliveryMode' => 'nullable|string|max:255',
                'contactMode' => 'nullable|string|max:255',
                'message' => 'nullable|string|max:5000',
                'job_title' => 'nullable|string|max:5000',
                'company_name' => 'nullable|string|max:5000',
                'group_size' => 'nullable|in:1-10,11-20,21-30,31-60,61-100,nolimit',
                'enquiry_for' => 'nullable|string|max:100',
                'agree' => 'required|string',
            ]);


            $contact = ContactSubmission::create($validated);

            try {
                // Mail::to('admin@example.com')->send(new ContactAdminMail($contact));
                Mail::to($contact->email)->send(new ContactUserMail($contact));
                MailHelper::sendSuccessMail(
                    $contact->email,
                    $contact->fullName,
                    $contact?->course?->course_title,
                    'Contact'


                );

            } catch (Exception $e) {
                \Illuminate\Support\Facades\Log::error("Mail Sending Failed: " . $e->getMessage());
            }

            return response()->json([
                'message' => 'Form submitted successfully.',
                'data' => $contact,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error("ContactSubmission Create Failed: " . $e->getMessage());

            $message = config('app.debug')
                ? $e->getMessage()
                : 'Failed to save your submission. Please try again.';

            return response()->json([
                'message' => $message,
            ], 500);
        }

    }

    public function storeLearningCalender(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'contact_number' => 'nullable|numeric', // Optional numeric validation
                'email' => 'nullable|email|max:255',
                'country' => 'nullable|string|max:255',
                'course' => 'nullable|exists:tbl_courses,id',
                'training_delivery_mode' => 'nullable|string|max:255',
                'enquiry_for' => 'nullable|in:individual,corporate',
                'preferred_contact_mode' => 'nullable|in:phone,email,both',
                'message' => 'nullable|string',
                'agree_terms' => 'required|boolean',
                'job_title' => 'nullable|string|max:5000',
                'company_name' => 'nullable|string|max:5000',
                'group_size' => 'nullable|in:1-10,11-20,21-30,31-60,61-100,nolimit',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $enquiry = LearningCalender::create($validator->validated());

            if ($enquiry) {
                MailHelper::sendSuccessMail(
                    $enquiry->email,
                    $enquiry->full_name,
                    $enquiry?->Coursedate?->course_title,
                    'Learning Calendar Enquiry'
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Enquiry submitted successfully!',
                'data' => $enquiry
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database error occurred',
                'error' => $e->getMessage()
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storeConsultationEnquiry(Request $request)
    {
        try {
            $rules = [
                'fullName' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'enquiryType' => 'nullable|string|max:255',
                'userType1' => 'nullable|in:Individual,Corporate',
                'message' => 'nullable|string',
                'job_title' => 'nullable|string|max:5000',
                'company_name' => 'nullable|string|max:5000',
                'group_size' => 'nullable|in:1-10,11-20,21-30,31-60,61-100,nolimit',
                'enquiry_for' => 'nullable|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            $enquiry = ConsultationEnquiry::create([
                'full_name' => $validated['fullName'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'enquiry_for' => $validated['enquiry_for'] ?? null,
                'user_type' => $validated['userType1'] ?? null,
                'message' => $validated['message'] ?? null,
                'job_title' => $validated['job_title'] ?? null,
                'group_size' => $validated['group_size'] ?? null
            ]);

            if ($enquiry) {
                MailHelper::sendSuccessMail(
                    $enquiry->email,
                    $enquiry->full_name,
                    null,
                    'Consultation Enquiry'
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Enquiry submitted successfully',
                'data' => $enquiry
            ], 200);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error storing consultation enquiry: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the enquiry. Please try again later.'
            ], 500);
        }
    }

    public function storeScheduleAppointment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'country_id' => 'nullable|integer|max:255',
                'course_id' => 'nullable|integer|max:255',
                'message' => 'nullable|string',
                'agreed' => 'required|boolean',
                'job_title' => 'nullable|string|max:5000',
                'company_name' => 'nullable|string|max:5000',
                'group_size' => 'nullable|in:1-10,11-20,21-30,31-60,61-100,nolimit',
                'enquiry_for' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $appointment = ScheduleAppointment::create($validator->validated());

            // dd($appointment?->course);

            if ($appointment) {

                MailHelper::sendSuccessMail(
                    $appointment->email,
                    $appointment->full_name,
                    $appointment?->course?->course_title,
                    'Schedule Appointment'


                );

            }

            return response()->json([
                'success' => true,
                'message' => 'Appointment scheduled successfully',
                'data' => $appointment
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storeTeamApplication(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|unique:tbl_team_applications,email',
                'phone' => 'nullable|string|max:20',
                'company_name' => 'nullable|string|max:255',
                'job_title' => 'nullable|string|max:255',
                'training_expertise' => 'nullable|string|max:255',
                'country_id' => 'nullable|exists:tbl_country,id',
                'course_id' => 'nullable|exists:tbl_courses,id',
                'training_delivery_mode' => 'nullable|string|max:255',
                'ready_to_travel' => 'nullable|in:Yes,No',
                'preferred_option' => 'nullable|in:Full Time,Part Time',
                'linkedin_profile' => 'nullable|url|max:255',
                'uploaded_files.*' => 'file|max:5120',

                'agree' => 'required|boolean',

                'group_size' => 'nullable|in:1-10,11-20,21-30,31-60,61-100,nolimit',
                'enquiry_for' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $validated = $validator->validated();

            // Handle file uploads
            $uploadedFiles = [];

            if ($request->hasFile('uploaded_files')) {
                foreach ($request->file('uploaded_files') as $file) {

                    $filename = time().'_'.$file->getClientOriginalName();
                    $destinationPath = public_path('teamApplication');

                    // create folder if not exists
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $file->move($destinationPath, $filename);

                    $uploadedFiles[] = $filename;
                }
            }

            $validated['uploaded_files'] = $uploadedFiles ? json_encode($uploadedFiles) : null;

            $application = TeamApplication::create($validated);




            if ($application) {
                MailHelper::sendSuccessMail(
                    $application->email,
                    $application->full_name,
                    $application?->Coursedate?->course_title,
                    'Team Application',
                    $application
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully',
                'data' => $application
            ], 201);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Team Application Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An internal error occurred. Please try again later.',
                'em' => $e->getMessage()
            ], 500);
        }
    }

    public function storeRequestForService(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'country_code' => 'nullable|string|max:10',
                'phone' => 'nullable|string|max:30',
                'message' => 'nullable|string',
                'service' => 'nullable|string|max:100',
                'accepted_terms' => 'required|boolean',
                'job_title' => 'nullable|string|max:5000',
                'company_name' => 'nullable|string|max:5000',
                'group_size' => 'nullable|in:1-10,11-20,21-30,31-60,61-100,nolimit',
                'enquiry_for' => 'nullable|string|max:100',
            ], [
                'accepted_terms.boolean' => 'The accepted terms field must be true or false i,e. [1 or 0]'
            ]);

            $data = RequestForService::create($validator->validated());

            if ($data) {
                MailHelper::sendSuccessMail(
                    $data->email,
                    $data->full_name,
                    null,
                    'Service Request'
                );
            }

            return response()->json([
                'status' => true,
                'message' => 'Request submitted successfully.',
                'data' => $data,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation exception.',
                'errors' => $e->getMessage(),
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function sendOtp(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $otp = rand(100000, 999999);

            EmailOtp::where('email', $request->email)->delete();

            EmailOtp::create([
                'email' => $request->email,
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]);

            Mail::to($request->email)->send(new SendOtpMail($otp));

            return response()->json(['message' => 'OTP sent successfully'], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error' => $e->getMessage(),
            ], 422);

        } catch (Swift_TransportException $e) {
            \Illuminate\Support\Facades\Log::error('Mail error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to send OTP email. Please try again later.'
            ], 500);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('OTP send error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Something went wrong while sending OTP.'
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required'
            ]);

            $record = EmailOtp::where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

            if (!$record) {
                return response()->json(['message' => 'Invalid OTP'], 400);
            }

            if (Carbon::now()->greaterThan($record->expires_at)) {
                return response()->json(['message' => 'OTP expired'], 400);
            }

            $record->delete();

            return response()->json(['message' => 'OTP verified successfully'], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->getMessage(),
            ], 422);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('OTP verify error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Something went wrong while verifying OTP.'
            ], 500);
        }
    }

    // public function coursePlans(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'course_id' => ' nullable|string|exists:tbl_courses,url_title',
    //         ]);

    //         $data = self::getFilterKeys($request?->all());



    //         $plans = self::getFilterData(new PlanMapping(), $data);

    //         // dd($plans);

    //         $country = CourseCountry::find($data?->country_id);
    //         $currency = $country?->currency;
    //         $currency_symbol = $country?->currency_symbol;


    //          $courseFeatures = Feature::where('course_id', $data['course_id'] ?? null)
    //         ->get()
    //         ->groupBy('plan_id')
    //         ->map(function ($features) {
    //             $included = $features->where('is_excluded', 0)->pluck('name');
    //             $excluded = $features->where('is_excluded', 1)->pluck('name');
    //             return [
    //                 'included' => $included->toArray(),
    //                 'excluded' => $excluded->toArray()
    //             ];
    //         });


    //         $plans = $plans->map(function ($item) use ($currency, $currency_symbol) {
    //             // Ensure $item is treated as an array for additional attributes
    //             $itemArray = $item->toArray();

    //             $itemArray['currency'] = $currency;
    //             $itemArray['currency_symbol'] = $currency_symbol;
    //             $itemArray['plan'] = $item?->plan?->name;
    //             $itemArray['isBasic'] = $item?->plan?->is_basic;
    //             $planId = $item->plan_id ?? $itemArray['plan_id'];
    //             $itemArray['included_features'] = $courseFeatures[$planId]['included'] ?? [];
    //             $itemArray['excluded_features'] = $courseFeatures[$planId]['excluded'] ?? [];

    //             $itemArray['id'] = $item?->id;

    //             return $itemArray;
    //         });



    //         return response()->json([
    //             'status' => true,
    //             'data' => $plans,
    //         ]);

    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $e->errors(),
    //         ], 422);

    //     } catch (Exception $e) {
    //         \Illuminate\Support\Facades\Log::error('Plan Fetch Error: ' . $e->getMessage(), [
    //             'trace' => $e->getTraceAsString(),
    //         ]);
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something went wrong while fetching plans.',
    //         ], 500);
    //     }

    // }


    public function coursePlans(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_id' => 'nullable|string|exists:tbl_courses,url_title',
            ]);

            $data = self::getFilterKeys($request->all());

            $plans = self::getFilterData(new PlanMapping(), $data);

            $country = CourseCountry::find($data->country_id ?? null);

            $currency = $country?->currency;
            $currency_symbol = $country?->currency_symbol;
            $multiplier = CourseCountry::whereKey($country?->id)->value('value_in_dollar') ?? 1;
            $courseFeatures = Feature::where('course_id', $data->course_id ?? null)
                ->get()
                ->groupBy('plan_id')
                ->map(function ($features) {
                    return [
                        'included' => $features->where('is_excluded', 0)->pluck('name')->toArray(),
                        'excluded' => $features->where('is_excluded', 1)->pluck('name')->toArray(),
                    ];
                });

            $plans = $plans->map(function ($item) use ($currency, $currency_symbol, $courseFeatures, $multiplier) {

                $itemArray = is_object($item) ? $item->toArray() : (array) $item;

                $planId = $item->plan_id;

                $itemArray['currency'] = $currency;
                $itemArray['currency_symbol'] = $currency_symbol;
                $itemArray['plan'] = $item?->plan?->name;
                $itemArray['isBasic'] = $item?->plan?->is_basic;
                $itemArray['course_fee'] *= (float) $multiplier;
                $planIncluded = $item?->plan?->included_features ?? [];
                $planIncluded = is_array($planIncluded)
                    ? $planIncluded
                    : json_decode($planIncluded, true) ?? [];

                $planExcluded = $item?->plan?->excluded_features ?? [];
                $planExcluded = is_array($planExcluded)
                    ? $planExcluded
                    : json_decode($planExcluded, true) ?? [];

                $courseIncluded = $courseFeatures[$planId]['included'] ?? [];
                $courseExcluded = $courseFeatures[$planId]['excluded'] ?? [];

                $itemArray['included_features'] = array_values(array_unique(
                    array_merge($planIncluded, $courseIncluded)
                ));


                $itemArray['excluded_features'] = array_values(array_unique(
                    array_merge($planExcluded, $courseExcluded)
                ));

                $itemArray['id'] = $item?->id;

                return $itemArray;
            });

            return response()->json([
                'status' => true,
                'data' => $plans,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Plan Fetch Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching plans.',
            ], 500);
        }
    }


    public function getCourseCategory($name)
    {
        $validator = Validator::make(['name' => $name], [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid category name provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = CourseCategory::where('page_link', $name)->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Course category not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function consultingList(Request $request)
    {
        try {
            $data = Consulting::query()
                ->with('consulting')
                ->get();

            $transformedData = $data->map(function ($item) {
                // Convert pointer array to object with numeric keys
                $pointerObject = [];
                foreach ($item->pointer as $index => $value) {
                    $pointerObject[(string) $index] = $value;
                }

                return [
                    'id' => $item->id,
                    'banner_Heading' => $item->banner_Heading,
                    'section_one_Heading' => $item->section_one_Heading,
                    'banner_content' => $item->banner_content,
                    'meta_title' => $item->meta_title,
  		    'slug' => $item->slug,
                    'meta_description' => $item->meta_description,
                    'status' => $item->status,
                    'pages_img' => $item->pages_img,
                    'company_id' => $item->company_id,
                    'section_one_content' => $item->section_one_content,
                    'section_two_Heading' => $item->section_two_Heading,
                    'chooseus_Heading' => $item->chooseus_Heading,
                    'choose_us_content' => $item->choose_us_content,

                    'pointer' => (object) $pointerObject,

                    'advantages' => (object) $item->advantages,

                    'section_two_sub_Heading' => $item->section_two_sub_Heading,
                    'base_text' => $item->base_text,
                    'faqs_contect' => $item->faqs_contect,
                    'country_id' => $item->country_id,
                    'created_by' => $item->created_by,
                    'updated_by' => $item->updated_by,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'consulting' => $item->consulting,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $transformedData,
            ], 200);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching Consulting: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Consulting.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function consultigPage(Request $request)
    {
        try {
            $validated = $request->validate([
                'slug' => 'required|string|exists:tbl_consulting,slug',
            ]);
 
            $consulting = Consulting::where('slug', $validated['slug'])->first();
            if (!$consulting) {
                return response()->json([
                    'status' => false,
                    'message' => 'Consulting data not found for the given meta_title.',
                ], 404);
            }

            $pointerObject = is_array($consulting->pointer) ? (object) $consulting->pointer : (object) [];
            $advantagesObject = is_array($consulting->advantages) ? (object) $consulting->advantages : (object) [];

            $responseData = [
                'id' => $consulting->id,
                'banner_Heading' => $consulting->banner_Heading,
                'section_one_Heading' => $consulting->section_one_Heading,
                'banner_content' => $consulting->banner_content,
                'meta_title' => $consulting->meta_title,
                'meta_description' => $consulting->meta_description,
                'status' => $consulting->status,
                'pages_img' => $consulting->pages_img,
                'company_id' => $consulting->company_id,
                'section_one_content' => $consulting->section_one_content,
                'section_two_Heading' => $consulting->section_two_Heading,
                'chooseus_Heading' => $consulting->chooseus_Heading,
                'choose_us_content' => $consulting->choose_us_content,
                'pointer' => $pointerObject,
                'advantages' => $advantagesObject,
                'section_two_sub_Heading' => $consulting->section_two_sub_Heading,
                'base_text' => $consulting->base_text,
                'faqs_contect' => $consulting->faqs_contect,
                'country_id' => $consulting->country_id,
                'created_by' => $consulting->created_by,
                'updated_by' => $consulting->updated_by,
                'created_at' => $consulting->created_at,
                'updated_at' => $consulting->updated_at,
                'consulting' => $consulting->consulting,
            ];

            return response()->json([
                'status' => true,
                'data' => $responseData,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            dd($e);
            \Illuminate\Support\Facades\Log::error('Consulting Fetch Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching Consulting.',
            ], 500);
        }
    }

    public function storeEnrollment(Request $request)
    {
        try {
            $validated = $request->validate([
                'enroll_type' => 'required|in:myself,someone,corporate',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'country_code' => 'required|string|max:10',
                'phone' => 'required|string|max:20',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'alternate_contact' => 'nullable|string|max:255',
                'alternate_email' => 'nullable|email|max:255',
                'company_name' => 'nullable|string|max:255',
            ]);

            $enrollment = Enrollment::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Enrollment saved successfully',
                'data' => $enrollment,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Enrollment store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function validPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer',
                'course_id' => 'required|integer|exists:tbl_courses,id',
                'transaction_id' => 'required|string|max:255',
                'paid_amount' => 'required|numeric|min:0',
                'payment_status' => 'required|in:Completed,Not Completed',
                'payment_date' => 'required',
            ]);


            $enrollment = Enrollment::where('id', $validated['id'])->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Enrollment not found',
                ], 404);
            }

            $enrollment->update([
                'course_id' => $validated['course_id'],
                'transaction_id' => $validated['transaction_id'],
                'paid_amount' => $validated['paid_amount'],
                'payment_status' => $validated['payment_status'],
                'payment_date' => $validated['payment_date'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enrollment updated successfully',
                'data' => $enrollment,
            ], 200);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Enrollment update failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'err' => $e->getMessage()
            ], 500);
        }
    }


    public function blogSearch(Request $request)
    {
        try {
            $validated = $request->validate([
                'filter' => 'nullable|string|max:255',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            $filter = $validated['filter'] ?? '';
            $perPage = $validated['per_page'] ?? 10;

            $blogs = KnowledgeCenter::where('resource_title', 'like', '%' . $filter . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'status' => true,
                'data' => $blogs->items(),
                'meta' => [
                    'current_page' => $blogs->currentPage(),
                    'total' => $blogs->total(),
                    'per_page' => $blogs->perPage(),
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Blog search failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function downloadInvoicePDF(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:tbl_enrollments,id'
            ]);

            $id = $request->id;


            $enrollment = Enrollment::with(['coursedate'  , 'schedule'])->findOrFail($id);
            $country = CourseCountry::where('currency', $enrollment->currency)->first();

            $qty = (int) ($enrollment->quantity ?? 1);
            $paidAmount = (float) ($enrollment->paid_amount ?? 0);

            $unitAmount = $qty > 0 ? ($paidAmount / $qty) : 0;

            $invoiceItems = collect([[
                'course_name' => $enrollment->coursedate->course_title ?? 'Course',
                'qty'         => $enrollment->quantity ?? 1,
                'amount' => number_format($unitAmount, 2, '.', ''),
                'total' => number_format((float) ($enrollment->paid_amount ?? 0), 2, '.', ''),
                'country' => $country?->name ?? 'india',
                'currency' =>  $country?->currency ?? '_',
            ]]);

            $total = $enrollment->paid_amount;
            $amountInWords = $this->numberToWords($total);

            $html = $this->generateInvoiceHTML(
                $enrollment,
                $country,
                $invoiceItems,
                $total,
                $amountInWords
            );

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOptions([
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                    'defaultFont' => 'DejaVu Sans'
                ]);

            $folderPath = public_path('invoices');

            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            $fileName = 'invoice_' . ($enrollment->invoice_no ?? 'ENR-' . $id . 'IN') . '.pdf';
            $filePath = $folderPath . '/' . $fileName;

            $pdf->save($filePath);

            $publicUrl = asset('invoices/' . $fileName);


            return response()->json([
                'status' => true,
                'message' => 'Invoice generated successfully',
                'pdf_url' => $publicUrl
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => $e->getMessage(),
                'line'   => $e->getLine(),
                'file'   => $e->getFile()
            ], 500);
        }
    }


    public function  generateInvoiceByEnrollmentId(int $enrollmentId): array
    {
        $enrollment = Enrollment::with(['coursedate', 'schedule'])
            ->findOrFail($enrollmentId);

        $country = CourseCountry::where('currency', $enrollment->currency)->first();

        $qty = (int) ($enrollment->quantity ?? 1);
        $paidAmount = (float) ($enrollment->paid_amount ?? 0);
        $unitAmount = $qty > 0 ? ($paidAmount / $qty) : 0;

        $invoiceItems = collect([[
            'course_name' => $enrollment->coursedate->course_title ?? 'Course',
            'qty'         => $qty,
            'amount'      => number_format($unitAmount, 2, '.', ''),
            'total'       => number_format($paidAmount, 2, '.', ''),
            'country'     => $country?->name ?? 'India',
            'currency'    => $country?->currency ?? '_',
        ]]);

        $total = $paidAmount;
        $amountInWords = $this->numberToWords($total);

        $html = $this->generateInvoiceHTML(
            $enrollment,
            $country,
            $invoiceItems,
            $total,
            $amountInWords
        );

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4')
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'DejaVu Sans'
            ]);

        $folderPath = public_path('invoices');

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        $fileName = 'invoice_' . ($enrollment->invoice_no ?? 'ENR-' . $enrollmentId) . '.pdf';
        $filePath = $folderPath . '/' . $fileName;

        $pdf->save($filePath);

        // ✅ Save invoice path in DB
        // $enrollment->invoice_path = 'invoices/' . $fileName;
        $enrollment->save();

        return [
            'path' => $filePath,
            'url'  => asset('invoices/' . $fileName),
            'name' => $fileName
        ];
    }



    private function generateInvoiceHTML($enrollment, $country , $invoiceItems, $total, $amountInWords): string
    {
                $itemsHTML = '';

                    foreach ($invoiceItems as $item) {
                        $itemsHTML .= '
                        <tr>
                            <td>' . htmlspecialchars($item["course_name"]) . '</td>

                            <td style="text-align:center;">
                                ' . (int) $item["qty"] . '
                            </td>

                            <td style="text-align:center;">
                                ' .  $item["currency"] . '
                            </td>

                            <td style="text-align:left;">
                                 ' . number_format((float) $item["amount"], 2) . '
                            </td>

                            <td style="text-align:left;">
                                 ' . number_format((float) $item["total"], 2) . '
                            </td>
                        </tr>';
                    }


                    $fullName = trim(($enrollment->first_name ?? '') . ' ' . ($enrollment->last_name ?? ''));
                    $invoiceNo = $enrollment->invoice_no ?? ('ENR-' . $enrollment->id);
                    $date = date('d-m-Y', strtotime($enrollment->payment_date ?? now()));
                    $phone = $enrollment->phone ?? '-';
                    $email = $enrollment->email ?? '-';
                    $company = $enrollment->company_name ?? '-';
                    $planName       = $enrollment->plan?->name ?? '-';
                    $scheduleId     = $enrollment->schedule_id ?? '-';
                    $paymentStatus  = ucfirst($enrollment->payment_status ?? 'pending');
                    $paymentDate = $enrollment->payment_date
                                        ? Carbon::createFromFormat('Y-m-d H:i:s', $enrollment->payment_date)
                                            ->format('d-m-Y h:i A')
                                        : '-';


                $transactionId  = $enrollment->transaction_id ?? '-';
                $countryName = $country->name ?? '-';
                $symbol =  $country->currency ?? '_';
                $startDate = $enrollment->schedule->class_start_date ?? '_';
                $endDate = $enrollment->schedule->class_end_date ?? '_';

                $gstRow = '';

                if (!empty($country?->name) && strcasecmp($country?->name, 'India') === 0) {
                    $gstRow = '<p><strong>GSTIN :</strong> 09BLSPP5713P1Z3</p>';
                }



                $companyRow = '';

                if (!empty($enrollment->company_name)) {
                    $companyRow = '
                        <tr>
                            <td class="label">Organization Name</td>
                            <td class="value">' . htmlspecialchars($enrollment->company_name) . '</td>
                        </tr>';
                }


                return '
            <!DOCTYPE html>
            <html>
        <head>
        <meta charset="UTF-8">
        <title>Invoice</title>

        <style>
        body{
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size:13.5px;
            margin:0;
            color:#222;
            line-height:1.5;
        }

        /* HEADER */
        .header{
            border-bottom:2px solid #000;
            padding-bottom:12px;
            margin-bottom:25px;
        }
        .header-table{
            width:100%;
        }
        .header-table td{
            vertical-align:middle;
        }
        .invoice-title{
            text-align:right;
            font-size:18px;
            font-weight:600;
        }

        /* ADDRESS BLOCKS */
        .clearfix:after{
            content:"";
            display:block;
            clear:both;
        }
        .left-block,
        .right-block{
            width:48%;
        }
        .left-block{
            float:left;
        }
        .right-block{
            float:right;
        }

        /* TEXT STYLES */
        p{
            margin:4px 0;
        }
        strong{
            font-weight:500;
        }

        /* INVOICE DETAILS TABLE */
        .invoice-table{
            width:100%;
            border-collapse:collapse;
            table-layout:fixed;
            margin-top:8px;
        }
        .invoice-table td{
            padding:4px 0;
            vertical-align:top;
        }
        .invoice-table .label{
            width:170px;
            font-weight:600;
        }
        .invoice-table .value{
            word-break:break-word;
        }

        /* ITEMS TABLE */
        .items-table{
            width:100%;
            border-collapse:collapse;
            margin-top:30px;
            font-size:13.5px;
        }
        .items-table th,
        .items-table td{
            border:1px solid #000;
            padding:8px 6px;
            text-align:left;
        }
        .items-table th{
            background:#f4f4f4;
            font-weight:600;
        }

        /* TOTAL */
        .total-row{
            margin-top:15px;
            font-size:15px;
            font-weight:600;
        }
        .amount-words{
            margin-top:6px;
            font-style:italic;
        }

        /* PAYMENT DETAILS */
        .payment-details{
            margin-top:30px;
            padding-top:10px;
            border-top:1px solid #ccc;
        }

        /* FOOTER */
        .footer{
            position:fixed;
            bottom:0;
            left:0;
            right:0;
            text-align:center;
            border-top:1px solid #ccc;
            padding:10px 20px;
            background:#fff;
            font-size:12px;
        }
        .footer p{
            margin:3px 0;
        }
        </style>
        </head>

        <body>

        <!-- HEADER -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td>
                        <img src="https://knowledgeadmin.scholaracad.com/assets/image/logo/scholaracad_logo.svg" height="45">
                    </td>
                    <td class="invoice-title">
                        Invoice - ' . htmlspecialchars($invoiceNo) . '
                    </td>
                </tr>
            </table>
        </div>

        <!-- ADDRESS + DETAILS -->
        <div class="clearfix">

            <!-- LEFT -->
            <div class="left-block">
                <p style="font-weight:600;">Scholar Academy Consulting</p>
                <p>A-43, Trikuta Hills, Star Sehkari Awas Samiti Ltd. Plot No. C-58/27, Noida Sector-62 Gautam Buddha Nagar, Uttar Pradesh, 201309 (India)</p>
                ' . $gstRow . '
                <p><strong>Trainer Name :</strong> TBD</p>
                <p><strong>HSN/SAC Code :</strong> 999293</p>
            </div>

            <!-- RIGHT -->
            <div class="right-block">
                <p style="font-weight:600;">Bill To</p>
                <hr style="border-top:1px dashed #999; border-bottom:none; margin:6px 0 10px;">
                <table class="invoice-table">
                    <tr>
                        <td class="label">Date</td>
                        <td class="value">' . htmlspecialchars($date) . '</td>
                    </tr>
                    <tr>
                        <td class="label">Customer Name</td>
                        <td class="value">' . htmlspecialchars($fullName) . '</td>
                    </tr>
                    <tr>
                        <td class="label">Contact Number</td>
                        <td class="value">' . htmlspecialchars($phone) . '</td>
                    </tr>
                    <tr>
                        <td class="label">Email</td>
                        <td class="value">' . htmlspecialchars($email) . '</td>
                    </tr>
                    ' . $companyRow . '
                    <tr>
                        <td class="label">Place Of Supply</td>
                        <td class="value">' . htmlspecialchars($countryName) . '</td>
                    </tr>
                </table>
            </div>

        </div>

        <!-- ITEMS -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Qty</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                ' . $itemsHTML . '
            </tbody>
        </table>

        <div class="total-row">
            Total Amount: ' . htmlspecialchars($symbol) . ' ' . number_format((float) $item["total"], 2) . '
        </div>

        <div class="amount-words">
            Amount in Words: ' . htmlspecialchars($amountInWords) . ' Only
        </div>

        <!-- PAYMENT DETAILS -->
        <div class="payment-details">
            <table class="invoice-table">
                <tr>
                    <td class="label">Plan Name</td>
                    <td class="value">' . htmlspecialchars($planName) . '</td>
                </tr>
                <tr>
                    <td class="label">Schedule ID</td>
                    <td class="value">' . htmlspecialchars($scheduleId) . '</td>
                </tr>
                <tr>
                    <td class="label">Batch Dates</td>
                    <td class="value">' . htmlspecialchars($startDate) . ' - ' . htmlspecialchars($endDate) . '</td>
                </tr>
                <tr>
                    <td class="label">Payment Status</td>
                    <td class="value">' . htmlspecialchars($paymentStatus) . '</td>
                </tr>
                <tr>
                    <td class="label">Payment Date</td>
                    <td class="value">' . htmlspecialchars($paymentDate) . '</td>
                </tr>
                <tr>
                    <td class="label">Transaction ID</td>
                    <td class="value">' . htmlspecialchars($transactionId) . '</td>
                </tr>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p><strong>Thank you for trusting ScholarAcad</strong></p>
            <p><strong>SUPPLY MEANT FOR EXPORT LETTER OF UNDERTAKING WITHOUT PAYMENT OF INTEGRATED TAX</strong></p>
            <p>Computer Generated Invoice – No Signature Required</p>
        </div>

        </body>
        </html>
        ';
    }



    private function numberToWords(float $number): string
    {
        if ($number == 0) {
            return 'Zero';
        }

        $ones = [
            '', 'One','Two','Three','Four','Five','Six','Seven','Eight','Nine'
        ];

        $tens = [
            '', '', 'Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'
        ];

        // Split number into integer and decimal parts
        $number = round($number, 2);
        $integerPart = (int) $number;
        $decimalPart = round(($number - $integerPart) * 100); // two digits

        // Convert integer part
        $words = $this->convertNumber($integerPart, $ones, $tens);

        // Convert decimal part (digit by digit)
        if ($decimalPart > 0) {
            $decimalDigits = str_pad($decimalPart, 2, '0', STR_PAD_LEFT); // ensure 2 digits
            $decimalWords = '';
            foreach (str_split($decimalDigits) as $digit) {
                $decimalWords .= ' ' . $ones[intval($digit)];
            }
            $words .= ' decimal' . $decimalWords;
        }

        return trim($words);
    }


    private function convertNumber(int $number, array $ones, array $tens): string
    {
        if ($number == 0) return 'Zero';

        if ($number < 10) return $ones[$number];

        if ($number < 20) return [
            'Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen',
            'Sixteen','Seventeen','Eighteen','Nineteen'
        ][$number - 10];

        if ($number < 100) {
            return $tens[intval($number / 10)] .
                ($number % 10 ? ' ' . $ones[$number % 10] : '');
        }

        if ($number < 1000) {
            return $ones[intval($number / 100)] . ' Hundred' .
                ($number % 100 ? ' ' . $this->convertNumber($number % 100, $ones, $tens) : '');
        }

        if ($number < 100000) {
            return $this->convertNumber(intval($number / 1000), $ones, $tens) . ' Thousand' .
                ($number % 1000 ? ' ' . $this->convertNumber($number % 1000, $ones, $tens) : '');
        }

        if ($number < 10000000) {
            return $this->convertNumber(intval($number / 100000), $ones, $tens) . ' Lakh' .
                ($number % 100000 ? ' ' . $this->convertNumber($number % 100000, $ones, $tens) : '');
        }

        return $this->convertNumber(intval($number / 10000000), $ones, $tens) . ' Crore' .
            ($number % 10000000 ? ' ' . $this->convertNumber($number % 10000000, $ones, $tens) : '');
    }


     public function stateURLList(Request $request)
    {
        try {
            $data = stateURL::query()->get();



            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching stateURL: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching stateURL.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }











    /** Dynamic Page api
     *
     *
     *
     *
     *
     *
     */

    // public function dynamicPages(Request $request)
    // {
    //     try {

    //         $validated = $request->validate([
    //             'course_id' => 'required|string',
    //             'country_id' => 'nullable|string',
    //             'state_id' => 'nullable|string',
    //         ]);

    //         $filterData = self::getFilterKeys($validated);

    //         $page = DynamicCoursePages::where('course_id', $filterData?->course_id)
    //             ->where('country_id', $filterData?->country_id)
    //             ->where('state_id', $filterData?->state_id)
    //             ->first();

    //         if (!$page) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Data Not Found !'
    //             ], 404);
    //         }

    //         $cat_id = $page?->coursedate?->course_category_id;

    //         $responseData = [
    //             'course_id' => $filterData?->course_id,
    //             'courseImage' => $this->getFilterData(new CourseImages, $filterData),
    //             'keyfeatures' => $this->getFilterData(new DynamicCourseKeyfeatures, $filterData),
    //             'bulletPoints' => $this->getFilterData(new CourseHighlights, $filterData),
    //             'allHeadline' => $this->getFilterData(new DynamicCourseSectionheading, $filterData),
    //             'newExamstructure' => $this->getFilterData(new NewExamStructure, $filterData),
    //             'courseHeading' => $this->getFilterData(new CourseHeading, $filterData),
    //             'courseDescription' => $this->getFilterData(new CourseHeadingDescription, $filterData),
    //             'whatyoulearn' => $this->getFilterData(new Course_Whatyoulearn, $filterData),
    //             'courseOverview' => $this->getFilterData(new CourseOverviews, $filterData),
    //             'courseWhyScholarAcad' => $this->getFilterData(new CourseWhyScholarAcad, $filterData),
    //             'aboutGeography' => $this->getFilterData(new AboutGeography, $filterData),
    //             'courseSchedule' => ($this->getCourseScheduleData(new CourseSchedule, $filterData)),
    //             // 'courseCurriculumFAQ' => $this->getFilterData(new CourseCurriculumFaq, $filterData, $cat_id),
    //             'courseCurriculumFAQ' => $this->getFilterData(new CourseCurriculumFaq, $filterData),
    //             'youtubeVideos' => $this->getFilterData(new CourseYouTube, $filterData),
    //             'courseLearningObjectives' => $this->getFilterData(new CourseLearningObjectives, $filterData),
    //             'certificationPath' => $this->getFilterData(new CourseCertificationPath, $filterData),
    //             'courseBenefits' => $this->getFilterData(new CourseBenefits, $filterData),
    //             'courseInstructor' => $this->getFilterData(new CourseTrainer, $filterData),
    //             'certificateEligibility' => $this->getFilterData(new CertificationEligibility, $filterData),
    //             'courseSalary' => $this->getFilterData(new CourseSalary, $filterData),
    //             'hiringCompanies' => $this->getFilterData(new HiringCompanies, $filterData),
    //             'courseTutionFee' => $this->getFilterData(new DynamicCourseTuitionFee, $filterData),
    //             'dynamicCourseHeading' => $this->getFilterData(new DynamicCourseHeadline, $filterData),
    //             'courseAlumni' => $this->getFilterData(new Alumni, $filterData),
    //             'certificationImage' => $this->getFilterData(new Certificateimage, $filterData),
    //             'coursePDF' => $this->getFilterData(new CoursePDF, $filterData),
    //             'certificationText' => $this->getFilterData(new Certificatetext, $filterData),
    //             'coursePlan' => $this->getFilterData(new PlanMapping, $filterData),
    //         ];

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Course data fetched successfully',
    //             'data' => $responseData
    //         ]);


    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (Exception $e) {
    //         \Illuminate\Support\Facades\Log::error('Dynamic Pages Error: ' . $e->getMessage(), [
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage(), 'file' => $e->getFile() . ':' . $e->getLine(),
    //         ], 500);
    //     }






    // }

    public function dynamicPages(Request $request)
    {
        try {
            $courseIdentifier = $request->input('course_id') ?? $request->input('slug') ?? $request->input('url_title') ?? $request->input('id');
            if (!$courseIdentifier) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => ['course_id' => ['The course id, slug, or url_title field is required.']]
                ], 422);
            }

            $validated = [
                'course_id' => $courseIdentifier,
                'country_id' => $request->input('country_id') ?? 'IN',
                'state_id' => $request->input('state_id'),
                'city_id' => $request->input('city_id'),
            ];





            $filterData = self::getFilterKeys($validated);

            if ($request->filled('state_id')) {
                if (!$filterData || !$filterData->state_id) {

                    return response()->json([
                        'status' => false,
                        'message' => 'Data Not Found!',
                    ], 404);
                }
            }


            $page = DynamicCoursePages::where('course_id', $filterData?->course_id)
                ->where(function ($q) use ($filterData) {
                    if (!empty($filterData?->course_country_id)) {
                        $q->where('country_id', $filterData->course_country_id);
                    }
                    if (!empty($filterData?->country_id)) {
                        $q->orWhere('country_id', $filterData->country_id);
                    }
                })
                ->where('state_id', $filterData?->state_id)
                ->first();

            if (!$page && $filterData?->course_id) {
                $page = DynamicCoursePages::where('course_id', $filterData->course_id)->first();
            }

            // If no dedicated page record, create a temporary one to allow data to flow
            if (!$page) {
                $page = new DynamicCoursePages();
                $page->course_id = $filterData?->course_id;
                $page->country_id = $filterData?->country_id;
                $page->state_id = $filterData?->state_id;
                $page->googleRating = null;
                $page->enrolled = null;
            }

            $cat_id = $page?->coursedate?->course_category_id;

            $countryName = '';
            $cityName = '';

            if ($request->filled('country_id')) {
                $country = CourseCountry::where('iso2', $request->country_id)->first();
                $countryName = $country?->name ?? '';
            }

            if ($request->filled('city_id')) {
                $city = City::where('id', $request->city_id)->first();
                $cityName = $city?->name ?? '';
            }


            $responseData = [
                'course_id' => $filterData?->course_id,
                'country_name' => $countryName,
                'city_name' => $cityName,
                'ratings' => $this->getFilterData(new DynamicCoursePages , $filterData),
                'courseImage' => $this->getFilterData(new CourseImages, $filterData),
                'keyfeatures' => $this->getFilterData(new DynamicCourseKeyfeatures, $filterData),
                'bulletPoints' => $this->getFilterData(new CourseHighlights, $filterData),
                'allHeadline' => $this->getFilterData(new DynamicCourseSectionheading, $filterData),
                'newExamstructure' => $this->getFilterData(new NewExamStructure, $filterData),
                'courseHeading' => $this->getFilterData(new CourseHeading, $filterData),
                'courseDescription' => $this->getFilterData(new CourseHeadingDescription, $filterData),
                'whatyoulearn' => $this->getFilterData(new Course_Whatyoulearn, $filterData),
                'courseOverview' => $this->getFilterData(new CourseOverviews, $filterData),
                'courseWhyScholarAcad' => $this->getFilterData(new CourseWhyScholarAcad, $filterData),
                'aboutGeography' => $this->getFilterData(new AboutGeography, $filterData),
                'courseSchedule' => $this->getCourseScheduleData(new CourseSchedule, $filterData),
                'courseCurriculumFAQ' => $this->getFilterData(new CourseCurriculumFaq, $filterData),
                'youtubeVideos' => $this->getFilterData(new CourseYouTube, $filterData),
                'courseLearningObjectives' => $this->getFilterData(new CourseLearningObjectives, $filterData),
                'certificationPath' => $this->getFilterData(new CourseCertificationPath, $filterData),
                'courseBenefits' => $this->getFilterData(new CourseBenefits, $filterData),
                'courseInstructor' => $this->getFilterData(new CourseTrainer, $filterData),
                'certificateEligibility' => $this->getFilterData(new CertificationEligibility, $filterData),
                'courseSalary' => $this->getFilterData(new CourseSalary, $filterData),
                'hiringCompanies' => $this->getFilterData(new HiringCompanies, $filterData),
                'courseTutionFee' => $this->getFilterData(new DynamicCourseTuitionFee, $filterData),
                'dynamicCourseHeading' => $this->getFilterData(new DynamicCourseHeadline, $filterData),
                'courseAlumni' => $this->getFilterData(new Alumni, $filterData),
                'certificationImage' => $this->getFilterData(new Certificateimage, $filterData),
                'coursePDF' => $this->getFilterData(new CoursePDF, $filterData),
                'certificationText' => $this->getFilterData(new Certificatetext, $filterData),
                'coursePlan' => $this->getPlansWithFeatures(new PlanMapping, $filterData),
                'CourseFaqs' => $this->getFilterData(new CourseFaqs ,$filterData)

            ];


            $responseData = $this->processCountryReplacement($responseData, $countryName, $cityName);

            return response()->json([
                'success' => true,
                'message' => 'Course data fetched successfully',
                'data' => $responseData
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Dynamic Pages Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(), 'file' => $e->getFile() . ':' . $e->getLine(),
            ], 500);
        }
    }

    private function processCountryReplacement($data, $countryName, $cityName)
    {
        if (is_string($data)) {
            $data = stripslashes($data);
            $data = html_entity_decode($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $data = preg_replace('/\x{00A0}|\x{200B}|\x{FEFF}/u', ' ', $data);

            return str_replace(
                ['{{country}}', '{{city}}'],
                [$countryName, $cityName],
                $data
            );
        }

        if ($data instanceof Collection) {
            return $data->map(function ($item) use ($countryName, $cityName) {
                return $this->processCountryReplacement($item, $countryName, $cityName);
            });
        }


        if ($data instanceof \Illuminate\Database\Eloquent\Model) {
            foreach ($data->getAttributes() as $key => $value) {
                if (is_string($value)) {
                    $data->{$key} = $this->processCountryReplacement(
                        $value,
                        $countryName,
                        $cityName
                    );
                }
            }
            return $data;
        }


        if (is_array($data)) {
            return array_map(fn ($v) =>
                $this->processCountryReplacement($v, $countryName, $cityName),
                $data
            );
        }

        return $data;
    }



    // private function getPlansWithFeatures($planModel, $filterData)
    // {
    //     // dd($filterData);
    //     // $plans = $this->getFilterData($planModel, $filterData);
    //     $plans = PlanMapping::where('course_id',$filterData->course_id)
    //                           ->get();
    //     // dd($plans);

    //     if (empty($plans)) {
    //         return $plans;
    //     }

    //     $planIds = collect($plans)->pluck('plan_id');
    //     // dd($planIds);
    //     $features = Feature::where('course_id', $filterData->course_id)
    //         ->whereIn('plan_id', $planIds)
    //         ->get()
    //         ->keyBy('plan_id');

    //         dd($features);

    //     return collect($plans)->map(function ($plan) use ($features) {
    //         $plan->features = $features->get($plan->id, collect([]))->pluck('name')->all();
    //         return $plan;
    //     });
    // }

private function getPlansWithFeatures($planModel, $filterData)
{
    return PlanMapping::with([
            'plan' => function ($q) {
                $q->select('id', 'name');
            },
            'features' => function ($q) use ($filterData) {
                $q->where('course_id', $filterData->course_id)
                  ->where('is_excluded', 0)
                  ->select('id', 'plan_id', 'name');
            }
        ])
        ->where('course_id', $filterData->course_id)
        ->get()
        ->map(function ($mapping) {

            return [
                'plan_id'   => $mapping->plan_id,
                'plan_name' => optional($mapping->plan)->name,
                'features'  => $mapping->features->pluck('name')->values(),
            ];
        });
}


    public function allTestimonial(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 20);
            $page    = (int) $request->input('page', 1);

            $testimonials = Testimonial::orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $testimonials->items(),
                'pagination' => [
                    'current_page' => $testimonials->currentPage(),
                    'per_page'     => $testimonials->perPage(),
                    'total'        => $testimonials->total(),
                    'last_page'    => $testimonials->lastPage(),
                    'from'         => $testimonials->firstItem(),
                    'to'           => $testimonials->lastItem(),
                    'has_more'     => $testimonials->hasMorePages(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch testimonials',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function footer(Request $request)
    {
        try {

        $footer = Pages :: where('status' ,  'Disclaimer-footer')->get();

        if(!$footer){
            return response()->json([
                'success' => false,
                'message' => 'do  not  found footer',
            ], 404);
        }

            return response()->json([
                'success' => true,
                'message' => 'footer fetch Successfully',
                'data' => $footer,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch footer',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function AllCourses(Request $request)
    {
        try {

        $footer = Pages :: where('status' ,  'All-Course')->get();

        if(!$footer){
            return response()->json([
                'success' => false,
                'message' => 'do  not  found All Courses',
            ], 404);
        }

            return response()->json([
                'success' => true,
                'message' => 'All Courses fetch Successfully',
                'data' => $footer,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch All Courses',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }





    /**    // helper functions
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     */

    protected function getFilterData(Model $model, $data, $course_cat_id = null)
    {
        try {
            $modelName = class_basename($model);
            $table = $model->getTable();
            $returnAll = ['AboutGeography', 'CourseFaqs' , 'CourseSchedule', 'CourseCurriculumFaq', 'CourseSalary', 'FaqsGeneral', 'Testimonial', 'CourseYouTube', 'PlanMapping' , 'CourseTrainer', 'HiringCompanies', 'DynamicCoursePages' , 'Alumni' , ];

            if (!\Illuminate\Support\Facades\Schema::hasTable($table)) {
                return in_array($modelName, $returnAll) ? collect([]) : null;
            }

            $columns = self::getColumns($model);
            if (empty($columns)) {
                $columns = ['*'];
            }

            $query = $model->newQuery();

            $query->select($columns);

        $excludeCourseId = ['AboutGeography', 'CourseSchedule', 'Alumni' , 'HiringCompanies', 'CourseTrainer'  ];
        $excludeCountryId = ['CourseCurriculumFaq', 'CourseYouTube', 'FaqsGeneral', 'CourseHeadingDescription', 'Certificateimage', 'CoursePDF', 'PlanMapping' ];
        $excludeStateId = ['CourseSchedule', 'CourseYouTube',  'CourseCurriculumFaq', 'FaqsGeneral', 'CourseHeadingDescription', 'PlanMapping', 'Alumni', 'Certificateimage', 'CoursePDF', 'Certificatetext'  , 'CourseTrainer' , 'HiringCompanies' ];

        $returnAll = ['AboutGeography', 'CourseFaqs' , 'CourseSchedule', 'CourseCurriculumFaq', 'CourseSalary', 'FaqsGeneral', 'Testimonial', 'CourseYouTube', 'PlanMapping' , 'CourseTrainer', 'HiringCompanies', 'DynamicCoursePages' , 'Alumni' , ];

        if ($model == 'PlanMapping') {
            $query->with('plan', 'plans.features');
        }

        if ($modelName == 'HiringCompanies') {
            $query?->limit(5);
        }
        if (!in_array($modelName, $excludeCourseId)) {
            $query->where('course_id', $data->course_id);
        }
        if (!in_array($modelName, $excludeCountryId)) {
            $courseCountryTables = ['DynamicCoursePages', 'CourseHighlights', 'DynamicCourseSectionheading', 'CourseOverviews', 'Course_Whatyoulearn', 'CourseImages'];
            $targetCountryId = in_array($modelName, $courseCountryTables)
                ? ($data->course_country_id ?? $data->country_id)
                : ($data->country_id ?? $data->course_country_id);

            if ($targetCountryId) {
                $query->where(function ($q) use ($targetCountryId, $data) {
                    $q->where('country_id', $targetCountryId);
                    if (!empty($data->country_id) && $data->country_id != $targetCountryId) {
                        $q->orWhere('country_id', $data->country_id);
                    }
                    if (!empty($data->course_country_id) && $data->course_country_id != $targetCountryId) {
                        $q->orWhere('country_id', $data->course_country_id);
                    }
                });
            }
        }
        if (!in_array($modelName, $excludeStateId)) {
            if (!empty($data->state_id)) {
                $query->where('state_id', $data->state_id);
            }
        }
        if ($modelName == 'CourseSchedule') {
            $query?->where('class_end_date', '>', now());
        }
        // if ($course_cat_id) {
        //     $query?->where('course_category', $course_cat_id);
        // }

        $result = in_array($modelName, $returnAll)
            ? $query->get()
            : $query->first();

        if ((empty($result) || ($result instanceof \Illuminate\Support\Collection && $result->isEmpty())) && !in_array($modelName, $excludeCourseId) && !empty($data->course_id)) {
            $fallbackQuery = $model->newQuery()->select($columns)->where('course_id', $data->course_id);
            if ($modelName == 'CourseSchedule') {
                $fallbackQuery->where('class_end_date', '>', now());
            }
            $fallbackResult = in_array($modelName, $returnAll) ? $fallbackQuery->get() : $fallbackQuery->first();
            if (!empty($fallbackResult) && !($fallbackResult instanceof \Illuminate\Support\Collection && $fallbackResult->isEmpty())) {
                $result = $fallbackResult;
            }
        }

        return $result;
        } catch (\Throwable $e) {
            return in_array($modelName, $returnAll) ? collect([]) : null;
        }
    }

    public static function getColumns(Model $model)
    {
        $table = $model->getTable();
        $except = [
            // 'id',
            'created_by',
            'updated_by',
            'deleted_at',
            'created_at',
            'updated_at'
        ];
        $columns = Schema::getColumnListing($table);
        $columns = array_diff($columns, $except);


        return $columns;
    }

    public static function getFilterKeys($array)
    {
        $course = Course::where('url_title', $array['course_id'])->first();

        // Use tbl_country (Country model) because all data tables reference tbl_country.id
        // tbl_country has India = id 99 (code_1='IN'), which matches data in keyfeatures etc.
        $country = !empty($array['country_id'])
            ? \Modules\DynamicMaster\Models\Country::where('code_1', strtoupper($array['country_id']))->first()
            : null;

        $courseCountry = !empty($array['country_id'])
            ? CourseCountry::where('iso2', strtoupper($array['country_id']))->first()
            : null;

        // State lookup via tbl_city (state_id in data tables = tbl_city.id)
        $state = !empty($array['state_id'])
            ? \Modules\Course\Models\State::where('name', $array['state_id'])->first()
            : null;

        return (object) [
            'course_id' => $course?->id,
            'country_id' => $country?->id,
            'course_country_id' => $courseCountry?->id,
            'state_id' => $state?->id,
        ];
    }

    public static function getCourseScheduleData(Model $model, $request)
    {
        $countryId = $request->country_id;

        $columns = self::getColumns($model);

        $multiplier = CourseCountry::whereKey($countryId)
            ->value('value_in_dollar') ?? 1;

        $schedules = CourseSchedule::query()
            ->select($columns)
            ->where('course_id', $request->course_id)
            ->where('country_id', $countryId)
            ->where('status','Public')
            ->where('enroll_end_date', '>=', Carbon::now())
            ->get();

        foreach ($schedules as $schedule) {
            $schedule->course_fees *=(float) $multiplier;
            $schedule->fee_currency = CourseCountry::whereKey($countryId)->value('currency');
        }

        return $schedules;
    }

}



