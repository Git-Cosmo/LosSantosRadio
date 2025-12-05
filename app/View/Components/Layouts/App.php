<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;
use Illuminate\View\View;

class App extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $metaDescription = null,
        public ?string $ogType = null,
        public ?string $ogImage = null,
        public ?string $ogImageAlt = null,
        public ?int $ogImageWidth = null,
        public ?int $ogImageHeight = null,
        public ?string $canonicalUrl = null,
        public ?string $twitterCard = null,
        public ?array $structuredData = null,
    ) {}

    public function render(): View
    {
        return view('layouts.app');
    }
}
