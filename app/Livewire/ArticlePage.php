<?php

namespace App\Livewire;

use Livewire\Component;

class ArticlePage extends Component
{
    public $article_id;
    public $article;
    public function mount($id)
    {
        $this->article_id = $id;

        $this->article = \App\Models\Article::find($this->article_id);
        if (!$this->article) {
            abort(404);
        }
        $this->article->increment('views');
        $this->article->save();
    }   

    public function like()
    {
        $this->article->increment('likes');
        $this->article->save();
    }
    public function unlike()
    {
        if ($this->article->likes > 0) {
            $this->article->decrement('likes');
            $this->article->save();
        }
    }
    public function render()
    {
        return view('livewire.article-page');
    }
}
