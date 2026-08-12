# IrskoStudy Sandbox Lead Review Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Validate the local IrskoStudy lead-review workflow from pending submission through approval and local CRM delivery.

**Architecture:** Sandbox n8n uses a dedicated Sanctum token and Docker hostname to call the redesigned local Laravel application. The inactive workflow PATCHes only Laravel review state; Laravel queued listeners perform approved-only local CRM delivery.

**Tech Stack:** Docker Compose, n8n, Laravel 12, Sanctum, Laravel queues, local Irsko CRM.

## Global Constraints

- Keep `n8n_sandbox` workflow inactive and execute it only through Manual Trigger.
- Store runtime tokens only in `/home/david/.secrets/n8n-sandbox/.env`.
- Use `http://crm.irsko.local:8080/api/lead` only in the local redesign `.env`; retain the production endpoint in production configuration.
- Do not change production `n8n` service or add a direct CRM node to the sandbox workflow.
- Do not commit unrelated existing worktree changes.

---

### Task 1: Configure local sandbox network and runtime values

**Files:**
- Modify: `/home/david/Development/docker/compose.n8n.yml:186-214`
- Modify: `/home/david/.secrets/n8n-sandbox/.env`
- Modify: `.env`

**Consumes:** The inactive `irskostudy-lead-review-sandbox.json` export and Laravel Sanctum review routes.
- **Produces:** A sandbox container that can resolve `irskostudy-redesign.local`, call review routes with a dedicated token, and cause Laravel to use local CRM.

- [ ] **Step 1: Confirm the sandbox service contains the local host mapping**

Run: `docker compose -f /home/david/Development/docker/compose.n8n.yml config | grep -A30 n8n_sandbox`

Expected: `irskostudy-redesign.local:172.18.0.1` appears only under `n8n_sandbox`.

- [ ] **Step 2: Recreate only the sandbox service**

Run: `docker compose -f /home/david/Development/docker/compose.n8n.yml up -d --force-recreate n8n_sandbox`

Expected: `server_n8n_sandbox` starts without recreating production `server_n8n`.

- [ ] **Step 3: Verify network and API authentication without exposing the token**

Run: `docker exec server_n8n_sandbox sh -c "getent hosts irskostudy-redesign.local && test -n \"\$IRSKOSTUDY_SANDBOX_SANCTUM_TOKEN\""`

Expected: hostname resolves to `172.18.0.1` and the command exits zero.

### Task 2: Import and execute the inactive review workflow

**Files:**
- Import: `/home/david/Development/n8n-sandbox/files/irskostudy-lead-review-sandbox.json`

**Consumes:** Sandbox environment variables and pending lead endpoint `GET /api/internal/leads/pending?limit=25&mark_fetched=1`.
- **Produces:** One approval PATCH to `/api/internal/leads/{lead}/review` for each qualifying test lead.

- [ ] **Step 1: Import the export through the sandbox UI at `http://127.0.0.1:5688`**

Expected: workflow is named `Sandbox - IrskoStudy Lead Review v1` and remains inactive.

- [ ] **Step 2: Submit one clearly labelled qualifying lead to the local application**

Run: `curl -sS -H "Host: irskostudy-redesign.local" -H "Accept: application/json" -H "Content-Type: application/json" -X POST http://127.0.0.1:8080/contact --data "{\"name\":\"Sandbox IrskoStudy Test\",\"email\":\"sandbox-lead-$(date +%s)@example.test\",\"message\":\"Mám zájem o studium v Irsku na univerzitě.\",\"consent\":true,\"source\":\"sandbox-test\"}"`

Expected: HTTP 201 with `approval_status` set to `pending_review`.

- [ ] **Step 3: Run the workflow through Manual Trigger**

Expected: the execution fetches the lead, evaluates it as approved, and PATCHes `approved` plus `qualified` review values.

- [ ] **Step 4: Verify review state before running the queue**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c "cd /home/sites/irskostudy_cz_redesign && php artisan tinker --execute='dump(App\\Models\\Lead::latest()->first([\"approval_status\", \"qualification_status\", \"qualification_source\", \"n8n_fetched_at\", \"n8n_processed_at\"])->toArray());'"`

Expected: `approved`, `qualified`, `n8n-sandbox`, and both n8n timestamps are present.

### Task 3: Verify approved-only CRM delivery and regression coverage

**Files:**
- Test: `tests/Feature/LeadReviewApiTest.php`
- Test: `tests/Feature/LeadCrmDeliveryTest.php`

**Consumes:** `LeadSubmitted` dispatch from `LeadApprovalService::review()` and queue listeners registered by Laravel auto-discovery.
- **Produces:** One local CRM delivery for the approved lead and passing lead regression tests.

- [ ] **Step 1: Run one local queue worker cycle**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c "cd /home/sites/irskostudy_cz_redesign && php artisan queue:work --once --tries=1"`

Expected: the approved lead listener posts to the configured local CRM endpoint.

- [ ] **Step 2: Verify delivery fields and the local CRM record**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c "cd /home/sites/irskostudy_cz_redesign && php artisan tinker --execute='dump(App\\Models\\Lead::latest()->first([\"crm_status\", \"crm_sent_at\", \"crm_error\"])->toArray());'"`

Expected: `crm_status` is `delivered`, `crm_sent_at` is present, and `crm_error` is null. Confirm one local CRM Deal named `Sandbox IrskoStudy Test` exists.

- [ ] **Step 3: Run lead-focused regression tests**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c "cd /home/sites/irskostudy_cz_redesign && php artisan test tests/Feature/LeadTest.php tests/Feature/LeadReviewApiTest.php tests/Feature/LeadCrmDeliveryTest.php"`

Expected: all selected tests pass.

- [ ] **Step 4: Validate routes, listeners, and whitespace**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c "cd /home/sites/irskostudy_cz_redesign && php artisan route:list --path=internal/leads && php artisan event:list" && git diff --check`

Expected: both Sanctum review routes and approved lead listeners are listed; `git diff --check` has no output.
