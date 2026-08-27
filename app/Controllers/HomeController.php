<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\CoreValue;
use App\Models\Faculty;
use App\Models\Leadership;
use App\Models\News;
use App\Models\Program;
use App\Models\Slider;
use App\Models\Testimonial;
final class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home', [
            'pageTitle'=>'Pakistan Cambridge School Hafizabad',
            'metaDescription'=>'Pakistan Cambridge School Hafizabad — a modern learning community focused on academic excellence, character, confidence and leadership.',
            'sliders'=>Slider::published(),
            'programs'=>array_slice(Program::published(),0,6),
            'values'=>array_slice(CoreValue::published(),0,4),
            'faculty'=>array_slice(Faculty::published(),0,4),
            'leadership'=>Leadership::withMessages(2),
            'news'=>News::latest(3),
            'testimonials'=>array_slice(Testimonial::published(),0,3),
        ]);
    }
}
