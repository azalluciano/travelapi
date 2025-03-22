import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import DestinationForm from "../components/admin/DestinationForm";
import { createDestination } from "../services/destinationService";

const CreateDestinationPage = () => {
  const navigate = useNavigate();
  const [error, setError] = useState(null);

  const handleSubmit = async (formData) => {
    try {
      await createDestination(formData);
      navigate("/admin/destinations");
    } catch (err) {
      setError(err.response?.data?.message || "Failed to create destination");
      console.error(err);
    }
  };

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-3xl font-bold mb-6">Create Destination</h1>

      {error && (
        <div
          className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
          role="alert"
        >
          <p>{error}</p>
        </div>
      )}

      <DestinationForm
        onSubmit={handleSubmit}
        buttonText="Create Destination"
      />
    </div>
  );
};

export default CreateDestinationPage;
