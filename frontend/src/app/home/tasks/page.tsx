"use client"
import "bootstrap/dist/css/bootstrap.min.css";
import Link from "next/link";
import React, { useEffect, useState } from "react";
import { useSearchParams } from "next/navigation";
import api from "@/app/axios";
import { Task } from "../../models/task";
import "../../components/navbar";
import withAuth from './../../withAuth';


const TasksList = () => {
  const [tasks, setTasks] = useState<Task[]>([]);
  const [error, setError] = useState("");
  const searchParams = useSearchParams();
  const message = searchParams.get("message");

  useEffect(() => {
    const fetchTasks = async () => {
      try {
        const response = await api.get("/tasks");
        setTasks(response.data); 
        console.log(response.data)
      } catch (error) {
        setError("Failed to fetch tasks");
      }
    };
    fetchTasks();
  }, []);

  const deleteTask = async (id : number | undefined) => {
    try {
      const response = await api.delete('/tasks/' + id);
      if (response.status === 200) {
        alert("Successfully Deleted");
        setTasks(tasks.filter(task => task.id !== id));
      } else {
        alert("Failed to delete task");
      }
    } catch (error) {
      console.error("There was an error deleting the task:", error);
    }
  };


  return (
    <div className="container mt-5">
      {message && <div className="alert alert-success">{message}</div>}
      {error && <div className="alert alert-danger">{error}</div>}

      <div className="row">
        <div className="col-md-12">
          <div className="card">
            <div className="card-header">
              <h4>Tasks List</h4>
              <Link
                href="/home/tasks/create"
                className="btn btn-primary float-end"
              >
                Add Task
              </Link>
            </div>
            <div className="card-body">
              <table className="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {tasks.length > 0 ? (
                    tasks.map((task) => (
                      <tr key={task.id}>
                        <td>{task.id}</td>
                        <td>{task.title}</td>
                        <td>{task.description}</td>
                        <td>{task.status}</td>
                        <td>{task.due_date}</td>
                        <td>
                          <Link
                            href={`/home/tasks/${task.id}/edit`}
                            className="btn btn-warning btn-sm me-2"
                          >
                            Edit
                          </Link>
                          <Link
                            href={`/home/tasks/${task.id}/show`}
                            className="btn btn-info btn-sm me-2"
                          >
                            Show
                          </Link>
                          <button
                            onClick={() => deleteTask(task.id)}
                            className="btn btn-danger btn-sm "
                          >
                            Delete
                          </button>
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan={6} className="text-center">
                        No tasks available
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default withAuth(TasksList);
