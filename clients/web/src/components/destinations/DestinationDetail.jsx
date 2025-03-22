import React, { useState, useEffect } from "react";
import { useParams, Link } from "react-router-dom";
import { getDestination } from "../../services/destinationService";

// Default image to be shown if there's no destination image
const defaultImage =
  "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTJlo0A1pmk9dewzQ5LBB36qnUnAHhPy3N1bg&s";

/**
 * DestinationDetail Component
 * Displays comprehensive information about a selected travel destination
 * Fetches and shows details based on the destination ID from URL params
 */
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
        setDestination(response.data.data);
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

  // Loading state
  if (loading) {
    return (
      <div className="flex justify-center items-center h-64">
        <div className="animate-pulse flex flex-col items-center">
          <div className="h-12 w-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
          <p className="text-gray-600">Loading destination details...</p>
        </div>
      </div>
    );
  }

  // Error state
  if (error) {
    return (
      <div className="max-w-4xl mx-auto p-4 text-center">
        <div className="bg-red-100 text-red-700 p-6 rounded-lg shadow">
          <svg
            className="w-12 h-12 mx-auto mb-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth="2"
              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
          <h2 className="text-xl font-bold mb-2">Error</h2>
          <p>{error}</p>
          <Link
            to="/"
            className="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
          >
            Return to Destinations
          </Link>
        </div>
      </div>
    );
  }

  // Not found state
  if (!destination) {
    return (
      <div className="max-w-4xl mx-auto p-4 text-center">
        <div className="bg-yellow-100 text-yellow-800 p-6 rounded-lg shadow">
          <svg
            className="w-12 h-12 mx-auto mb-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth="2"
              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
          <h2 className="text-xl font-bold mb-2">Destination Not Found</h2>
          <p>We couldn't find the destination you're looking for.</p>
          <Link
            to="/"
            className="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
          >
            Browse All Destinations
          </Link>
        </div>
      </div>
    );
  }

  // Destination detail view
  return (
    <div className="max-w-4xl mx-auto p-4">
      <Link
        to="/"
        className="inline-flex items-center mb-6 text-blue-600 hover:text-blue-800 transition-colors"
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
            d="M10 19l-7-7m0 0l7-7m-7 7h18"
          ></path>
        </svg>
        Back to Destinations
      </Link>

      <div className="bg-white rounded-lg shadow-lg overflow-hidden">
        <div className="relative">
          <img
            src={destination.image || defaultImage}
            alt={`${destination.name} travel destination`}
            className="w-full h-80 sm:h-96 object-cover"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
          <div className="absolute bottom-0 left-0 p-6 text-white">
            <h1 className="text-3xl sm:text-4xl font-bold">
              {destination.name}
            </h1>
          </div>
        </div>

        <div className="p-6">
          <div className="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 pb-6 border-b border-gray-200">
            <div className="mb-4 sm:mb-0">
              <span className="text-2xl font-bold text-green-600">
                ${destination.price}
              </span>
              <span className="text-gray-500 ml-2">per person</span>
            </div>
            <div className="flex items-center">
              <svg
                className="w-5 h-5 text-gray-500 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                ></path>
              </svg>
              <span className="text-gray-700 font-medium">
                {destination.duration} days
              </span>
            </div>
          </div>

          <div className="prose max-w-none">
            <h2 className="text-xl font-semibold mb-4">
              About this Destination
            </h2>
            <p className="text-gray-700 whitespace-pre-line leading-relaxed">
              {destination.description}
            </p>
          </div>

          {destination.highlights && (
            <div className="mt-8">
              <h2 className="text-xl font-semibold mb-4">Highlights</h2>
              <ul className="grid grid-cols-1 md:grid-cols-2 gap-3">
                {destination.highlights.map((highlight, index) => (
                  <li key={index} className="flex items-start">
                    <svg
                      className="w-5 h-5 text-green-500 mr-2 mt-1 flex-shrink-0"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M5 13l4 4L19 7"
                      ></path>
                    </svg>
                    <span>{highlight}</span>
                  </li>
                ))}
              </ul>
            </div>
          )}

          <div className="mt-8 pt-6 border-t border-gray-200">
            <button className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition-colors">
              Book This Trip
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default DestinationDetail;
