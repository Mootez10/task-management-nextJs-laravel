"use client";
import React, {SyntheticEvent, useState} from 'react';
import { useRouter } from "next/navigation";
import 'bootstrap/dist/css/bootstrap.min.css';
import Link from "next/link";
import "../globals.css";
import api from '../axios';


const SignIn = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const router = useRouter();

  const submit = async (e: SyntheticEvent) => {
      e.preventDefault();

      try {
          const response = await api.post('/login', { email, password }, {
              headers: {
                  'Content-Type': 'application/json'
              }
          });

          console.log(response.data);
          
          const token = response.data.token;
          localStorage.setItem('token', token);

          router.push('/home/tasks');
      } catch (err) {
          console.log(err);
      }
  };
  
  return (
    <>
      <form onSubmit={submit}>
      <div className="container mt-5">
        <div className="row justify-content-center">
          <div className="col-md-4">
            <div className="card">
              <h3 className="card-header text-center">Login</h3>
              <div className="card-body">
                  <div className="form-group mb-3">
                    <input
                      type="email"
                      placeholder="Email"
                      id="email"
                      className="form-control"
                      name="email"
                      required
                      onChange={e => setEmail(e.target.value)}
                    />
                  </div>
                  <div className="form-group mb-3">
                    <input
                      type="password"
                      placeholder="Password"
                      id="password"
                      className="form-control"
                      name="password"
                      required
                      onChange={e => setPassword(e.target.value)}
                    />
                  </div>
                  <div className="d-grid mx-auto">
                    <button type="submit" className="btn btn-dark btn-block">
                      Sign In
                    </button>
                  </div>
                <div className="text-center mt-3">
                  <span>Don't have an account? </span>
                  <Link href="/signup" className="text-primary">Register now</Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </form>
    </>
  );
};

export default SignIn;
