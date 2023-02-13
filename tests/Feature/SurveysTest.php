<?php

namespace Tests\Feature;

use App\Models\SurveysModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;


class SurveysTest extends TestCase
{
    protected SurveysModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new SurveysModel();
    }

    public function testReadDataReturnsCollection()
    {
        $this->assertInstanceOf(Collection::class, $this->model->getSurveys());
    }
}
