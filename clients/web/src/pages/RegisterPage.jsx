import React from "react";
import RegisterForm from "../components/auth/RegisterForm";

const RegisterPage = () => {
  return (
    <div className="container mx-auto p-4">
      <h1 className="text-3xl font-bold mb-6 text-center">Admin Register</h1>
      <RegisterForm />
    </div>
  );
};

export default RegisterPage;
