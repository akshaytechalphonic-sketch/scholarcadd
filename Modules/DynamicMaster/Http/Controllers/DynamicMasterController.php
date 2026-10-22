<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Services\DynamicMasterService;
use Modules\DynamicMaster\Http\Requests\DynamicMasterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\returnArgument;

class DynamicMasterController extends Controller
{
    protected DynamicMasterService $service;

    public function __construct(DynamicMasterService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->all($request);
    }

    protected function get_parent_list(Request $request)
    {
        return $this->service->get_parent_list($request);
    }

    protected function edit(Request $request)
    {
        return $this->service->edit($request);
    }

    protected function store(DynamicMasterRequest $request)
    {
        return $this->service->store($request);
    }

    protected function update(Request $request)
    {
        return $this->service->update($request);
    }

    protected function delete(DynamicMasterRequest $request)
    {
        return $this->service->delete($request);
    }


    protected function child_index(Request $request)
    {
        return $this->service->child_all($request);
    }
    protected function switch_company(Request $request)
    {
        return $this->service->switch_company($request);
    }

    protected function secure_file(Request $request)
    {
        $filePath = urldecode($request->path ?? '');
        $filePath = ltrim(trim($filePath), '/');

        if (!empty($filePath)) {
            // 1. Check local storage (ensure it's not a Git LFS text pointer)
            $localFullPath = storage_path("app/public/{$filePath}");
            $isLfsPointer = false;
            if (file_exists($localFullPath) && is_file($localFullPath)) {
                $head = @file_get_contents($localFullPath, false, null, 0, 40);
                if ($head && str_starts_with($head, 'version https://git-lfs')) {
                    $isLfsPointer = true;
                }
            }

            if (!$isLfsPointer && file_exists($localFullPath) && is_file($localFullPath)) {
                $mimeType = @mime_content_type($localFullPath) ?: 'image/png';
                return response()->file($localFullPath, [
                    'Access-Control-Allow-Origin' => '*',
                    'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                    'Content-Type' => $mimeType,
                ]);
            }

            // Also check public path directly
            $publicFullPath = public_path($filePath);
            $isPublicLfs = false;
            if (file_exists($publicFullPath) && is_file($publicFullPath)) {
                $head = @file_get_contents($publicFullPath, false, null, 0, 40);
                if ($head && str_starts_with($head, 'version https://git-lfs')) {
                    $isPublicLfs = true;
                }
            }

            if (!$isPublicLfs && file_exists($publicFullPath) && is_file($publicFullPath)) {
                $mimeType = @mime_content_type($publicFullPath) ?: 'image/png';
                return response()->file($publicFullPath, [
                    'Access-Control-Allow-Origin' => '*',
                    'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                    'Content-Type' => $mimeType,
                ]);
            }

            // 2. Try fetching from live demo/production server if available
            try {
                $remoteUrl = "https://api.demotechalphonic.tech/master/secure-documents?path=" . urlencode($filePath);
                $ctx = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'timeout' => 3,
                        'follow_location' => 1,
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);
                $remoteContent = @file_get_contents($remoteUrl, false, $ctx);
                if ($remoteContent !== false && strlen($remoteContent) > 0) {
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $detectedMime = $finfo->buffer($remoteContent);
                    if (str_starts_with($detectedMime, 'image/')) {
                        // Cache locally for future requests
                        @mkdir(dirname($localFullPath), 0777, true);
                        @file_put_contents($localFullPath, $remoteContent);

                        return response($remoteContent, 200, [
                            'Content-Type' => $detectedMime,
                            'Access-Control-Allow-Origin' => '*',
                            'Cache-Control' => 'public, max-age=86400',
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                // fall through to placeholder
            }
        }

        // 3. Fallback: Return a clean SVG placeholder image (NEVER HTML to prevent ERR_BLOCKED_BY_ORB)
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="240" viewBox="0 0 400 240">
            <rect width="100%" height="100%" fill="#F1F5F9"/>
            <rect x="20" y="20" width="360" height="200" rx="12" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" stroke-dasharray="4 4"/>
            <circle cx="200" cy="100" r="32" fill="#E2D2FF"/>
            <path d="M190 100 L198 108 L212 92" stroke="#882CFB" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            <text x="200" y="155" font-family="-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif" font-size="14" font-weight="600" fill="#475569" text-anchor="middle">ScholarAcad Image</text>
            <text x="200" y="175" font-family="-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif" font-size="11" fill="#94A3B8" text-anchor="middle">Preview Placeholder</text>
        </svg>';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Access-Control-Allow-Origin' => '*',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
    protected function get_child(Request $request)
    {
        return $this->service->get_child($request);
    }

    protected function sql_create(Request $request)
    {
        return view('dynamicmaster::sql');
    }
    protected function sql_store(Request $request)
    {
        try {
            DB::statement($request->sql_command);

            return back()->with('success', "Done");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function planCreate()
    {
        return view('');
    }





}
