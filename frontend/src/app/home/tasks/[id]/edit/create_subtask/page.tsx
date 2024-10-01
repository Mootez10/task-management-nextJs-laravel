"use client";
import api from '@/app/axios';
import axios from 'axios';
import Link from 'next/link';
import { useParams, useRouter } from 'next/navigation';
import { useState, useEffect, SyntheticEvent } from 'react';
import { Subtask } from '@/app/models/subtask';
import withAuth from '@/app/withAuth';

const CreateSubTask = () => {
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [status, setStatus] = useState('pending');
  const [dueDate, setDueDate] = useState<string>('');
  const [errorMessage, setErrorMessage] = useState('');
  const { id } = useParams();
  const router = useRouter();
  const [token, setToken] = useState('');

  useEffect(() => {
    const storedToken = localStorage.getItem('token');
    if (storedToken) {
      setToken(storedToken);
    }
  }, []);

  const submit = async (e: SyntheticEvent) => {
    e.preventDefault();

    try {
      const subtaskData: Subtask = {
        title,
        description,
        status,
        due_date: new Date(dueDate).toISOString().split('T')[0],
        task_id: id  
      };

      const response = await api.post('/subtasks', subtaskData, {
        headers: {
          'Content-Type': 'application/json',
          
        },
      });

      if (response.status === 201 || response.status === 200) {
        router.push(`/home/tasks/${id}/edit`);
      } else {
        setErrorMessage('Unexpected response status: ' + response.status);
      }
    } catch (error) {
      if (axios.isAxiosError(error)) {
        console.error('Axios error:', error.response);
        setErrorMessage(error.response?.data.message || 'Error occurred while creating the task.');
      } else {
        console.error('Unexpected error:', error);
        setErrorMessage('An unexpected error occurred.');
      }
    }
  };

  return (
    <>
      <div className="container mt-5">
        <div className="row">
          <div className="col-md-12">
            <div className="card">
              <div className="card-header">
                <h4>
                  Create SubTask
                  <Link href={`/home/tasks/${id}/edit`}className="btn btn-danger float-end">
                    Back
                  </Link>
                </h4>
              </div>
              <div className="card-body">
                <form onSubmit={submit}>
                  <div className="mb-3">
                    <label>Title</label>
                    <input
                      type="text"
                      name="title"
                      className="form-control"
                      value={title}
                      onChange={(e) => setTitle(e.target.value)}
                      required
                    />
                  </div>

                  <div className="mb-3">
                    <label>Description</label>
                    <textarea
                      name="description"
                      className="form-control"
                      value={description}
                      onChange={(e) => setDescription(e.target.value)}
                      required
                    />
                  </div>

                  <div className="mb-3">
                    <label htmlFor="status">Status</label>
                    <select
                      name="status"
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
                      name="due_date"
                      className="form-control"
                      value={dueDate}
                      onChange={(e) => setDueDate(e.target.value)}
                      
                    />
                  </div>

                  
                  {errorMessage && <div className="alert alert-danger">{errorMessage}</div>}

                  <div className="mb-3">
                    <button type="submit" className="btn btn-primary">
                      Save
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default withAuth(CreateSubTask);
