"use client";

import { useEffect, useState } from 'react';
import { Task } from '@/app/models/task';
import { useSearchParams } from 'next/navigation';
import api from '@/app/axios';

const Statistics = () => {
  const [doneTasks, setDoneTasks] = useState<Task[]>([]);
  const [pendingTasks ,setPendingTasks] = useState<Task[]>([]);
  const [canceledTasks, setCanceledTasks] = useState<Task[]>([]);
  const [doneCount, setDoneCount] = useState(0);
  const [pendingCount, setPendingCount] = useState(0);
  const [canceledCount, setCanceledCount] = useState(0);
  const [error, setError] = useState("");
  const searchParams = useSearchParams();
  const message = searchParams.get("message");

  useEffect(() => {
    const fetchTasks = async () => {
      try {
        const response = await api.get("/statistics");
        console.log(response.data);
        setDoneTasks(response.data.doneTasks.tasks);
        setPendingTasks(response.data.pendingTasks.tasks)
        setCanceledTasks(response.data.canceledTasks.tasks)
        setDoneCount(response.data.doneTasks.count);
        setPendingCount(response.data.pendingTasks.count)
        setCanceledCount(response.data.canceledTasks.count)
      } catch (error) {
        setError("Failed to fetch tasks");
      }
    };
    fetchTasks();
  }, []);

  return (
    <div className="container mt-5">
      <div className="row justify-content-center">
        <div className="col-md-4">
          <h1 className="text-center">Completed Tasks</h1>
          <div className="card text-white bg-success mb-3">
            <div className="card-body text-center">
              <h5 className="card-title">Completed Tasks</h5>
              <p className="card-text">{doneCount} Task(s) done</p>
            </div>
          </div>
        </div>
        <div className="col-md-4">
          <h1 className="text-center">Pending Tasks</h1>
          <div className="card text-white bg-warning mb-3">
            <div className="card-body text-center">
              <h5 className="card-title">Pending Tasks</h5>
              <p className="card-text">{pendingCount} Task(s) pending</p>
            </div>
          </div>
        </div>
        <div className="col-md-4">
          <h1 className="text-center">Canceled Tasks</h1>
          <div className="card text-white bg-danger mb-3">
            <div className="card-body text-center">
              <h5 className="card-title">Canceled Tasks</h5>
              <p className="card-text">{canceledCount} Task(s) canceled</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Statistics;
