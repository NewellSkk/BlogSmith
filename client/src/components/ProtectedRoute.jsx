import { useContext } from "react";
import { Navigate } from "react-router-dom";
import { AuthContext } from "../context/AuthContext";
import Loading from "./Loading";

export default function ProtectedRoute({ children }) {
  const { user, loading } = useContext(AuthContext);

  if (loading) return       <Loading/>;

  if (user) return children;

  return <Navigate to="/login" />;
}
