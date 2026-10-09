<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DecryptEncryptedPayload
{
   public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('encrypt-request') || $request->is('encrypt-request')) {
            return $next($request);
        }
        if ($request->isMethod('post') && $request->has('payload')) {
            try {

                $decryptedJson = decrypt($request->input('payload'));
                //dd('hhh',$decryptedJson);

                $decryptedArray = json_decode($decryptedJson, true);
                if (!is_array($decryptedArray)) {
                    throw new \Exception('Invalid decrypted payload structure');
                }
                $request->replace($decryptedArray);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or corrupt payload.',
                    'error' => $e->getMessage(),
                ], 400);
            }
        }

        return $next($request);
    }
}

