import { useState } from "react";
import axiosClient from "../api/axiosClient";
import styles from "../styles/PostForm.module.css";

export default function CreatePost() {
    const [form, setForm] = useState({ title: "", body: "" });

    const submit = async (e) => {
        e.preventDefault();
        try {
                    await axiosClient.post("/posts", form);

        } catch (error) {
            console.log('create post error:',error);
        }
        setForm({ title: "", body: "" }); // clear form after success
    };

    return (
        <div className={styles.container}>
            <h2 className={styles.title}>Create Post</h2>

            <form onSubmit={submit} className={styles.form}>
                <input
                    className={styles.input}
                    placeholder="Title"
                    value={form.title}
                    onChange={(e) => setForm({ ...form, title: e.target.value })}
                />

                <textarea
                    className={styles.textarea}
                    placeholder="Post body"
                    value={form.body}
                    onChange={(e) => setForm({ ...form, body: e.target.value })}
                />

                <button className={styles.button}>Save Post</button>
            </form>
        </div>
    );
}
