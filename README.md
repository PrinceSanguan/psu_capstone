<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvard University Academic Portal - README</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #a53a3a, #8b0000);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card h2 {
            color: #a53a3a;
            margin-bottom: 1rem;
            border-bottom: 2px solid #a53a3a;
            padding-bottom: 0.5rem;
        }
        
        .card h3 {
            color: #555;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .tech-stack {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .tech-item {
            background: #f8f9fa;
            border-left: 4px solid #a53a3a;
            padding: 1rem;
            border-radius: 0 5px 5px 0;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .feature-item {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .feature-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .feature-item h4 {
            color: #a53a3a;
            margin-bottom: 0.5rem;
        }
        
        .roles-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin: 1rem 0;
        }
        
        .role-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            padding: 1.5rem;
            border-top: 4px solid #a53a3a;
        }
        
        .role-card h4 {
            color: #a53a3a;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }
        
        .role-card ul {
            list-style: none;
            padding-left: 0;
        }
        
        .role-card li {
            padding: 0.3rem 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .role-card li:before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
        }
        
        .installation-steps {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
        }
        
        .installation-steps ol {
            padding-left: 1.5rem;
        }
        
        .installation-steps li {
            margin: 0.5rem 0;
            font-family: 'Courier New', monospace;
            background: #fff;
            padding: 0.5rem;
            border-radius: 4px;
            border-left: 3px solid #a53a3a;
        }
        
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
        }
        
        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
        }
        
        .badge {
            display: inline-block;
            background: #a53a3a;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            margin: 0.2rem;
        }
        
        code {
            background: #f4f4f4;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        
        .footer {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem;
            color: #666;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎓 Harvard University Academic Portal</h1>
            <p>A Complete Academic Management System for Students</p>
            <div style="margin-top: 1rem;">
                <span class="badge">Laravel</span>
                <span class="badge">MySQL</span>
                <span class="badge">PHP</span>
                <span class="badge">Open Source</span>
            </div>
        </div>

        <!-- Overview -->
        <div class="card">
            <h2>📋 Project Overview</h2>
            <p>This is a comprehensive academic portal system inspired by Harvard University's design. It's perfect for students learning web development or working on academic projects. The system manages the complete academic workflow from student enrollment to grade reporting.</p>

            <div class="info">
                <strong>🎯 Perfect for:</strong> Computer Science students, Web development learners, Final year projects, Portfolio showcase
            </div>
        </div>

        <!-- Tech Stack -->
        <div class="card">
            <h2>🛠️ Technology Stack</h2>
            <div class="tech-stack">
                <div class="tech-item">
                    <h4>🔧 Backend Framework</h4>
                    <p><strong>Laravel</strong> - A powerful PHP framework that handles all server-side logic, authentication, and database operations</p>
                </div>
                <div class="tech-item">
                    <h4>🗄️ Database</h4>
                    <p><strong>MySQL</strong> - Stores all user data, academic records, grades, and system information</p>
                </div>
                <div class="tech-item">
                    <h4>🎨 Frontend</h4>
                    <p><strong>HTML, CSS, JavaScript</strong> with TailwindCSS for styling and FontAwesome for icons</p>
                </div>
                <div class="tech-item">
                    <h4>📊 Additional Features</h4>
                    <p><strong>PDF/Excel Export</strong> - Generate reports and grade sheets for download</p>
                </div>
            </div>
        </div>

        <!-- User Roles -->
        <div class="card">
            <h2>👥 User Roles & Access Levels</h2>
            <p>The system has three main types of users, each with different permissions and access levels:</p>

            <div class="roles-container">
                <div class="role-card">
                    <h4>👨‍💼 Admin (System Administrator)</h4>
                    <ul>
                        <li>Add and remove students</li>
                        <li>Add and manage faculty members</li>
                        <li>Create and manage subjects</li>
                        <li>Create class sections</li>
                        <li>Assign faculty to teach specific subjects</li>
                        <li>Enroll students in classes</li>
                        <li>Set up grading systems for each subject</li>
                        <li>View all uploaded syllabi</li>
                        <li>System-wide oversight and management</li>
                    </ul>
                </div>

                <div class="role-card">
                    <h4>👨‍🏫 Faculty (Teachers/Professors)</h4>
                    <ul>
                        <li>View assigned classes and students</li>
                        <li>Upload course syllabi</li>
                        <li>Create assessments (quizzes, tests, activities, exams)</li>
                        <li>Enter and manage student scores</li>
                        <li>Create classroom seat plans</li>
                        <li>View detailed analytics and reports</li>
                        <li>Export grade reports to PDF/Excel</li>
                        <li>Track student performance over time</li>
                    </ul>
                </div>

                <div class="role-card">
                    <h4>👨‍🎓 Students (Clients)</h4>
                    <ul>
                        <li>View enrolled classes and subjects</li>
                        <li>Check grades and scores</li>
                        <li>Download course syllabi</li>
                        <li>View academic progress</li>
                        <li>See midterm and final grades</li>
                        <li>Access overall grade calculations</li>
                        <li>View class information and faculty details</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Features -->
        <div class="card">
            <h2>✨ Key Features</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <h4>🔐 User Authentication</h4>
                    <p>Secure login system with role-based access control using student numbers</p>
                </div>

                <div class="feature-item">
                    <h4>📚 Subject Management</h4>
                    <p>Complete CRUD operations for academic subjects with codes, names, and descriptions</p>
                </div>

                <div class="feature-item">
                    <h4>👥 Student Enrollment</h4>
                    <p>Manage student enrollment in different sections and subjects by semester</p>
                </div>

                <div class="feature-item">
                    <h4>📋 Faculty Assignment</h4>
                    <p>Assign teachers to specific subjects and class sections</p>
                </div>

                <div class="feature-item">
                    <h4>📄 Syllabus Management</h4>
                    <p>Upload, download, and manage course syllabi with timestamp tracking</p>
                </div>

                <div class="feature-item">
                    <h4>📝 Assessment Creation</h4>
                    <p>Create quizzes, unit tests, activities, and exams with flexible scoring</p>
                </div>

                <div class="feature-item">
                    <h4>🎯 Grade Management</h4>
                    <p>Enter scores, calculate grades, and track student performance</p>
                </div>

                <div class="feature-item">
                    <h4>💺 Seat Plan Creator</h4>
                    <p>Visual tool to create and manage classroom seating arrangements</p>
                </div>

                <div class="feature-item">
                    <h4>📊 Analytics Dashboard</h4>
                    <p>Detailed performance analytics with charts and statistical insights</p>
                </div>

                <div class="feature-item">
                    <h4>📈 Report Generation</h4>
                    <p>Export detailed academic reports in PDF and Excel formats</p>
                </div>

                <div class="feature-item">
                    <h4>⚙️ Grading System</h4>
                    <p>Customizable grading percentages for different assessment types</p>
                </div>

                <div class="feature-item">
                    <h4>📅 Academic Calendar</h4>
                    <p>Semester-based organization with school year tracking</p>
                </div>
            </div>
        </div>

        <!-- Installation Guide -->
        <div class="card">
            <h2>🚀 Installation Guide</h2>
            <p>Follow these steps to set up the Harvard Academic Portal on your local machine:</p>

            <div class="warning">
                <strong>Prerequisites:</strong> Make sure you have PHP 8.1+, Composer, MySQL, and a web server (like XAMPP or MAMP) installed on your computer.
            </div>

            <div class="installation-steps">
                <ol>
                    <li>git clone https://github.com/yourusername/harvard-portal.git</li>
                    <li>cd harvard-portal</li>
                    <li>composer install</li>
                    <li>cp .env.example .env</li>
                    <li>php artisan key:generate</li>
                    <li>Create a MySQL database named 'harvard_portal'</li>
                    <li>Update .env file with your database credentials</li>
                    <li>php artisan migrate</li>
                    <li>php artisan db:seed (if you have seeders)</li>
                    <li>php artisan serve</li>
                </ol>
            </div>

            <div class="info">
                <strong>📝 Database Configuration:</strong> Edit the <code>.env</code> file and update the database settings:
                <br><code>DB_DATABASE=harvard_portal</code>
                <br><code>DB_USERNAME=your_username</code>
                <br><code>DB_PASSWORD=your_password</code>
            </div>
        </div>

        <!-- Database Structure -->
        <div class="card">
            <h2>🗄️ Database Structure</h2>
            <p>The system uses the following main database tables:</p>

            <h3>Core Tables:</h3>
            <ul>
                <li><strong>users</strong> - Stores all system users (admins, faculty, students)</li>
                <li><strong>subjects</strong> - Academic subjects with codes and descriptions</li>
                <li><strong>sections</strong> - Class sections with year levels and capacity</li>
                <li><strong>assessments</strong> - Quizzes, tests, activities, and exams</li>
                <li><strong>student_scores</strong> - Individual student scores for assessments</li>
            </ul>

            <h3>Relationship Tables:</h3>
            <ul>
                <li><strong>section_subject</strong> - Links sections with subjects and faculty</li>
                <li><strong>section_student</strong> - Student enrollment in specific sections</li>
                <li><strong>syllabi</strong> - Course syllabi uploaded by faculty</li>
                <li><strong>seat_plans</strong> - Classroom seating arrangements</li>
                <li><strong>grading_systems</strong> - Subject-specific grading percentages</li>
            </ul>
        </div>

        <!-- Usage Guide -->
        <div class="card">
            <h2>📖 How to Use</h2>

            <h3>For Students Learning Web Development:</h3>
            <p>This project is an excellent learning resource because it demonstrates:</p>
            <ul>
                <li><strong>MVC Architecture</strong> - See how Laravel organizes code into Models, Views, and Controllers</li>
                <li><strong>Database Design</strong> - Learn about relationships, foreign keys, and data normalization</li>
                <li><strong>Authentication</strong> - Understand how user login and role-based access works</li>
                <li><strong>CRUD Operations</strong> - See how to Create, Read, Update, and Delete data</li>
                <li><strong>File Uploads</strong> - Learn how to handle file uploads (syllabi)</li>
                <li><strong>Data Visualization</strong> - Explore how to create charts and analytics</li>
            </ul>

            <h3>Default Login Credentials:</h3>
            <div class="warning">
                You'll need to create these users through the admin panel or database seeders:
                <br><strong>Admin:</strong> student_number: "admin001", password: "password"
                <br><strong>Faculty:</strong> student_number: "faculty001", password: "password"
                <br><strong>Student:</strong> student_number: "student001", password: "password"
            </div>
        </div>

        <!-- Project Structure -->
        <div class="card">
            <h2>📁 Project Structure</h2>
            <div style="font-family: monospace; background: #f8f9fa; padding: 1rem; border-radius: 5px;">
                harvard-portal/<br>
                ├── app/<br>
                │   ├── Http/Controllers/    # Application logic<br>
                │   │   ├── Admin/          # Admin-specific controllers<br>
                │   │   ├── Faculty/        # Faculty-specific controllers<br>
                │   │   ├── Client/         # Student-specific controllers<br>
                │   │   └── Auth/           # Authentication controllers<br>
                │   └── Models/             # Database models<br>
                ├── database/<br>
                │   └── migrations/         # Database structure<br>
                ├── resources/<br>
                │   └── views/              # HTML templates<br>
                │       ├── admin/          # Admin interface<br>
                │       ├── faculty/        # Faculty interface<br>
                │       └── client/         # Student interface<br>
                ├── routes/<br>
                │   └── web.php             # Application routes<br>
                └── public/                 # Web-accessible files
            </div>
        </div>

        <!-- Contributing -->
        <div class="card">
            <h2>🤝 Contributing & Learning</h2>
            <p>This project is open source and perfect for learning! Here's how you can use it:</p>

            <h3>Learning Opportunities:</h3>
            <ul>
                <li><strong>Add New Features</strong> - Try adding attendance tracking, messaging system, or mobile app</li>
                <li><strong>Improve UI/UX</strong> - Practice frontend development by enhancing the interface</li>
                <li><strong>Add Tests</strong> - Learn testing by writing unit and feature tests</li>
                <li><strong>API Development</strong> - Create REST APIs for mobile app integration</li>
                <li><strong>Security Enhancements</strong> - Implement additional security measures</li>
            </ul>

            <h3>Customization Ideas:</h3>
            <ul>
                <li>Change the university branding to your own school</li>
                <li>Add your own subjects and grading systems</li>
                <li>Implement different academic calendar systems</li>
                <li>Add support for multiple languages</li>
                <li>Create mobile-responsive designs</li>
            </ul>
        </div>

        <!-- License & Support -->
        <div class="card">
            <h2>📜 License & Support</h2>
            <p>This project is open source and available for educational purposes. Feel free to use it for your learning projects, final year projects, or portfolio demonstrations.</p>

            <div class="info">
                <strong>💡 Tips for Students:</strong>
                <ul>
                    <li>Start by understanding the database structure</li>
                    <li>Follow the MVC pattern when adding new features</li>
                    <li>Read Laravel documentation to understand the framework better</li>
                    <li>Practice by modifying existing features before adding new ones</li>
                    <li>Use this project as a reference for your own academic projects</li>
                </ul>
            </div>

            <div class="warning">
                <strong>⚠️ Important Note:</strong> This is a learning project. If you plan to use it in a real academic environment, make sure to implement proper security measures, data validation, and backup systems.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Created for educational purposes • Perfect for student projects and learning Laravel development</p>
            <p>📚 Happy Learning! 🎓</p>
        </div>
    </div>

</body>
</html>
