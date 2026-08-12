<?php

namespace Tests\Feature;

use Tests\TestCase;

class SandboxLeadReviewWorkflowTest extends TestCase
{
    public function test_sandbox_workflow_uses_the_table_driven_local_llm_route(): void
    {
        $workflow = json_decode(file_get_contents('/home/david/Development/n8n-sandbox/files/irskostudy-lead-review-sandbox.json'), true, 512, JSON_THROW_ON_ERROR);
        $nodes = collect($workflow['nodes']);

        $this->assertTrue($nodes->contains('name', 'Fetch AI Route From DB'));
        $this->assertTrue($nodes->contains('name', 'Local LLM Lead Check'));
        $this->assertFalse($nodes->contains(function (array $node): bool {
            return str_contains(json_encode($node['parameters'] ?? []), 'http://llama_cpp:8080');
        }));

        $pollNode = $nodes->firstWhere('name', 'Poll Sandbox Test Leads');
        $this->assertNotNull($pollNode);
        $this->assertStringContainsString('source=sandbox-test', $pollNode['parameters']['url']);
    }
}
