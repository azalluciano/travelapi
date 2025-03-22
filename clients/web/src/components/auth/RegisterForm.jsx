import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { register } from "../../services/authService"; // Assure-toi que le service d'enregistrement est bien implémenté

const RegisterForm = () => {
  // Navigation handler
  const navigate = useNavigate();

  // State management
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });

  // Error and loading states
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);
  const [formErrors, setFormErrors] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });

  // Input change handler with validation
  const handleChange = (e) => {
    setFormData((prev) => ({
      ...prev,
      [e.target.name]: e.target.value,
    }));
  };

  // Validate form before submission
  const validateForm = () => {
    let isValid = true;
    let errors = {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
    };

    // Full Name validation: Only letters and spaces allowed
    const nameRegex = /^[a-zA-Z\s]+$/;
    if (!nameRegex.test(formData.name)) {
      errors.name = "Full name should contain only letters and spaces.";
      isValid = false;
    }

    // Email validation: Standard email format
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(formData.email)) {
      errors.email = "Please enter a valid email address.";
      isValid = false;
    }

    // Password validation: Minimum 8 characters, at least one number, one letter, and one special character
    const passwordRegex =
      /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordRegex.test(formData.password)) {
      errors.password =
        "Password must be at least 8 characters long, contain at least one letter, one number, and one special character.";
      isValid = false;
    }

    // Password confirmation validation
    if (formData.password !== formData.password_confirmation) {
      errors.password_confirmation = "Passwords do not match.";
      isValid = false;
    }

    setFormErrors(errors);
    return isValid;
  };

  // Secure registration handler
  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    // Validate form data before submitting
    if (!validateForm()) {
      setLoading(false);
      return;
    }

    try {
      // Secure registration call
      await register(formData);
      navigate("/login"); // Redirect to login after successful registration
    } catch (err) {
      // Sanitized error handling
      setError(err.response?.data?.message || "Registration failed");
      console.error("Registration error:", err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100">
      <div className="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow-md">
        <div className="text-center">
          <h1 className="text-2xl font-bold text-gray-900">Create Account</h1>
        </div>

        {/* Error message display */}
        {error && (
          <div className="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
            {error}
          </div>
        )}

        {/* Responsive form container */}
        <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
          {/* Full name input */}
          <div>
            <label
              htmlFor="name"
              className="block text-sm font-medium text-gray-700"
            >
              Full Name
            </label>
            <input
              id="name"
              name="name"
              type="text"
              required
              className="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Your full name"
              value={formData.name}
              onChange={handleChange}
            />
            {formErrors.name && (
              <p className="mt-2 text-sm text-red-600">{formErrors.name}</p>
            )}
          </div>

          {/* Email input */}
          <div>
            <label
              htmlFor="email"
              className="block text-sm font-medium text-gray-700"
            >
              Email
            </label>
            <input
              id="email"
              name="email"
              type="email"
              autoComplete="email"
              required
              className="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="your@email.com"
              value={formData.email}
              onChange={handleChange}
            />
            {formErrors.email && (
              <p className="mt-2 text-sm text-red-600">{formErrors.email}</p>
            )}
          </div>

          {/* Password input */}
          <div>
            <label
              htmlFor="password"
              className="block text-sm font-medium text-gray-700"
            >
              Password
            </label>
            <input
              id="password"
              name="password"
              type="password"
              autoComplete="new-password"
              required
              className="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="••••••••"
              value={formData.password}
              onChange={handleChange}
            />
            {formErrors.password && (
              <p className="mt-2 text-sm text-red-600">{formErrors.password}</p>
            )}
          </div>

          {/* Password confirmation */}
          <div>
            <label
              htmlFor="password_confirmation"
              className="block text-sm font-medium text-gray-700"
            >
              Confirm Password
            </label>
            <input
              id="password_confirmation"
              name="password_confirmation"
              type="password"
              autoComplete="new-password"
              required
              className="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="••••••••"
              value={formData.password_confirmation}
              onChange={handleChange}
            />
            {formErrors.password_confirmation && (
              <p className="mt-2 text-sm text-red-600">
                {formErrors.password_confirmation}
              </p>
            )}
          </div>

          {/* Submit button */}
          <div>
            <button
              type="submit"
              disabled={loading}
              className="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              {loading ? "Registering..." : "Register"}
            </button>
          </div>

          {/* Login link */}
          <div className="text-center">
            <p className="text-sm text-gray-600">
              Already have an account?{" "}
              <a
                href="/login"
                className="font-medium text-indigo-600 hover:text-indigo-500"
              >
                Login
              </a>
            </p>
          </div>
        </form>
      </div>
    </div>
  );
};

export default RegisterForm;
