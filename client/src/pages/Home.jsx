import { Outlet } from "react-router-dom";
import Navbar from "../components/Navbar";
import styles from "../styles/Home.module.css"; // adjust path

export default function Home() {
 

  return (
    <div className={styles.container}>
      <Navbar />      
      <Outlet />
    </div>
  );
}
