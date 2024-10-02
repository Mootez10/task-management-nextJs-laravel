"use client";
import React, { SyntheticEvent, useState } from "react";
import { useRouter } from "next/navigation";
import "bootstrap/dist/css/bootstrap.min.css";
import Link from "next/link";
import "../globals.css";
import api from "../axios";

const SignUp = () => {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmpassword, setConfirmPassword] = useState("");

  const router = useRouter();

  const submit = async (e: SyntheticEvent) => {
      e.preventDefault();

      try {
          const response = await api.post("/register", { 
              name, 
              email, 
              password, 
              password_confirmation: confirmpassword
          });

          console.warn("msg:", response.data);
          router.push("/signin");
      } catch (err) {
          console.error(err);
      }
  };
    return (
        <>
            <form onSubmit={submit}>
                <div className="container mt-5">
                    <div className="row justify-content-center">
                        <div className="col-md-4">
                            <div className="card">
                                <h3 className="card-header text-center">
                                    Register
                                </h3>
                                <div className="card-body">
                                    <div className="form-group mb-3">
                                        <input
                                            type="text"
                                            placeholder="name"
                                            id="name"
                                            className="form-control"
                                            name="name"
                                            required
                                            onChange={(e) =>
                                                setName(e.target.value)
                                            }
                                        />
                                    </div>
                                    <div className="form-group mb-3">
                                        <input
                                            type="email"
                                            placeholder="Email"
                                            id="email"
                                            className="form-control"
                                            name="email"
                                            required
                                            onChange={(e) =>
                                                setEmail(e.target.value)
                                            }
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
                                            onChange={(e) =>
                                                setPassword(e.target.value)
                                            }
                                        />
                                    </div>
                                    <div className="form-group mb-3">
                                        <input
                                            type="password"
                                            placeholder="ConfirmPassword"
                                            id="confirmpassword"
                                            className="form-control"
                                            name="confirmpassword"
                                            required
                                            onChange={(e) =>
                                                setConfirmPassword(
                                                    e.target.value
                                                )
                                            }
                                        />
                                    </div>
                                    <div className="d-grid mx-auto">
                                        <button
                                            type="submit"
                                            className="btn btn-dark btn-block"
                                        >
                                            Sign Up
                                        </button>
                                    </div>

                                    <div className="text-center mt-3">
                                        <span>Already have an account? </span>
                                        <Link href="/signin">Login now</Link>
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

export default SignUp;
