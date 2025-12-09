import { useState, useContext } from "react";
import axiosClient, { initSanctum } from "../api/axiosClient";
import { AuthContext } from "../context/AuthContext";
import { useNavigate } from "react-router-dom";
import Cookies from "js-cookie";
import styles from "../styles/Auth.module.css";

export default function Login() {
  const { setUser } = useContext(AuthContext);
  const [form, setForm] = useState({ email: "", password: "" });
  const navigate = useNavigate();

  const submit = async (e) => {
    e.preventDefault();

    try {
      const res = await axiosClient.post("/login", form);
      setUser(res.data.user);
      Cookies.set("access_token", res.data.token, { expires: 7 });
      navigate("/");
    } catch (error) {
      console.log("Full error:", error.response?.data);
    }
  };

  return (
    <div className={styles.formContainer}>
      <h2 className={styles.title}>Login</h2>

      <form onSubmit={submit}>
        <div className={styles.formGroup}>
          <label className={styles.label}>Email</label>
          <input
            name="email"
            className={styles.input}
            placeholder="email"
            autoComplete="email"
            onChange={(e) => setForm({ ...form, email: e.target.value })}
          />
        </div>

        <div className={styles.formGroup}>
          <label className={styles.label}>Password</label>
          <input
            name="password"
            type="password"
            className={styles.input}
            placeholder="password"
            autoComplete="current-password"
            onChange={(e) => setForm({ ...form, password: e.target.value })}
          />
        </div>

        <button className={styles.btn}>Login</button>
      </form>
      <p style={{ marginTop: "10px" }}>
        New here? <a href="/register">Create an account</a>
      </p>
    </div>
  );
}
