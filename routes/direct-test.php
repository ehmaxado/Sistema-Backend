<?php

Route::post('/direct-test', function (Illuminate\Http\Request $request) {
    return response()->json([
        'raw_body' => $request->getContent(),
        'all_data' => $request->all(),
        'json_data' => $request->json()->all(),
        'headers' => [
            'Content-Type' => $request->header('Content-Type'),
            'Accept' => $request->header('Accept'),
        ]
    ]);
});
