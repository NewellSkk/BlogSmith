import { useState, useEffect, useContext } from "react";
import { Link, useNavigate } from "react-router-dom";
import axiosClient from "../api/axiosClient";
import styles from "../styles/Posts.module.css";
import Loading from "./Loading";
import { AuthContext } from "../context/AuthContext";
const Posts = () => {
  const { user } = useContext(AuthContext);
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();
  useEffect(() => {
    axiosClient
      .get("/posts")
      .then((res) => {
        setPosts(res.data.posts);
      })
      .finally(setLoading(false));
  }, []);

  
  const deletePost = (postId) => {
    axiosClient.delete(`/posts/${postId}`).finally(()=>navigate(0));
  };
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

            <div
              className={`${styles.buttonRow} ${
                user.id != post.user.id ? styles.hideFunction : ""
              }`}
            >
              <Link to={`/edit/${post.id}`}>
                <button className={`${styles.button} ${styles.edit}`}>
                  Edit
                </button>
              </Link>

              <button
                className={`${styles.button} ${styles.delete} `}
                onClick={()=>deletePost(post.id)}
              >
                Delete
              </button>
            </div>
          </div>
        ))
      )}
    </>
  );
};
export default Posts;
