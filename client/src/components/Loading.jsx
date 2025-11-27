import styles from "../styles/Loading.module.css";

const Loading = () => {
  return (
    <div className={styles.loadingWrapper}>
      <i
        className={`fa-solid fa-spinner fa-spin ${styles.loadingIcon}`}
      />
    </div>
  );
};

export default Loading;
