import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import axiosClient from "../api/axiosClient";
import styles from "../styles/Posts.module.css";
import Loading from "./Loading";
const Posts = () => {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);
  useEffect(() => {
    axiosClient
      .get("/posts")
      .then((res) => {
        setPosts(res.data.posts);
        console.log("POSTS:", res.data.posts);
      })
      .finally(setLoading(false));
  }, []);
  return (
    <>
      <h2 className={styles.heading}>All Posts</h2>
      {loading ? (
        <Loading />
      ) : (
        posts.map((post) => (
          <div key={post.title} className={styles.postCard}>
            <h3 className={styles.postTitle}>
              {post.title}{" "}
              <span className={styles.author}>
                by {post.user?.name ?? "Unknown"}
              </span>
            </h3>

            <p className={styles.postBody}>{post.body}</p>

            <div className={styles.buttonRow}>
              <Link to={`/edit/${post.id}`}>
                <button className={`${styles.button} ${styles.edit}`}>
                  Edit
                </button>
              </Link>

              <form
                onSubmit={async (e) => {
                  e.preventDefault();
                  await axiosClient.delete(`/posts/${post.id}`);
                }}
              >
                <button className={`${styles.button} ${styles.delete}`}>
                  Delete
                </button>
              </form>
            </div>
          </div>
        ))
      )}
    </>
  );
};
export default Posts;
