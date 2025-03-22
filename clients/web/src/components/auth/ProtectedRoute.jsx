import React, { useEffect, useState } from "react";
import { Navigate } from "react-router-dom";
import { isAuthenticated } from "../../services/authService";

// Component to protect admin routes
const ProtectedRoute = ({ children }) => {
  const [loading, setLoading] = useState(true);
  const [authorized, setAuthorized] = useState(false);

  useEffect(() => {
    const checkAuth = async () => {
      const auth = isAuthenticated();
      setAuthorized(auth);
      setLoading(false);
    };

    checkAuth();
  }, []);

  if (loading) {
    return <div className="text-center py-8">Checking authentication...</div>;
  }

  if (!authorized) {
    return <Navigate to="/login" />;
  }

  return children;
};

export default ProtectedRoute;
