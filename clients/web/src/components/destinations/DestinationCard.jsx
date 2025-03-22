import React from "react";
import { Link } from "react-router-dom";

// Default image to be shown if there's no destination image
const defaultImage =
  "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTJlo0A1pmk9dewzQ5LBB36qnUnAHhPy3N1bg&s";

// Component for displaying a destination card on the home page
const DestinationCard = ({ destination }) => {
  return (
    <div className="bg-white rounded-lg shadow-md overflow-hidden">
      {/* Use the default image if no image is provided */}
      <img
        src={destination.image || defaultImage}
        alt={destination.name}
        className="w-full h-48 object-cover"
      />
      <div className="p-4">
        <h2 className="text-xl font-semibold mb-2">{destination.name}</h2>
        <p className="text-gray-600 mb-2 truncate">{destination.description}</p>
        <div className="flex justify-between items-center">
          <span className="font-bold text-green-600">${destination.price}</span>
          <span className="text-gray-500">{destination.duration} days</span>
        </div>
        <Link
          to={`/destinations/${destination.id}`}
          className="mt-4 block text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition"
        >
          View Details
        </Link>
      </div>
    </div>
  );
};

export default DestinationCard;
