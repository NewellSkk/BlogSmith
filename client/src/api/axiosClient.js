import axios from "axios";
import Cookies from "js-cookie";

const axiosClient = axios.create({
  baseURL: "http://localhost:8000/api",
  withCredentials: false,
  headers:{
    'Accept':'application/json',
    'Content-Type':'application/json',
  }
});

axiosClient.interceptors.request.use((config)=>{
  const token= Cookies.get("access_token");
  if(token){
    config.headers.Authorization=`Bearer ${token}`;
  }
  return config;
})
//FOR CSRF SIGN ON
// export const initSanctum = async () => {
//   try {
//     const a = await axios.get("http://localhost:8000/sanctum/csrf-cookie", {
//       withCredentials: true,
//     });
//     console.log("Sanctum initializing...",a);
//   } catch (error) {
//     console.error("Failed to initialize sanctum", error);
//   }
// };
export default axiosClient;
