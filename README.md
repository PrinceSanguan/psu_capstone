<p align="center">
<a href="#"><img src="https://img.shields.io/badge/laravel-v10.x-red.svg" alt="Laravel Version"></a>
<a href="#"><img src="https://img.shields.io/badge/php-^8.1-blue.svg" alt="PHP Version"></a>
<a href="#"><img src="https://img.shields.io/badge/mysql-8.0+-orange.svg" alt="MySQL Version"></a>
<a href="#"><img src="https://img.shields.io/badge/license-MIT-green.svg" alt="License"></a>
</p>

## About Harvard University Academic Portal

Harvard University Academic Portal is a comprehensive academic management system built with Laravel and MySQL. This project provides a complete solution for managing students, faculty, courses, grades, and academic workflows. Perfect for students learning web development or working on academic projects.

The system includes features such as:

-   **User Authentication** with role-based access control
-   **Student Management** - enrollment, grades, and academic tracking
-   **Faculty Management** - class assignments, grade entry, and analytics
-   **Course Management** - subjects, sections, and curriculum organization
-   **Assessment Tools** - quizzes, tests, activities, and exams
-   **Grade Analytics** - comprehensive reporting and data visualization
-   **Syllabus Management** - upload and download course materials
-   **Seat Plan Creator** - visual classroom seating arrangements
-   **Report Generation** - PDF and Excel export capabilities

## User Roles & Permissions

### 👨‍💼 Admin (System Administrator)

-   Add and remove students and faculty
-   Create and manage subjects and sections
-   Assign faculty to teach specific subjects
-   Enroll students in classes
-   Set up grading systems for each subject
-   System-wide oversight and management

### 👨‍🏫 Faculty (Teachers/Professors)

-   View assigned classes and students
-   Upload course syllabi and materials
-   Create assessments (quizzes, tests, activities, exams)
-   Enter and manage student scores
-   Create classroom seat plans
-   View detailed analytics and performance reports
-   Export grade reports to PDF/Excel

### 👨‍🎓 Students (Clients)

-   View enrolled classes and subjects
-   Check grades and academic progress
-   Download course syllabi and materials
-   View midterm and final grades
-   Access overall grade calculations
-   See class information and faculty details

## Technology Stack

-   **Backend Framework**: Laravel (PHP)
-   **Database**: MySQL
-   **Frontend**: HTML, CSS, JavaScript with TailwindCSS
-   **Icons**: FontAwesome
-   **Charts**: Chart.js for analytics
-   **Export**: PDF and Excel generation
-   **Authentication**: Laravel's built-in authentication system

## Installation

Follow these steps to set up the Harvard Academic Portal on your local machine:

### Prerequisites

Make sure you have the following installed:

-   PHP 8.1 or higher
-   Composer
-   MySQL 8.0+
-   Node.js & NPM (optional, for asset compilation)

### Step-by-Step Installation

```bash
# Clone the repository
git clone https://github.com/yourusername/harvard-portal.git

# Navigate to project directory
cd harvard-portal

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create database (make sure MySQL is running)
# Create a database named 'harvard_portal' in your MySQL

# Update .env file with your database credentials
# DB_DATABASE=harvard_portal
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run database migrations
php artisan migrate

# (Optional) Seed the database with sample data
php artisan db:seed

# Start the development server
php artisan serve
```

The application will be available at `http://localhost:8000`

## Database Structure

### Core Tables

-   **users** - All system users (admins, faculty, students)
-   **subjects** - Academic subjects with codes and descriptions
-   **sections** - Class sections with year levels and capacity
-   **assessments** - Quizzes, tests, activities, and exams
-   **student_scores** - Individual student scores for assessments

### Relationship Tables

-   **section_subject** - Links sections with subjects and faculty
-   **section_student** - Student enrollment in specific sections
-   **syllabi** - Course syllabi uploaded by faculty
-   **seat_plans** - Classroom seating arrangements
-   **grading_systems** - Subject-specific grading percentages

## Key Features

### 🔐 Authentication System

Secure login system using student numbers with role-based access control for admins, faculty, and students.

### 📚 Academic Management

Complete CRUD operations for subjects, sections, and student enrollment with semester-based organization.

### 📝 Assessment & Grading

Flexible assessment creation with customizable grading systems and automatic grade calculations.

### 📊 Analytics Dashboard

Comprehensive performance analytics with visual charts and statistical insights for faculty.

### 📄 Document Management

Upload and download syllabi with timestamp tracking and file management.

### 💺 Seat Plan Creator

Interactive tool for creating and managing classroom seating arrangements.

### 📈 Report Generation

Export detailed academic reports in PDF and Excel formats with comprehensive grade breakdowns.

## Usage for Learning

This project is an excellent learning resource for students because it demonstrates:

### Web Development Concepts

-   **MVC Architecture** - See how Laravel organizes code
-   **Database Design** - Learn about relationships and normalization
-   **Authentication** - Understand user login and role-based access
-   **CRUD Operations** - Complete Create, Read, Update, Delete examples
-   **File Uploads** - Handle file uploads and downloads
-   **Data Visualization** - Create charts and analytics

### Laravel Features

-   Eloquent ORM relationships
-   Migration and schema design
-   Route organization and middleware
-   Blade templating engine
-   Form validation and error handling
-   File storage and management

## Default Credentials

You'll need to create users through the admin panel or database seeders:

```
Admin: student_number: "admin001", password: "password"
Faculty: student_number: "faculty001", password: "password"
Student: student_number: "student001", password: "password"
```

## Project Structure

```
harvard-portal/
├── app/
│   ├── Http/Controllers/    # Application logic
│   │   ├── Admin/          # Admin-specific controllers
│   │   ├── Faculty/        # Faculty-specific controllers
│   │   ├── Client/         # Student-specific controllers
│   │   └── Auth/           # Authentication controllers
│   └── Models/             # Database models
├── database/
│   └── migrations/         # Database structure
├── resources/
│   └── views/              # HTML templates
│       ├── admin/          # Admin interface
│       ├── faculty/        # Faculty interface
│       └── client/         # Student interface
├── routes/
│   └── web.php             # Application routes
└── public/                 # Web-accessible files
```

## Contributing & Customization

This project is perfect for learning and customization:

### Learning Opportunities

-   Add new features like attendance tracking or messaging
-   Improve UI/UX with modern frontend frameworks
-   Implement API endpoints for mobile app integration
-   Add comprehensive testing (unit and feature tests)
-   Enhance security with additional measures

### Customization Ideas

-   Change university branding to your own school
-   Add support for different grading systems
-   Implement multiple language support
-   Create mobile-responsive designs
-   Add email notifications and alerts

## Educational Use

This project is specifically designed for educational purposes:

-   **Computer Science Students** - Learn web development with a real-world project
-   **Final Year Projects** - Use as a base for academic management systems
-   **Portfolio Showcase** - Demonstrate full-stack development skills
-   **Learning Laravel** - Comprehensive example of Laravel best practices

## Security Note

⚠️ **Important**: This is a learning project. For production use in real academic environments, implement additional security measures, data validation, backup systems, and follow security best practices.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For questions about this educational project or suggestions for improvements, please create an issue in the repository. This project is maintained for learning purposes and community contribution.

---

**Created for educational purposes • Perfect for student projects and learning Laravel development**

🎓 **Happy Learning!** 📚
