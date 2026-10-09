<?php

    function validationError($error, $redirectBack = true)
    {

        $allMessages = '';
        $messages = $error;
        foreach ($messages->getMessages() as $field => $errors) {
            foreach ($errors as $error) {
                $allMessages.= "$field : $error";
            }
        }

        if ($redirectBack) {
            return back()->with(['error'=> $allMessages])->withInput();
        }

        return response()->json([
            'status' => false,
            'type' => 'validation',
            'error' => $allMessages,
        ]);
    }

    // General error
    function error($message = 'Something went wrong.', $redirectBack = true, $status = 500)
    {
        if ($redirectBack) {
            //dd('ggg');
            return redirect()->back()->with('error', $message);
        }

        return response()->json([
            'status' => 'error',
            'error' => $message,
        ], $status);
    }

    // Success response
    function success($message = 'Operation successful.', $redirectTo = null, $data = [])
    {
        if ($redirectTo) {
            return redirect($redirectTo)
                ->with('success', $message);
        }

        return response()->json([
            'status' => 'success',
            'success' => $message,
            'data' => $data,
        ], 200);
    }

    // Direct JSON response
    function json($data = [], $message = '', $status = 200)
    {
        return response()->json([
            'status' => $status === 200 ? 'success' : 'error',
            'success' => $message,
            'data' => $data,
        ], $status);
    }
