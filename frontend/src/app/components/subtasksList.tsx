"use client";
import Link from 'next/link';
import React, { useEffect, useState } from 'react';
import { useParams, useSearchParams } from 'next/navigation';
import api from '@/app/axios';
import { Subtask } from '../models/subtask';

const SubtasksList = () => {
  const { id } = useParams();
  const [subtasks, setSubTasks] = useState<Subtask[]>([]);
  const [error, setError] = useState("");
  const message = useSearchParams().get("message");

  useEffect(() => {
    const fetchTasks = async () => {
      try {
        const { data } = await api.get(`/subtasks/${id}`);
        setSubTasks(data.subTasks);
      } catch {
        setError("You still haven't subtasks yet.");
      }
    };
    fetchTasks();
  }, [id]);

  const deleteSubTask = async (subtaskId: number | undefined) => {
    try {
      const response = await api.delete(`/subtasks/${subtaskId}`);
      if (response.status === 200) {
        alert("Successfully Deleted");
        setSubTasks((prev) => prev.filter(({ id }) => id !== subtaskId));
      } else {
        alert("Failed to delete subtask");
      }
    } catch (error) {
      console.error("Error deleting subtask:", error);
    }
  };

  return (
    <div className="container mt-5">
      {message && <div className="alert alert-success">{message}</div>}
      {error && <div className="alert alert-danger">{error}</div>}
      <div className="card">
        <div className="card-header">
          <h4>SubTasks List</h4>
          <Link href={`/home/tasks/${id}/edit/create_subtask`} className="btn btn-primary float-end">
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
              {subtasks.length ? (
                subtasks.map(({ id, title, description, status, due_date }) => (
                  <tr key={id}>
                    <td>{id}</td>
                    <td>{title}</td>
                    <td>{description}</td>
                    <td>{status}</td>
                    <td>{due_date}</td>
                    <td>
                      <Link href={`/home/tasks/${id}/edit/${id}`} className="btn btn-warning btn-sm me-2">Edit</Link>
                      <Link href={`/home/tasks/${id}/show/${id}`} className="btn btn-info btn-sm me-2">Show</Link>
                      <button onClick={() => deleteSubTask(id)} className="btn btn-danger btn-sm">Delete</button>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={6} className="text-center">No tasks available</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default SubtasksList;
