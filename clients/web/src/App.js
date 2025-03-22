import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Navbar from "./components/common/Navbar";
import Footer from "./components/common/Footer";
import HomePage from "./pages/HomePage";
import DestinationDetailPage from "./pages/DestinationDetailPage";
import LoginPage from "./pages/LoginPage";
import AdminDashboardPage from "./pages/AdminDashboardPage";
import CreateDestinationPage from "./pages/CreateDestinationPage";
import EditDestinationPage from "./pages/EditDestinationPage";
import ProtectedRoute from "./components/auth/ProtectedRoute";
import RegisterPage from "./pages/RegisterPage";

function App() {
  return (
    <Router>
      <div className="flex flex-col min-h-screen">
        <Navbar />
        <main className="flex-grow bg-gray-50">
          <Routes>
            {/* Public Routes */}
            <Route path="/" element={<HomePage />} />
            <Route
              path="/destinations/:id"
              element={<DestinationDetailPage />}
            />
            <Route path="/login" element={<LoginPage />} />
            <Route path="/register" element={<RegisterPage />} />

            {/* Protected Admin Routes */}
            <Route
              path="/admin/destinations"
              element={
                <ProtectedRoute>
                  <AdminDashboardPage />
                </ProtectedRoute>
              }
            />
            <Route
              path="/admin/destinations/create"
              element={
                <ProtectedRoute>
                  <CreateDestinationPage />
                </ProtectedRoute>
              }
            />
            <Route
              path="/admin/destinations/edit/:id"
              element={
                <ProtectedRoute>
                  <EditDestinationPage />
                </ProtectedRoute>
              }
            />
          </Routes>
        </main>
        <Footer />
      </div>
    </Router>
  );
}

export default App;
