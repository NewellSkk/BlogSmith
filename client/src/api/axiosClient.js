import axios from "axios";

const axiosClient = axios.create({
  baseURL: "http://localhost:8000/api",
  withCredentials: true,
  withXSRFToken:true,
  headers:{
    'Accept':'application/json',
    'Content-Type':'application/json',
  }
});

export const initSanctum = async () => {
  try {
    const a = await axios.get("http://localhost:8000/sanctum/csrf-cookie", {
      withCredentials: true,
    });
    console.log("Sanctum initializing...",a);
  } catch (error) {
    console.error("Failed to initialize sanctum", error);
  }
};
export default axiosClient;
