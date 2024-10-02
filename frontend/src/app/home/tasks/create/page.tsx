"use client";
import api from '@/app/axios';
import axios from 'axios';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { useState, useEffect, SyntheticEvent } from 'react';
import { Task } from './../../../models/task';
import withAuth from '@/app/withAuth';

const CreateTask = () => {
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [status, setStatus] = useState('pending');
  const [dueDate, setDueDate] = useState<string>('');
  const [errorMessage, setErrorMessage] = useState('');
  const [token, setToken] = useState('');
  const router = useRouter();

  useEffect(() => {
    const storedToken = localStorage.getItem('token');
    if (storedToken) {
      setToken(storedToken);
    }
    console.log(token)
  }, []);
  

  const submit = async (e: SyntheticEvent) => {
    e.preventDefault();

    const taskData: Task = {
      title,
      description,
      status,
      due_date: new Date(dueDate).toISOString().split('T')[0]
    };

    try {
      const response = await api.post('/tasks', taskData);
      if ([200, 201].includes(response.status)) {
        router.push('/home?message=Task created successfully');
      } else {
        setErrorMessage(`Unexpected response status: ${response.status}`);
      }
    } catch (error) {
      if (axios.isAxiosError(error)) {
        setErrorMessage(error.response?.data.message || 'Error occurred while creating the task.');
      } else {
        setErrorMessage('An unexpected error occurred.');
      }
    }
  };

  return (
    <div className="container mt-5">
      <div className="row">
        <div className="col-md-12">
          <div className="card">
            <div className="card-header">
              <h4>
                Create Task
                <Link href="/home/tasks" className="btn btn-danger float-end">Back</Link>
              </h4>
            </div>
            <div className="card-body">
              <form onSubmit={submit}>
                <div className="mb-3">
                  <label>Title</label>
                  <input
                    type="text"
                    className="form-control"
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    required
                  />
                </div>

                <div className="mb-3">
                  <label>Description</label>
                  <textarea
                    className="form-control"
                    value={description}
                    onChange={(e) => setDescription(e.target.value)}
                    required
                  />
                </div>

                <div className="mb-3">
                  <label htmlFor="status">Status</label>
                  <select
                    id="status"
                    className="form-control"
                    value={status}
                    onChange={(e) => setStatus(e.target.value)}
                  >
                    <option value="pending">Pending</option>
                    <option value="done">Done</option>
                    <option value="canceled">Canceled</option>
                  </select>
                </div>

                <div className="mb-3">
                  <label>Due Date</label>
                  <input
                    type="date"
                    className="form-control"
                    value={dueDate}
                    onChange={(e) => setDueDate(e.target.value)}
                  />
                </div>

                {errorMessage && <div className="alert alert-danger">{errorMessage}</div>}

                <div className="mb-3">
                  <button type="submit" className="btn btn-primary">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default withAuth(CreateTask);
