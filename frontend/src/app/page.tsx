"use client";
import { useEffect, useState } from "react";
import axios from "./axios";
import { Task } from "./models/task";
import { useRouter } from "next/navigation";
import 'bootstrap/dist/css/bootstrap.min.css';
import './globals.css';


export default function Home() {
  const [tasks, setTasks] = useState<Array<Task>>([]);
  const router= useRouter();

  const handleSignIn = () => {
    router.push('/signin');
  };
  const handleSignUp = () => {
    router.push('/signup');
  };
  

  useEffect(() => {
    const fetchTasks = async () => {
      try {
        const response = await axios.get("/tasks");
        setTasks(response.data);
        console.log(response);
      } catch (err) {
        console.error(err);
      }
    };

    fetchTasks();
  }, []);

  return (
    <div
      className="d-flex justify-content-center align-items-center"
      style={{ height: "100vh" }}
    >
      <div className="text-center">
        <div className="title mb-4">
          <h1>MDW Task Management</h1>
        </div>
        <div className="buttons px-3">
          <button className="btn btn-primary mx-3" onClick={handleSignUp}>Sign Up</button>
          <button
            className="btn btn-outline-dark"
            data-toggle="modal"
            data-target="#loginModal"
            onClick={handleSignIn}
          >
            Sign In
          </button>
        </div>
      </div>
    </div>
  );
}
