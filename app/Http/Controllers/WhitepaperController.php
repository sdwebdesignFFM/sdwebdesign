<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Contracts\View\View;

class WhitepaperController extends Controller
{
    public function platformVsStandard(): View
    {
        $title = 'Eigene Plattform oder Standard-Software? — Whitepaper für Mittelständler';
        $description = 'Entscheidungs-Leitfaden für Mittelständler: Standard-Software, DIY-/AI-Builder oder eigene Plattform? Strukturiert, neutral, ohne Sales-Pitch. Kostenloser PDF-Download.';

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setCanonical(url('/whitepaper/eigene-plattform-vs-standard-software'));

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl(url('/whitepaper/eigene-plattform-vs-standard-software'));
        OpenGraph::setType('article');

        return view('pages.whitepaper.platform-vs-standard');
    }
}
