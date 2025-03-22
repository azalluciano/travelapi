import React from "react";
import AdminDestinationList from "../components/admin/AdminDestinationList";

const AdminDashboardPage = () => {
  return (
    <div className="container mx-auto p-4">
      <h1 className="text-3xl font-bold mb-6">Admin Dashboard</h1>
      <AdminDestinationList />
    </div>
  );
};

export default AdminDashboardPage;
