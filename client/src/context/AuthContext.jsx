import { createContext, useEffect, useState } from "react";
import axiosClient from "../api/axiosClient";

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading,setLoading]=useState(true);

  const getUser = async () => {
    try {
     
      const res = await axiosClient.get("/user");
      setUser(res.data.user);
    } catch (error) {
      setUser(null);
    }finally{
      setLoading(false);
    }
  };

  useEffect(() => {
    getUser();
  }, []);

  return <AuthContext.Provider value={{user,setUser,loading}}>{children}</AuthContext.Provider>;
};
