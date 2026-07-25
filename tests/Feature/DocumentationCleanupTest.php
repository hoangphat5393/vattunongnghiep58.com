<?php

namespace Tests\Feature;

use Tests\TestCase;

class DocumentationCleanupTest extends TestCase
{
    /** @var list<string> */
    private array $removedDocs = [
        'PLAN-category-recursive.md',
        'docs/frontend-tailwind-v4-canonicalization.md',
        'docs/ui-upgrade.md',
        'bug_hunt_prompt.txt',
        'CHANGELOG.md',
    ];

    public function test_removed_historical_docs_do_not_exist(): void
    {
        foreach ($this->removedDocs as $relativePath) {
            $this->assertFileDoesNotExist(base_path($relativePath));
        }
    }

    public function test_living_doc_index_does_not_link_to_removed_files(): void
    {
        $recommendations = (string) file_get_contents(base_path('RECOMMENDATIONS.md'));

        $this->assertStringNotContainsString('PLAN-category-recursive.md', $recommendations);
        $this->assertStringNotContainsString('frontend-tailwind-v4-canonicalization.md', $recommendations);
        $this->assertStringNotContainsString('ui-upgrade.md', $recommendations);
        $this->assertStringNotContainsString('bug_hunt_prompt.txt', $recommendations);
    }

    public function test_core_documentation_files_remain(): void
    {
        $this->assertFileExists(base_path('README.md'));
        $this->assertFileExists(base_path('RECOMMENDATIONS.md'));
        $this->assertFileExists(base_path('docs/IMPROVEMENT_PLAYBOOK.md'));
        $this->assertFileExists(base_path('docs/BACKEND_AUDIT_PLAYBOOK.md'));
        $this->assertFileExists(base_path('docs/PROJECT_ANALYSIS.md'));
        $this->assertFileExists(base_path('docs/CHANGE_LOG.md'));
        $this->assertFileExists(base_path('MASTER.md'));
        $this->assertFileExists(base_path('docs/TABLE_GLOSSARY.md'));
        $this->assertFileExists(base_path('docs/ROUTE_GLOSSARY.md'));
        $this->assertFileExists(base_path('docs/DB_AUDIT.md'));
        $this->assertFileExists(base_path('docs/REFACTOR_3NONG_PLAYBOOK.md'));
    }

    public function test_backend_audit_playbook_lists_p0_items(): void
    {
        $playbook = (string) file_get_contents(base_path('docs/BACKEND_AUDIT_PLAYBOOK.md'));

        $this->assertStringContainsString('BACK-001', $playbook);
        $this->assertStringContainsString('BACK-018', $playbook);
        $this->assertStringContainsString('BACK-001 … BACK-018 hoàn tất', $playbook);
    }

    public function test_stale_root_changelog_is_removed(): void
    {
        $this->assertFileDoesNotExist(base_path('CHANGELOG.md'));
        $this->assertFileExists(base_path('docs/CHANGE_LOG.md'));
    }
}
