import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import {
  getDestinations,
  deleteDestination,
} from "../../services/destinationService";

// Component for displaying and managing destinations in the admin dashboard
const AdminDestinationList = () => {
  const [destinations, setDestinations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [deleteConfirm, setDeleteConfirm] = useState(null);

  useEffect(() => {
    fetchDestinations();
  }, []);

  const fetchDestinations = async () => {
    try {
      setLoading(true);
      const response = await getDestinations();
      setDestinations(response.data);
      setError(null);
    } catch (err) {
      setError("Failed to load destinations");
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id) => {
    if (deleteConfirm === id) {
      try {
        await deleteDestination(id);
        setDestinations(destinations.filter((dest) => dest.id !== id));
        setDeleteConfirm(null);
      } catch (err) {
        setError("Failed to delete destination");
        console.error(err);
      }
    } else {
      setDeleteConfirm(id);
    }
  };

  if (loading) {
    return <div className="text-center py-8">Loading destinations...</div>;
  }

  if (error) {
    return <div className="text-center text-red-500 py-8">{error}</div>;
  }

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h2 className="text-2xl font-bold">Manage Destinations</h2>
        <Link
          to="/admin/destinations/create"
          className="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700"
        >
          Add New Destination
        </Link>
      </div>

      {destinations.length === 0 ? (
        <p className="text-center py-8">No destinations found.</p>
      ) : (
        <div className="overflow-x-auto">
          <table className="min-w-full bg-white border border-gray-200">
            <thead>
              <tr>
                <th className="py-2 px-4 border-b text-left">Name</th>
                <th className="py-2 px-4 border-b text-left">Price</th>
                <th className="py-2 px-4 border-b text-left">Duration</th>
                <th className="py-2 px-4 border-b text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {destinations.map((destination) => (
                <tr key={destination.id}>
                  <td className="py-2 px-4 border-b">{destination.name}</td>
                  <td className="py-2 px-4 border-b">${destination.price}</td>
                  <td className="py-2 px-4 border-b">
                    {destination.duration} days
                  </td>
                  <td className="py-2 px-4 border-b text-right space-x-2">
                    <Link
                      to={`/admin/destinations/edit/${destination.id}`}
                      className="inline-block bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600"
                    >
                      Edit
                    </Link>
                    <button
                      onClick={() => handleDelete(destination.id)}
                      className={`inline-block py-1 px-3 rounded ${
                        deleteConfirm === destination.id
                          ? "bg-red-600 hover:bg-red-700"
                          : "bg-red-500 hover:bg-red-600"
                      } text-white`}
                    >
                      {deleteConfirm === destination.id ? "Confirm" : "Delete"}
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
};

export default AdminDestinationList;
