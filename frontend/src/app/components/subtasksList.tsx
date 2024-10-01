"use client"
import Link from 'next/link';
import React, { useEffect, useState } from 'react'
import { useParams, useSearchParams } from 'next/navigation';
import api from '@/app/axios';
import { Subtask } from '../models/subtask';
import axios from 'axios';

const SubtasksList = () => {
  const { id } = useParams();
    const [subtasks, setSubTasks] = useState<Subtask[]>([]);
    const [error, setError] = useState("");
    const searchParams = useSearchParams();
    const message = searchParams.get("message");
  
    useEffect(() => {
      const fetchTasks = async () => {
        try {
          const response = await api.get("/subtasks/"+id);
          console.log("message",response.data.subTasks)
          setSubTasks(response.data.subTasks); 
          
        } catch (error) {
          setError("Failed to fetch tasks");
        }
      };
      fetchTasks();
    }, [id]);

    const deleteSubTask = async (id : number | undefined) => {
      try {
        const response = await api.delete('/subtasks/' + id);
        if (response.status === 200) {
          alert("Successfully Deleted");
          setSubTasks(subtasks.filter(subtask => subtask.id !== id));
        } else {
          alert("Failed to delete task");
        }
      } catch (error) {
        console.error("There was an error deleting the subtask:", error);
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
                <h4>SubTasks List</h4>
                <Link
                  href={`/home/tasks/${id}/edit/create_subtask`}
                  className="btn btn-primary float-end"
                >
                  Add SubTask
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
                    {subtasks.length > 0 ? (
                      subtasks.map((subtask) => (
                        <tr key={subtask.id}>
                          <td>{subtask.id}</td>
                          <td>{subtask.title}</td>
                          <td>{subtask.description}</td>
                          <td>{subtask.status}</td>
                          <td>{subtask.due_date}</td>
                          <td>
                            <Link
                              href={`/home/tasks/${id}/edit/${subtask.id}`}
                              className="btn btn-warning btn-sm me-2"
                            >
                              Edit
                            </Link>
                            <Link
                              href={`/home/tasks/${id}/show/${subtask.id}`}
                              className="btn btn-info btn-sm me-2"
                            >
                              Show
                            </Link>
                            <button
                              onClick={() => deleteSubTask(subtask.id)}
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
}

export default SubtasksList