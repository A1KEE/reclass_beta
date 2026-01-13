<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

// Applicant form
Route::get('/applicants/create', [ApplicantController::class, 'create']);
Route::post('/applicants', [ApplicantController::class, 'store'])->name('applicants.store');

// Get QS via AJAX
Route::get('/get-qs', [ApplicantController::class, 'getQS'])->name('get.qs');

// Validate uploaded education PDF
Route::post('/validate-education-file', function(Request $request){
    if(!$request->hasFile('education_file')) {
        return response()->json(['status'=>'invalid']);
    }

    $file = $request->file('education_file');

    if($file->getClientOriginalExtension() !== 'pdf'){
        return response()->json(['status'=>'invalid']);
    }

    $parser = new Parser();
    $pdf = $parser->parseFile($file->getPathname());
    $text = strtolower($pdf->getText());

    // Keywords for matching
    $bachelorKeywords = [
        'bachelor of education',
        'bachelor of secondary education',
        'bsed','beed','b.ed','b.s.ed',
        'bachelor in secondary education'
    ];

    $masterKeywords = [
        'master of education',
        'master of arts in education',
        'maed','m.ed','master’s degree',
        'educational leadership','educational management'
    ];

    $invalidKeywords = [
        'loan','co-borrower','housing','agreement','receipt',
        'invoice','contract','salary','certificate of employment'
    ];

    if(preg_match('/'.implode('|',$invalidKeywords).'/',$text)){
        return response()->json(['status'=>'invalid']);
    } elseif(preg_match('/'.implode('|',$masterKeywords).'/',$text)){
        return response()->json(['status'=>'valid','level'=>'master']);
    } elseif(preg_match('/'.implode('|',$bachelorKeywords).'/',$text)){
        return response()->json(['status'=>'valid','level'=>'bachelor']);
    } else {
        return response()->json(['status'=>'invalid']);
    }
});
 