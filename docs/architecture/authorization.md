# Authorization Architecture for CareerConnectBD

## Authorization Overview

CareerConnectBD will use Laravel's built-in authentication foundation from Breeze as the entry point and add a role-based authorization layer on top of it. This approach keeps the system simple, maintainable, and scalable while preserving the current Breeze authentication flow.

The design will support three primary user roles:

- Admin
- Employer
- Job Seeker

The architecture is intentionally structured so additional roles can be introduced later without requiring a redesign of the core authorization approach.

## User Roles

The application will define the following roles:

- Admin: full platform administration, user moderation, and configuration access.
- Employer: can manage company-related content, post job opportunities, and manage hiring-related actions.
- Job Seeker: can manage a personal profile, apply to jobs, and manage job-seeking activities.

## Planned Database Structure

The initial authorization design is planned around the following concepts:

- users table: existing authentication and profile data.
- roles table: stores available roles such as admin, employer, and job seeker.
- role_user table: links users to one or more roles.
- permissions table: stores fine-grained permissions for future expansion.
- permission_role table: links roles to permissions.

This structure is intentionally future-proof. The initial implementation can use role-based checks, while permissions can be added later without major changes.

## Relationship Overview

The planned relationship model is:

- User belongs to many Roles.
- Role belongs to many Users.
- Role has many Permissions.
- Permission belongs to many Roles.

A simple role assignment model is sufficient for Milestone 2.2, while permissions remain available for future expansion.

## Middleware Flow

The authorization layer will use middleware to protect routes based on role checks.

Planned flow:

1. User authenticates using Breeze authentication.
2. Middleware checks whether the authenticated user has the required role.
3. If authorized, the request continues.
4. If not authorized, the user is redirected or denied access based on the route policy.

This keeps route protection centralized and easy to maintain.

## Login Flow

The current Breeze login flow remains the authentication entry point.

Planned login authorization flow:

1. User logs in through Breeze.
2. Session is created as usual.
3. Role information is resolved from the user record.
4. The user is redirected according to the role-based redirect strategy.

## Route Protection Flow

Routes will be protected using role-based middleware and policy checks where appropriate.

Planned approach:

- Public routes remain accessible to everyone.
- Admin routes are protected by admin middleware.
- Employer routes are protected by employer middleware.
- Job Seeker routes are protected by job seeker middleware.
- Shared routes may use a general authenticated middleware plus role-specific checks.

## Security Considerations

The authorization design will follow Laravel best practices:

- Authentication remains handled by Breeze.
- Authorization is enforced on the server side.
- Middleware and policies will be the primary enforcement points.
- Mass assignment will remain protected through proper model definitions.
- Role assignment should be controlled by trusted administrative actions.
- Role checks should never rely only on client-side logic.
- Future permissions should also be enforced server-side.

## Laravel Best Practices

The design will align with Laravel conventions by:

- keeping authorization logic centralized in middleware and policies,
- using descriptive naming for roles and authorization helpers,
- keeping business rules in dedicated services or policy classes when implementation begins,
- avoiding custom logic in controllers where authorization checks can be centralized,
- preserving the current Breeze authentication behavior.

## Future Permission Expansion Strategy

The architecture is designed to expand gradually:

1. Start with roles only.
2. Introduce a permissions table later.
3. Map permissions to roles.
4. Add policy-based authorization checks for domain actions.
5. Optionally introduce gates or custom authorization helpers for advanced use cases.

This ensures the initial design remains lightweight while remaining ready for larger authorization requirements later.
