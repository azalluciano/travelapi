import React, { useState, useEffect } from "react";
import DestinationCard from "./DestinationCard";
import { getDestinations } from "../../services/destinationService";

const DestinationList = () => {
  const [destinations, setDestinations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState("");

  useEffect(() => {
    fetchDestinations();
  }, []);

  const fetchDestinations = async (filters = {}) => {
    try {
      setLoading(true);
      const response = await getDestinations(filters);
      setDestinations(response.data.data);
      setError(null);
    } catch (err) {
      setError("Failed to load destinations");
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleSearch = (e) => {
    e.preventDefault();
    fetchDestinations({ name: searchTerm });
  };

  const handleExportCSV = () => {
    const headers = ["name", "description", "price", "duration", "image"];
    const rows = destinations.map((destination) => [
      destination.name,
      destination.description,
      destination.price,
      destination.duration,
      destination.image,
    ]);

    const csvContent = [
      headers.join(","),
      ...rows.map((row) => row.join(",")),
    ].join("\n");

    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    const timestamp = new Date().toISOString().replace(/[:.-]/g, "_");
    link.download = `destinations_${timestamp}.csv`;
    link.click();
    URL.revokeObjectURL(url);
  };

  if (loading) {
    return <div className="text-center py-8">Loading destinations...</div>;
  }

  if (error) {
    return <div className="text-center text-red-500 py-8">{error}</div>;
  }

  return (
    <div>
      <div className="mb-6">
        <form onSubmit={handleSearch} className="flex">
          <input
            type="text"
            placeholder="Search destinations..."
            className="flex-1 p-2 border border-gray-300 rounded-l"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
          <button
            type="submit"
            className="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700"
          >
            Search
          </button>
        </form>
      </div>

      <div className="mb-6">
        <button
          onClick={handleExportCSV}
          className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
        >
          Export to CSV
        </button>
      </div>

      {destinations.length === 0 ? (
        <p className="text-center py-8">No destinations found.</p>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {destinations.map((destination) => (
            <DestinationCard key={destination.id} destination={destination} />
          ))}
        </div>
      )}
    </div>
  );
};

export default DestinationList;
