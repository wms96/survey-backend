<?php

namespace App\Http\Controllers;

use App\Models\SurveysModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SurveysController extends Controller
{
    private SurveysModel $surveyModel;

    public function __construct()
    {
        $this->surveyModel = new SurveysModel();
    }

    function index(Request $request): JsonResponse
    {
        $surveys = $this->surveyModel->getSurveys($request->get('keyword', false));

        return new JsonResponse($surveys->toArray());
    }

    function show($code): JsonResponse
    {
        return new JsonResponse($this->surveyModel->find($code));
    }
}
