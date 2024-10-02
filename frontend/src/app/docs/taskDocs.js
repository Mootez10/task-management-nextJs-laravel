// src/app/docs/taskDocs.js

/**
 * Represents a task in the task management system.
 * 
 * @typedef {Object} Task
 * @property {number} [id] - The unique identifier for the task (optional).
 * @property {string} title - The title of the task.
 * @property {string} description - A brief description of the task.
 * @property {string} status - The current status of the task (e.g., 'pending', 'completed').
 * @property {Date} date - The date when the task was created or updated.
 */

/**
 * Represents a subtask associated with a task in the task management system.
 * 
 * @typedef {Object} Subtask
 * @property {number} [id] - The unique identifier for the subtask (optional).
 * @property {number} taskId - The ID of the parent task.
 * @property {string} title - The title of the subtask.
 * @property {string} description - A brief description of the subtask.
 * @property {string} status - The current status of the subtask (e.g., 'pending', 'completed').
 * @property {Date} date - The date when the subtask was created or updated.
 */