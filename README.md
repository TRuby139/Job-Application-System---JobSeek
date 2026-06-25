# Job-Application-System---JobSeek
This is a system develop with simple function such as Log in, Resume Builder, Job Search &amp; Filtering, Process Tracking, Job Posting Management

## Backend Setup (XAMPP)

This project uses a pure PHP and MySQL backend. Follow these steps to run it locally:

1. **Install XAMPP**: Download and install XAMPP if you haven't already.
2. **Start Services**: Open the XAMPP Control Panel and start **Apache** and **MySQL**.
3. **Database Setup**:
   - Open your browser and go to `http://localhost/phpmyadmin`.
   - The database will be created automatically. Just go to the "Import" tab and upload the `database/database.sql` file.
4. **Project Placement**:
   - Move this entire project folder into the `htdocs` directory (e.g., `C:\xampp\htdocs\JobSeek`).
5. **Accessing the App**:
   - Open your browser and go to `http://localhost/JobSeek/index.html`.

## API Endpoints

All backend endpoints are located in the `api/` directory and return JSON responses.
When connecting the frontend to these endpoints, ensure your `fetch()` calls include `credentials: 'include'` to maintain session state.

## Test Accounts

For testing the application, you can use the following pre-registered accounts:
- **Email:** TR@gmail.com | **Password:** 123456
- **Email:** Test@gmail.com | **Password:** 123456

Users can also register a new account by themselves directly through the application's registration page.

## Core Functionalities

1. **User Authentication System**
   - **Login & Registration**: Dedicated pages (`login.html`, `register.html`) allow users (both job seekers and employers) to create accounts, authenticate, and manage their sessions securely.
   - **Profile Management**: Profile handling is integrated into the site's header and navigation.

2. **Job Browsing & Applications**
   - **Landing & Search Page** (`index.html`): Features a dynamic job browsing experience with live search filtering, pagination, and advanced filters (like minimum salary range).
   - **Job Details** (`job-details.html`): Provides a dedicated view for individual job postings, where applicants can read full job descriptions and apply for the role directly.

3. **Employer Dashboard & Job Management**
   - **Management Hub** (`management.html`): A comprehensive dashboard for employers replacing hardcoded postings with dynamic database rendering. Also allow employers to edit the job details.
   - **Active Postings**: Features a dynamic table for tracking active job postings that automatically sorts listings and highlights the `new_applicants_count` for each job. It also includes live search filtering and pagination specifically for employers.

4. **Applicant Tracking (Kanban Board)**
   - **Manage Jobs** (`manage-job.html`): Contains a Kanban board (drag-and-drop column style layout) for employers to organize and track applicants through different hiring stages (e.g., applied, interviewing, hired).
   - **Mobile Support**: The Kanban board logic includes full support and responsive design for phone versions.

5. **Candidate Process Tracker**
   - **Application Tracking** (`tracking.html`): A dedicated dashboard for job seekers to track the status of the jobs they've applied for.
   - **Visual Statuses**: Displays a visual process tracker that highlights the current status of applications, correctly updates interview counts, and shows a red bar if an application is declined.

6. **Backend Integration**
   - **Dynamic API** (`api/`, `database/`): The frontend is fully hooked up to a backend database, allowing live fetching of employer jobs, real-time application status syncing, and secure data storage rather than relying on static HTML.