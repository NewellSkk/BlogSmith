import { BrowserRouter, Routes, Route } from "react-router-dom";
import Home from "./pages/Home";
import Login from "./pages/Login";
import EditPost from "./pages/EditPost";
import ProtectedRoute from "./components/ProtectedRoute";
import Register from "./pages/Register";
import Posts from "./components/Posts";
import CreatePost from "./components/CreatePost";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route
          path="/"
          element={
            <ProtectedRoute>
              <Home />
            </ProtectedRoute>
          }>
            <Route path="/" element={<Posts/>} />
            <Route path="/createPost" element={<CreatePost/>}/>
          </Route>

        <Route path="/login" element={<Login />} />
        
        <Route path="/register" element={<Register />} />

        <Route
          path="/edit/:id"
          element={
            <ProtectedRoute>
              <EditPost />
            </ProtectedRoute>
          }
        />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
