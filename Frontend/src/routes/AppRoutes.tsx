import { Routes, Route, Navigate } from "react-router-dom";

import Layout from "../components/layout/Layout";

import AdminPage from "../pages/AdminPage";
import StudentPage from "../pages/StudentPage";
import NotFound from "../pages/NotFound";

export default function AppRoutes() {
  return (
    <Layout>
      <Routes>

        {/* Default redirect */}
        <Route path="/" element={<Navigate to="/student" />} />

        {/* Admin */}
        <Route path="/admin" element={<AdminPage />} />

        {/* Student */}
        <Route path="/student" element={<StudentPage />} />

        {/* 404 */}
        <Route path="*" element={<NotFound />} />

      </Routes>
    </Layout>
  );
}
