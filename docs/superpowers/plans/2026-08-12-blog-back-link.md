# Blog Back Link Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make the blog-post return link visually consistent with the redesign link language.

**Architecture:** Keep the existing blog route and replace only its presentation with an inline left-arrow variant. A feature assertion protects the required label, circular arrow, and reversed hover movement.

**Tech Stack:** Laravel Blade, Tailwind CSS, PHPUnit.

## Global Constraints

- Preserve `route('blog')` and native anchor semantics.
- Use the existing brand colors and motion utility classes.

---

### Task 1: Replace the Blog Back Link

**Files:**
- Modify: `resources/views/blog/show.blade.php:37`
- Modify: `tests/Feature/BlogRedesignTest.php:83-96`

- [ ] Add a failing assertion for the left-arrow treatment.
- [ ] Replace the plain anchor with an inline group link containing a green circular left chevron.
- [ ] Run `php artisan test tests/Feature/BlogRedesignTest.php`.
