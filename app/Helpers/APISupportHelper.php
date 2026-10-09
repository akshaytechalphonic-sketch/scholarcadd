<?php
namespace App\Helpers; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;
 
class APISupportHelper
{
    // public static function folder_name($doc_id, $doc_model, $document_type, $uploadedImage, $size, $file_type,$module_name = null)
    // {
    //     $extension = $uploadedImage->getClientOriginalExtension();

    //     if ($file_type == "image" || $file_type == "images")
    //         $allowedExtensions = ['jpeg', 'png', 'jpg'];
    //     else if ($file_type == "document")
    //         $allowedExtensions = ['pdf', 'doc', 'docx', 'xlsx', 'xls'];
    //     else if ($file_type == "image_document")
    //         $allowedExtensions = ['jpeg', 'png', 'jpg', 'pdf', 'doc', 'docx', 'xlsx', 'xls'];

    //     if (in_array($extension, $allowedExtensions) && $uploadedImage->getSize() / 1048576 <= $size) {

    //         $doc_data = Controller::model($doc_model,$module_name)::where('id', $doc_id)->first();

    //         if ($doc_data) {
    //             $company_id = $doc_data->company_id;
    //             $doc_code = base64_encode(convert_uuencode($doc_id));
    //         } else {
    //             return false;
    //         }

    //         $company = Controller::model('Company','DynamicMaster')::where('id', $company_id)->first();
    //         $company_name = $company ? $company->name : 'Default_Company';
    //         $company_name = ($company_name);
    //         $doc_type = ($doc_model);
    //         $document_type = ($document_type);
    //         $folder_path = "documents/{$company_name}/{$doc_model}/{$doc_code}/{$document_type}";

    //         $imageName = time() . rand(100, 999) . '.' . $extension;
    //         $image_url = "{$folder_path}/{$imageName}";

    //         Storage::disk('public')->makeDirectory($folder_path);

    //         Storage::disk('public')->putFileAs($folder_path, $uploadedImage, $imageName);

    //         return [$imageName, $image_url];
    //     } else {
    //         return false;
    //     }
    // }
  
}
