<?php

namespace App\Livewire\Patient;

use Livewire\Component;

class FeaturedArticles extends Component
{
    public $featured_articles;

    public function getFeaturedArticles()
    {
        $this->featured_articles = \App\Models\Article::where('is_featured', true)->get()->shuffle()->take(2);
        return $this->featured_articles;
    }
    public function render()
    {
        return view('livewire.patient.featured-articles',[
            'articles' => $this->getFeaturedArticles(),
        ]);
    }
}
