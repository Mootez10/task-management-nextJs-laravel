"use client";

import api from "@/app/axios";
import withAuth from "@/app/withAuth";
import axios from "axios";
import Link from "next/link";
import { useParams, useRouter } from "next/navigation";
import { useEffect, useState } from "react";

const ShowTaskPage = () =>{
  const { id } = useParams();
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
  }, [id]);

  const fetchTask = async () => {
    try {
      const result = await api.get('http://localhost:8000/api/tasks/' + id);
      
      console.log('API Response:', result.data);

      const task = result.data.task;

      setInputs({
        title: task.title || '',
        description: task.description || '',
        status: task.status || 'pending',
        due_date: task.due_date || ''
      });

      setLoading(false);
    } catch (err) {
      console.log("Something went wrong", err);
      setLoading(false);
    }
  };

  return (
    <div className="container">
      <div className="row">
        <div className="col-md-12 mt-5">
          <div className="card">
            <div className="card-header">
              <h4>
                Show Task
                <Link href="/home/tasks" className="btn btn-danger float-end">
                  Back
                </Link>
              </h4>
            </div>
            <div className="card-body">
              {loading ? (
                <p>Loading task data...</p>
              ) : (
                <>
                  <form>
                    <div className="mb-3">
                      <label>Title</label>
                      <input
                        type="text"
                        name="title"
                        className="form-control"
                        value={inputs.title}
                        disabled
                      />
                    </div>

                    <div className="mb-3">
                      <label>Description</label>
                      <input
                        type="text"
                        name="description"
                        className="form-control"
                        value={inputs.description}
                        disabled
                      />
                    </div>

                    <div className="mb-3">
                      <label>Status</label>
                      <select
                        name="status"
                        id="status"
                        className="form-control"
                        value={inputs.status}
                        disabled
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
                        value={inputs.due_date}
                        disabled
                      />
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

export default withAuth(ShowTaskPage)
