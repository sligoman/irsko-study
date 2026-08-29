<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class AiblogPlacementRoutesTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_guide_and_news_listings_only_query_their_matching_aiblog_types(): void
    {
        $guideQuery = $this->listingQueryForContentType(1);
        $newsQuery = $this->listingQueryForContentType(2);

        Mockery::mock('alias:Sligoman\AiblogApiWeb\Models\AiblogPost')
            ->shouldReceive('with')
            ->twice()
            ->with('contentType')
            ->andReturn($guideQuery, $newsQuery);

        $this->get('/prakticky-pruvodce')
            ->assertOk()
            ->assertViewHas('posts', fn (LengthAwarePaginator $posts) => $posts->isEmpty());

        $this->get('/novinky')
            ->assertOk()
            ->assertViewHas('posts', fn (LengthAwarePaginator $posts) => $posts->isEmpty());
    }

    private function listingQueryForContentType(int $contentType): object
    {
        $query = Mockery::mock();
        $paginator = new LengthAwarePaginator(collect(), 0, 10, 1, ['path' => '/']);

        $query->shouldReceive('where')
            ->once()
            ->with('content_type_id', $contentType)
            ->andReturnSelf();
        $query->shouldReceive('orderBy')->once()->with('created_at', 'desc')->andReturnSelf();
        $query->shouldReceive('paginate')->once()->with(10)->andReturn($paginator);

        return $query;
    }
}
