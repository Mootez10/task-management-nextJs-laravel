"use client";

import "bootstrap/dist/css/bootstrap.min.css";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import withAuth from './../withAuth';


const HomePage = () => {
 

  const router = useRouter();

  useEffect(() => {
    router.push("/home/tasks");
  }, [router]);

  return null;
};

export default withAuth(HomePage);
