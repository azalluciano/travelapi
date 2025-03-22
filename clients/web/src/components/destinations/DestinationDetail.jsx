import React, { useState, useEffect } from "react";
import { useParams, Link } from "react-router-dom";
import { getDestination } from "../../services/destinationService";

// Component for displaying detailed information about a destination
const DestinationDetail = () => {
  const { id } = useParams();
  const [destination, setDestination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchDestination = async () => {
      try {
        setLoading(true);
        const response = await getDestination(id);
        setDestination(response.data);
        setError(null);
      } catch (err) {
        setError("Failed to load destination details");
        console.error(err);
      } finally {
        setLoading(false);
      }
    };

    fetchDestination();
  }, [id]);

  if (loading) {
    return (
      <div className="text-center py-8">Loading destination details...</div>
    );
  }

  if (error) {
    return <div className="text-center text-red-500 py-8">{error}</div>;
  }

  if (!destination) {
    return <div className="text-center py-8">Destination not found</div>;
  }

  return (
    <div className="max-w-4xl mx-auto p-4">
      <Link to="/" className="inline-block mb-6 text-blue-600 hover:underline">
        &larr; Back to Destinations
      </Link>

      <div className="bg-white rounded-lg shadow-lg overflow-hidden">
        <img
          src={destination.image}
          alt={destination.name}
          className="w-full h-64 object-cover"
          onError={(e) => {
            e.target.src = "https://via.placeholder.com/800x400?text=No+Image";
          }}
        />

        <div className="p-6">
          <h1 className="text-3xl font-bold mb-4">{destination.name}</h1>

          <div className="flex justify-between mb-6">
            <div className="text-xl font-bold text-green-600">
              ${destination.price}
            </div>
            <div className="text-gray-700">{destination.duration} days</div>
          </div>

          <div className="prose max-w-none">
            <h2 className="text-xl font-semibold mb-2">Description</h2>
            <p className="text-gray-700">{destination.description}</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default DestinationDetail;
