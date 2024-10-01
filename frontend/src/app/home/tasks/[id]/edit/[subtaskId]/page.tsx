"use client";
import React, { useState, useEffect, SyntheticEvent } from "react";
import Link from "next/link";
import api from "@/app/axios";
import { useParams, useRouter } from "next/navigation";
import withAuth from "@/app/withAuth";

const  EditSubTaskPage =() =>{
  const { subtaskId, id } = useParams();
  const router = useRouter();
  const [message, setMessage] = useState('');
  const [inputs, setInputs] = useState({
    title: '',
    description: '',
    status: 'pending',
    due_date: ''
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchTask();
  }, [subtaskId]);

  const formatDateForInput = (date : Date) => {
    if (!date) return '';
    const d = new Date(date);
    return d.toISOString().split('T')[0];
  };

  const fetchTask = async () => {
    try {
      const result = await api.get("/subtasks_id/" + subtaskId);
      console.log("API Response subtask:", result.data);
      if (result?.data?.subTask) {
        const subtask = result.data.subTask;
        subtask.due_date = formatDateForInput(subtask.due_date);
        setInputs(subtask);
      }
      setLoading(false);
    } catch (err) {
      console.log("Something went wrong:", err);
      setLoading(false);
    }
  };

  const handleChange = (event: React.ChangeEvent<HTMLInputElement> | React.ChangeEvent<HTMLSelectElement>) => {
    const { name, value } = event.target;
    setInputs((values) => ({ ...values, [name]: value }));
    console.log(setInputs);
  };

  const uploadSubTask = async () => {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    if (inputs.title) formData.append('title', inputs.title);
    if (inputs.description) formData.append('description', inputs.description);
    if (inputs.status) formData.append('status', inputs.status);
    if (inputs.due_date) formData.append('due_date', inputs.due_date);

    try {
      const response = await api.post("/subtasks/" + subtaskId, formData, {
        headers: { 'Content-Type': "multipart/form-data" },
      });
      setMessage(response.data.message);
      console.log(response);

      setTimeout(() => {
        router.push(`/home/tasks/${id}/edit`);
      }, 1000);
    } catch (error) {
      console.log("Something went wrong:", error);
    }
  };

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    await uploadSubTask();
  };

  return (
    <div className="container">
      <div className="row">
        <div className="col-md-12 mt-5">
          <div className="card">
            <div className="card-header">
              <h4>
                Edit SubTask
                <Link href="/home/tasks" className="btn btn-danger float-end">
                  Back
                </Link>
              </h4>
            </div>
            <div className="card-body">
              {loading ? (
                <p>Loading Subtask data...</p>
              ) : (
                <>
                  <p className="text-success"><b>{message}</b></p>
                  <form onSubmit={handleSubmit}>
                    <div className="mb-3">
                      <label>Title</label>
                      <input
                        type="text"
                        name="title"
                        className="form-control"
                        value={inputs.title || ''}
                        onChange={handleChange}
                        required
                      />
                    </div>

                    <div className="mb-3">
                      <label>Description</label>
                      <input
                        type="text"
                        name="description"
                        className="form-control"
                        value={inputs.description || ''}
                        onChange={handleChange}
                        required
                      />
                    </div>

                    <div className="mb-3">
                      <label htmlFor="status">Status</label>
                      <select
                        name="status"
                        id="status"
                        className="form-control"
                        value={inputs.status || 'pending'}
                        onChange={handleChange}
                        required
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
                        value={inputs.due_date || ''}
                        onChange={handleChange}
                        required
                      />
                    </div>

                    <div className="mb-3">
                      <button type="submit" className="btn btn-primary">
                        Update
                      </button>
                    </div>
                  </form>
                </>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default withAuth(EditSubTaskPage)
