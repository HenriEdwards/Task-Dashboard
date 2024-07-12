// Function validates user input & returns errors & cont if ok
function validateTaskForm (title, description, dueDate, status) {
  title = title.trim();
  description = description.trim();
  dueDate = dueDate.trim();
  status = status.trim();
  
  // Check if input is empty
  if (title.length == 0 || description.length == 0 || 
    dueDate.length == 0 || status.length == 0) {
    $("#response-task").text("Input can't be empty.");
    return false;
  }

  // Check if the password contains only alphanumeric characters
  const invalidRegex = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/;
  if (!invalidRegex.test(dueDate)) {
    $("#response").text("Date in invalid format.");
    return false;
  }

  let dataObj = {
    title: title, 
    description: description,
    dueDate: dueDate, 
    status: status
  };

  return dataObj;
}

$(document).ready(function() {
  // Array to store user related tasks
  let tasks = [];

  // Function loads tasks from server with pagination
  function loadTasks(page = 1) {
    $.ajax({
      type: "GET",
      url: "/Task-Dashboard/controllers/TaskController.php",
      data: {
        type: "getTasks",
        page: page
      },
      success: function(response) {
        if (response.response) {
          tasks = response.tasks;
          renderTasks(response.tasks);
          renderPagination(response.totalPages, page);
        } else {
          $("#task-list").html("<p>No tasks found.</p>");
        }
      },
      error: function(xhr) {
        let errorMessage = "Error loading tasks.";
        try {
          const response = JSON.parse(xhr.responseText);
          errorMessage = response.error;
        } catch (e) {
          errorMessage = "Internal server error. Please try again later.";
        }
        $("#response-task").text(errorMessage);
      }
    });
  }

  // Function renders tasks to DOM
  function renderTasks(tasks) {
    if (!tasks.length) {
      $("#task-list").html("<p class = 'no-tasks-found'>No tasks found.</p>");
      return;
    }
    let taskTable = `
      <table class="task-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          ${tasks.map(task => `
            <tr class="task">
              <td>${task.title}</td>
              <td>${task.description}</td>
              <td>${task.due_date}</td>
              <td>${task.status_val}</td>
              <td class="task-update-delete-btns">
                <div class='btns-align'>
                  <button class="edit" data-id="${task.task_ID}">Edit</button>
                  <button class="delete" data-id="${task.task_ID}">Delete</button>
                </div>
              </td>
            </tr>
          `).join('')}
        </tbody>
      </table>
    `;
    // Populate tasks
    $("#task-list").html(taskTable);
  }
  
  // Function renders pagination controls
  function renderPagination(totalPages) {
    let paginationControls = '';
    for (let i = 1; i <= totalPages; i++) {
      paginationControls += `<button class="pagination-btn" data-page="${i}">${i}</button>`;
    }
    // Populate pagination controls
    $("#pagination-controls").html(paginationControls);
  }

  // Handle form submission for creating/updating tasks
  $('#task-form').submit(function(e) {
    e.preventDefault();

    let taskId = $("#task_id").val();
    let title = $("#title").val();
    let description = $("#description").val();
    let dueDate = $("#due_date").val();
    let status = $("#status").val();

    // Validate input
    let inputData = validateTaskForm (title, description, dueDate, status);

    // Stop if invalid
    if (!inputData) {
      return;
    }

    let action = taskId ? 'updateTask' : 'createTask';

    // Ajax request to create/update tasks
    $.ajax({
      type: "POST",
      url: "/Task-Dashboard/controllers/TaskController.php",
      data: {
        type: action,
        task_id: taskId,
        title: inputData.title,
        description: inputData.description,
        due_date: inputData.dueDate,
        status: inputData.status
      },
      success: function(response) {
        if (response.response) {
          $("#response-task").text("Task saved.");
          // Call loadTasks to reload rendered tasks after successful db update
          loadTasks();
          $("#task-form")[0].reset();
          $("#task_id").val('');
          $("#task-btn").text('Create Task');
        } else {
          $("#response-task").text(response.error);
        }
      },
      error: function(xhr, status, error) {
        let errorMessage = "Error saving task.";
        try {
          const response = JSON.parse(xhr.responseText);
          errorMessage = response.error;
        } catch (e) {
          errorMessage = "Internal server error. Please try again later.";
        }
        $("#response-task").text(errorMessage);
      }
    });
  });

  // Handle task deletion
  $(document).on('click', '.delete', function() {
    // Retrieve the ID of the task to delete
    let taskId = $(this).data('id');
     // Ajax request to delete task
    $.ajax({
      type: "POST",
      url: "/Task-Dashboard/controllers/TaskController.php",
      data: {
        type: 'deleteTask',
        task_id: taskId
      },
      success: function(response) {
        if (response.response) {
          $("#response-task").text("Task deleted successfully.");
          // Call loadTasks to reload rendered tasks after successful db update
          loadTasks();
        } else {
          $("#response-task").text(response.error);
        }
      },
      error: function(xhr, status, error) {
        let errorMessage = "Error deleting task.";
        try {
          const response = JSON.parse(xhr.responseText);
          errorMessage = response.error;
        } catch (e) {
          errorMessage = "Internal server error. Please try again later.";
        }
        $("#response-task").text(errorMessage);
      }
    });
  });

  // Handle task editing
  $(document).on('click', '.edit', function() {
    let taskId = $(this).data('id');
    // Find relevant task
    let task = tasks.find(t => t.task_ID == taskId);

    if (task) {
      // Populate form fields with task data for editing
      $("#task_id").val(task.task_ID);
      $("#title").val(task.title);
      $("#description").val(task.description);
      $("#due_date").val(task.due_date);
      $("#status").val(task.status_val);
      $("#task-btn").text('Update Task');
    }
  });

  // Handle pagination 
  $(document).on('click', '.pagination-btn', function() {
    let page = $(this).data('page');
    loadTasks(page);
  });

  // Initial load of tasks
  loadTasks();
});
