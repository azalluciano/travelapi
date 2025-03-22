import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import {
  getDestinations,
  deleteDestination,
} from "../../services/destinationService";

/**
 * AdminDestinationList - A component for administrators to manage travel destinations
 * Features:
 * - Displays a list of all destinations in a responsive table
 * - Provides edit and delete functionality
 * - Includes confirmation step before deletion
 * - Fully responsive design for all devices
 */
const AdminDestinationList = () => {
  const [destinations, setDestinations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [deleteConfirm, setDeleteConfirm] = useState(null);

  // Fetch destinations when component mounts
  useEffect(() => {
    fetchDestinations();
  }, []);

  /**
   * Fetches destination data from the API
   * Handles loading states and error cases
   */
  const fetchDestinations = async () => {
    try {
      setLoading(true);
      const response = await getDestinations();
      // Verify response data structure to prevent rendering errors
      if (response?.data?.data) {
        setDestinations(response.data.data);
        setError(null);
      } else {
        throw new Error("Invalid data structure received");
      }
    } catch (err) {
      setError("Failed to load destinations. Please try again later.");
    } finally {
      setLoading(false);
    }
  };

  /**
   * Handles the destination deletion process
   * Implements a two-step confirmation to prevent accidental deletions
   * @param {string} id - The ID of the destination to delete
   */
  const handleDelete = async (id) => {
    // If already in confirmation state, proceed with deletion
    if (deleteConfirm === id) {
      try {
        await deleteDestination(id);
        setDestinations(destinations.filter((dest) => dest.id !== id));
        setDeleteConfirm(null);
      } catch (err) {
        setError("Failed to delete destination. Please try again.");
      }
    } else {
      // First click: Request confirmation
      setDeleteConfirm(id);

      // Auto-reset confirmation state after 5 seconds
      setTimeout(() => {
        setDeleteConfirm((currentId) => (currentId === id ? null : currentId));
      }, 5000);
    }
  };

  /**
   * Formats price with proper currency display
   * @param {number} price - The raw price value
   * @returns {string} - Formatted price string
   */
  const formatPrice = (price) => {
    return `$${Number(price).toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })}`;
  };

  // Conditional rendering for loading state
  if (loading) {
    return (
      <div className="flex justify-center items-center min-h-[300px]">
        <div className="text-center text-gray-600">
          <div className="w-8 h-8 border-4 border-gray-300 border-t-gray-600 rounded-full mx-auto mb-3 animate-spin"></div>
          <p>Loading destinations...</p>
        </div>
      </div>
    );
  }

  // Conditional rendering for error state
  if (error) {
    return (
      <div className="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-4 rounded shadow-md">
        <div className="flex">
          <div>
            <p className="font-bold">Error</p>
            <p className="text-sm">{error}</p>
            <button
              onClick={fetchDestinations}
              className="mt-2 text-sm bg-red-200 hover:bg-red-300 text-red-700 py-1 px-3 rounded transition duration-200"
            >
              Retry
            </button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="bg-white rounded-lg shadow-md p-6">
      {/* Header section with title and add button */}
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b pb-4">
        <h2 className="text-2xl font-bold text-gray-800">
          Manage Destinations
          <span className="ml-2 text-sm font-normal text-gray-500">
            ({destinations.length} total)
          </span>
        </h2>
        <Link
          to="/admin/destinations/create"
          className="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition duration-200 flex items-center shadow-sm"
        >
          <svg
            className="w-5 h-5 mr-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth="2"
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"
            ></path>
          </svg>
          Add New Destination
        </Link>
      </div>

      {/* Empty state display */}
      {destinations.length === 0 ? (
        <div className="bg-gray-50 rounded-lg p-8 text-center">
          <p className="text-gray-600 mb-4">No destinations found.</p>
          <Link
            to="/admin/destinations/create"
            className="inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-200"
          >
            Create your first destination
          </Link>
        </div>
      ) : (
        // Responsive table implementation
        <div className="overflow-hidden rounded-lg border border-gray-200">
          {/* Desktop view - Full table */}
          <div className="hidden md:block overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Image
                  </th>
                  <th className="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                  </th>
                  <th className="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Price
                  </th>
                  <th className="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Duration
                  </th>
                  <th className="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {destinations.map((destination) => (
                  <tr
                    key={destination.id}
                    className="hover:bg-gray-50 transition duration-150"
                  >
                    <td className="py-3 px-4 whitespace-nowrap">
                      {destination.image ? (
                        <img
                          src={destination.image}
                          alt={destination.name}
                          className="w-16 h-16 object-cover rounded"
                          onError={(e) => {
                            e.target.onerror = null;
                            e.target.src = "/placeholder-image.jpg";
                          }}
                        />
                      ) : (
                        <div className="w-16 h-16 bg-gray-200 rounded"></div>
                      )}
                    </td>
                    <td className="py-3 px-4 whitespace-nowrap font-medium text-gray-800">
                      {destination.name}
                    </td>
                    <td className="py-3 px-4 whitespace-nowrap text-gray-600">
                      {formatPrice(destination.price)}
                    </td>
                    <td className="py-3 px-4 whitespace-nowrap text-gray-600">
                      {destination.duration}{" "}
                      {destination.duration === 1 ? "day" : "days"}
                    </td>
                    <td className="py-3 px-4 whitespace-nowrap text-right">
                      <div className="flex justify-end space-x-2">
                        <Link
                          to={`/admin/destinations/edit/${destination.id}`}
                          className="inline-flex items-center bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition duration-200"
                          aria-label={`Edit ${destination.name}`}
                        >
                          <svg
                            className="w-4 h-4 mr-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                          >
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              strokeWidth="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            ></path>
                          </svg>
                          Edit
                        </Link>
                        <button
                          onClick={() => handleDelete(destination.id)}
                          className={`inline-flex items-center py-1 px-3 rounded transition duration-200 ${
                            deleteConfirm === destination.id
                              ? "bg-red-600 hover:bg-red-700"
                              : "bg-red-500 hover:bg-red-600"
                          } text-white`}
                          aria-label={`Delete ${destination.name}`}
                        >
                          <svg
                            className="w-4 h-4 mr-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                          >
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              strokeWidth="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            ></path>
                          </svg>
                          {deleteConfirm === destination.id
                            ? "Confirm"
                            : "Delete"}
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {/* Mobile view - Card layout */}
          <div className="md:hidden divide-y divide-gray-200">
            {destinations.map((destination) => (
              <div key={destination.id} className="p-4">
                <div className="flex items-center space-x-4">
                  {destination.image ? (
                    <img
                      src={destination.image}
                      alt={destination.name}
                      className="w-16 h-16 object-cover rounded flex-shrink-0"
                      onError={(e) => {
                        e.target.onerror = null;
                        e.target.src = "/placeholder-image.jpg";
                      }}
                    />
                  ) : (
                    ""
                  )}
                  <div className="flex-1 min-w-0">
                    <p className="text-sm font-medium text-gray-900 truncate">
                      {destination.name}
                    </p>
                    <div className="flex items-center mt-1">
                      <span className="text-sm text-gray-600 mr-3">
                        {formatPrice(destination.price)}
                      </span>
                      <span className="text-sm text-gray-500">
                        {destination.duration}{" "}
                        {destination.duration === 1 ? "day" : "days"}
                      </span>
                    </div>
                  </div>
                </div>
                <div className="mt-4 flex justify-end space-x-2">
                  <Link
                    to={`/admin/destinations/edit/${destination.id}`}
                    className="inline-flex items-center bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition duration-200 text-sm"
                  >
                    <svg
                      className="w-4 h-4 mr-1"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                      ></path>
                    </svg>
                    Edit
                  </Link>
                  <button
                    onClick={() => handleDelete(destination.id)}
                    className={`inline-flex items-center py-1 px-3 rounded transition duration-200 text-sm ${
                      deleteConfirm === destination.id
                        ? "bg-red-600 hover:bg-red-700"
                        : "bg-red-500 hover:bg-red-600"
                    } text-white`}
                  >
                    <svg
                      className="w-4 h-4 mr-1"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                      ></path>
                    </svg>
                    {deleteConfirm === destination.id ? "Confirm" : "Delete"}
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};

export default AdminDestinationList;
