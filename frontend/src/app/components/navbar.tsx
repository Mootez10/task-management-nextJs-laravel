import Link from "next/link";
import Image from "next/image";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "../globals.css";
import api from "../axios";
import { useRouter } from "next/navigation";
import { SyntheticEvent } from "react";


const Navbar = () => {
  const router = useRouter();

  const submit = async (e: SyntheticEvent) => {
    e.preventDefault();

    await api.post('/logout')
    .then((res) => {
      console.warn("msg:" , res.data)
      localStorage.removeItem('token');
    })
    .catch((err)=>{
      console.error(err)
    })
    router.push('/signin');
  }
  return (
    <nav
      className="navbar navbar-light"
      style={{ backgroundColor: "#e3f2fd", marginTop: "20px" }}
    >
      <div className="container-fluid">
        <ul className="navbar-nav d-flex flex-row justify-content-between w-100 align-items-center">
          <li className="nav-item mx-3">
            <Link href="/home">
              <Image
                src="/assets/images/logo.png"
                width={300}
                height={65}
                alt="Logo"
              />
            </Link>
          </li>
          <li className="nav-item mx-3">
            <Link className="nav-link active" href="/home/tasks">
              ALL TASKS
            </Link>
          </li>
          <li className="nav-item dropdown mx-3">
            <Link
              className="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdownMenuLink"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              TASKS STATUS
            </Link>
            <ul
              className="dropdown-menu"
              aria-labelledby="navbarDropdownMenuLink"
            >
              <li>
                <Link className="dropdown-item" href="/home/status/done">
                  Done
                </Link>
              </li>
              <li>
                <Link className="dropdown-item" href="/home/status/pending">
                  Still Pending
                </Link>
              </li>
              <li>
                <Link className="dropdown-item" href="/home/status/canceled">
                  Canceled
                </Link>
              </li>
            </ul>
          </li>
          <li className="nav-item mx-3">
            <Link className="nav-link" href="/home/statistics">
              STATISTICS
            </Link>
          </li>
          <li className="nav-item mx-3">
            <form action="/logout" method="POST" className="btn btn-danger btn-sm">
              <Link className="nav-link" href="#" onClick={submit}>
                Logout
              </Link>
            </form>
          </li>
        </ul>
      </div>
    </nav>
  );
};

export default Navbar;
