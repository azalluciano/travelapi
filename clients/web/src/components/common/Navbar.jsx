import React from "react";
import { Link, useNavigate } from "react-router-dom";
import { isAuthenticated, logout } from "../../services/authService";

const Navbar = () => {
  const navigate = useNavigate();
  const authenticated = isAuthenticated();
  const [isOpen, setIsOpen] = React.useState(false);

  const handleLogout = async () => {
    await logout();
    navigate("/login");
  };

  return (
    <nav className="bg-blue-600 border-b shadow-sm">
      <div className="container mx-auto px-4 py-4">
        <div className="flex justify-between items-center">
          <div className="font-bold text-white text-xl">
            Honeymoon Destinations
          </div>

          {/* Menu desktop */}
          <div className="hidden md:flex items-center space-x-6">
            <Link
              to="/"
              className="text-white hover:text-gray-900 transition-colors"
            >
              Home
            </Link>

            {authenticated ? (
              <>
                <Link
                  to="/admin/destinations"
                  className="text-white hover:text-gray-900 transition-colors"
                >
                  Admin Dashboard
                </Link>
                <button
                  onClick={handleLogout}
                  className="text-white hover:text-gray-900 transition-colors"
                >
                  Logout
                </button>
              </>
            ) : (
              <>
                <Link
                  to="/login"
                  className="text-white hover:text-gray-900 transition-colors"
                >
                  Login
                </Link>
                <Link
                  to="/register"
                  className="text-white hover:text-gray-900 transition-colors"
                >
                  Register
                </Link>
              </>
            )}
          </div>

          {/* Menu mobile */}
          <div className="md:hidden">
            <button
              onClick={() => setIsOpen(!isOpen)}
              className="p-2 hover:bg-gray-100 rounded"
            >
              <svg
                className="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
            </button>
          </div>
        </div>

        {/* Menu mobile déroulant */}
        {isOpen && (
          <div className="md:hidden mt-4 space-y-2">
            <Link
              to="/"
              onClick={() => setIsOpen(false)}
              className="block text-white hover:text-gray-900 p-2 transition-colors"
            >
              Home
            </Link>

            {authenticated ? (
              <>
                <Link
                  to="/admin"
                  onClick={() => setIsOpen(false)}
                  className="block text-white hover:text-gray-900 p-2 transition-colors"
                >
                  Admin Dashboard
                </Link>
                <button
                  onClick={handleLogout}
                  className="block text-white hover:text-gray-900 p-2 transition-colors"
                >
                  Logout
                </button>
              </>
            ) : (
              <>
                <Link
                  to="/login"
                  onClick={() => setIsOpen(false)}
                  className="block text-white hover:text-gray-900 p-2 transition-colors"
                >
                  Login
                </Link>
                <Link
                  to="/register"
                  onClick={() => setIsOpen(false)}
                  className="block text-white hover:text-gray-900 p-2 transition-colors"
                >
                  Register
                </Link>
              </>
            )}
          </div>
        )}
      </div>
    </nav>
  );
};

export default Navbar;
