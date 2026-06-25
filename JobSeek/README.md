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
