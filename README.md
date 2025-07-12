# Personal Workstation - Task Management System

A personalized task management system built with Laravel 12, featuring project management, team collaboration, and task tracking capabilities. The system dynamically adapts to show the logged-in user's name (e.g., "Ива's workstation" or "Daniel's workstation").

## Features

### 🚀 Core Features
- **Project Management**: Create, edit, and manage projects with customizable colors and priorities
- **Task Management**: Full CRUD operations for tasks with types (Story, Bug, Task, Epic)
- **Team Management**: Add and manage team members with roles and departments
- **Comments System**: Real-time commenting on tasks with user attribution
- **File Attachments**: Upload and manage file attachments on tasks
- **Status Tracking**: Track task progress through different statuses (To Do, In Progress, Completed, Blocked)
- **Priority Management**: Set task priorities (Low, Medium, High)
- **Story Points**: Agile story point estimation for tasks
- **Due Dates**: Set and track task due dates with overdue notifications

### 👥 User Management
- **Admin System**: Complete admin access to all projects and tasks
- **Role-Based Access**: Different user roles (Admin, Developer, Tester, Project Manager)
- **Team Member Profiles**: Comprehensive user profiles with performance metrics
- **Department Organization**: Organize team members by departments

### 🎨 User Interface
- **Modern Design**: Clean, responsive UI built with Tailwind CSS
- **Dashboard**: Comprehensive dashboard with statistics and recent activity
- **Kanban Board**: Visual task management with drag-and-drop functionality
- **Search Functionality**: Global search across projects and tasks
- **Responsive Design**: Works on desktop, tablet, and mobile devices

### 🔐 Security & Access Control
- **Authentication**: Laravel's built-in authentication system
- **Authorization**: Role-based access control throughout the application
- **Project Membership**: Control access to projects through membership
- **Admin Override**: Admin users have access to everything

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js and npm
- MySQL or PostgreSQL database

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/r3s0l7e/tasks.git
   cd tasks
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Configuration**
   - Update `.env` file with your database credentials
   - Create a new database for the application

6. **Run Migrations and Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build Assets**
   ```bash
   npm run build
   ```

8. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

9. **Start the Development Server**
   ```bash
   php artisan serve
   ```

## Default Login Credentials

After running the seeders, you can log in with:

- **Admin User**: 
  - Email: `iva@wuvu.com`
  - Password: `password`

## Project Structure

### Controllers
- `DashboardController`: Main dashboard with statistics
- `ProjectController`: Project management CRUD operations
- `TaskController`: Task management with both nested and standalone routes
- `TeamMemberController`: Team member management
- `TaskCommentController`: Task commenting system
- `ProfileController`: User profile management
- `SearchController`: Global search functionality

### Models
- `Project`: Project model with relationships
- `Task`: Task model with full relationships
- `User`: Enhanced user model with team member fields
- `TaskComment`: Task commenting system
- `TaskAttachment`: File attachment management

### Key Features Implementation
- **Cascading Deletes**: Proper cleanup when deleting projects/tasks
- **File Management**: Secure file upload and storage
- **Admin Access**: Complete admin override for all operations
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Search**: Full-text search across projects and tasks

## Database Schema

### Main Tables
- `users`: User management with team member fields
- `projects`: Project information and settings
- `tasks`: Task management with full metadata
- `task_comments`: Task commenting system
- `task_attachments`: File attachment management
- `project_members`: Project membership relationships

## API Routes

### Project Routes
- `GET /projects` - List all projects
- `POST /projects` - Create new project
- `GET /projects/{id}` - View project details
- `PUT /projects/{id}` - Update project
- `DELETE /projects/{id}` - Delete project

### Task Routes
- `GET /tasks` - Global task listing
- `POST /tasks` - Create new task
- `GET /tasks/{id}` - View task details
- `PUT /tasks/{id}` - Update task
- `DELETE /tasks/{id}` - Delete task

### Team Routes
- `GET /team` - List team members
- `POST /team` - Add team member
- `GET /team/{id}` - View team member
- `PUT /team/{id}` - Update team member
- `DELETE /team/{id}` - Remove team member

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please create an issue in the GitHub repository or contact the development team.

---

Built with ❤️ using Laravel 12 and Tailwind CSS
