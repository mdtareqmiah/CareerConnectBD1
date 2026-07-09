# Job Seeker Module Architecture

## Module Overview
The Job Seeker module will provide a structured profile experience for users with the job-seeker role. The design is intentionally modular so it can evolve into a full profile management system, application tracking feature, and future API support without changing the core authorization foundation.

## Profile Structure
The initial profile design should support the following sections:

- Personal Information
  - full name
  - date of birth
  - phone number
  - gender
  - country / city
  - address
- Professional Summary
  - short biography
  - headline
- Education
  - institution
  - degree
  - field of study
  - start and end dates
  - description
- Experience
  - company name
  - position
  - location
  - start and end dates
  - responsibilities
- Skills
  - skill name
  - proficiency level
- Languages
  - language name
  - proficiency level
- Certifications
  - title
  - issuing organization
  - issue date
  - expiry date
- Projects
  - project name
  - description
  - technologies used
  - project URL
- Portfolio Links
  - label
  - URL
- Resume
  - file upload
  - file name
  - storage path
- Profile Photo
  - image upload
  - storage path
- Social Links
  - platform
  - URL
- Career Preference
  - target role
  - job category
  - work type
  - location preference
- Expected Salary
  - desired salary range
- Preferred Job Category
  - category or tag
- Preferred Job Type
  - full-time / part-time / contract / remote / hybrid
- Preferred Location
  - city / region / country
- Availability
  - immediate / notice period / specific date

## Database Planning
The module should be implemented with a normalized structure in future migrations:

- A dedicated job seeker profile record linked to the user.
- Supporting tables for education, experience, skills, languages, certifications, projects, portfolio links, and social links.
- Separate file storage references for resume and profile photo.
- Optional JSON or dedicated tables for preferences and availability if the domain grows.

## Relationship Planning
- One user may have one job seeker profile.
- A job seeker profile may have many education entries.
- A job seeker profile may have many experience entries.
- A job seeker profile may have many skills, languages, certifications, projects, portfolio links, and social links.
- Resume and profile photo should be stored as file references rather than binary content in the database.

## Future API Structure
When APIs are introduced, the module can expose endpoints such as:

- GET /api/job-seekers/{id}
- POST /api/job-seekers/profile
- PUT /api/job-seekers/profile
- POST /api/job-seekers/education
- PUT /api/job-seekers/education/{id}
- DELETE /api/job-seekers/education/{id}

## Future Service Structure
The service layer should keep business logic isolated from controllers and requests:

- app/Services/JobSeeker/ProfileService
- app/Services/JobSeeker/EducationService
- app/Services/JobSeeker/ExperienceService
- app/Services/JobSeeker/UploadService

## Validation Strategy
Validation should be handled through dedicated form requests or request classes in later implementation steps:

- required fields for core profile information
- optional fields for secondary sections
- file validation for resume and photo uploads
- URL validation for links
- date validation for experience and education ranges

## File Upload Strategy
- Store uploaded resumes and profile photos in a dedicated disk such as public or local.
- Keep file metadata in the database.
- Enforce size and type validation.
- Generate predictable names or unique file names for safe storage.

## Security Considerations
- Restrict profile access to the authenticated owner or an authorized admin.
- Validate all uploaded files.
- Avoid trusting client-submitted file names.
- Use Laravel storage and authorization conventions.
- Keep role-based access enforced through existing middleware.

## Laravel Best Practices
- Use resource-oriented controller structure when implemented.
- Keep business logic in services.
- Use form requests for validation.
- Keep the module scoped to the existing role system.
- Avoid coupling the module directly to the view layer.

## Future Expansion
The module can later support:
- portfolio and project management
- resume builder workflows
- job application tracking
- recommendation signals
- public profile visibility controls
- search indexing for recruiter discovery
