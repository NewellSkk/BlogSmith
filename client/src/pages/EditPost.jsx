// src/pages/EditPost.jsx
import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import axiosClient from "../api/axiosClient";
import styles from "../styles/PostForm.module.css"; // <-- rename file as you prefer

export default function EditPost() {
    const { id } = useParams();
    const navigate = useNavigate();
    const [form, setForm] = useState({ title: "", body: "" });

    useEffect(() => {
        axiosClient.get(`/posts/${id}`).then((res) => setForm(res.data));
    }, [id]);

    const submit = async (e) => {
        e.preventDefault();
        await axiosClient.put(`/posts/${id}`, form);
        navigate("/"); // redirect after update
    };

    return (
        <div className={styles.container}>
            <h1 className={styles.title}>Edit Post</h1>

            <form onSubmit={submit} className={styles.form}>
                <input
                    className={styles.input}
                    value={form.title}
                    placeholder="Title"
                    onChange={(e) =>
                        setForm({ ...form, title: e.target.value })
                    }
                />

                <textarea
                    className={styles.textarea}
                    value={form.body}
                    placeholder="Post content..."
                    onChange={(e) =>
                        setForm({ ...form, body: e.target.value })
                    }
                />

                <button className={styles.button}>Save Changes</button>
            </form>
        </div>
    );
}
