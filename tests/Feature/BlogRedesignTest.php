<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BlogRedesignTest extends TestCase
{
    public function test_blog_index_uses_irsko_redesign_sections(): void
    {
        $posts = collect([
            $this->article('featured-clanek', 'Jak se připravit na studium v Irsku'),
            $this->article('druhy-clanek', 'Ubytování a první dny v Irsku'),
            $this->article('treti-clanek', 'Přihlášky krok za krokem'),
        ]);

        $paginator = new LengthAwarePaginator($posts, $posts->count(), 10, 1, [
            'path' => route('blog'),
        ]);

        $html = (string) view('blog.index', ['posts' => $paginator])->render();

        $this->assertStringContainsString('data-redesign-page="blog-index"', $html);
        $this->assertStringContainsString('data-redesign-section="blog-guide-hero"', $html);
        $this->assertStringContainsString('data-redesign-section="blog-featured"', $html);
        $this->assertStringContainsString('data-redesign-section="blog-grid"', $html);
        $this->assertStringContainsString('Praktický průvodce', $html);
        $this->assertStringContainsString('Číst článek', $html);
    }

    public function test_blog_post_uses_irsko_redesign_article_layout(): void
    {
        $post = $this->article('jak-studovat-v-irsku', 'Jak studovat v Irsku');
        $post->content = '<h2>Začátek plánování</h2><p>Obsah článku.</p>';

        $html = (string) view('blog.show', ['post' => $post])->render();

        $this->assertStringContainsString('data-redesign-page="blog-post"', $html);
        $this->assertStringContainsString('data-redesign-section="blog-post-hero"', $html);
        $this->assertStringContainsString('data-redesign-section="blog-post-content"', $html);
        $this->assertStringContainsString('class="blog-post type-text-md text-brand-dark-green"', $html);
        $this->assertStringContainsString('Zpět na blog', $html);
        $this->assertStringContainsString('Praktický průvodce ke studiu v Irsku', $html);
    }


    public function test_blog_post_can_render_more_article_cards(): void
    {
        $post = $this->article('jak-studovat-v-irsku', 'Jak studovat v Irsku');
        $morePosts = collect([
            $this->article('dalsi-clanek', 'Další článek o Irsku'),
            $this->article('prakticke-tipy', 'Praktické tipy pro studenty'),
        ]);

        $html = (string) view('blog.show', [
            'post' => $post,
            'morePosts' => $morePosts,
        ])->render();

        $this->assertStringContainsString('data-redesign-section="blog-post-more"', $html);
        $this->assertStringContainsString('Další články', $html);
        $this->assertStringContainsString('Další článek o Irsku', $html);
        $this->assertStringContainsString('Praktické tipy pro studenty', $html);
    }


    public function test_blog_cards_use_higher_resolution_images_and_no_green_fallbacks(): void
    {
        $indexTemplate = file_get_contents(resource_path('views/blog/index.blade.php'));
        $showTemplate = file_get_contents(resource_path('views/blog/show.blade.php'));

        $this->assertStringContainsString('$blogImageSet($article, 3)', $indexTemplate);
        $this->assertStringContainsString('$blogImageSet($item, 3)', $showTemplate);
        $this->assertStringNotContainsString('bg-brand-dark-green', $indexTemplate);
        $this->assertStringNotContainsString('bg-brand-dark-green', $showTemplate);
        $this->assertStringNotContainsString('bg-brand-light-green', $indexTemplate);
        $this->assertStringNotContainsString('bg-brand-light-green', $showTemplate);
    }

    private function article(string $slug, string $title): object
    {
        return (object) [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => 'Krátký úvod k článku o studiu a životě v Irsku.',
            'content' => '<p>Text článku o Irsku.</p>',
            'featured_image' => null,
            'image' => null,
            'created_at' => Carbon::parse('2026-08-04'),
            'categories' => collect([(object) ['name' => 'Studium']]),
        ];
    }
}
