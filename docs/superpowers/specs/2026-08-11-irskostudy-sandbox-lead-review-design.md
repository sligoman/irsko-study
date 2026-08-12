# IrskoStudy Sandbox Lead Review

## Scope

Validate the redesigned IrskoStudy lead pipeline locally without exposing production n8n or CRM endpoints to sandbox activity.

## Design

- The `n8n_sandbox` Docker service resolves `irskostudy-redesign.local` to the Docker host. No production n8n service changes.
- Sandbox-only environment variables provide the IrskoStudy base URL, a dedicated local Sanctum token, and the local CRM endpoint. The token remains outside the repository.
- The local redesigned application uses `http://crm.irsko.local:8080/api/lead`; production continues to use `https://crm.irsko.ie/api/lead`.
- The n8n export remains inactive. Manual execution fetches pending leads, applies deterministic rules, and PATCHes the local review API. It has no CRM node.
- Laravel dispatches approved leads once; existing queued listeners send notification email and create the local CRM Deal.

## Verification

1. Import the export into sandbox n8n without enabling it.
2. Submit one clearly labelled local test lead with study-in-Ireland intent.
3. Execute the workflow from Manual Trigger.
4. Run the Laravel queue once and verify approved lifecycle fields plus one local CRM Deal.
5. Run the lead feature tests and configuration checks.

## Safety

The workflow remains inactive after the test. No production service, endpoint, or token is used. A rejected test lead must not create a CRM Deal.
