<?php

// For Auth Response
if (!function_exists('authResponse')) {
    function authResponse($token = null, $user = null, $message = null, $status = 200)
    {
        return response()->json([
            'id' => $user->id,
            'nursery_id' => $user->nursery->id ?? $user->parent->nursery_id  ?? null,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles[0]->name ?? null,
            'token' => $token,
            'message' => $message,
            'status' => $status,
            'expire_in' => auth()->factory()->getTTL(),
        ], $status);
    }
}

// For Content Response
if (!function_exists('contentResponse')) {
    function contentResponse($content, $message = null, $status = 200)
    {
        return response()->json([
            'content' => $content,
            'message' => $message,
            'status' => $status,
        ], $status);
    }
}

// For Success Response
if (!function_exists('messageResponse')) {
    function messageResponse($message = null, $status = 200)
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
        ], $status);
    }
}


// ============================================================== //    
// For Success Response
if (!function_exists('arrayUnest')) {
    function arrayUnest($arrayUnest, $unset)
    {
        $nurseryArray = $arrayUnest->toArray();
        $combinedArray = array_merge($nurseryArray, $nurseryArray[$unset]);
        unset($combinedArray[$unset]);
        return $combinedArray;
    }
}


// For Failed Response
if (!function_exists('fetchOne')) {
    function fetchOne($message)
    {
        return 'View ' . $message . ' Successfully';
    }
}

// For Failed Response
if (!function_exists('fetchAll')) {
    function fetchAll($message)
    {
        return 'Fetches ' . $message . ' Successfully';
    }
}
