import React from "react";
import DestinationList from "../components/destinations/DestinationList";

const HomePage = () => {
  return (
    <div className="container mx-auto p-4">
      <h1 className="text-3xl font-bold mb-6">Honeymoon Destinations</h1>
      <DestinationList />
    </div>
  );
};

export default HomePage;
