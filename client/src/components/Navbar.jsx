import { useContext } from "react";
import { NavLink } from "react-router-dom";
import { AuthContext } from "../context/AuthContext";
import axiosClient from "../api/axiosClient";
import styles from "../styles/Navbar.module.css"; // adjust path

export default function Navbar() {
  const { user, setUser } = useContext(AuthContext);

  const logout = async () => {
    try {
      const res = await axiosClient.get("/logout");
      if (res.status === 200) setUser(null);
    } catch (err) {
      console.error("Logout failed", err);
    }
  };

  return (
    <nav className={styles.navbar}>
      <div className={styles.navLinks}>
        <NavLink 
          to="/" 
          className={({ isActive }) => (isActive ? styles.active : styles.link)}
        >
          Home
        </NavLink>

        {user && (
          <NavLink 
            to="/createPost" 
            className={({ isActive }) => (isActive ? styles.active : styles.link)}
          >
            Create Post
          </NavLink>
        )}
      </div>

      <div className={styles.navLinks}>
        {user ? (
          <>
            <span className={styles.welcome}>User:{user.name}</span>
            <button className={styles.button} onClick={logout}>
              Logout
            </button>
          </>
        ) : (
          <>
            <NavLink 
              to="/login"
              className={({ isActive }) => (isActive ? styles.active : styles.link)}
            >
              Login
            </NavLink>

            <NavLink 
              to="/register"
              className={({ isActive }) => (isActive ? styles.active : styles.link)}
            >
              Register
            </NavLink>
          </>
        )}
      </div>
    </nav>
  );
}
