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
        $this->assertStringContainsString('href="' . route('home') . '"', $html);
        $this->assertStringContainsString('Domů', $html);
        $this->assertStringContainsString('Blog', $html);
        $this->assertStringContainsString('suitcase-rolling.svg', $html);
        $this->assertStringContainsString('studenty z Česka', $html);
        $this->assertStringContainsString('Výběr školy a oboru', $html);
        $this->assertStringContainsString('Přihláška z Česka', $html);
        $this->assertStringContainsString('Život studenta v Irsku', $html);
        $this->assertStringContainsString('h-[320px]', $html);
        $this->assertStringContainsString('h-56', $html);
        $this->assertStringContainsString('type-display-sm', $html);
        $this->assertStringContainsString('type-display-xl-decorative', $html);
        $this->assertStringContainsString('transition-move-figma', $html);
        $this->assertStringContainsString('flex flex-1 flex-col', $html);
    }

    public function test_blog_index_prioritizes_study_articles_for_students(): void
    {
        $posts = collect([
            $this->article('general-irsko', 'St. Patrick’s Day v Irsku'),
            $this->article('study-ireland', 'Jak vybrat školu pro studium v Irsku'),
        ]);

        $paginator = new LengthAwarePaginator($posts, $posts->count(), 10, 1, [
            'path' => route('blog'),
        ]);

        $html = (string) view('blog.index', ['posts' => $paginator])->render();

        $featuredStart = strpos($html, 'data-redesign-section="blog-featured"');
        $studyPosition = strpos($html, 'Jak vybrat školu pro studium v Irsku', $featuredStart);
        $generalPosition = strpos($html, 'St. Patrick’s Day v Irsku', $featuredStart);

        $this->assertLessThan($generalPosition, $studyPosition);
    }

    public function test_blog_footer_has_no_top_gap_after_back_to_top(): void
    {
        $footer = file_get_contents(resource_path('views/components/footer.blade.php'));

        $this->assertStringContainsString('$isBlogPage', $footer);
        $this->assertStringContainsString('pt-0', $footer);
    }

    public function test_blog_back_to_top_merges_into_footer_treatment(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $this->assertStringContainsString('<x-back-to-top />', $layout);

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
        $this->assertStringContainsString('Praktický průvodce pro studenty z Česka', $html);
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

        $this->assertStringContainsString('$blogImageSet($featured, 4)', $indexTemplate);
        $this->assertStringContainsString('$blogImageSet($article, 4)', $indexTemplate);
        $this->assertStringContainsString('$blogImageSet($item, 4)', $showTemplate);
        $this->assertStringNotContainsString('bg-gradient', $indexTemplate);
        $this->assertStringNotContainsString('bg-gradient', $showTemplate);
        $this->assertStringContainsString('suitcase-rolling.svg', $indexTemplate);
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
