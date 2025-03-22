import React, { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";
import DestinationForm from "../components/admin/DestinationForm";
import {
  getDestination,
  updateDestination,
} from "../services/destinationService";

const EditDestinationPage = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [destination, setDestination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchDestination = async () => {
      try {
        setLoading(true);
        const response = await getDestination(id);
        setDestination(response.data.data);
        setError(null);
      } catch (err) {
        setError("Failed to load destination");
        console.error(err);
      } finally {
        setLoading(false);
      }
    };

    fetchDestination();
  }, [id]);

  const handleSubmit = async (formData) => {
    try {
      console.log("tayyyyyy", formData);
      await updateDestination(id, formData);
      navigate("/admin/destinations");
    } catch (err) {
      setError(err.response?.data?.message || "Failed to update destination");
      console.error(err);
    }
  };

  if (loading) {
    return <div className="container mx-auto p-4 text-center">Loading...</div>;
  }

  if (error && !destination) {
    return (
      <div className="container mx-auto p-4">
        <div
          className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"
          role="alert"
        >
          <p>{error}</p>
        </div>
      </div>
    );
  }

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-3xl font-bold mb-6">Edit Destination</h1>

      {error && (
        <div
          className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
          role="alert"
        >
          <p>{error}</p>
        </div>
      )}

      {destination && (
        <DestinationForm
          initialData={destination}
          onSubmit={handleSubmit}
          buttonText="Update Destination"
        />
      )}
    </div>
  );
};

export default EditDestinationPage;
