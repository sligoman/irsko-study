# Unified Lead Form Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Align the homepage and floating lead forms with the local Irsko.ie consultation experience through one shared Vue component.

**Architecture:** `contact-form.vue` provides inline and modal variants with shared request behavior. Blade owns homepage composition and global modal visibility. Existing lead persistence stays unchanged.

**Tech Stack:** Laravel Blade, Vue 3, Tailwind CSS, Vite, PHPUnit.

## Global Constraints

- Keep the `/contact` request contract and lead-review workflow unchanged.
- Use a local image asset only; do not hotlink the reference image.
- Keep the inline form detailed and the modal form compact, matching local Irsko.ie.

---

### Task 1: Lock visual and component contract with regression tests

**Files:**
- Modify: `tests/Feature/ContactFormComponentTest.php`
- Modify: `tests/Feature/FloatingLeadModalTest.php`
- Test: `tests/Feature/HomepageRedesignTest.php`

- [ ] Add assertions for `variant="inline"`, `variant="modal"`, modal dialog semantics, local image rendering, and no legacy `position="floating"` branch.
- [ ] Run the focused tests and verify they fail before implementation.

### Task 2: Consolidate the form component

**Files:**
- Modify: `resources/js/components/contact-form.vue`

- [ ] Add a `variant` prop with inline and modal field sets.
- [ ] Share request, error, success, and field behavior; selecting a program prepopulates the existing message field.
