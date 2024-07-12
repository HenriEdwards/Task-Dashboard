## Task Manager
Developed using HTML / CSS / JavaScript / jQuery / PHP / mySQL / XAMPP

A task manager app that allows users to manage personal tasks.

### Features

#### Tasks
 - Create new tasks
 - List tasks
 - Update tasks
 - Delete tasks
 - Task pagination

#### Task data
- Title
- Description
- Status
- Due date

#### Users
- Login / Registration
- Session storage
- Authentication

------------------------------------

### Structure
- MVC file/folder structure
- Frontend validation.

------------------------------------
### Project Setup
    1. Clone locally.
    2. Move/Copy repo to XAMPP 'htdocs' directory.
    3. Lauch Apache & mySQL.
    4. Navigate to phpmyadmin.
    5. Create a new database.
    6. Find database in sql-database folder in cloned directory.
    7. Create a new database & import sql file.
    8. Not necessary to configure any files.
    9. Navigate to http://localhost/Task-Dashboard


------------------------------------
### Assumption / Decisions
- Decision to use MVC architechture, seperation of concerns
- Task sort by due date
- Get tasks from db based on user selected page, more requests but smaller/faster requests 
- Singleton pattern on database class
- Customised responses - more econtrol
