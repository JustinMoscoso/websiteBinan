    # Weekly Practicum Journal - Website Biñan

    **Area Assignment:** ICTO  
    **Shift/Time:** 8:00 am to 5:00 pm  

    ---

    ## Week 1: April 21 to 24, 2026
    **Task: Pre Assessment and Setup**

    ### Software Development:
    April 21. Took the pre-assessment and reviewed the skills needed for OJT tasks.
    April 22. Set up VS Code, XAMPP, and the local server for web development practice.
    April 23. Practiced creating a simple web page and handling basic user input.
    April 24. Fixed coding errors and improved the output based on the instructions.

    ### Technical Documents:
    April 21. Identified the areas that needed improvement based on the assessment.
    April 22. Recorded setup notes and configuration requirements for the tools.
    April 23. Reviewed how forms and user inputs should be organized.
    April 24. Noted the corrections made during code refinement and testing.
Documented tool setups and identified areas for improvement.
    ---

    ## Week 2: April 27 to 30, 2026
    **Task: Database Management**

    ### Software Development:
    April 27. Started planning the database schema architecture and scoping boundaries for the Website Biñan User Access Leveling.
    April 28. Created the Data Flow Diagram (DFD) to map the movement of admin inputs and restrict local/department accounts to their own scopes.
    April 29. Designed the Entity-Relationship Diagram (ERD) mapping the roles (Superadmin, Admin, Encoder, Viewer) to respective data tables.
    April 30. Reviewed the database structure, defined foreign key relationships, and performed normalization on the user roles and department schemas.

    ### Technical Documents:
    April 27. Documented the initial system flow and identified the data restriction boundaries needed for local accounts.
    April 28. Reviewed the DFD to ensure data movement and restriction points are easy to understand.
    April 29. Prepared the ERD to define the connections between user roles, department tables, and services.
    April 30. Documented the normalized database structure for better security and scalability.

    ---

    ## Week 3: May 4 to 8, 2026
    **Task: ERD Finalization**

    ### Software Development:
    May 4. Continued improving and finalizing the ERD schema for emergency hotlines (supporting PLDT, Smart, Globe, and Intelco) and user profiles.
    May 5. Revised tables, primary keys, foreign keys, and relationships to support role-based query isolation.
    May 6. Prepared the revised database schema layout for approval and verified all relational constraints.
    May 7. Started planning the mock design of the restricted admin dashboard and hotline CMS cards.
    May 8. Brainstormed the UI pages, buttons, forms, and navigation flow based on permission levels.

    ### Technical Documents:
    May 4. Documented the ERD revisions and reviewed database accuracy for the new hotline columns.
    May 5. Checked if the finalized ERD was fully aligned with the application requirements and scoping boundaries.
    May 6. Finalized the important database relationships for approval by the supervisor.
    May 7. Prepared notes for the UI dashboard mock designs based on the approved database structures.
    May 8. Documented the planned navigation flow and permission checks for the user interface.

    ---

    ## Week 4: May 11 to 15, 2026
    **Task: UI Design and Gantt Chart**

    ### Software Development:
    May 11. Improved the mock design of the login page layout, spacing, and error alerts.
    May 12. Worked on the dashboard overview layout and organized the project tasks in the Gantt Chart.
    May 13. Discussed Superadmin, Admin, Encoder, and Viewer roles and access restrictions across departments.
    May 14. Planned how front-end templates should hide/show sidebar menu items and elements dynamically based on user session roles.
    May 15. Improved the UI login page fields, dashboard elements, and input validation styles.

    ### Technical Documents:
    May 11. Documented the improvements made to the login page mock layout and user feedback alerts.
    May 12. Prepared the Gantt Chart tracking tasks, development schedules, and deadlines.
    May 13. Documented the planned user roles, permission matrices, and path restrictions.
    May 14. Reviewed and documented the front-end template conditional visibility checks and layout logic.
    May 15. Noted the validation fixes, input patterns, and interface design changes.

    ---

    ## Week 5: May 18 to 22, 2026
    **Task: Database and Back End Development**

    ### Software Development:
    May 18. Reviewed SQL tables for users, barangays, departments, services, and audit trails to ensure integrity.
    May 19. Checked how system controller forms should validate, save, and update database records.
    May 20. Reviewed member and sector relationships in the database for the Barangay Staff rich-text integration.
    May 21. Improved back-end controller logic to enforce user roles, checking that departments/barangays can only modify their own content.
    May 22. Reviewed and implemented the audit trail feature for recording administrator and encoder actions in the database.

    ### Technical Documents:
    May 18. Documented the database tables, data types, and schemas implemented for Website Biñan.
    May 19. Recorded data input validation rules and controller update methods.
    May 20. Documented the member and sector database relationships for barangay content.
    May 21. Documented the role validation logic and permission boundary checks inside PHP controllers.
    May 22. Documented the purpose, schema, and logging structure of the user audit trail feature.

    ---

    ## Week 6: May 25 to 26, 2026 and May 28 to 29, 2026
    **Task: System Flow and Database Review**

    ### Software Development:
    May 25. Continued revising the database structure, member fields, and Quill.js implementation helper.
    May 26. Tested the system flow on localhost from login, session creation, to database updating.
    May 28. Reviewed member data handling and how sector classifications should be restricted in the controller.
    May 29. Reviewed overall system progress, user roles, database functions, and security boundaries.

    ### Technical Documents:
    May 25. Documented database tweaks and integration steps for Quill.js in the member edit forms.
    May 26. Noted test results for the login flow, session timing, and dashboard access checks.
    May 28. Documented the access restriction checks and verified that routes were securely blocked.
    May 29. Organized project documentation based on the completed system parts and role tests.

    ---

    ## Week 7: June 1 to 5, 2026
    **Task: System Development Review**

    ### Software Development:
    June 1. Reviewed the system flow on localhost from login to dashboard access and checked for layout errors.
    June 2. Checked how system features connect with stored database records for emergency hotlines.
    June 3. Reviewed back-end issues, database connection queries, and CDN asset localization files.
    June 4. Checked completed project parts (e.g. Globe/Smart hotline cards) and identified remaining fixes.
    June 5. Continued checking system progress, testing all roles (Viewer, Encoder, Admin, Superadmin).

    ### Technical Documents:
    June 1. Documented the current system flow and local testing progress for the unified website.
    June 2. Reviewed the integration between database records and the emergency hotline dynamic cards.
    June 3. Noted the downloaded third-party libraries (bootstrap, fontawesome, jquery, etc.) for local asset storage.
    June 4. Documented the overall completion status and final checklist items.
    June 5. Documented the final role validation results and remaining cosmetic adjustments.

    ---

    ## Week 8: June 8 to 12, 2026
    **Task: System Optimization and Form Validation**

    ### Software Development:
    June 8. Enhanced front-end and back-end form validation patterns for the hotline numbers (PLDT, Smart, Globe, Intelco).
    June 9. Implemented custom regex rules to ensure users input valid phone formats (e.g. XXXX-XXX-XXXX and XXX-XXXX).
    June 10. Fixed validation message alerts in the admin panel contacts modal.
    June 11. Optimized AJAX calls for dynamic retrieval of department-specific hotlines.
    June 12. Conducted cross-browser testing for the contact forms on both mobile and desktop.

    ### Technical Documents:
    June 8. Updated the Hotline Management API schemas and validation guides.
    June 9. Documented the regular expression pattern configurations for telecom numbers.
    June 10. Recorded contact validation test scenarios and troubleshooting procedures.
    June 11. Documented the optimized AJAX response models for system admins.
    June 12. Noted layout compatibility issues and browser-specific CSS adjustments.

    ---

    ## Week 9: June 15 to 19, 2026
    **Task: Dynamic Hotline Render and Sidebar Refactoring**

    ### Software Development:
    June 15. Refactored the dashboard sidebar to dynamically display menus (Barangays, Departments, Careers) according to role limitations.
    June 16. Integrated PHP session validation checks inside the sidebar template rendering process.
    June 17. Fixed navigation links so department and barangay sub-admins can only see options within their scopes.
    June 18. Updated the public Hotlines view to dynamically display the new Smart and Globe columns.
    June 19. Managed layout pagination settings for hotlines table lists containing extensive records.

    ### Technical Documents:
    June 15. Documented the sidebar menu access-scoping hierarchy.
    June 16. Recorded session verification protocols used in standard template views.
    June 17. Noted fixes for URL routing overrides and sidebar boundary protection.
    June 18. Documented the updated public hotline card CSS styling guidelines.
    June 19. Recorded performance guidelines for large-table rendering and pagination.

    ---

    ## Week 10: June 22 to 26, 2026
    **Task: Barangay Staff Rich-Text Integration**

    ### Software Development:
    June 22. Integrated Quill.js rich-text editor inside the Barangay Content modal for staff lists.
    June 23. Configured the toolbar buttons for Quill.js (bold, italic, list types, and link formats).
    June 24. Bound the Quill.js editors to hidden inputs on the main form submission for saving.
    June 25. Handled data extraction and sanitization on the controller to prevent security risks (XSS).
    June 26. Populated the editor with existing database data during record edit routines.

    ### Technical Documents:
    June 22. Documented the rich-text editor integration plan for the Barangay Staff module.
    June 23. Recorded configuration parameters and active toolbar settings for Quill.js.
    June 24. Documented the DOM event handlers binding Quill.js outputs to forms.
    June 25. Recorded sanitization logic and anti-XSS rules in the controllers.
    June 26. Documented the data population flows for edit forms.

    ---

    ## Week 11: June 29 to July 3, 2026
    **Task: Local Asset Integration (CDN Localization)**

    ### Software Development:
    June 29. Identified all external CDNs being used for stylesheets and scripts.
    June 30. Downloaded external libraries (Bootstrap, Font Awesome, jQuery, AOS, SweetAlert2) locally.
    July 1. Created directories under public/assets/ for vendor files.
    July 2. Updated the PHP asset helper to load files using the local asset folder paths.
    July 3. Verified system layout rendering without internet access to confirm offline capability.

    ### Technical Documents:
    June 29. Documented the list of CDN dependencies and asset sizes.
    June 30. Created a log of local library versions and sources.
    July 1. Recorded the local directory path layout for vendor assets.
    July 2. Documented the updated PHP asset helper logic and usage references.
    July 3. Created the Asset Localization Summary documentation.

    ---

    ## Week 12: July 6 to 10, 2026
    **Task: User Access Leveling Verification**

    ### Software Development:
    July 6. Audited Controller routes (Admin, Career, Services) for role check compliance.
    July 7. Implemented strict direct-link blocks in the controllers for unauthorized roles.
    July 8. Tested the system using Viewer, Encoder, Admin, and Superadmin test accounts.
    July 9. Fixed privilege escalation issues on AJAX update endpoints.
    July 10. Added user audit trail entries for unauthorized URL access attempts.

    ### Technical Documents:
    July 6. Documented the system path security checklist.
    July 7. Recorded path access enforcement methods and boundary checks.
    July 8. Compiled role-specific test results and account access tables.
    July 9. Noted security fixes for the API updates endpoints.
    July 10. Documented the audit trail schema update and logging structure.

    ---

    ## Week 13: July 13 to 17, 2026
    **Task: Final Integration Testing and Turnover**

    ### Software Development:
    July 13. Performed full staging checks on the MySQL database, tables, and relationships.
    July 14. Ran cross-browser testing for rich-text rendering and layout responsiveness.
    July 15. Fixed secondary CSS alignment issues on the municipal contact details.
    July 16. Gathered final patch logs and compiled the database update scripts.
    July 17. Handed over all system files, database scripts, and project documentation to the supervisor.

    ### Technical Documents:
    July 13. Compiled final database validation reports.
    July 14. Documented multi-device layout responsiveness and browser compatibility lists.
    July 15. Recorded minor interface fixes and alignment styling parameters.
    July 16. Prepared the final release notes and patch notes documentation.
    July 17. Documented the final handover logs and project completion checklists.
