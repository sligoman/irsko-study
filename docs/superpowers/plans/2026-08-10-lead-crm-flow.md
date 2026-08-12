# Lead CRM Flow Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development or superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Make irskostudy store every enquiry pending n8n review and deliver only approved leads to the shared `crm.irsko.ie/api/lead` CRM.

**Architecture:** Keep the existing `/contact` endpoint and Vue forms, add a Form Request and rate limiter, persist lifecycle/attribution/consent fields, and expose Sanctum-protected n8n pending/review endpoints. The first approval dispatches an after-commit `LeadSubmitted` event; queued listeners then send the internal email, applicant confirmation, and CRM payload.

**Tech Stack:** Laravel 12, Eloquent, Laravel HTTP client, queued listeners, Vue 3, PHPUnit.

## Global Constraints

- CRM URL is `https://crm.irsko.ie/api/lead` and must be configurable through `CRM_LEAD_URL`.
- Never commit CRM credentials or `.env` values.
- Existing `/contact` JSON response compatibility remains intact.
- Existing Vue form entry points remain usable.
- Tests use fakes for mail, events, queues, and HTTP.

### Task 1: Lead schema and validation

**Files:**
- Create: `app/Http/Requests/StoreLeadRequest.php`
- Create: `database/migrations/<timestamp>_add_lifecycle_fields_to_leads_table.php`
- Modify: `app/Models/Lead.php`
- Test: `tests/Feature/LeadTest.php`

- [ ] Add failing tests for consent, source metadata, default pending status, and validation limits.
- [ ] Run focused tests and confirm expected failures.
- [ ] Add migration fields: `source`, `source_ip`, `user_agent`, `consent_at`, `status`, `crm_status`, `crm_response`, `crm_sent_at`, `crm_error`, and indexes.
- [ ] Implement `StoreLeadRequest` validation and normalize email.
- [ ] Run focused tests.

### Task 2: Reliable event listeners and CRM handoff

**Files:**
- Modify: `app/Events/LeadSubmitted.php`
- Create: `app/Listeners/SendLeadStoredNotification.php`
- Create: `app/Listeners/SendLeadNotification.php`
- Create: `app/Listeners/PostLeadToCRM.php`
- Modify: `app/Providers/AppServiceProvider.php` or bootstrap event registration
- Modify: `config/services.php`
- Test: `tests/Feature/LeadCrmDeliveryTest.php`

- [ ] Add failing tests for queued listener registration and exact CRM JSON payload.
- [ ] Implement after-commit event dispatch.
- [ ] Implement queued internal mail and applicant confirmation listeners.
- [ ] Implement CRM listener with `Http::post`, timeout, response tracking, and thrown failures for queue retry.
- [ ] Run focused tests.

### Task 3: Browser submission protection and attribution

**Files:**
- Modify: `resources/views/layouts/app.blade.php`
- Modify: `app/Http/Controllers/LeadController.php`
- Modify: `routes/web.php`
- Modify: `resources/js/components/contact-form.vue`
- Test: `tests/Feature/LeadTest.php`, `tests/Feature/ContactFormComponentTest.php`

- [ ] Add failing test for CSRF meta, route throttle, and JSON response shape.
- [ ] Add CSRF meta tag and `throttle:leads` middleware.
- [ ] Use `StoreLeadRequest`, capture source/page/IP/user agent/consent, dispatch after commit, and return `{lead, message}`.
- [ ] Add consent checkbox and hidden source/referrer fields to both form variants.
- [ ] Run focused and full redesign tests.

### Task 4: Verification and documentation

- [ ] Run focused lead tests.
- [ ] Run full redesign regression tests.
- [ ] Run `git diff --check` and PHP syntax checks.
- [ ] Verify `/contact` browser HTML contains CSRF token and consent field.
- [ ] Document required `CRM_LEAD_URL` and queue worker configuration in `README.md`.
