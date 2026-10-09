<?php

namespace Modules\Loginer\Services;

use Modules\Loginer\Repositories\LoginerRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Traits\HasRoles;
use Modules\Course\Models\CourseCategory;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseCountry;
use Modules\Course\Models\State;
use Modules\Course\Models\City;
use Modules\FAQS\Models\FaqsGeneral;
use App\Models\Enrollment;
use Modules\Course\Models\DynamicCoursePages;


class LoginerService
{
    protected LoginerRepository $repository;

    public function __construct(LoginerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function login()
    {
        $user = Auth::user();
        if ($user) {
            $locale = session('locale', config('app.locale'));
            App::setLocale($locale); // ensure locale is set
            return redirect()->route('dashboard');
        }
        return view('loginer::auth.login');
    }

    public function registrationRequest(Request $request)
    {
        return $this->repository->registrationRequest($request);
    }
    public function registrationRequestOtpStore($request)
    {
        return $this->repository->registrationRequestOtpStore($request);
    }
    public function registrationRequestStoreFinal($request)
    {
        return $this->repository->registrationRequestStoreFinal($request);
    }
    public function login_check(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $isApi = $request->expectsJson() || $request->isJson() || $request->is('api/*') || $request->ajax() || str_contains($request->header('Content-Type', ''), 'application/json') || str_contains($request->header('Accept', ''), 'application/json');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($isApi) {
                return response()->json([
                    'status' => false,
                    'message' => 'The email or password is incorrect.'
                ], 401);
            }
            return back()
                ->withInput()
                ->withErrors(['email' => __('auth.failed')])
                ->with('error', 'The email or password is incorrect.');
        }
        $user = Auth::user();
        // if ($user->is2FAEnabled()) {
        //     return redirect()->route('2fa.verify');
        // }
        $history = $this->repository->loginHistory($user);
        $user_company = $user->userCompany->first();
        if (!$user->company_id && $user_company) {
            $user->company_id =  $user_company->company_id;
            $user->save();
        }
        $request->session()->regenerate();

        if ($isApi) {
            $token = null;
            try {
                if (class_exists(\Tymon\JWTAuth\Facades\JWTAuth::class)) {
                    $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
                }
            } catch (\Exception $e) {}

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ], 200);
        }

        // Role-based login redirect
        if ($user->hasRole('Admin')) {
            return redirect()->route('dashboard')->with('success', 'Welcome Admin!');
        } elseif ($user->hasRole('Seller')) {
            return redirect()->route('manager.dashboard')->with('success', 'Welcome Manager!');
        } elseif ($user->hasRole('Buyer')) {
            return redirect()->route('userprofile-form')->with('success', 'Welcome Buyers!');
        }
        return redirect()->intended(route('dashboard'))->with('success', 'Login successful!');
    }

    public function dashboard()
    {
        $totalCategories = CourseCategory::count();
        $totalCourse =  Course::count();
        $totalCountry =  CourseCountry::count();
        $totalState =   State::count();
        $totalCity =  City::count();
        $totalFaqs =  FaqsGeneral::count();
        $totalCandidate =  Enrollment::count();
        $totalCourseLinks = DynamicCoursePages::count();

        return view('loginer::dashboard', compact('totalCategories' , 'totalCourseLinks' ,'totalCandidate','totalFaqs', 'totalCourse' , 'totalState', 'totalCity','totalCountry'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
