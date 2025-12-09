import { useContext, useState } from "react";
import axiosClient from "../api/axiosClient";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../context/AuthContext";
import Cookies from "js-cookie";
import styles from "../styles/Auth.module.css";

const Register = () => {
  const [form, setForm] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });
  const { setUser } = useContext(AuthContext);
  const navigate = useNavigate();

  const submit = async (e) => {
    e.preventDefault();



    try {
      const res = await axiosClient.post("/register", form);
      setUser(res.data.user);
      Cookies.set("access_token", res.data.token, { expires: 7 });
      navigate("/");
    } catch (error) {
      console.log("ERROR:", error.response?.data || error);
    }
  };

  return (
    <div className={styles.formContainer}>
      <h2 className={styles.title}>Register</h2>

      <form onSubmit={submit}>
        <div className={styles.formGroup}>
          <label className={styles.label}>Name</label>
          <input
            type="text"
            placeholder="Full name"
            className={styles.input}
            onChange={(e) => setForm({ ...form, name: e.target.value })}
          />
        </div>

        <div className={styles.formGroup}>
          <label className={styles.label}>Email</label>
          <input
            type="email"
            placeholder="Email"
            autoComplete="email"
            className={styles.input}
            onChange={(e) => setForm({ ...form, email: e.target.value })}
          />
        </div>

        <div className={styles.formGroup}>
          <label className={styles.label}>Password</label>
          <input
            type="password"
            placeholder="Password"
            autoComplete="current-password"
            className={styles.input}
            onChange={(e) => setForm({ ...form, password: e.target.value })}
          />
        </div>

        <div className={styles.formGroup}>
          <label className={styles.label}>Confirm Password</label>
          <input
            type="password"
            placeholder="Password"
            autoComplete="current-password"
            className={styles.input}
            onChange={(e) =>
              setForm({ ...form, password_confirmation: e.target.value })
            }
          />
        </div>

        <button className={styles.btn}>Register</button>
      </form>
      <p style={{ marginTop: "10px" }}>
        Already registered? <a href="/login">Login</a>
      </p>
    </div>
  );
};

export default Register;
