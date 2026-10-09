<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="ScholarAcad API Documentation",
 *      description="API documentation for ScholarAcad Backend and Admin Panel",
 *      @OA\Contact(
 *          email="admin@scholaracad.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Primary API Server"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Enter your Bearer token in the format: Bearer <token>"
 * )
 */
class Controller extends BaseController
{
        // public function __construct()
        // {
        //     $this->middleware(function ($request, $next) {
        //         if (!Auth::check()) {
        //             return redirect()->route('login'); // redirect to your login route
        //         }
        //         return $next($request);
        //     });
        // }
    public static function loginandcompany()
    {
        $company = Auth::check() ? Auth::user()?->company_id : true;
        $userId = Auth::user()?->id;
        $authdetails = [$userId, $company];
        return $authdetails;
    }

    public static function logindepartment()
    {
        $emp_id = Auth::user()?->emp_id;
        $depts = self::model('Company_employee')::where('id', $emp_id)->pluck('department_id')->first();
        return self::model('Department')::where('id', $depts)->first();
    }

    public static function db()
    {
        return 'Illuminate\Support\Facades\DB';
    }

    public static function helper($help, $module = null)
    {
        if ($module) {
            return 'Modules\\' . $module . '\\Http\\Helpers\\' . $help;
        }

        return 'App\\Helpers\\' . $help;
    }


    public static function model($mod, $module = null)
    {
        if ($module) {
            return 'Modules\\' . $module . '\Models\\' . $mod;
        }
        return 'App\Models\\' . $mod;
    }

    public static function user()
    {
        return Auth::user();
    }
    public static function user_id()
    {
        return Auth::user()?->id;
    }

    public static function unique_one($mod, $col1, $value1)
    {
        $cmp_id = Auth::user()->company_id;
        $model = self::model($mod);
        $data = $model::ActiveOnly($cmp_id)->where($col1, $value1)->first();
        if ($data)
            return true;
        else
            return false;
    }

    public static function unique_two($mod, $col1, $value1, $col2, $value2, $module = null)
    {
        if ($module) {
            $cmp_id = Auth::user()->company_id;
            Controller::model($mod, $module)::where('status', 'Active')->get();
            $data = self::model($mod, $module)::ActiveOnly($cmp_id)->where($col1, $value1)->Orwhere($col2, $value2)->first();
            if ($data)
                return true;
            else
                return false;
        } else {
            $cmp_id = Auth::user()->company_id;
            Controller::model($mod)::where('status', 'Active')->get();
            $data = self::model($mod)::ActiveOnly($cmp_id)->where($col1, $value1)->Orwhere($col2, $value2)->first();
            if ($data)
                return true;
            else
                return false;

        }

    }


    //  public static function file_extension($uploadedImage,$type,$folder,$size)
    // {
    //     $extension = $uploadedImage->getClientOriginalExtension();
    //     $imageName = time().rand(100,999). '.' . $extension;
    //     if($type=="image")
    //       $allowedExtensions = ['jpeg','png','jpg'];
    //     else if($type=="document")
    //       $allowedExtensions = ['pdf','doc','docx','xlsx','xls'];
    //     else if($type=="image_document")
    //       $allowedExtensions = ['jpeg','png','jpg','pdf','doc','docx','xlsx','xls'];

    //     if(in_array($extension, $allowedExtensions) && $uploadedImage->getSize()/1048576<= $size)
    //     {
    //         Storage::disk('public')->makeDirectory($folder);

    //         Storage::disk('public')->putFileAs($folder, $uploadedImage, $imageName);
    //         return  $imageName;
    //     }
    //     else
    //         return false;

    // }

    public static function new_file_extension($uploadedImage, $type, $folder, $size)
    {
        $extension = $uploadedImage->getClientOriginalExtension();
        $imageName = time() . rand(100, 999) . '.' . $extension;
        if ($type == "image")
            $allowedExtensions = ['jpeg', 'png', 'jpg'];
        else if ($type == "document")
            $allowedExtensions = ['pdf', 'doc', 'docx', 'xlsx', 'xls'];
        else if ($type == "image_document")
            $allowedExtensions = ['jpeg', 'png', 'jpg', 'pdf', 'doc', 'docx', 'xlsx', 'xls'];

        if (in_array($extension, $allowedExtensions) && $uploadedImage->getSize() / 1048576 <= $size) {
            Storage::disk('public')->makeDirectory($folder);

            Storage::disk('public')->putFileAs($folder, $uploadedImage, $imageName);
            return $folder . '/' . $imageName;
        } else
            return false;

    }

    public static function generateRandomString($length = 12)
    {

        // Define character sets
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '@#$%^&*!';

        // Ensure at least one from each set
        $randomString = $upper[random_int(0, strlen($upper) - 1)];
        $randomString .= $lower[random_int(0, strlen($lower) - 1)];
        $randomString .= $numbers[random_int(0, strlen($numbers) - 1)];
        $randomString .= $specialChars[random_int(0, strlen($specialChars) - 1)];

        // Fill the rest
        $allChars = $upper . $lower . $numbers . $specialChars;

        while (strlen($randomString) < $length) {
            $randomString .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Avoid weak patterns
        $invalidPatterns = ['1234', 'abcd', 'qwerty', 'asdf', 'zxcv'];
        foreach ($invalidPatterns as $pattern) {
            if (stripos($randomString, $pattern) !== false) {
                return self::generateRandomString($length); // fixed recursive call
            }
        }

        // Shuffle for randomness
        return substr(str_shuffle($randomString), 0, $length);
    }


}
